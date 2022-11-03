<template>
  <div>
    <content-header page="Reservations">
      <template>
        <li class="breadcrumb-item active">Reservations</li>
      </template>
    </content-header>

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Reservations Table</h3>

                <div class="card-tools">
                  <button
                    type="submit"
                    class="btn btn-success"
                    @click="showCreateModal"
                  >
                    <i class="fas fa-plus mr-1"></i>
                    Create new reservation
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Guest Name</th>
                      <th>Room Name</th>
                      <th>Check In</th>
                      <th>Check Out</th>
                      <!-- <th>Room Booked</th> -->
                      <th>Guests Count</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(reservation, index) in reservations.data"
                      :key="reservation.id"
                    
                    >

                      <td>{{ index + 1 }}</td>
                      <td>
                        {{ reservation.first_name }}
                        {{ reservation.last_name }}
                      </td>
                      <td>{{ reservation.room_name }}</td>
                      <td>{{ reservation.check_in }}</td>
                      <td>{{ reservation.check_out }}</td>
                      <!-- <td>
                                                {{ reservation.rooms }} room(s)
                                            </td> -->
                      <td>
                        {{ reservation.guests }}
                        person(s)
                      </td>
                      <td>
                        <button
                          class="btn btn-sm btn-success"
                          @click="showEditModal(reservation)"
                        >
                          <i class="fas fa-edit"></i>
                        </button>
                        <button
                          class="btn btn-sm btn-danger"
                          @click="deleteReservation(reservation.id)"
                        >
                          <i class="fas fa-trash"></i>
                        </button>
                        <button
                          class="btn btn-sm btn-primary"
                         @click="checkInGuest(reservation.id)"
                          v-if="isCheckedIn(reservation.check_in)"
                        >
                          Check-In
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination
                  :data="reservations"
                  @pagination-change-page="getResults"
                ></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </div>

    <modal
      :title="
        method == 'create' ? 'Create new reservation' : 'Update reservation'
      "
      :submit="method == 'create' ? 'Create reservation' : 'Update reservation'"
      @submitForm="submitForm"
    >
      <div class="form-group">
        <label for="guest_id">Guest Name</label>
        <select
          v-model="form.guest_id"
          id="guest_id"
          class="form-control"
          :class="{
            'is-invalid': form.errors.has('guest_id'),
          }"
        >
          <option value="" selected hidden disabled>Select Guest</option>
          <option
            v-for="guest in guests"
            :key="guest.id"
            :value="guest.id"
            :selected="guest.id == form.guest_id"
          >
            {{ guest.first_name + " " + guest.last_name }}
          </option>
        </select>
        <has-error :form="form" field="guest_id"></has-error>
      </div>

      <div class="form-group">
        <label for="room_id">Room Name</label>
        <select
          v-model="form.room_id"
          id="room_id"
          class="form-control"
          :class="{
            'is-invalid': form.errors.has('room_id'),
          }"
        >
          <option value="" selected hidden disabled>Select Room</option>
          <option
            v-for="room in rooms"
            :key="room.id"
            :value="room.id"
            :selected="room.id == form.room_id"
          >
            {{ room.name }}
          </option>
        </select>
        <has-error :form="form" field="room_id"></has-error>
      </div>

      <div class="form-group">
        <label for="check_in">Check In</label>
        <input
          v-model="form.check_in"
          type="date"
          id="check_in"
          class="form-control"
          :class="{
            'is-invalid': form.errors.has('check_in'),
          }"
        />
        <has-error :form="form" field="check_in"></has-error>
      </div>

      <div class="form-group">
        <label for="check_out">Check Out</label>
        <input
          v-model="form.check_out"
          type="date"
          id="check_out"
          class="form-control"
          :class="{
            'is-invalid': form.errors.has('check_out'),
          }"
        />
        <has-error :form="form" field="check_out"></has-error>
      </div>

      <div class="form-group">
        <label for="guests">Number of Guests</label>
        <input
          v-model="form.guests"
          type="number"
          id="guests"
          class="form-control"
          :class="{
            'is-invalid': form.errors.has('guests'),
          }"
        />
        <has-error :form="form" field="guests"></has-error>
      </div>

      <!-- <div class="form-group">
                <label for="rooms">Number of Rooms</label>
                <input 
                    v-model="form.rooms"
                    type="number"
                    id="rooms"
                    class="form-control"
                    :class="{
                        'is-invalid': form.errors.has('rooms')
                    }"
                >
                <has-error :form="form" field="rooms"></has-error>
            </div> -->
    </modal>
  </div>
</template>

<script>
import ContentHeader from "./layouts/ContentHeader";
import Modal from "./layouts/Modal";

export default {
  name: "Reservations",

  components: {
    ContentHeader,
    Modal,
  },

  data: function () {
    return {
      method: "create",
      reservations: {},
      guests: {},
      rooms: {},
      form: new Form({
        id: "",
        guest_id: "",
        room_id: "",
        check_in: "",
        check_out: "",
        guests: "",
        // rooms: ""
      }),
    };
  },

  mounted: function () {
    this.fetchReservations();
    this.fetchGuests();
    this.fetchRooms();
  },

  methods: {
    getResults: function (page = 1) {
      axios.get("/api/reservations?page=" + page).then((response) => {
        this.reservations = response.data;
      });
    },

    isCheckedIn: function (checkedInDate) {
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, "0");
      var mm = String(today.getMonth() + 1).padStart(2, "0"); //January is 0!
      var yyyy = today.getFullYear();

      today = yyyy + "-" + mm + "-" + dd;
      return today == checkedInDate;
    },

    showCreateModal: function () {
      this.method = "create";
      this.form.reset();
      this.form.clear();
      this.showModal();
    },

    showEditModal: function (reservation) {
      this.method = "update";
      this.form.clear()
      this.form.reset()
      this.form.fill(reservation);
      console.log(this.form)
      this.showModal();
    },

    submitForm: function () {
      this.method == "create"
        ? this.createReservation()
        : this.updateReservation();
    },

    fetchGuests: function () {
      axios
        .get("/api/guest")
        .then(({ data }) => {
          this.guests = data;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    fetchRooms: function () {
      axios
        .get("/api/room")
        .then(({ data }) => {
          this.rooms = data;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    fetchReservations: function () {
      axios
        .get("/api/reservations")
        .then((response) => {
          this.reservations = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    createReservation: function () {
      this.$Progress.start();
      this.form
        .post("/api/reservations")
        .then((response) => {
          this.$Progress.finish();
          this.fireToast("success", response.data.msg);
          this.closeModal();
          this.fetchReservations();
        })
        .catch((error) => {
          this.$Progress.fail();
          this.fireSwal("error", error.response.data.msg);
          this.closeModal();
        });
    },

    updateReservation: function () {
      this.$Progress.start();
      this.form
        .put("/api/reservations/" + this.form.id)
        .then(() => {
          this.$Progress.finish();
          this.fireToast("success", "Reservation updated successfully!");
          this.closeModal();
          this.fetchReservations();
        })
        .catch((error) => {
          this.$Progress.fail();
          this.fireSwal("error", error.response.data.msg);
          this.closeModal();
        });
    },
    checkInGuest:function(id) {
        Swal.fire({
        title: "Are you sure?",
        text: "You want to check in guest?",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, check-In!",
      }).then((result) => {
        if (result.isConfirmed) {
          this.$Progress.start();
          axios
            .post(`/api/reservation/check-in/${id}`)
            .then((response) => {
              this.$Progress.finish();
              this.fireSwal(
                "success",
                "Check-In",
                "Guest Checked-In successfully!"
              );
              this.fetchReservations();
            })
            .catch((error) => {
              this.$Progress.fail();
            });
        }
      });
    },
    deleteReservation: function (id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          this.$Progress.start();
          axios
            .delete(`/api/reservations/${id}`)
            .then((response) => {
              this.$Progress.finish();
              this.fireSwal(
                "success",
                "Deleted!",
                "Reservation deleted successfully!"
              );
              this.fetchReservations();
            })
            .catch((error) => {
              this.$Progress.fail();
            });
        }
      });
    },
  },
};
</script>

<style></style>

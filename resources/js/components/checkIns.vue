<template>
    <div>
        <content-header page="Check-In Lists">
            <template>
                <li class="breadcrumb-item active">Check-In Lists</li>
            </template>
        </content-header>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Check-In Table
                                </h3>
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
                                            <th>Guests Count</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(reservation,
                                            index) in reservations.data"
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
                                            <td>
                                                {{
                                                    reservation.guests
                                                }}
                                                person(s)
                                            </td>
                                            <td>
                                                <button
                                                    class="btn btn-sm btn-primary"
                                                    @click="
                                                        showAddServiceModal(
                                                            reservation
                                                        )
                                                    "
                                                >
                                                   Add Service
                                                </button>
                                                <button
                                                    class="btn btn-sm btn-success"
                                                    @click="checkOutGuest(reservation.id)"
                                                >
                                                    Check-Out
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <pagination :data="reservations" @pagination-change-page="getResults"></pagination>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>

        <modal
            :title="'Add Service'"
            
            :submit="'Add Service'"
            @submitForm="addService"
        >
            <div class="form-group">
                <label for="service_id">Service</label>
                <select 
                    v-model="form.service_id" 
                    id="service_id"
                    class="form-control"
                    :class="{
                        'is-invalid': form.errors.has('service_id')
                    }"
                >
                    <option value="" selected hidden disabled>Select Service</option>
                    <option v-for="service in services" :key="service.id" :value="service.id" >{{ service.name }}</option>
                </select>
                <has-error :form="form" field="service_id"></has-error>
            </div>


            <div class="form-group">
                <label for="no_of_times">Number of times service taken</label>
                <input 
                    v-model="form.no_of_times"
                    type="number"
                    id="no_of_times"
                    class="form-control"
                    :class="{
                        'is-invalid': form.errors.has('no_of_times')
                    }"
                >
                <has-error :form="form" field="no_of_times"></has-error>
            </div>

            <div class="form-group">
                <label for="service_charges"> Total Service charges</label>
                <input 
                    v-model="form.service_charges"
                    type="number"
                    id="service_charges"
                    class="form-control"
                    :class="{
                        'is-invalid': form.errors.has('service_charges')
                    }"
                >
                <has-error :form="form" field="service_charges"></has-error>
            </div>

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
        Modal
    },

    data: function() {
        return {
            reservations: {},
            guests: {},
            rooms: {},
            services: {},
            form: new Form({
                reservation_id:"",
                service_id:"",
                no_of_times:"",
                service_charges:""
            })
        };
    },

    mounted: function() {
        this.fetchReservations();
        this.fetchGuests();
        this.fetchRooms();
        this.fetchServices();
    },

    methods: {
        getResults: function (page = 1) {
            axios.get("/api/reservations?page=" + page)
                .then(response => {
                    this.reservations = response.data
                })
        },

        showAddServiceModal: function(reservation) {
            this.form.reset();
            this.form.clear();
            this.form.reservation_id = reservation.id;
            this.showModal();
        },

        fetchGuests: function () {
            axios.get("/api/guest")
                .then(({ data }) => {
                    this.guests = data
                })
                .catch(error => {
                    console.log(error)
                })
        },

        fetchRooms: function () {
            axios.get("/api/room")
                .then(({ data }) => {
                    this.rooms = data
                })
                .catch(error => {
                    console.log(error)
                })
        },
        fetchServices: function () {
            axios.get("/api/room/services")
                .then(({ data }) => {
                    this.services = data.data;
                })
                .catch(error => {
                    console.log(error)
                })
        },

        fetchReservations: function() {
            axios
                .get("/api/reservation/checkIns")
                .then((response) => {
                    this.reservations = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },


        addService: function() {
         this.$Progress.start();
        axios
        .post("/api/reservation/addService" ,this.form)
        .then(() => {
          this.$Progress.finish();
          this.fireToast("success", "Service added successfully!");
          this.closeModal();
          this.fetchReservations();
        })
        .catch(() => {
          this.$Progress.fail();
        });
        },
        checkOutGuest:function(id) {
        Swal.fire({
        title: "Are you sure?",
        text: "You want to check out guest?",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, check-Out!",
      }).then((result) => {
        if (result.isConfirmed) {
          this.$Progress.start();
          axios
            .post(`/api/reservation/check-out/${id}`)
            .then((response) => {
              this.$Progress.finish();
              this.generateBill(id);
            })
            .catch((error) => {
              this.$Progress.fail();
            });
        }
      });
    },
      generateBill :function(id) {
        Swal.fire({
        text: "Guest check out successfully",
        icon: "success",
        showCancelButton: false,
        confirmButtonColor: "#3085d6",
        confirmButtonText:"Generate Bill",
      }).then((result) => {
        if (result.isConfirmed) {
         this.$router.push({ path: `/bill/${id}` })
        }
      });
    },

    }
};
</script>

<style></style>

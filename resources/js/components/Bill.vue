<template>
  <div class="container" id="app">
    <div id ="bill">
      <div class="text-center">
        <h1>INVOICE</h1>
      </div>
      <div class="text-right">
        <h4>MPUAT Guest House</h4>
        mpuat@info.com<br />
        University Road
      </div>
      <div class="card">
        <div class="card-header">
          <h4>Guest Information</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col">
              <b>Name</b>: {{ guest.first_name + "" + guest.last_name }} <br />
            </div>
            <div class="col"><b>Email</b>: {{ guest.email_address }}<br /></div>
          </div>
          <div class="row">
            <div class="col">
              <b>Phone No</b>: {{ guest.phone_number }} <br />
            </div>
            <div class="col">
              <b>Street Address</b>: {{ guest.street_address }} <br />
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h4>Booking Information</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col"><b>Room Name</b>: {{ room.name }}<br /></div>
            <div class="col">
              <b>Check-in-date</b>: {{ reservation.check_in }}<br />
            </div>
          </div>
          <div class="row">
            <div class="col">
              <b>Check-out-date</b>: {{ reservation.check_out }} <br />
            </div>
            <div class="col"><b>Day of Stay</b>: {{ day_of_stay }}<br /></div>
          </div>
        </div>
      </div>
      <!-- / end client details section -->
      <div class="card">
        <div class="card-header">
          <h4>Additional Services</h4>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover">
            <thead>
              <tr>
                <th></th>
                <th>Service</th>
                <th>Description</th>
                <th>Hrs/qty</th>
                <th>Service charge</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(service, index) in services" :key="service.id">
                <td>{{ index + 1 }}</td>
                <td>
                  {{ service.name }}
                </td>
                <td>{{ service.description }}</td>
                <td>{{ service.no_of_times }}</td>
                <td>{{ service.service_charges }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <table>
            <tr>
              <th>Total Room Charge:</th>
              <td>{{ total_room_amount }}</td>
            </tr>
            <tr>
              <th>Total Additional Service Charge:</th>
              <td>{{ total_service_amount }}</td>
            </tr>
            <tr>
              <th>Total Charge:</th>
              <td>{{ total_amount }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <button class="btn btn-success noPrint" @click="downloadWithCSS" >
      <b>Print Invoice</b>
    </button>
  </div>
</template>
<script>
import jsPDF from "jspdf";
import html2canvas from "html2canvas";
export default {
  name: "Bill",

  data: function () {
    return {
      id: this.$route.params.id,
      reservation: {},
      guest: {},
      room: {},
      services: [],
      day_of_stay: "",
      total_room_amount: 0,
      total_service_amount: 0,
      total_amount: 0,
    };
  },

  computed: {
    type: function () {
      return this.types.filter((type) => {
        return type.id == this.room.room_type_id;
      })[0];
    },
  },

  mounted: function () {
    this.fetchBill();
  },

  methods: {
    fetchBill: function () {
      axios
        .post(`/api/reservation/bill/${this.id}`)
        .then(({ data }) => {
          this.reservation = data.reservation;
          this.guest = data.guest;
          this.room = data.room;
          this.services = data.services;
          this.day_of_stay = data.day_of_stay;
          this.total_room_amount = data.room_amount;
          this.total_service_amount = data.total_service_charges;
          this.total_amount = +data.room_amount + +data.total_service_charges;
        })
        .catch((error) => {
          console.log(error);
        });
    },
downloadWithCSS() {
  window.print();
// var pdf = new jsPDF();
//     var element = document.getElementById('bill');
//     var width= element.style.width;
//     var height = element.style.height;
//     html2canvas(element).then(canvas => {
//         var image = canvas.toDataURL('image/png');
//         pdf.addImage(image, 'JPEG', width, height);
//         pdf.save('sample.pdf');
  //  });
 },
  },
};
</script>
<style scoped>
@media print {
  .noPrint{
    display:none;
  }
}
</style>
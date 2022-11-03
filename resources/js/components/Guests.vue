<template>
    <div>
        <content-header page="Guests">
            <template>
                <li class="breadcrumb-item active">Guests</li>
            </template>
        </content-header>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Guest List
                                </h3>

                                <div class="card-tools">
                                    <button type="submit" class="btn btn-success" @click="showCreateModal">
                                        <i class="fas fa-plus mr-1"></i>
                                        Create new Guest
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email Address</th>
                                            <th>Phone Number</th>
                                            <th>Street Address</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(guest, index) in guests.data" :key="guest.id">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ guest.first_name }}</td>
                                            <td>{{ guest.last_name }}</td>
                                            <td>{{ guest.email_address }}</td>
                                            <td>{{ guest.phone_number }}</td>
                                            <td>{{ guest.street_address }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-success" @click="showEditModal(guest)"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-danger" @click="deleteGuest(guest.id)"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <pagination :data="guests" @pagination-change-page="getResults"></pagination>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>

        <modal
            :title="method == 'create' ? 'Create new guest' : 'Edit guest'"
            :submit="method == 'create' ? 'Create guest' : 'Update guest'"
            :method="method == 'create' ? 'create' : 'update'"
            @submitForm="submitForm"
        >
            <template>
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input 
                        v-model="form.first_name"
                        type="text"
                        id="first_name"
                        class="form-control"
                        :class="{
                            'is-invalid': form.errors.has('first_name')
                        }"
                    >
                    <has-error :form="form" field="first_name"></has-error>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input 
                        v-model="form.last_name"
                        type="text"
                        id="last_name"
                        class="form-control"
                        :class="{
                            'is-invalid': form.errors.has('last_name')
                        }"
                    >
                    <has-error :form="form" field="last_name"></has-error>
                </div>

                <div class="form-group">
                    <label for="email_address">Email Address</label>
                    <input 
                        v-model="form.email_address"
                        type="text"
                        id="email_address"
                        class="form-control"
                        :class="{
                            'is-invalid': form.errors.has('email_address')
                        }"
                    >
                    <has-error :form="form" field="email_address"></has-error>
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input 
                        v-model="form.phone_number"
                        type="text"
                        id="phone_number"
                        class="form-control"
                        :class="{
                            'is-invalid': form.errors.has('phone_number')
                        }"
                    >
                    <has-error :form="form" field="phone_number"></has-error>
                </div>

                <div class="form-group">
                    <label for="street_address">Street Address</label>
                    <input 
                        v-model="form.street_address"
                        type="text"
                        id="street_address"
                        class="form-control"
                        :class="{
                            'is-invalid': form.errors.has('street_address')
                        }"
                    >
                    <has-error :form="form" field="street_address"></has-error>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import ContentHeader from "./layouts/ContentHeader";
import Modal from "./layouts/Modal";

export default {
    name: "Guests",

    components: {
        ContentHeader,
        Modal
    },

    data: function() {
        return {
            guests: {},
            method: 'create',
            form: new Form({
                id: "",
                first_name: "",
                last_name: "",
                email_address: "",
                phone_number: "",
                street_address: ""
            })
        };
    },

    mounted: function () {
        this.fetchGuests()
    },

    methods: {
        getResults: function (page = 1) {
            axios.get("/api/guests?page=" + page)
                .then(response => {
                    this.guests = response.data
                })
        },

        showCreateModal: function () {
            this.method = 'create'
            this.form.clear()
            this.form.reset()
            this.showModal()
        },
        
        showEditModal: function (guest) {
            this.method = "update";
            this.form.clear()
            this.form.reset()
            this.form.fill(guest);
            this.showModal()
        },
        
        submitForm: function(method) {
            this.method == "create" ? this.createGuest() : this.updateGuest();
        },

        fetchGuests: function () {
            axios.get("/api/guests")
                .then((response) => {
                    this.guests = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        },

        createGuest: function () {
            this.$Progress.start()

            this.form.post("/api/guests")
                .then(response => {
                    this.$Progress.finish()
                    this.fireToast("success", "Guest created successfully!")
                    this.closeModal()
                    this.fetchGuests()
                })
                .catch(error => {
                    this.$Progress.fail()
                })
        },

        updateGuest: function () {
            this.$Progress.start()

            this.form.put(`/api/guests/${this.form.id}`)
                .then(response => {
                    this.$Progress.finish()
                    this.fireToast("success", "Guest updated successfully!")
                    this.closeModal()
                    this.fetchGuests()
                })
                .catch(error => {
                    this.$Progress.fail()
                })
        },
        deleteGuest: function (id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(result => {
                if (result.isConfirmed) {
                    this.$Progress.start()

                    axios.delete(`/api/guests/${id}`)
                        .then(response => {
                            this.$Progress.finish()
                            this.fireSwal("success", "Deleted!", "Guest deleted successfully!")
                            this.fetchGuests()
                        })
                        .catch(error => {
                            this.$Progress.fail()
                        })
                }
            })
        },
    }
};
</script>

<style></style>

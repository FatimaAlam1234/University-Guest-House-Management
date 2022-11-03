<template>
    <div>
        <content-header page="Room Services">
            <template>
                <li class="breadcrumb-item">
                    <router-link to="/rooms">Rooms</router-link>
                </li>
                <li class="breadcrumb-item active">Room Services</li>
            </template>
        </content-header>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Room Services Table
                                </h3>

                                <div class="card-tools">
                                    <button
                                        type="submit"
                                        class="btn btn-success"
                                        @click="showCreateModal"
                                    >
                                        <i class="fas fa-plus mr-1"></i>
                                        Create new service
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Service Name</th>
                                            <th>Service Description</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(service, index) in services.data"
                                            :key="service.id"
                                        >
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ service.name }}</td>
                                            <td>{{ service.description }}</td>
                                            <td>
                                                <button
                                                    class="btn btn-sm btn-success"
                                                    @click="showEditModal(service)"
                                                >
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button
                                                    class="btn btn-sm btn-danger"
                                                    @click="deleteService(service.id)"
                                                >
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <pagination :data="services" @pagination-change-page="getResults"></pagination>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>

        <modal
            :title="method == 'create' ? 'Create new service' : 'Update service'"
            :submit="method == 'create' ? 'Create service' : 'Update service'"
            @submitForm="submitForm"
        >
            <div class="form-group">
                <label for="name">Name</label>
                <input
                    v-model="form.name"
                    type="text"
                    id="name"
                    class="form-control"
                    :class="{
                        'is-invalid': form.errors.has('name')
                    }"
                />
                <has-error :form="form" field="name"></has-error>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea 
                    v-model="form.description"
                    id="description"
                    class="form-control"
                    :class="{
                        'is-invalid': form.errors.has('description')
                    }"
                ></textarea>
                <has-error :form="form" field="description"></has-error>
            </div>
        </modal>
    </div>
</template>

<script>
import ContentHeader from "./layouts/ContentHeader";
import Modal from "./layouts/Modal";

export default {
    name: "RoomServices",

    components: {
        ContentHeader,
        Modal
    },

    data: function() {
        return {
            services: {},
            method: "create",
            form: new Form({
                id: "",
                name: "",
                description: ""
            })
        };
    },

    mounted: function () {
        this.fetchServices()
    },

    methods: {
        getResults: function (page = 1) {
            axios.get("/api/room/services?page=" + page)
                .then(response => {
                    this.services = response.data
                })
        },
        
        showCreateModal: function () {
            this.method = 'create'
            this.form.reset()
            this.form.clear()
            this.showModal()
        },
        
        showEditModal: function (service) {
            this.method = 'update'
            this.form.clear()
            this.form.reset()
            this.form.fill(service)
            this.showModal()
        },
        
        submitForm: function() {
            this.method == "create" ? this.createService() : this.updateService();
        },

        fetchServices: function() {
            axios.get("/api/room/services")
                .then((response) => {
                    this.services = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        },

        createService: function() {
            this.$Progress.start()
            this.form.post("/api/room/services")
                .then(() => {
                    this.$Progress.finish()
                    this.fireToast('success', 'Service created successfully!')
                    this.closeModal()
                    this.fetchServices()
                })
                .catch(() => {
                    this.$Progress.fail()
                })
        },

        updateService: function() {
            this.$Progress.start()
            this.form.put(`/api/room/services/${this.form.id}`)
                .then(() => {
                    this.$Progress.finish()
                    this.fireToast('success', 'Service updated successfully!')
                    this.closeModal()
                    this.fetchServices()
                })
                .catch(() => {
                    this.$Progress.fail()
                })
        },

        deleteService: function(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$Progress.start()

                    axios.delete(`/api/room/services/${id}`)
                        .then(response => {
                            this.$Progress.finish()
                            this.fireSwal("success", "Deleted!", "Service deleted successfully!")
                            this.fetchServices()
                        })
                        .catch(error => {
                            this.$Progress.fail()
                        })
                }
            })
        }
    }
};
</script>

<style></style>

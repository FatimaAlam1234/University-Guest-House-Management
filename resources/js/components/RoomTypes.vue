<template>
    <div>
        <content-header page="Room Types">
            <template>
                <li class="breadcrumb-item">
                    <router-link to="/rooms">Rooms</router-link>
                </li>
                <li class="breadcrumb-item active">Room Types</li>
            </template>
        </content-header>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Room Types Table
                                </h3>

                                <div class="card-tools">
                                    <button
                                        type="submit"
                                        class="btn btn-success"
                                        @click="showCreateModal"
                                    >
                                        <i class="fas fa-plus mr-1"></i>
                                        Create new type
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Type Name</th>
                                            <th>Base Price</th>
                                            <th>Room Size</th>
                                            <th>Maximum Capacity</th>
                                            <th>Bed Type</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(type, index) in types.data" :key="type.id">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ type.name }}</td>
                                            <td><i class="fas fa-rupee-sign"></i> {{ type.price }}</td>
                                            <td>{{ type.size }} m<sup>2</sup></td>
                                            <td>Maximum {{ type.capacity }} person(s)</td>
                                            <td>{{ type.bed_type }}</td>
                                            <td>
                                                <button
                                                    class="btn btn-sm btn-success"
                                                    @click="showEditModal(type)"
                                                >
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button
                                                    class="btn btn-sm btn-danger"
                                                    @click="
                                                        deleteType(type.id)
                                                    "
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
                                <pagination :data="types" @pagination-change-page="getResults"></pagination>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>

        <modal
            :title="method == 'create' ? 'Create new type' : 'Update type'"
            :submit="method == 'create' ? 'Create type' : 'Update type'"
            @submitForm="submitForm"
        >
            <div class="form-group">
                <label for="name">Type name</label>
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
                <label for="price">Base Price</label>
                <input
                    v-model="form.price"
                    type="number"
                    step="0.01"
                    id="price"
                    class="form-control"
                    :class="{
                        'is-invalid': form.errors.has('price')
                    }"
                />
                <has-error :form="form" field="price"></has-error>
            </div>

            <div class="form-group">
                <label for="size">Room Size</label>
                <input
                    v-model="form.size"
                    type="number"
                    step="1"
                    id="size"
                    class="form-control"
                    :class="{
                        'is-invalid': form.errors.has('size')
                    }"
                />
                <has-error :form="form" field="size"></has-error>
            </div>

            <div class="form-group">
                <label for="capacity">Maximum Capacity</label>
                <input
                    v-model="form.capacity"
                    type="number"
                    step="0.01"
                    id="capacity"
                    class="form-control"
                    :class="{
                        'is-invalid': form.errors.has('capacity')
                    }"
                />
                <has-error :form="form" field="capacity"></has-error>
            </div>

            <div class="form-group">
                <label for="bed_type">Bed Type</label>
                <input
                    v-model="form.bed_type"
                    type="text"
                    id="bed_type"
                    class="form-control"
                    :class="{
                        'is-invalid': form.errors.has('bed_type')
                    }"
                />
                <has-error :form="form" field="bed_type"></has-error>
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
            method: "create",
            types: {},
            form: new Form({
                id: "",
                name: "",
                price: "",
                size: "",
                capacity: "",
                bed_type: ""
            })
        };
    },

    mounted: function() {
        this.fetchTypes();
    },

    methods: {
        getResults: function (page = 1) {
			axios.get('/api/room/types?page=' + page)
				.then(response => {
					this.types = response.data;
				});
		},
        
        showCreateModal: function() {
            this.method = "create";
            this.form.clear();
            this.form.reset();
            this.showModal();
        },

        showEditModal: function(type) {
            this.method = "update";
            this.form.clear()
            this.form.reset()
            this.form.fill(type);
            this.showModal();
        },

        submitForm: function(method) {
            this.method == "create" ? this.createType() : this.updateType();
        },

        fetchTypes: function() {
            axios
                .get("/api/room/types")
                .then(response => {
                    this.types = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        createType: function() {
            this.$Progress.start()
            this.form.post("/api/room/types")
                .then(() => {
                    this.$Progress.finish()
                    this.fireToast('success', 'Type created successfully!')
                    this.closeModal()
                    this.fetchTypes()
                })
                .catch(() => {
                    this.$Progress.fail()
                })
        },

        updateType: function() {
            this.$Progress.start()
            this.form.put(`/api/room/types/${this.form.id}`)
                .then(() => {
                    this.$Progress.finish()
                    this.fireToast('success', 'Type updated successfully!')
                    this.closeModal()
                    this.fetchTypes()
                })
                .catch(() => {
                    this.$Progress.fail()
                })
        },

        deleteType: function(id) {
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

                    axios.delete(`/api/room/types/${id}`)
                        .then(response => {
                            this.$Progress.finish()
                            this.fireSwal("success", "Deleted!", "Type deleted successfully!")
                            this.fetchTypes()
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

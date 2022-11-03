<template>
    <div>
        <content-header page="Users">
            <template>
                <li class="breadcrumb-item active">Users</li>
            </template>
        </content-header>

        <div class="content">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-success" @click="showCreateModal">
                            Add new user
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4" v-for="user in users.data" :key="user.id">
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img
                                        class="profile-user-img img-fluid img-circle"
                                        :src="profilePicture(user)"
                                    />
                                </div>

                                <h3 class="profile-username text-center">
                                    {{ user.username }}
                                </h3>

                                <p class="text-muted text-center">
                                    {{ user.level }}
                                </p>

                                <ul
                                    class="list-group list-group-unbordered mb-3"
                                >
                                    <li class="list-group-item">
                                        <b>First Name</b>
                                        <a class="float-right">{{
                                            user.first_name
                                        }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Last Name</b>
                                        <a class="float-right">{{
                                            user.last_name
                                        }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Email Address</b>
                                        <a class="float-right">{{
                                            user.email_address
                                        }}</a>
                                    </li>
                                </ul>

                                <router-link
                                    :to="`/profile/${user.id}`"
                                    class="btn btn-primary btn-block"
                                    >Check Profile</router-link
                                >
                                <button class="btn btn-danger btn-block" @click="deleteUser(user.id)" v-if="authenticatedUser.level == 'superadmin'">Delete user</button>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <pagination :data="users" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
            </div>
        </div>

        <modal title="Create new user" submit="Create user" method="create" @submitForm="submitForm">
            <template>
                <div class="form-group">
                    <label>Username</label>
                    <input
                        v-model="form.username"
                        type="text"
                        name="username"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.has('username') }"
                    />
                    <has-error :form="form" field="username"></has-error>
                </div>

                <div class="form-group">
                    <label>First Name</label>
                    <input
                        v-model="form.first_name"
                        type="text"
                        name="first_name"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.has('first_name') }"
                    />
                    <has-error :form="form" field="first_name"></has-error>
                </div>

                <div class="form-group">
                    <label>Last Name</label>
                    <input
                        v-model="form.last_name"
                        type="text"
                        name="last_name"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.has('last_name') }"
                    />
                    <has-error :form="form" field="last_name"></has-error>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input
                        v-model="form.email_address"
                        type="text"
                        name="email_address"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.has('email_address') }"
                    />
                    <has-error :form="form" field="email_address"></has-error>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input
                        v-model="form.password"
                        type="password"
                        name="password"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.has('password') }"
                    />
                    <has-error :form="form" field="password"></has-error>
                </div>

                <div class="form-group">
                    <label>Level</label>
                    <select
                        v-model="form.level"
                        name="level"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.has('level') }"
                    >
                        <option value="" hidden selected disabled>Select Level</option>
                        <option value="admin">Admin</option>
                        <option value="superadmin">Superadmin</option>
                    </select>
                    <has-error :form="form" field="level"></has-error>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import ContentHeader from "./layouts/ContentHeader";
import Modal from "./layouts/Modal";

export default {
    name: "Profile",

    components: {
        ContentHeader,
        Modal
    },

    data: function() {
        return {
            users: {},
            method: 'create',

            authenticatedUser: user,

            form: new Form({
                id: "",
                username: "",
                first_name: "",
                last_name: "",
                email_address: "",
                password: "",
                level: "",
                profile_picture: "",
                background_picture: ""
            })
        };
    },

    mounted: function() {
        this.fetchUser();
    },

    methods: {
        getResults: function (page = 1) {
            axios.get("/api/users?page=" + page)
                .then(response => {
                    this.users = response.data
                })
        },

        showCreateModal: function () {
            this.method = 'create'
            this.form.reset()
            this.form.clear()
            this.showModal()
        },
        
        profilePicture: function(user) {
            if (user.profile_picture != null) {
                if (user.profile_picture.startsWith("data")) {
                    return user.profile_picture;
                } else {
                    return `/img/uploads/profile_pictures/${user.profile_picture}`;
                }
            } else {
                return "/img/user.png";
            }
        },

        submitForm: function (method) {
            method == "create" ? this.createUser() : '';
        },

        fetchUser: function() {
            axios
                .get(`/api/users`)
                .then((response) => {
                    this.users = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        createUser: function() {
            this.$Progress.start();
            
            this.form.post("/api/users")
                .then(response => {
                    this.$Progress.finish();
                    this.fireSwal("success", "User created successfully!")
                    this.closeModal();
                    this.fetchUser();
                })
                .catch(error => {
                    this.$Progress.fail()
                })
        },

        deleteUser: function (id) {
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

                    axios.delete(`/api/users/${id}`)
                        .then(response => {
                            this.$Progress.finish()
                            this.fireSwal("success", "Deleted!", "User deleted successfully!")
                            this.fetchUser()
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

<template>
    <div>
        <content-header page="Profile">
                <template>
                    <li class="breadcrumb-item active">Profile</li>
                </template>
        </content-header>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile" :style="backgroundPicture()">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle" :src="profilePicture()">
                                </div>

                                <h3 class="profile-username text-center">{{ user.username }}</h3>

                                <p class="text-muted text-center">{{ user.level }}</p>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#general" data-toggle="tab">General</a></li>
                                </ul>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="general">
                                        <form role="form" @submit.prevent="updateUser">
                                            <div class="form-group">
                                                <label for="username">Username</label>
                                                <input type="text" v-model="form.username" name="username" id="username" class="form-control" :class="{ 'is-invalid': form.errors.has('username') }">
                                            </div>
                                            <has-error :form="form" field="username"></has-error>

                                            <div class="form-group">
                                                <label for="first_name">First Name</label>
                                                <input type="text" v-model="form.first_name" name="first_name" id="first_name" class="form-control" :class="{ 'is-invalid': form.errors.has('first_name') }">
                                            </div>
                                            <has-error :form="form" field="first_name"></has-error>

                                            <div class="form-group">
                                                <label for="last_name">Last Name</label>
                                                <input type="text" v-model="form.last_name" name="last_name" id="last_name" class="form-control" :class="{ 'is-invalid': form.errors.has('last_name') }">
                                            </div>
                                            <has-error :form="form" field="last_name"></has-error>

                                            <div class="form-group">
                                                <label for="email_address">Email Address</label>
                                                <input type="email" v-model="form.email_address" name="email_address" id="email_address" class="form-control" :class="{ 'is-invalid': form.errors.has('email_address') }">
                                            </div>
                                            <has-error :form="form" field="email_address"></has-error>

                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" v-model="form.password" name="password" id="password" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                            </div>
                                            <has-error :form="form" field="password"></has-error>
                                            
                                            <div class="form-group">
                                                <label for="level">Level</label>
                                                <select v-model="form.level" name="level" id="level" class="form-control" :class="{ 'is-invalid': form.errors.has('level') }">
                                                    <option value="admin" :selected="form.level == 'admin'">Admin</option>
                                                    <option value="superadmin" :selected="form.level == 'superadmin'">Super Admin</option>
                                                </select>
                                            </div>
                                            <has-error :form="form" field="level"></has-error>

                                            <div class="form-group">
                                                <label for="profile_picture">Profile Picture</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="profile_picture" id="profile_picture" @change="setProfilePicture">
                                                        <label class="custom-file-label" for="profile_picture">Choose file</label>
                                                    </div>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="">Upload</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="background_picture">Background Picture</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="background_picture" id="background_picture" @change="setBackgroundPicture">
                                                        <label class="custom-file-label" for="background_picture">Choose file</label>
                                                    </div>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="">Upload</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-block btn-success" v-if="authenticatedUser.id == user.id || authenticatedUser.level == 'superadmin'">Update profile</button>
                                        </form>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div><!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ContentHeader from './layouts/ContentHeader'

export default {
    name: 'ProfileDetail',

    components: {
        ContentHeader,
    },

    data: function () {
        return {
            authenticatedUser: user,
            id: this.$route.params.id,

            edit: false,
            user: [],

            form: new Form({
                id: '',
                username: '',
                first_name: '',
                last_name: '',
                email_address: '',
                password: '',
                level: '',
                profile_picture: '',
                background_picture: '',
            }),
        }
    },

    mounted: function () {
        this.fetchUser()
        this.profilePicture()
        this.backgroundPicture()  
    },

    methods: {
        profilePicture: function () {
            if ( this.form.profile_picture != null ) {
                if ( this.form.profile_picture.startsWith('data') ) {
                    return this.form.profile_picture
                } else {
                    return `/img/uploads/profile_pictures/${this.form.profile_picture}`
                }
            } else {
                return '/img/user.png'
            }
        },

        backgroundPicture: function () {
            if ( this.form.background_picture != null ) {
                if ( this.form.background_picture.startsWith('data') ) {
                    return {
                        'background': `url(${this.form.background_picture}) center center/cover`,
                    }
                }
                else {
                    return {
                        'background': `url(/img/uploads/background_pictures/${this.form.background_picture}) center center/cover`,
                    }
                }
            }
            else {
                return {
                    'background-color': `#14101a`,
                    'background-image': `url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='18' viewBox='0 0 100 18'%3E%3Cpath fill='%23003558' fill-opacity='0.4' d='M61.82 18c3.47-1.45 6.86-3.78 11.3-7.34C78 6.76 80.34 5.1 83.87 3.42 88.56 1.16 93.75 0 100 0v6.16C98.76 6.05 97.43 6 96 6c-9.59 0-14.23 2.23-23.13 9.34-1.28 1.03-2.39 1.9-3.4 2.66h-7.65zm-23.64 0H22.52c-1-.76-2.1-1.63-3.4-2.66C11.57 9.3 7.08 6.78 0 6.16V0c6.25 0 11.44 1.16 16.14 3.42 3.53 1.7 5.87 3.35 10.73 7.24 4.45 3.56 7.84 5.9 11.31 7.34zM61.82 0h7.66a39.57 39.57 0 0 1-7.34 4.58C57.44 6.84 52.25 8 46 8S34.56 6.84 29.86 4.58A39.57 39.57 0 0 1 22.52 0h15.66C41.65 1.44 45.21 2 50 2c4.8 0 8.35-.56 11.82-2z'%3E%3C/path%3E%3C/svg%3E")`,
                }
            }    
        },
        
        validatePictures: function (file) {
            const validExtensions = ['png', 'jpg', 'jpeg', 'svg']
            const fileType = file.type.split('/')[file.type.split('/').length - 1]
            const fileSize = file.size
            const checkFileType = validExtensions.indexOf(fileType) < 0
            const checkFileSize = fileSize > 2000000

            if (checkFileType) return { success: false, message: 'The file is not supported!'}
            if (checkFileSize) return { success: false, message: 'The file is too large' }
            if (!checkFileSize && !checkFileType) return { success: true, }
        },

        setProfilePicture: function (e) {
            const file = e.target.files[0]
            const reader = new FileReader()

            const validator = this.validatePictures(file)

            if (!validator.success) {
                this.fireSwal('error', 'Failed', validator.message)
            } else {
                reader.onloadend = file => {
                    this.form.profile_picture = reader.result
                }
                reader.readAsDataURL(file)
            }
        },

        setBackgroundPicture: function (e) {
            const file = e.target.files[0]
            const reader = new FileReader()

            const validator = this.validatePictures(file)

            if (!validator.success) {
                this.fireSwal('error', 'Failed', validator.message)
            } else {
                reader.onloadend = file => {
                    this.form.background_picture = reader.result
                }
                reader.readAsDataURL(file)
            }
        },
        
        fetchUser: function () {
            axios.get(`/api/users/${this.id}`)
                .then(({data}) => {
                    this.form.originalData = data[0]
                    this.form.fill(this.form.originalData)
                    this.user = this.form.originalData
                })
                .catch(error => {
                    console.log(error.message)
                })
        },

        updateUser: function () {
            if ( this.authenticatedUser.level != 'superadmin' && this.authenticatedUser.id != this.id ) {
                return this.fireSwal("error", "You are not authorized!")
            }
            
            if ( this.form.password == "" || this.form.password == null ) this.form.password = null

            this.$Progress.start();
            
            this.form.put(`/api/users/${this.form.id}`)
                .then(response => {
                    this.$Progress.finish()
                    this.fetchUser()
                    location.reload()
                })
                .catch(error => {
                    this.$Progress.fail()
                })
        }
    }
}
</script>

<style lang="scss" scoped>
.box-profile {
    h3, 
    p {
        margin: 0;
        color: #fff !important;
    }

    h3 { margin-top: 1rem; }

    p { opacity: 0.5; }
}
</style>
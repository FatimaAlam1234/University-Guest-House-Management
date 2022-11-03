<template>
    <div>
        <content-header page="Rooms">
            <template>
                <li class="breadcrumb-item active">Rooms</li>
            </template>
        </content-header>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-success" @click="showCreateModal">
                            <i class="fas fa-plus mr-1"></i>
                            Add new room
                        </button>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-4" v-for="(room) in rooms.data" :key="room.id">
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="d-flex align-items-center justify-content-center flex-column" :class="room.photo ? 'text-white' : ''" style="height: 200px" :style="room.photo ? `background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.75)), url(/img/uploads/rooms/${room.photo}) center center/cover` : ''">
                                    <h3 class="profile-username text-center mb-2">
                                        {{ room.name }}
                                    </h3>

                                    <p class="text-center m-0" style="opacity: 0.75">
                                        {{ room.type }} |Rs. {{ room.amount }}
                                    </p>
                                </div>

                                <ul
                                    class="list-group list-group-unbordered mb-3"
                                >
                                    <li class="list-group-item">
                                        <b>Room Price</b>
                                        <a class="float-right"><i class="fas fa-rupee-sign"></i>{{ room.price }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Room Size</b>
                                        <a class="float-right">{{ room.size }} m<sup>2</sup></a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Room Capacity</b>
                                        <a class="float-right">Maximum {{ room.capacity }} person(s)</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Bed Type</b>
                                        <a class="float-right">{{ room.bed_type }}</a>
                                    </li>
                                </ul>

                                <router-link
                                    :to="`/rooms/${room.id}`"
                                    class="btn btn-primary btn-block"
                                    >Check Room</router-link
                                >
                                <button class="btn btn-danger btn-block" @click="deleteRoom(room.id)">Delete room</button>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <pagination :data="rooms" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
            </div>
        </div>

        <modal
            :title="method == 'create' ? 'Create new room' : 'Edit room'"
            :submit="method == 'create' ? 'Create room' : 'Update room'"
            @submitForm="submitForm"
        >
            <template>
                <div class="form-group">
                    <label for="name">Room Name</label>
                    <input 
                        v-model="form.name"
                        type="text"
                        id="name"
                        class="form-control"
                        :class="{
                            'is-invalid': form.errors.has('name')
                        }"
                    >
                    <has-error :form="form" field="name"></has-error>
                </div>

                <div class="form-group">
                    <label for="room_type_id">Room Type</label>
                    <select 
                        v-model="form.room_type_id" 
                        id="room_type_id"
                        class="form-control"
                        :class="{
                            'is-invalid': form.errors.has('room_type_id')
                        }"
                    >
                        <option value="">Select Type</option>
                        <option v-for="type in types" :key="type.id" :value="type.id">{{ type.name }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="amount">Room Amount</label>
                    <input 
                        v-model="form.amount"
                        type="number"
                        id="amount"
                        class="form-control"
                        :class="{
                            'is-invalid': form.errors.has('amount')
                        }"
                        min="0"
                    >
                    <has-error :form="form" field="amount"></has-error>
                </div>

                <div class="form-group">
                    <label for="photo">Room Photo</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="photo" @change="setPhoto">
                            <label class="custom-file-label" for="photo">Choose file</label>
                        </div>
                    </div>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import ContentHeader from './layouts/ContentHeader'
import Modal from './layouts/Modal'

export default {
    name: 'Rooms',

    components: {
        ContentHeader,
        Modal,
    },

    data: function () {
        return {
            method: 'create',
            rooms: {},
            types: {},
            form: new Form({
                id: '',
                photo: '',
                name: '',
                room_type_id: '',
                amount: '',
            }),
        }
    },

    mounted: function () {
        this.fetchRooms()
        this.fetchRoomTypes()
    },

    methods: {
        getResults: function (page = 1) {
            axios.get("/api/rooms?page=" + page)
                .then(response => {
                    this.rooms = response.data
                })
        },
        
        showCreateModal: function () {
            this.method = 'create'
            this.form.clear()
            this.form.reset()
            this.showModal()
        },

        submitForm: function () {
            this.method == 'create' ? this.createRoom() : this.updateRoom()
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

        setPhoto: function (e) {
            const file = e.target.files[0]
            const reader = new FileReader()

            const validator = this.validatePictures(file)

            if (!validator.success) return this.fireSwal('error', 'Failed!', validator.message)
            else {
                reader.onloadend = (file) => {
                    this.form.photo = reader.result
                }
    
                reader.readAsDataURL(file)
            }

        },
        
        fetchRooms: function () {
            axios.get("/api/rooms")
                .then((response) => {
                    this.rooms = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        },

        fetchRoomTypes: function () {
            axios.get("/api/room/type")
                .then((response) => {
                    this.types = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        },

        createRoom: function () {
            this.$Progress.start()
            this.form.post("/api/rooms")
                .then((response) => {
                    this.$Progress.finish()
                    this.fireToast('success', 'Room created successfully!')
                    this.closeModal()
                    this.fetchRooms()
                })
                .catch(() => {
                    this.$Progress.fail()
                })
        },

        deleteRoom: function (id) {
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
                    axios.delete(`/api/rooms/${id}`)
                        .then(response => {
                            this.$Progress.finish()
                            this.fireSwal("success", "Deleted!", "Room deleted successfully!")
                            this.fetchRooms()
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

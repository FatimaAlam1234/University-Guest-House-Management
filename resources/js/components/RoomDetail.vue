<template>
    <div>
        <content-header page="Room Detail">
            <template>
                <li class="breadcrumb-item">
                    <router-link to="/rooms">Rooms</router-link>
                </li>
                <li class="breadcrumb-item active">Room Detail</li>
            </template>
        </content-header>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div
                                class="card-body box-profile d-flex align-items-center justify-content-center flex-column"
                                :class="room.photo ? 'text-white' : ''"
                                :key="room.id"
                                :style="
                                    room.photo
                                        ? `background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.75)), url(/img/uploads/rooms/${room.photo}) center center/cover`
                                        : ''
                                "
                            >
                                <h3 class="profile-username text-center">
                                    {{ room.name }}
                                </h3>

                                <p class="text-center" style="opacity: 0.75">
                                    {{ type.name }} |
                                    {{ room.amount }} Rs.
                                </p>
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
                                    <li class="nav-item">
                                        <a
                                            class="nav-link active"
                                            href="#general"
                                            data-toggle="tab"
                                            >General</a
                                        >
                                    </li>
                                </ul>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="general">
                                        <form
                                            role="form"
                                            @submit.prevent="updateRoom"
                                        >
                                            <div class="form-group">
                                                <label for="name"
                                                    >Room Name</label
                                                >
                                                <input
                                                    v-model="form.name"
                                                    type="text"
                                                    id="name"
                                                    class="form-control"
                                                    :class="{
                                                        'is-invalid': form.errors.has(
                                                            'name'
                                                        )
                                                    }"
                                                />
                                                <has-error
                                                    :form="form"
                                                    field="name"
                                                ></has-error>
                                            </div>

                                            <div class="form-group">
                                                <label for="room_type_id"
                                                    >Room Type</label
                                                >
                                                <select
                                                    v-model="form.room_type_id"
                                                    id="room_type_id"
                                                    class="form-control"
                                                    :class="{
                                                        'is-invalid': form.errors.has(
                                                            'room_type_id'
                                                        )
                                                    }"
                                                >
                                                    <option
                                                        v-for="type in types"
                                                        :key="type.id"
                                                        :value="type.id"
                                                        :selected="
                                                            type.id ==
                                                                room.room_type_id
                                                        "
                                                    >
                                                        {{ type.name }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="amount"
                                                    >Room Amount</label
                                                >
                                                <input
                                                    v-model="form.amount"
                                                    type="number"
                                                    id="amount"
                                                    class="form-control"
                                                    :class="{
                                                        'is-invalid': form.errors.has(
                                                            'amount'
                                                        )
                                                    }"
                                                    step="1"
                                                />
                                            </div>

                                            <div class="form-group">
                                                <label for="photo"
                                                    >Room Photo</label
                                                >
                                                <div class="input-group mb-3">
                                                    <div
                                                        class="input-group-prepend"
                                                    >
                                                        <span
                                                            class="input-group-text"
                                                            >Upload</span
                                                        >
                                                    </div>
                                                    <div class="custom-file">
                                                        <input
                                                            type="file"
                                                            class="custom-file-input"
                                                            id="photo"
                                                            @change="setPhoto"
                                                        />
                                                        <label
                                                            class="custom-file-label"
                                                            for="photo"
                                                            >Choose file</label
                                                        >
                                                    </div>
                                                </div>
                                            </div>

                                            <button
                                                type="submit"
                                                class="btn btn-block btn-success"
                                            >
                                                Update Room
                                            </button>
                                        </form>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ContentHeader from "./layouts/ContentHeader";

export default {
    name: "RoomDetail",

    components: {
        ContentHeader
    },

    data: function() {
        return {
            id: this.$route.params.id,
            room: [],
            types: [],
            form: new Form({
                id: "",
                name: "",
                room_type_id: "",
                amount: "",
                photo: ""
            })
        };
    },

    computed: {
        type: function () {
            return this.types.filter(type => {
                return type.id == this.room.room_type_id
            })[0]
        }
    },

    mounted: function() {
        this.fetchRoom();
        this.fetchRoomTypes();
    },

    methods: {
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

            if ( !validator.success ) {
                this.form.photo = this.room.photo
                return this.fireSwal("error", validator.message)
            } else {
                reader.onloadend = file => {
                    this.form.photo = reader.result
                }
                reader.readAsDataURL(file)
            }
        },
        
        fetchRoom: function() {
            axios
                .get(`/api/rooms/${this.id}`)
                .then(({ data }) => {
                    this.room = data;
                    this.form.fill(data);
                })
                .catch(error => {
                    console.log(error);
                });
        },

        fetchRoomTypes: function() {
            axios
                .get("/api/room/type")
                .then(({ data }) => {
                    this.types = data;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        updateRoom: function () {
            this.$Progress.start()
            this.form.put(`/api/rooms/${this.room.id}`)
                .then(response => {
                    this.$Progress.finish()
                    this.fireToast('success', 'Room updated successfully!')
                    this.closeModal()
                    this.fetchRoom()
                })
                .catch(error => {
                    this.$Progress.fail()
                })
        }
    }
};
</script>

<style lang="scss" scoped>
.box-profile {
    height: 250px;

    h3,
    p {
        margin: 0;
        // color: #fff !important;
    }

    h3 {
        margin-top: 1rem;
    }

    p {
        opacity: 0.5;
    }
}
</style>

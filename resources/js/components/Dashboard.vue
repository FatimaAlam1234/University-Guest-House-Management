<template>
    <div>
        <content-header page="Dashboard">
            <template>
                <li class="breadcrumb-item active">Dashboard</li>
            </template>
        </content-header>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{guests}}</h3>

                                <p>Guests</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="/guests" class="small-box-footer"
                                >More info <i class="fas fa-arrow-circle-right"></i
                            ></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{rooms}}</h3>

                                <p>Total Rooms</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="/rooms" class="small-box-footer"
                                >More info <i class="fas fa-arrow-circle-right"></i
                            ></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{rooms_types}}</h3>

                                <p>Room Types</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="/room/types" class="small-box-footer"
                                >More info <i class="fas fa-arrow-circle-right"></i
                            ></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{services}}</h3>

                                <p>Services</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="/room/services" class="small-box-footer"
                                >More info <i class="fas fa-arrow-circle-right"></i
                            ></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
</template>

<script>
import ContentHeader from './layouts/ContentHeader'

export default {
    name: 'Dashboard',

    components: {
        ContentHeader,
    },
     data: function() {
        return {
            guests:"",
            rooms :"",
            rooms_types :"",
            services :""
        };
    },
    mounted: function () {
        this.fetchGuestHouseInfo()
    },
     methods: {
         fetchGuestHouseInfo: function () {
            axios.get("/api/guestHouseInfo")
                .then((response) => {
                    console.log(response.data);
                    this.guests = response.data.guests,
                    this.rooms = response.data.rooms,
                    this.rooms_types = response.data.roomTypes,
                    this.services = response.data.services
                })
                .catch(error => {
                    console.log(error)
                })
        },
     }
};
</script>

<style></style>

import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
    {
        path: '/dashboard',
        name: 'dashboard',
        component: require('../components/Dashboard.vue').default
    },
    {
        path: '/users',
        name: 'users',
        component: require('../components/Users.vue').default
    },
    {
        path: '/profile/:id',
        name: 'profile',
        component: require('../components/Profile.vue').default
    },
    {
        path: '/guests',
        name: 'guests',
        component: require('../components/Guests.vue').default
    },
    {
        path: '/rooms',
        name: 'rooms',
        component: require('../components/Rooms.vue').default,
    },
    {
        path: '/rooms/:id',
        name: 'room_detail',
        component: require('../components/RoomDetail.vue').default,
    },
    {
        path: '/room/types',
        name: 'room_types',
        component: require('../components/RoomTypes.vue').default,
    },
    {
        path: '/room/services',
        name: 'room_services',
        component: require('../components/RoomServices.vue').default,
    },
    {
        path: '/reservations',
        name: 'reservations',
        component: require('../components/Reservations.vue').default,
    },
    {
        path: '/reservation/checkIns',
        name: 'checkIns',
        component: require('../components/checkIns.vue').default,
    },
    {
        path: '/bill/:id',
        name: 'generate_bill',
        component: require('../components/Bill.vue').default,
    },
]

export default new VueRouter({
    mode: 'history',
    routes,
})
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");
require("admin-lte");

window.Vue = require("vue");

// Vue Router
import router from "./router";

// V Form
import { Form, HasError, AlertError } from "vform";

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

window.Form = Form;

/**
 * This section of the code is used to import and configure javascript
 * libraries and dependencies. Including importing components creating
 * Vue global configs.
 */

// Sweet Alert 2
import Swal from "sweetalert2";

window.Swal = Swal;

// Vue Progress Bar
import VueProgressBar from "vue-progressbar";

const options = {
    color: "#bffaf3",
    failedColor: "#874b4b",
    thickness: "5px",
    transition: {
        speed: "0.2s",
        opacity: "0.6s",
        termination: 300,
    },
    autoRevert: true,
    location: "top",
    inverse: false,
};

Vue.use(VueProgressBar, options);

// Laravel Vue Pagination
Vue.component('pagination', require('laravel-vue-pagination'));

// Global Methods
Vue.mixin({
    methods: {
        fireToast: function (icon, title) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 2000,
            });

            Toast.fire({
                icon,
                title,
            });
        },

        fireSwal: function (icon, title, message) {
            Swal.fire(title, message, icon);
        },

        showModal: function () {
            $('#exampleModal').modal('show')
        },

        closeModal: function () {
            $("#exampleModal").modal("hide");
        },
    },
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
    router,
});

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue';
import Vuelidate from "vuelidate";
import store from './store/index'
import Dayjs from 'vue-dayjs';
import vSelect from 'vue-select'

Vue.component('v-select', vSelect)
window.axios = require('axios');
Vue.use(Dayjs);

Vue.use(Vuelidate);
Vue.use(require('vue-cookies'));
window.Vue = Vue;


window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


//Curated
Vue.component('vue-community-index', () => import(/* webpackChunkName: "assets/vci" */ './pages/Curated/index.vue'));
Vue.component('vue-community-show', () => import(/* webpackChunkName: "assets/vcs" */ './pages/Curated/show.vue'));
Vue.component('vue-community-create', () => import(/* webpackChunkName: "assets/vcc" */ './pages/Curated/create.vue'));
Vue.component('vue-community-edit', () => import(/* webpackChunkName: "assets/vce" */ './pages/Curated/edit.vue'));

Vue.component('vue-post-show', () => import(/* webpackChunkName: "assets/vps" */ './pages/Curated/Posts/show.vue'));
Vue.component('vue-post-edit', () => import(/* webpackChunkName: "assets/vpe" */ './pages/Curated/Posts/edit.vue'));
Vue.component('vue-post-create', () => import(/* webpackChunkName: "assets/vpc" */ './pages/Curated/Posts/create.vue'));

Vue.component('vue-community-admin-approval', () => import(/* webpackChunkName: "assets/vcaa" */ './pages/Admin/Communities/admin-communities-queue.vue'));
Vue.component('vue-community-approval-bar', () => import(/* webpackChunkName: "assets/vcab" */ './pages/Admin/Communities/admin-communities-approval.vue'));
Vue.component('vue-admin-communities-index', () => import(/* webpackChunkName: "assets/vcae" */ './pages/Admin/Communities/admin-communities-index.vue'));


//Docks
Vue.component('dock-index', () => import(/* webpackChunkName: "assets/ds" */ './pages/Admin/Featured/admin-docks-index.vue'));

//Nav
Vue.component('vue-nav', () => import(/* webpackChunkName: "assets/vnd" */ './pages/Header/header-desktop.vue'));
Vue.component('vue-nav-mobile', () => import(/* webpackChunkName: "assets/vnm" */ './pages/Header/header-mobile.vue'));

//Footer
Vue.component('vue-footer', () => import(/* webpackChunkName: "assets/lfo" */ './pages/Footer/footer.vue'));





//Home
Vue.component('index', () => import(/* webpackChunkName: "assets/ei" */ './pages/Home/index.vue'));

//Components
Vue.component('modal-wrapper', () => import(/* webpackChunkName: "assets/mw" */ './components/modals/modal-wrapper.vue'));
Vue.component('vue-email-verify', () => import(/* webpackChunkName: "assets/lev" */ './components/modals/Components/email-verify-modal.vue'));

//Creation 
Vue.component('vue-creation-idea', () => import(/* webpackChunkName: "assets/cvci" */ './pages/Creation/index.vue'));


//Create Events
Vue.component('vue-create-nav', () => import(/* webpackChunkName: "assets/cn" */ './pages/create/nav.vue'));
Vue.component('vue-create-organizer-nav', () => import(/* webpackChunkName: "assets/con" */ './pages/create/organizer-nav.vue'));
Vue.component('vue-create-nav-bottom-bar', () => import(/* webpackChunkName: "assets/cbn" */ './pages/create/nav-bottom-bar.vue'));
Vue.component('create-location-google', () => import(/* webpackChunkName: "assets/cl" */ './pages/create/location-google.vue'));
Vue.component('create-category', () => import(/* webpackChunkName: "assets/cc" */ './pages/create/category.vue'));
Vue.component('create-title', () => import(/* webpackChunkName: "assets/ct" */ './pages/create/title.vue'));
Vue.component('create-image', () => import(/* webpackChunkName: "assets/ci" */ './pages/create/image.vue'));
Vue.component('create-shows', () => import(/* webpackChunkName: "assets/cs" */ './pages/create/shows.vue'));
Vue.component('vue-create-tickets', () => import(/* webpackChunkName: "assets/ct" */ './pages/create/tickets.vue'));
Vue.component('show-listing', () => import(/* webpackChunkName: "assets/csl" */ './pages/create/show-listing.vue'));
Vue.component('create-description', () => import(/* webpackChunkName: "assets/cd" */ './pages/create/description.vue'));
Vue.component('create-advisories', () => import(/* webpackChunkName: "assets/ca" */ './pages/create/advisories.vue'));
Vue.component('vue-create-review-event', () => import(/* webpackChunkName: "assets/cre" */ './pages/create/review-event.vue'));
Vue.component('vue-create-first-event', () => import(/* webpackChunkName: "assets/cs" */ './pages/create/start-event-creation.vue'));



//Admin Area
Vue.component('admin-nav', () => import(/* webpackChunkName: "assets/an" */ './pages/Admin/nav.vue'));
Vue.component('admin-categories', () => import(/* webpackChunkName: "assets/ac" */ './pages/Admin/Creation/admin-categories-index.vue'));
Vue.component('admin-contact-level', () => import(/* webpackChunkName: "assets/acl" */ './pages/Admin/Creation/admin-contact-levels-index.vue'));
Vue.component('admin-genres', () => import(/* webpackChunkName: "assets/ag" */ './pages/Admin/Creation/admin-genres-index.vue'));
Vue.component('vue-admin-stats', () => import(/* webpackChunkName: "assets/as" */ './pages/Admin/admin-dashboard.vue'));
Vue.component('admin-staff-picks', () => import(/* webpackChunkName: "assets/ap" */ './pages/Admin/admin-staff-picks.vue'));
Vue.component('admin-mobility', () => import(/* webpackChunkName: "assets/am" */ './pages/Admin/Creation/admin-mobility-index.vue'));
Vue.component('admin-user', () => import(/* webpackChunkName: "assets/au" */ './pages/Admin/Users/admin-users-index.vue'));
Vue.component('admin-content-advisories', () => import(/* webpackChunkName: "assets/aca" */ './pages/Admin/Creation/admin-content-advisories-index.vue'));
Vue.component('vue-admin-remote-locations', () => import(/* webpackChunkName: "assets/arl" */ './pages/Admin/Creation/admin-remote-locations-index.vue'));
Vue.component('vue-admin-interactive-levels', () => import(/* webpackChunkName: "assets/ail" */ './pages/Admin/Creation/admin-interactive-levels-index.vue'));

//Profile
Vue.component('user-profile', () => import(/* webpackChunkName: "assets/up" */  './pages/Profile/profile-show.vue'));
Vue.component('vue-user-notifications', () => import(/* webpackChunkName: "assets/un" */  './pages/Profile/notifications.vue'));
Vue.component('vue-user-account', () => import(/* webpackChunkName: "assets/ua" */  './pages/Profile/account.vue'));

// Messages
Vue.component('message-index', () => import(/* webpackChunkName: "assets/mi" */  './pages/Messages/message-index.vue'));

//Organizers
Vue.component('organizer-show', () => import(/* webpackChunkName: "assets/os" */  './pages/Organizers/organizer-show.vue'));
Vue.component('organizer-create', () => import(/* webpackChunkName: "assets/oc" */ './pages/Organizers/organizer-create.vue'));
Vue.component('vue-org-approval-bar', () => import(/* webpackChunkName: "assets/aab" */ './pages/Admin/Organizers/admin-organizers-approval.vue'));
Vue.component('vue-org-approval-list', () => import(/* webpackChunkName: "assets/al" */ './pages/Admin/Organizers/admin-organizers-queue.vue'));
Vue.component('admin-organizer', () => import(/* webpackChunkName: "assets/ao" */ './pages/Admin/Organizers/admin-organizers-index.vue'));

//Events
Vue.component('event-show', () => import(/* webpackChunkName: "assets/es" */ './pages/Events/event-show.vue'));
Vue.component('vue-event-edit', () => import(/* webpackChunkName: "assets/ee" */ './pages/Events/event-edit.vue'));
Vue.component('vue-search-online', () => import(/* webpackChunkName: "assets/eos" */ './pages/Search/vue-search-online.vue'));
Vue.component('vue-search-location', () => import(/* webpackChunkName: "assets/esl" */ './pages/Search/vue-search-location.vue'));
Vue.component('vue-search-all', () => import(/* webpackChunkName: "assets/esa" */ './pages/Search/vue-search-all.vue'));

Vue.component('vue-admin-events', () => import(/* webpackChunkName: "assets/ae" */ './pages/Admin/Events/admin-events-index.vue'));
Vue.component('vue-admin-boneyard', () => import(/* webpackChunkName: "assets/ab" */ './pages/Admin/Events/admin-events-boneyard.vue'));
Vue.component('vue-admin-purgatory', () => import(/* webpackChunkName: "assets/apg" */ './pages/Admin/Events/admin-events-purgatory.vue'));
Vue.component('vue-admin-event-requests', () => import(/* webpackChunkName: "assets/aers" */ './pages/Admin/Events/admin-events-requests.vue'));
Vue.component('admin-event-reviews', () => import(/* webpackChunkName: "assets/aer" */ './pages/Admin/Events/admin-events-reviews.vue'));
Vue.component('vue-admin-event-show', () => import(/* webpackChunkName: "assets/aes" */ './pages/Admin/Events/Components/event-show-component.vue'));
Vue.component('approval-bar', () => import(/* webpackChunkName: "assets/aab" */ './pages/Admin/Events/admin-events-approval.vue'));
Vue.component('approval-list', () => import(/* webpackChunkName: "assets/al" */ './pages/Admin/Events/admin-events-queue.vue'));

//Staffpicks
Vue.component('vue-staffpicks-show', () => import(/* webpackChunkName: "assets/ssp" */ './pages/staffpicks/staffpicks-show.vue'));


//Login 
Vue.component('vue-register', () => import(/* webpackChunkName: "assets/lr" */ './pages/Auth/register.vue'));
Vue.component('vue-login', () => import(/* webpackChunkName: "assets/lr" */ './pages/Auth/login.vue'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


if(document.getElementById("header")){
    const header = new Vue({
        el: '#header',
        store,
    });
}
if(document.getElementById("create")){
    const create = new Vue({
        el: '#create',
        store,
    });
}
if(document.getElementById("admin")){
    const admin = new Vue({
        el: '#admin',
    });
}
if(document.getElementById("bodyArea")){
    const bodyArea = new Vue({
        el: '#bodyArea',
        store,
    });
}
if(document.getElementById("footer")){
    const footer = new Vue({
        el: '#footer',
    });
}
if(document.getElementById("flash")){
    const flash = new Vue({
        el: '#flash',
    });
}

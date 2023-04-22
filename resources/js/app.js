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

Vue.prototype.$envImageUrl = process.env.MIX_IMAGE_URL,

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


//Curated
Vue.component('vue-community-index', () => import(/* webpackChunkName: "js/vci" */ './pages/Curated/index.vue'));
Vue.component('vue-community-show', () => import(/* webpackChunkName: "js/vcs" */ './pages/Curated/show.vue'));
Vue.component('vue-community-create', () => import(/* webpackChunkName: "js/vcc" */ './pages/Curated/create.vue'));
Vue.component('vue-community-edit', () => import(/* webpackChunkName: "js/vce" */ './pages/Curated/edit.vue'));

Vue.component('vue-post-show', () => import(/* webpackChunkName: "js/vps" */ './pages/Curated/Posts/show.vue'));
Vue.component('vue-post-edit', () => import(/* webpackChunkName: "js/vpe" */ './pages/Curated/Posts/edit.vue'));
Vue.component('vue-post-create', () => import(/* webpackChunkName: "js/vpc" */ './pages/Curated/Posts/create.vue'));

Vue.component('vue-community-admin-approval', () => import(/* webpackChunkName: "js/vcaa" */ './pages/Admin/Communities/admin-communities-queue.vue'));
Vue.component('vue-community-approval-bar', () => import(/* webpackChunkName: "js/vcab" */ './pages/Admin/Communities/admin-communities-approval.vue'));
Vue.component('vue-admin-communities-index', () => import(/* webpackChunkName: "js/vcae" */ './pages/Admin/Communities/admin-communities-index.vue'));


//Docks
Vue.component('dock-index', () => import(/* webpackChunkName: "js/ds" */ './pages/Admin/Featured/admin-docks-index.vue'));

//Nav
Vue.component('vue-nav', () => import(/* webpackChunkName: "js/vnd" */ './pages/Header/header-desktop.vue'));
Vue.component('vue-nav-mobile', () => import(/* webpackChunkName: "js/vnm" */ './pages/Header/header-mobile.vue'));

//Footer
Vue.component('vue-footer', () => import(/* webpackChunkName: "js/lfo" */ './pages/Footer/footer.vue'));


//Home
Vue.component('index', () => import(/* webpackChunkName: "js/ei" */ './pages/Home/index.vue'));
Vue.component('index-mobile', () => import(/* webpackChunkName: "js/eim" */ './pages/Home/index-mobile.vue'));

//Components
Vue.component('modal-wrapper', () => import(/* webpackChunkName: "js/mw" */ './components/modals/modal-wrapper.vue'));
Vue.component('vue-email-verify', () => import(/* webpackChunkName: "js/lev" */ './components/modals/Components/email-verify-modal.vue'));

//Creation 
Vue.component('vue-creation-index', () => import(/* webpackChunkName: "js/ivci" */ './pages/Creation/index.vue'));


//Create Events
Vue.component('vue-create-nav', () => import(/* webpackChunkName: "js/cn" */ './pages/create/nav.vue'));
Vue.component('vue-create-organizer-nav', () => import(/* webpackChunkName: "js/con" */ './pages/create/organizer-nav.vue'));
Vue.component('vue-create-nav-bottom-bar', () => import(/* webpackChunkName: "js/cbn" */ './pages/create/nav-bottom-bar.vue'));
Vue.component('create-location-google', () => import(/* webpackChunkName: "js/cl" */ './pages/create/location-google.vue'));
Vue.component('create-category', () => import(/* webpackChunkName: "js/cc" */ './pages/create/category.vue'));
Vue.component('create-title', () => import(/* webpackChunkName: "js/ct" */ './pages/create/title.vue'));
Vue.component('create-image', () => import(/* webpackChunkName: "js/ci" */ './pages/create/image.vue'));
Vue.component('create-shows', () => import(/* webpackChunkName: "js/cs" */ './pages/create/shows.vue'));
Vue.component('vue-create-tickets', () => import(/* webpackChunkName: "js/ct" */ './pages/create/tickets.vue'));
Vue.component('show-listing', () => import(/* webpackChunkName: "js/csl" */ './pages/create/show-listing.vue'));
Vue.component('create-description', () => import(/* webpackChunkName: "js/cd" */ './pages/create/description.vue'));
Vue.component('create-advisories', () => import(/* webpackChunkName: "js/ca" */ './pages/create/advisories.vue'));
Vue.component('vue-create-review-event', () => import(/* webpackChunkName: "js/cre" */ './pages/create/review-event.vue'));
Vue.component('vue-create-first-event', () => import(/* webpackChunkName: "js/cs" */ './pages/create/start-event-creation.vue'));



//Admin Area
Vue.component('admin-nav', () => import(/* webpackChunkName: "js/an" */ './pages/Admin/nav.vue'));
Vue.component('admin-categories', () => import(/* webpackChunkName: "js/ac" */ './pages/Admin/Creation/admin-categories-index.vue'));
Vue.component('admin-contact-level', () => import(/* webpackChunkName: "js/acl" */ './pages/Admin/Creation/admin-contact-levels-index.vue'));
Vue.component('admin-genres', () => import(/* webpackChunkName: "js/ag" */ './pages/Admin/Creation/admin-genres-index.vue'));
Vue.component('vue-admin-stats', () => import(/* webpackChunkName: "js/as" */ './pages/Admin/admin-dashboard.vue'));
Vue.component('admin-staff-picks', () => import(/* webpackChunkName: "js/ap" */ './pages/Admin/admin-staff-picks.vue'));
Vue.component('admin-mobility', () => import(/* webpackChunkName: "js/am" */ './pages/Admin/Creation/admin-mobility-index.vue'));
Vue.component('admin-user', () => import(/* webpackChunkName: "js/au" */ './pages/Admin/Users/admin-users-index.vue'));
Vue.component('admin-content-advisories', () => import(/* webpackChunkName: "js/aca" */ './pages/Admin/Creation/admin-content-advisories-index.vue'));
Vue.component('vue-admin-remote-locations', () => import(/* webpackChunkName: "js/arl" */ './pages/Admin/Creation/admin-remote-locations-index.vue'));
Vue.component('vue-admin-interactive-levels', () => import(/* webpackChunkName: "js/ail" */ './pages/Admin/Creation/admin-interactive-levels-index.vue'));

//Profile
Vue.component('user-profile', () => import(/* webpackChunkName: "js/up" */  './pages/Profile/profile-show.vue'));
Vue.component('vue-user-notifications', () => import(/* webpackChunkName: "js/un" */  './pages/Profile/notifications.vue'));
Vue.component('vue-user-account', () => import(/* webpackChunkName: "js/ua" */  './pages/Profile/account.vue'));

// Messages
Vue.component('message-index', () => import(/* webpackChunkName: "js/mi" */  './pages/Messages/message-index.vue'));

//Organizers
Vue.component('organizer-show', () => import(/* webpackChunkName: "js/os" */  './pages/Organizers/organizer-show.vue'));
Vue.component('organizer-create', () => import(/* webpackChunkName: "js/oc" */ './pages/Organizers/organizer-create.vue'));
Vue.component('vue-org-approval-bar', () => import(/* webpackChunkName: "js/aab" */ './pages/Admin/Organizers/admin-organizers-approval.vue'));
Vue.component('vue-org-approval-list', () => import(/* webpackChunkName: "js/al" */ './pages/Admin/Organizers/admin-organizers-queue.vue'));
Vue.component('admin-organizer', () => import(/* webpackChunkName: "js/ao" */ './pages/Admin/Organizers/admin-organizers-index.vue'));

//Events
Vue.component('event-show', () => import(/* webpackChunkName: "js/es" */ './pages/Events/event-show.vue'));
Vue.component('vue-event-edit', () => import(/* webpackChunkName: "js/ee" */ './pages/Events/event-edit.vue'));
Vue.component('vue-search-online', () => import(/* webpackChunkName: "js/eos" */ './pages/Search/search-at-home.vue'));
Vue.component('vue-search-online-mobile', () => import(/* webpackChunkName: "js/eosm" */ './pages/Search/search-at-home-mobile.vue'));
Vue.component('vue-search-location', () => import(/* webpackChunkName: "js/esl" */ './pages/Search/search-in-person.vue'));
Vue.component('vue-search-location-mobile', () => import(/* webpackChunkName: "js/eslm" */ './pages/Search/search-in-person-mobile.vue'));
Vue.component('vue-search-all', () => import(/* webpackChunkName: "js/esa" */ './pages/Search/vue-search-all.vue'));
Vue.component('vue-search-all-mobile', () => import(/* webpackChunkName: "js/esam" */ './pages/Search/vue-search-all-mobile.vue'));

Vue.component('vue-admin-events', () => import(/* webpackChunkName: "js/ae" */ './pages/Admin/Events/admin-events-index.vue'));
Vue.component('vue-admin-boneyard', () => import(/* webpackChunkName: "js/ab" */ './pages/Admin/Events/admin-events-boneyard.vue'));
Vue.component('vue-admin-purgatory', () => import(/* webpackChunkName: "js/apg" */ './pages/Admin/Events/admin-events-purgatory.vue'));
Vue.component('vue-admin-event-requests', () => import(/* webpackChunkName: "js/aers" */ './pages/Admin/Events/admin-events-requests.vue'));
Vue.component('admin-event-reviews', () => import(/* webpackChunkName: "js/aer" */ './pages/Admin/Events/admin-events-reviews.vue'));
Vue.component('vue-admin-event-show', () => import(/* webpackChunkName: "js/aes" */ './pages/Admin/Events/Components/event-show-component.vue'));
Vue.component('approval-bar', () => import(/* webpackChunkName: "js/aab" */ './pages/Admin/Events/admin-events-approval.vue'));
Vue.component('approval-list', () => import(/* webpackChunkName: "js/al" */ './pages/Admin/Events/admin-events-queue.vue'));

//Staffpicks
Vue.component('vue-staffpicks-show', () => import(/* webpackChunkName: "js/ssp" */ './pages/staffpicks/staffpicks-show.vue'));


//Login 
Vue.component('vue-register', () => import(/* webpackChunkName: "js/lr" */ './pages/Auth/register.vue'));
Vue.component('vue-login', () => import(/* webpackChunkName: "js/lr" */ './pages/Auth/login.vue'));

// Components 
Vue.component('show-more', () => import(/* webpackChunkName: "js/smo" */ './components/ShowMoreBlade.vue'));
Vue.component('video-player', () => import(/* webpackChunkName: "js/vpo" */ './pages/Events/Show/VideoPlayer.vue'));
Vue.component('event-show-quickbuy', () => import(/* webpackChunkName: "js/esqb" */ './pages/Events/Show/event-show-quickbuy.vue'));
Vue.component('event-show-map', () => import(/* webpackChunkName: "js/eslc" */ './pages/Events/Show/event-show-location.vue'));
Vue.component('event-show-dates', () => import(/* webpackChunkName: "js/esdc" */ './pages/Events/Show/event-show-dates.vue'));
Vue.component('event-show-favorite', () => import(/* webpackChunkName: "js/esfc" */ './pages/Events/Show/Components/event-favorite.vue'));

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

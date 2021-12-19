/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue';
import Vuelidate from "vuelidate";
import store from './store/index'
import { Icon }  from 'leaflet';
import 'leaflet/dist/leaflet.css';
import Dayjs from 'vue-dayjs';
import vSelect from 'vue-select'
import 'remixicon/fonts/remixicon.css'

Vue.component('v-select', vSelect)
window.axios = require('axios');
Vue.use(Dayjs);

Vue.use(Vuelidate);
Vue.use(require('vue-cookies'));
window.Vue = Vue;


window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


// this part resolve an issue where the markers would not appear
// delete Icon.Default.prototype._getIconUrl;
Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png')
});

//Curated
Vue.component('vue-community-index', () => import(/* webpackChunkName: "assets/vci" */ './pages/curated/index.vue'));
Vue.component('vue-community-show', () => import(/* webpackChunkName: "assets/vcs" */ './pages/curated/show.vue'));
Vue.component('vue-community-create', () => import(/* webpackChunkName: "assets/vcc" */ './pages/curated/create.vue'));
Vue.component('vue-community-edit', () => import(/* webpackChunkName: "assets/vce" */ './pages/curated/edit.vue'));

Vue.component('vue-post-show', () => import(/* webpackChunkName: "assets/vps" */ './pages/curated/posts/show.vue'));
Vue.component('vue-post-edit', () => import(/* webpackChunkName: "assets/vpe" */ './pages/curated/posts/edit.vue'));
Vue.component('vue-post-create', () => import(/* webpackChunkName: "assets/vpc" */ './pages/curated/posts/create.vue'));

Vue.component('vue-community-admin-approval', () => import(/* webpackChunkName: "assets/vcaa" */ './pages/adminArea/approve/community-approval-list.vue'));
Vue.component('vue-community-approval-bar', () => import(/* webpackChunkName: "assets/vcab" */ './pages/adminArea/approve/community-approval-bar.vue'));


//Docks
Vue.component('dock-index', () => import(/* webpackChunkName: "assets/ds" */ './pages/featured/index.vue'));

//Nav
Vue.component('vue-nav', () => import(/* webpackChunkName: "assets/ls" */ './pages/navigation/nav.vue'));

//Footer
Vue.component('vue-footer', () => import(/* webpackChunkName: "assets/lfo" */ './pages/navigation/footer.vue'));

//Home
Vue.component('index', () => import(/* webpackChunkName: "assets/ei" */ './pages/home/index.vue'));

//Components
Vue.component('favorite', () => import(/* webpackChunkName: "assets/lf" */ './pages/profile/favorite-button.vue'));
Vue.component('modal-wrapper', () => import(/* webpackChunkName: "assets/mw" */ './components/modals/modal-wrapper.vue'));
Vue.component('tabs', () => import(/* webpackChunkName: "assets/lts" */ './pages/layouts/tabs.vue'));
Vue.component('tab', () => import(/* webpackChunkName: "assets/lt" */ './pages/layouts/tab.vue'));
// Vue.component('image-upload', () => import(/* webpackChunkName: "assets/liu" */ './pages/layouts/image-upload.vue'));
// Vue.component('vue-alert', () => import(/* webpackChunkName: "assets/la" */ './pages/layouts/alert.vue'));
Vue.component('vue-email-verify', () => import(/* webpackChunkName: "assets/lev" */ './pages/profile/components/email-verification-modal.vue'));

//Create Events
Vue.component('vue-create-nav', () => import(/* webpackChunkName: "assets/cn" */ './pages/create/nav.vue'));
Vue.component('vue-create-organizer-nav', () => import(/* webpackChunkName: "assets/con" */ './pages/create/organizer-nav.vue'));
Vue.component('vue-create-nav-bottom-bar', () => import(/* webpackChunkName: "assets/cbn" */ './pages/create/nav-bottom-bar.vue'));
Vue.component('create-location-google', () => import(/* webpackChunkName: "assets/cl" */ './pages/create/location-google.vue'));
Vue.component('create-category', () => import(/* webpackChunkName: "assets/cc" */ './pages/create/category.vue'));
Vue.component('create-organizer', () => import(/* webpackChunkName: "assets/co" */ './pages/create/organizer.vue'));
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
Vue.component('admin-nav', () => import(/* webpackChunkName: "assets/an" */ './pages/adminArea/nav.vue'));
Vue.component('approval-bar', () => import(/* webpackChunkName: "assets/aab" */ './pages/adminArea/approve/approval-bar.vue'));
Vue.component('approval-list', () => import(/* webpackChunkName: "assets/al" */ './pages/adminArea/approve/approval-list.vue'));
Vue.component('vue-org-approval-bar', () => import(/* webpackChunkName: "assets/aab" */ './pages/adminArea/approve/org-approval-bar.vue'));
Vue.component('vue-org-approval-list', () => import(/* webpackChunkName: "assets/al" */ './pages/adminArea/approve/org-approval-list.vue'));
Vue.component('admin-categories', () => import(/* webpackChunkName: "assets/ac" */ './pages/adminArea/admin-categories.vue'));
Vue.component('admin-contact-level', () => import(/* webpackChunkName: "assets/acl" */ './pages/adminArea/admin-contact-level.vue'));
Vue.component('admin-genres', () => import(/* webpackChunkName: "assets/ag" */ './pages/adminArea/admin-genres.vue'));
Vue.component('vue-admin-events', () => import(/* webpackChunkName: "assets/ae" */ './pages/adminArea/admin-events.vue'));
Vue.component('vue-admin-boneyard', () => import(/* webpackChunkName: "assets/ab" */ './pages/adminArea/admin-boneyard.vue'));
Vue.component('vue-admin-purgatory', () => import(/* webpackChunkName: "assets/apg" */ './pages/adminArea/admin-purgatory.vue'));
Vue.component('vue-admin-stats', () => import(/* webpackChunkName: "assets/as" */ './pages/adminArea/admin-stats.vue'));
Vue.component('admin-event-reviews', () => import(/* webpackChunkName: "assets/aer" */ './pages/adminArea/admin-event-reviews.vue'));
Vue.component('admin-staff-picks', () => import(/* webpackChunkName: "assets/ap" */ './pages/adminArea/admin-staff-picks.vue'));
Vue.component('admin-mobility', () => import(/* webpackChunkName: "assets/am" */ './pages/adminArea/admin-mobility.vue'));
Vue.component('admin-user', () => import(/* webpackChunkName: "assets/au" */ './pages/adminArea/admin-user.vue'));
Vue.component('admin-organizer', () => import(/* webpackChunkName: "assets/ao" */ './pages/adminArea/admin-organizer.vue'));
Vue.component('admin-content-advisories', () => import(/* webpackChunkName: "assets/aca" */ './pages/adminArea/admin-content-advisories.vue'));
Vue.component('vue-admin-remote-locations', () => import(/* webpackChunkName: "assets/arl" */ './pages/adminArea/admin-remote-locations.vue'));
Vue.component('vue-admin-interactive-levels', () => import(/* webpackChunkName: "assets/ail" */ './pages/adminArea/admin-interactive-levels.vue'));
Vue.component('vue-admin-event-show', () => import(/* webpackChunkName: "assets/aes" */ './pages/adminArea/admin-event-show.vue'));
Vue.component('vue-admin-event-requests', () => import(/* webpackChunkName: "assets/aers" */ './pages/adminArea/admin-event-requests.vue'));


//Profile
Vue.component('user-profile', () => import(/* webpackChunkName: "assets/up" */  './pages/profile/profile-index.vue'));
Vue.component('vue-user-notifications', () => import(/* webpackChunkName: "assets/un" */  './pages/profile/notifications.vue'));
Vue.component('vue-user-account', () => import(/* webpackChunkName: "assets/ua" */  './pages/profile/account.vue'));
Vue.component('vue-user-favorited', () => import(/* webpackChunkName: "assets/uf" */  './pages/profile/favorited.vue'));

// Messages
Vue.component('message-index', () => import(/* webpackChunkName: "assets/mi" */  './pages/messages/message-index.vue'));
Vue.component('message-show', () => import(/* webpackChunkName: "assets/ms" */  './pages/messages/message-show.vue'));

//Organizers
Vue.component('organizer-show', () => import(/* webpackChunkName: "assets/os" */  './pages/organizers/organizer-show.vue'));

//Events
Vue.component('event-show', () => import(/* webpackChunkName: "assets/es" */ './pages/events/event-show.vue'));
Vue.component('vue-event-edit', () => import(/* webpackChunkName: "assets/ee" */ './pages/events/event-edit.vue'));
Vue.component('vue-event-edit-listing-item', () => import(/* webpackChunkName: "assets/edi" */ './pages/events/components/event-edit-item.vue'));
Vue.component('vue-search-online', () => import(/* webpackChunkName: "assets/eos" */ './pages/search/vue-search-online.vue'));
Vue.component('vue-search-location', () => import(/* webpackChunkName: "assets/esl" */ './pages/search/vue-search-location.vue'));
Vue.component('vue-search-all', () => import(/* webpackChunkName: "assets/esa" */ './pages/search/vue-search-all.vue'));

//Staffpicks
Vue.component('vue-staffpicks-show', () => import(/* webpackChunkName: "assets/ssp" */ './pages/staffpicks/staffpicks-show.vue'));


//Login 
Vue.component('vue-register', () => import(/* webpackChunkName: "assets/lr" */ './pages/profile/login.vue'));
Vue.component('vue-reset-password', () => import(/* webpackChunkName: "assets/lrp" */ './pages/layouts/reset-password.vue'));

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

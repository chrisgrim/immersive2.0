<template>
    <nav 
        class="bg-slate-700 overflow-auto h-screen md:h-full"
        :class="navtype">
        <div class="p-4">
            <a href="/"> 
                <svg class="h-12 w-12 fill-white my-8 border border-white rounded-full hover:bg-black">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                </svg>
            </a>
            <a 
                href="/admin/dashboard"
                class="text-white mb-12 text-4xl ml-4">Admin Area</a>
            <div class="flex flex-col">
                <a
                    v-if="user.isModerator"
                    class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white" 
                    href="/admin/events/finalize">
                    Approve Events
                    <p class="bg-red-500 w-6 h-6 flex items-center justify-center text-white text-sm font-semibold rounded-full" v-if="eventApproval"> {{ eventApproval }}</p>
                </a>
                <a
                    v-if="user.isModerator"
                    class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white" 
                    href="/admin/organizers/finalize">
                    Approve Organizers
                    <p class="bg-red-500 w-6 h-6 flex items-center justify-center text-white text-sm font-semibold rounded-full" v-if="orgApproval"> {{ orgApproval }} </p>
                </a>
                <a
                    v-if="user.isModerator"
                    class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white" 
                    href="/admin/communities/finalize">
                    Approve Communities
                    <p class="bg-red-500 w-6 h-6 flex items-center justify-center text-white text-sm font-semibold rounded-full" v-if="comApproval"> {{ comApproval }} </p>
                </a>
                <a 
                    v-if="user.isModerator"
                    class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                    href="/admin/event-requests">
                    Approve Requests
                    <p class="bg-red-500 w-6 h-6 flex items-center justify-center text-white text-sm font-semibold rounded-full" v-if="requestApproval"> {{ requestApproval }} </p>
                </a>

                <a 
                    v-if="user.isCurator"
                    class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                    href="/admin/events">
                    Edit Events
                </a>
                <a 
                    v-if="user.isAdmin"
                    class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                    href="/admin/communities/index">
                    Edit Communities
                </a>
                <a
                    v-if="user.isAdmin"
                    class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                    href="/admin/organizer">
                    Edit Organizers
                </a>
                <a 
                    v-if="user.isAdmin"
                    class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                    href="/admin/docks">
                    Docks
                </a>
                <a 
                    v-if="user.isAdmin"
                    class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                    href="/admin/boneyard">
                    Boneyard
                </a>
                <a 
                    v-if="user.isAdmin"
                    class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                    href="/admin/events/purgatory">
                    Purgatory
                </a>
                <a 
                    v-if="user.isAdmin"
                    class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                    href="/admin/users">
                    Edit Users
                </a>
                <a
                    v-if="user.isAdmin"
                    class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                    href="/categories/create">
                    Categories
                </a>
                <a
                    v-if="user.isAdmin"
                    class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                    href="/genres/create">
                    Tags
                </a>
                <div 
                    v-if="user.isAdmin"
                    @click="advisories =! advisories"
                    class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white">
                    Advisories
                    <svg 
                        :class="{'rotate-90': advisories}"
                        class="w-8 h-8 fill-gray-400">
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-right-s-line`" />
                    </svg>
                </div>
                <div 
                    class="ml-4" 
                    v-if="advisories && user.isAdmin">
                    <a 
                        class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                        href="/contactlevels/create">
                        Contact Levels
                    </a>
                    <a
                        class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                        href="/remotelocations/create">
                        Remote Locations
                    </a>
                    <a
                        class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                        href="/contentadvisories/create">
                        Content Advisories
                    </a>
                    <a 
                        class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                        href="/mobilities/create">
                        Mobility Advisories
                    </a>
                    <a 
                        class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                        href="/interactivelevels/create">
                        Interactive Levels
                    </a>
                </div>

                <a
                    v-if="user.isModerator"
                    class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                    href="/admin/organizer">
                    Edit Organizers
                </a>

                <a
                    v-if="user.isModerator"
                    class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                    href="/reviewevents/create">
                    Event Reviews
                </a>
                <a 
                    v-if="user.isModerator"
                    class="text-gray-400 flex items-center text-1xl px-4 py-2 rounded-2xl hover:bg-slate-800 hover:text-white"
                    href="/staffpicks/create">
                    Picks Of The Week
                </a>
            </div>
        </div>
    </nav>
</template>

<script>

    export default {

        props: ['user', 'navtype'],

        data() {
            return {
                active: '',
                advisories: false,
                eventApproval: '',
                orgApproval: '',
                comApproval: '',
                requestApproval: ''
            };
        },

        methods: {

            getStatus() {
                axios.get('/admin/nav/fetch')
                .then( res => {
                    this.eventApproval = res.data.event;
                    this.orgApproval = res.data.org;
                    this.comApproval = res.data.com;
                    this.requestApproval = res.data.request;
                })
            },

            onLoad() {
                let path = new URL(window.location.href).pathname;
                path == '/categories/create' ? this.active = 'categories' : '';
                path == '/genres/create' ? this.active = 'tags' : '';
                path == '/admin/docks' ? this.active = 'docks' : '';
                path == '/contactlevels/create' ? this.active = 'contact' : '';
                path == '/remotelocations/create' ? this.active = 'remote' : '';
                path == '/contentadvisories/create' ? this.active = 'content' : '';
                path == '/mobilities/create' ? this.active = 'mobilities' : '';
                path == '/interactivelevels/create' ? this.active = 'interactive' : '';
                path == '/admin/users' ? this.active = 'users' : '';
                path == '/admin/events' ? this.active = 'events' : '';
                path == '/admin/communities' ? this.active = 'communities' : '';
                path == '/admin/boneyard' ? this.active = 'boneyard' : '';
                path == '/admin/events/purgatory' ? this.active = 'purgatory' : '';
                path == '/admin/organizer' ? this.active = 'organizers' : '';
                path == '/reviewevents/create' ? this.active = 'reviews' : '';
                path == '/staffpicks/create' ? this.active = 'picks' : '';
                path == '/admin/organizers/finalize' ? this.active = 'orgApprove' : '';
                path == '/admin/communities/finalize' ? this.active = 'comApprove' : '';
                path == '/admin/event-requests' ? this.active = 'requestApprove' : '';
                path == '/admin/events/finalize' ? this.active = 'approve' : '';

                if (this.active == 'contact' || this.active == 'remote' || this.active == 'mobilities' || this.active == 'interactive' || this.active == 'interactive') {
                    this.advisories = true;
                }
            },
        },

        mounted() {
            this.onLoad();
            this.getStatus();
        },  
    }
</script>




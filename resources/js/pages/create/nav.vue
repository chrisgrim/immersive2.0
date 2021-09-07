<template>
    <div class="ce__nav">
        <div 
            @click="hide=!hide" 
            class="create-nav-hamburger"
            :class="{active: !hide}">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <nav 
            class="create" 
            :class="{hide: hide}"
            :style="pageHeight">
            <div>
                <div class="grid create-nav-header">
                    <div class="create-nav-header__home">
                        <div class="nav-create-logo">
                            <a :href="backUrl">
                                <h3>EI</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div v-if="isOrganizer" class="create-nav-guide">
                    <h3>Submit your Organization</h3>
                </div>
                <div v-else class="create-nav-guide">
                    <h3>Submit your Event</h3>
                </div>

                <div 
                    v-if="!isOrganizer" 
                    class="grid create-menu">
                    <div 
                        style="cursor:pointer" 
                        @click="urlClick('title', '0')">
                        <NavMenuItem 
                            :active="{active : url == `/create/${this.event.slug}/title`}" 
                            name="Title" 
                            :iconstatus="this.event.status >= 1 || isCompleted ? 'completed' : 'uncomplete'" />
                    </div>
                    <div 
                        style="cursor:pointer" 
                        @click="urlClick('location', '1')">
                        <NavMenuItem 
                            :active="{active : url == `/create/${this.event.slug}/location`}" 
                            name="Location" 
                            :iconstatus="this.event.status >= 2 || isCompleted ? 'completed' : this.event.status >= 1 ? 'uncomplete' : 'locked'" />
                    </div>
                    <div 
                        style="cursor:pointer" 
                        @click="urlClick('category', '2')">
                        <NavMenuItem 
                            :active="{active : url == `/create/${this.event.slug}/category`}" 
                            name="Category" 
                            :iconstatus="this.event.status >= 3 || isCompleted ? 'completed' : this.event.status >= 2 ? 'uncomplete' : 'locked'" />
                    </div>
                    <div 
                        style="cursor:pointer" 
                        @click="urlClick('shows', '3')">
                        <NavMenuItem 
                            :active="{active : url == `/create/${this.event.slug}/shows`}" 
                            name="Dates and Times" 
                            :iconstatus="this.event.status >= 4 || isCompleted ? 'completed' : this.event.status >= 3 ? 'uncomplete' : 'locked'" />
                    </div>
                    <div style="cursor:pointer" @click="urlClick('tickets', '4')">
                        <NavMenuItem 
                            :active="{active : url == `/create/${this.event.slug}/tickets`}" 
                            name="Pricing" 
                            :iconstatus="this.event.status >= 5 || isCompleted ? 'completed' : this.event.status >= 4 ? 'uncomplete' : 'locked'" />
                    </div>
                    <div style="cursor:pointer" @click="urlClick('description', '5')">
                        <NavMenuItem 
                            :active="{active : url == `/create/${this.event.slug}/description`}" 
                            name="Description" 
                            :iconstatus="this.event.status >= 6 || isCompleted ? 'completed' : this.event.status >= 5 ? 'uncomplete' : 'locked'" />
                    </div>
                    <div style="cursor:pointer" @click="urlClick('advisories', '6')">
                        <NavMenuItem 
                            :active="{active : url == `/create/${this.event.slug}/advisories`}" 
                            name="Advisories" 
                            :iconstatus="this.event.status >= 7 || isCompleted ? 'completed' : this.event.status >= 6 ? 'uncomplete' : 'locked'" />
                    </div>
                    <div style="cursor:pointer" @click="urlClick('images', '7')">
                        <NavMenuItem 
                            :active="{active : url == `/create/${this.event.slug}/images`}" 
                            name="Image" 
                            :iconstatus="this.event.status >= 8 || isCompleted ? 'completed' : this.event.status >= 7 ? 'uncomplete' : 'locked'" />
                    </div>
                    <div v-if="cantReview" style="cursor:pointer" @click="urlClick('review', '8')">
                        <NavMenuItem 
                            :active="{active : url == `/create/${this.event.slug}/review`}" 
                            name="Final Review" 
                            :iconstatus="this.event.status >= 8 || isCompleted ? 'uncomplete' : 'locked'" />
                    </div>
                </div>
                <div style="position: relative;background: #fb5050;top: 1rem;left: 0px;padding: 1rem;border-radius: 1rem;color: white;font-size: 1rem;">
                    Our site is still in Beta. Please take note of any bugs you encounter and email them to support@everythingimmersive.com
                    We really appreciate your help improving our site!
                </div>
            </div>
        </nav>
    </div>
</template>

<script>

    import NavMenuItem  from './components/nav-menu-item.vue'

    export default {
        props: ['event', 'user'],

        components: { NavMenuItem },

        computed: {
            backUrl() {
                return this.user.hasCreatedOrganizers  ? '/create/events/edit' : '/'
            },
            isCompleted() {
                return this.event.status == 'p' || this.event.status == 'e' || this.event.status == 'r' || this.event.status == 'n' ? true : false
            },
        },

        data() {
            return {
                onOrganizer: false,
                onTitle: false,
                onLocation: false,
                onCategory: false,
                onShows: false,
                onDescription: false,
                onAdvisories: false,
                onImage: false,
                url: window.location.pathname,
                hover: '',
                pageHeight:'',
                isNotReview: window.location.pathname == `/create/${this.event.slug}/review` ? false : true,
                isOrganizer: window.location.pathname == `/organizer/create` ? true : false,
                readyToSubmit: false,
                cantReview: this.event.status == 'p' || this.event.status == 'e' ? false : true,
                hide: true,
            }
        },
        methods: {
            handleResize() {
                this.pageHeight = `min-height:${window.innerHeight}px`;
            },

            urlClick(value, loc) {
                if (this.event.status >= loc) {
                    this.$store.commit('onnavurl', { page: value, submitted: new Date().getTime()});
                }
            },

            conditionalBodyClass(bool, className) {
                bool ? document.body.classList.add(className) : document.body.classList.remove(className)
            },

            checkSubmissionStatus() {
                this.event.status >= '8' ? this.readyToSubmit = true : false;
            },

            checkOld() {
                this.event.status == 'd' ? alert("Our submission process has changed. Please start at the event title section and re-save each section. Your data isn't lost, it just needs to be saved in a new format. Sorry for any inconvenience.") : ''
            },
        },

        watch: {
            hide() {
                return !this.hide ? this.conditionalBodyClass(true, 'noscroll') : this.conditionalBodyClass(false, 'noscroll')
            }
        },

        destroyed() {
            window.removeEventListener('resize', this.handleResize);
        },

        mounted() {
            this.checkOld();
        },

        created() {
            window.addEventListener('resize', this.handleResize)
            this.handleResize();
            this.checkSubmissionStatus();
        }
    }
</script>

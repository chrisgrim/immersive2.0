<template>
    <div 
        :class="{ 'w-full' : mobile }"
        class="absolute md:flex md:relative">
        <div 
            v-if="userCanEdit"
            :class="{ 'hidden' : mobile }"
            class="mr-4 w-20 h-20 flex justify-center items-center text-center shadow-custom-4 rounded-lg">
            <a :href="`/create/${event.slug}/title`">
                <p>Edit</p>
            </a>
        </div>
        <div 
            :class="{ 'absolute' : mobile }"
            class="right-8 top-8 bg-white flex justify-center items-center text-center shadow-custom-4 rounded-full md:w-20 md:h-20">
            <button 
                aria-label="favorite"
                type="button"
                class="border-none p-4" 
                @click.prevent="toggle">
                <svg    
                    :class="{ 'stroke-default-red fill-default-red' : isFavorited, 'stroke-black' : !isFavorited }"
                    class="w-6 h-6 fill-white stroke-[4px] hover:scale-125 overflow-visible md:w-8 md:h-8">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-heart-3-line`" />
                </svg>
            </button>
        </div>
    </div>
</template>

<script>

    export default {
        props: ['event', 'user', 'mobile'],

        data() {
            return {
                isFavorited: this.event.isFavorited,
                notify: false,
                login: false,
                isVerifyVisible: false,
                canFavorite: this.user && this.user.email_verified_at ? true : false,
                hasCookie: this.$cookies.isKey('news'),
                userCanEdit: this.user && this.user.isAdmin,
            }
        },

        methods: {
            toggle() {
                if (this.canFavorite && this.hasCookie) { return this.isFavorited ? this.destroy() : this.create() }
                if (this.canFavorite) { 
                    this.$store.commit('notifyModal', true)
                    return this.isFavorited ? this.destroy() : this.create();
                }
                if (this.user) { return this.$store.commit('verifyModal', true) }
                return this.$store.commit('loginModal', true)
            },
            async create() {
                await axios.post(`/favorite/${this.event.slug}/favorites`);
                this.isFavorited = true;
            },
            async destroy() {
                await axios.delete(`/favorite/${this.event.slug}/favorites`);
                this.isFavorited = false;
            },
        },
    }
</script>
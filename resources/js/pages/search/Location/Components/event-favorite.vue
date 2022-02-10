<template>
    <div>
        <div class="absolute right-0 top-0 z-10 flex justify-center items-center text-center rounded-full md:w-16 md:h-16 md:right-0 md:top-0">
            <button 
                aria-label="favorite"
                type="button"
                class="border-none p-4" 
                @click.prevent="toggle">
                <svg    
                    :class="[ 
                        isFavorited ? 'stroke-default-red fill-default-red' : '',
                        !isFavorited && !mobile ? 'fill-white stroke-black' : '',
                        !isFavorited && mobile ? 'fill-[#0000006e] stroke-white' : '']"
                    class="w-12 h-12 stroke-[2px] hover:scale-125 overflow-visible md:w-10 md:h-10">
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
                isApproved: this.event.status !== 'p' ? 'dis' : '',
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
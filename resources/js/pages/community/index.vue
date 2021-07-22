<template>
    <div class="community-index">
        <div class="add">
            <div class="add-button">
                <button 
                    @click="active=!active"
                    :class="{active: active}">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                    </svg>
                </button>
                <template v-if="active">
                    <div class="options">
                        <div class="title">
                            <p>Create</p>
                        </div>
                        <a href="/communities/create">
                            Community
                        </a>
                        <a href="#">
                            Listing
                        </a>
                    </div>
                </template>
            </div>
        </div>
        <div>
            <h2>My Communities</h2>
            <div
                class="hero"
                v-for="(community) in communities"
                :key="community.id">
                <a
                    target="_blank"
                    :href="`/communities/${community.slug}`">
                    <div class="header">
                        <div class="com-name">
                            <h2>{{ community.name }}</h2>
                        </div>
                        <div class="com-image">
                            <img 
                                v-if="community.thumbImagePath"
                                :src="`/storage/${community.thumbImagePath}`">
                        </div>
                    </div>
                </a>
                <SimpleAlbum 
                    :title="true"
                    :image="true"
                    :elements="community.limited_listings" />
                <div>
                    <a :href="`/index/${community.slug}/listing`">
                        <button>Edit</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    
    import SimpleAlbum from '../../components/Vue-Album-Simple.vue'
    export default {
        
        props: ['user', 'value' ],

        components: { SimpleAlbum },

        computed: {
            locked() {
                return community => this.isLocked(community)
            },
            getStatus() {
                return community => this.status(community)
            },
            getStatusCircle() {
                return community => this.statusCircle(community)
            },
        },

        data() {
            return {
                communities: this.value,
                active: null,
            }
        },

        methods: {
            async deleteCommunity(community) {
                await axios.delete(`/communities/${community.slug}`)
                .then( res => {
                    console.log(res.data);
                })
            },
            status(community) {
                if (community.status === 'p') return 'approved';
                if (community.status === 'r') return 'under review';
                if (community.status === 'n') return 'revise and resubmit';
                return '-'
            },
            statusCircle(community) {
                if (community.status === 'p') return `background: rgb(27, 187, 27)`;
                if (community.status === 'r') return `background: black`;
                if (community.status === 'n') return `background: rgb(255 194 21)`;
                return `background: #bf1515`;
            },
            isLocked(community) {
                if (community.status === 'r') return true;
            },
            cleanDate(data) {
                return this.$dayjs(data).format("MMM DD, YYYY");
            },
        }

    }
</script>
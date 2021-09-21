<template>
    <div class="community-index">
        <div class="community-header">
            <div class="add">
                <div class="add-button">
                    <button 
                        @click="active=!active"
                        class="add__icon"
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
                        </div>
                    </template>
                </div>
            </div>
            <h2>My Communities</h2>
        </div>
        <div>
            <div
                v-for="(community) in communities"
                :key="community.id">
                <section class="section-a">
                    <div class="section-a__wrapper">
                        <div class="header-a">
                            <div class="header-a__content">
                                <div class="header-a__wrapper">
                                    <div class="header-a__name">
                                        <h2>{{ community.name }}</h2>
                                    </div>
                                    <div class="header-a__blurb">
                                        <p>{{ community.blurb }}</p>
                                    </div>
                                    <a :href="`/communities/${community.slug}/edit`">
                                        <button> Edit </button>
                                    </a>
                                    <a :href="`/communities/${community.slug}`">
                                        <button> View </button>
                                    </a>
                                </div>
                            </div>
                            <div class="header-a__image">
                                <picture>
                                    <source 
                                        type="image/webp" 
                                        :srcset="`/storage/${community.thumbImagePath}`"> 
                                    <img 
                                        :src="`/storage/${community.thumbImagePath.slice(0, -4)}jpg`" 
                                        :alt="`${community.name} Community`">
                                </picture>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>

<script>
    
    export default {
        
        props: ['user', 'value' ],

        components: {  },

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
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
                                    <template v-if="isPublic(community)">
                                        <a :href="`/communities/${community.slug}/edit`">
                                            <button> Edit </button>
                                        </a>
                                        <a :href="`/communities/${community.slug}`">
                                            <button> View </button>
                                        </a>
                                    </template>
                                    <template v-else>
                                        <div class="header-a__blurb">
                                            <p>-- Community under review --</p>
                                        </div>
                                    </template>
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

        data() {
            return {
                communities: this.value,
                active: null,
            }
        },

        methods: {
            // async deleteCommunity(community) {
            //     await axios.delete(`/communities/${community.slug}`)
            //     .then( res => {
            //         console.log(res.data);
            //     })
            // },
            isPublic(community) {
                if (community.status === 'p') return true;
            },
        }

    }
</script>
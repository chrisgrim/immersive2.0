<template>
    <div class="community-show">
        <div class="lay-a wide">
            <template v-if="mobile">
                <nav class="es__mobile-nav">
                    <button 
                        @click="onBack"
                        class="arrow svg">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                        </svg>
                    </button>
                </nav>
            </template>
            <template v-else>
                <div class="breadcrumbs">
                    <p>
                        <a :href="`/`">Everything Immersive</a> > {{ community.name }} Community
                    </p>
                </div>
            </template>
            <div class="header-a">
                <div v-if="owner" class="header-a__edit">
                    <a :href="`/communities/${community.slug}/edit`">
                        <button>Edit Community</button>
                    </a>
                </div>
                <div class="header-a__content">
                    <div class="header-a__wrapper">
                        <div class="header-a__name">
                            <h2>{{ community.name }}</h2>
                        </div>
                        <div class="header-a__blurb">
                            <p>{{ community.blurb }}</p>
                        </div>
                    </div>
                </div>
                <div class="header-a__image">
                    <picture>
                        <source 
                            type="image/webp" 
                            :srcset="`/storage/${headerImage}`"> 
                        <img 
                            :src="`/storage/${headerImage.slice(0, -4)}jpg`" 
                            :alt="`${community.name} Community`">
                    </picture>
                </div>
            </div>
        </div>
        <div class="lay-a">
            <div class="wrapper">
                <div class="sidebar left">
                    <div class="sticky">
                        <div class="com-curators">
                            <h4>Meet your curators</h4>
                            <div 
                                v-for="curator in community.curators"
                                :key="curator.id"
                                class="curator">
                                <template v-if="curator && curator.thumbImagePath">
                                    <picture>
                                        <source 
                                            type="image/webp" 
                                            :srcset="`/storage/${curator.thumbImagePath}`"> 
                                        <img 
                                            :src="`/storage/${curator.thumbImagePath.slice(0, -4)}`" 
                                            :alt="`${curator.name} Community`">
                                    </picture>
                                </template>
                                <p>{{ curator.name }}</p>
                            </div>
                        </div>
                        <h4>About {{ value.name }}</h4>
                        <div class="com-description">
                            <ShowMore 
                                :text="value.description"
                                :limit="150" />
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div 
                        class="shelves" 
                        v-for="shelf in shelvesWithPosts"
                        :key="shelf.id">
                        <Shelf 
                            :community="community"
                            :shelf="shelf" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Shelf from './shelves/shelf-show.vue'
    import ShowMore from '../../components/ShowMore.vue'
    export default {
        
        props: [ 'value', 'shelves', 'owner' ],

        components: { Shelf, ShowMore },

        computed: {
            shelvesWithPosts() {
                if (!this.shelves[0].published_posts) { return }
                return this.shelves.filter( shelf => shelf.published_posts.length)
            }
        },

        data() {
            return {
                community: this.value,
                headerImage: window.innerWidth < 768 ? this.value.thumbImagePath : this.value.largeImagePath,
                mobile: window.innerWidth < 768,
            }
        },

        methods: {
            onBack() {
                document.referrer == "" ? window.location.href = '/' : window.history.back()
            }
        },

    }
</script>
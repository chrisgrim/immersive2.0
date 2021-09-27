<template>
    <div class="community-show">
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
        <div class="com-intro">
            <div class="li-post">
                <div 
                    class="shelves" 
                    v-for="shelf in shelvesWithPosts"
                    :key="shelf.id">
                    <Shelf 
                        :community="community"
                        :shelf="shelf" />
                </div>
            </div>
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
                    {{ curator.name }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Shelf from './posts/shelves/shelf-show.vue'
    export default {
        
        props: [ 'value', 'shelves', 'owner' ],

        components: { Shelf },

        computed: {
            shelvesWithPosts() {
                if (!this.shelves[0].published_posts.data) { return }
                return this.shelves.filter( shelf => shelf.published_posts.data.length)
            }
        },

        data() {
            return {
                community: this.value,
                headerImage: window.innerWidth < 768 ? this.value.thumbImagePath : this.value.largeImagePath,
            }
        },

        methods: {
        },

    }
</script>
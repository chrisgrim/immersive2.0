<template>
    <div class="listing-show lay-a">
        <div class="breadcrumbs">
            <p v-if="owner">
                <a :href="`/index/${community.slug}/listing`">{{community.name}}</a> > {{listing.name}}
            </p>
            <p v-else>
                <a :href="`/communities/${community.slug}`">{{community.name}}</a> > {{listing.name}}
            </p>
        </div>
        <div class="wrapper">
            <div class="content">
                <template v-if="headerImage">
                    <div class="listing-header">
                        <div class="listing-image">
                            <picture>
                                <source 
                                    type="image/webp" 
                                    :srcset="`/storage/${headerImage}`"> 
                                <img 
                                    :src="`/storage/${headerImage.slice(0, -4)}`" 
                                    :alt="`${listing.name} Listing`">
                            </picture>
                        </div>
                    </div>
                </template>
                <div class="listing-content">
                    <div class="listing-name">
                        <h2>{{ listing.name }}</h2>
                    </div>
                    <div class="listing-owner">
                        <p>by {{ listing.user.name }}</p>
                    </div>
                    <div class="listing-blurb blurb">
                        <div v-html="listing.blurb" />
                    </div>
                    <div 
                        v-for="card in listing.cards"
                        :key="card.id"
                        class="listing-card">
                        <Card :card="card" />
                    </div>
                </div>
            </div>
            <div class="sidebar">
                <div 
                    :class="{ hidden : sideHidden }"
                    class="sticky">
                    <h3>{{ listing.name }}</h3>
                    <p><a :href="`/communities/${community.slug}`">{{ community.name }}</a></p>
                    <hr>
                    <div class="listing-owner">
                        <p>by {{ listing.user.name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Card  from './card.vue'
    export default {
        
        props: [ 'value', 'user', 'owner', 'community'],

        components: { Card },

        computed: {

        },

        data() {
            return {
                listing: this.value,
                headerImage: window.innerWidth < 768 ? this.value.thumbImagePath : this.value.largeImagePath,
                sideHidden: true,
            }
        },

        methods: {
            handleScroll (event) {
              if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    window.scrollY > 375 ? this.sideHidden = false : this.sideHidden = true;
                }, 25); // delay
            }
        },

        created () {
            window.addEventListener('scroll', this.handleScroll);
        },

        unmounted () {
            window.removeEventListener('scroll', this.handleScroll);
        },

    }
</script>
<template>
    <div class="post-show">
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
                    <p v-if="owner">
                        <a :href="`/`">Everything Immersive</a> > <a :href="`/communities/${community.slug}/edit`">{{community.name}}</a> > {{post.name}}
                    </p>
                    <p v-else>
                        <a :href="`/`">Everything Immersive</a> > <a :href="`/communities/${community.slug}`">{{community.name}}</a> > {{post.name}}
                    </p>
                </div>
            </template>
            <template v-if="headerImage && value.type==='s'">
                <div class="post-header">
                    <picture>
                        <source 
                            type="image/webp" 
                            :srcset="`/storage/${headerImage}`"> 
                        <img 
                            :src="`/storage/${headerImage.slice(0, -4)}`" 
                            :alt="`${post.name} Post`">
                    </picture>
                </div>
            </template>
            <template v-else>
                <div class="post-header empty" />
            </template>
        </div>
        <div class="lay-a">
            <div class="wrapper">
                <div class="sidebar left">
                    <div class="sticky">
                        <div class="post-info">
                            <div class="post-name">
                                <h2>{{ post.name }}</h2>
                            </div>
                            <div class="post-blurb blurb">
                                <div v-html="post.blurb" />
                            </div>
                            <div class="post-owner">
                                <p>by {{ post.user.name }}</p>
                            </div>
                            <template v-if="owner">
                                <a :href="`/communities/${community.slug}/${value.slug}/edit`">
                                    <button class="outline">
                                        edit
                                    </button>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="post-content">
                        <div 
                            v-for="card in post.cards"
                            :key="card.id"
                            class="post-card">
                            <Card :card="card" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Card  from './cards/card.vue'
    export default {
        
        props: [ 'value', 'user', 'owner', 'community'],

        components: { Card },

        computed: {

        },

        data() {
            return {
                post: this.value,
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
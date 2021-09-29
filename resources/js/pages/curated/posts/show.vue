<template>
    <div class="post-show lay-a">
        <div class="breadcrumbs">
            <p v-if="owner">
                <a :href="`/`">Everything Immersive</a> > <a :href="`/communities/${community.slug}/edit`">{{community.name}}</a> > {{post.name}}
            </p>
            <p v-else>
                <a :href="`/`">Everything Immersive</a> > <a :href="`/communities/${community.slug}`">{{community.name}}</a> > {{post.name}}
            </p>
        </div>
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
        <div class="wrapper">
            <div class="content">
                <div class="post-content">
                    <div class="post-name">
                        <h2>{{ post.name }}</h2>
                    </div>
                    <div class="post-blurb blurb">
                        <div v-html="post.blurb" />
                    </div>
                    <div class="post-owner">
                        <p>by {{ post.user.name }}</p>
                    </div>
                    <div 
                        v-for="card in post.cards"
                        :key="card.id"
                        class="post-card">
                        <Card :card="card" />
                    </div>
                </div>
            </div>
            <div class="sidebar">
                <template v-if="owner">
                    <a :href="`/communities/${community.slug}/${value.slug}/edit`">
                        <button class="outline">
                            edit
                        </button>
                    </a>
                </template>
                <div 
                    :class="{ hidden : sideHidden }"
                    class="sticky">
                    <h3>{{ post.name }}</h3>
                    <p><a :href="`/communities/${community.slug}`">{{ community.name }}</a></p>
                    <hr>
                    <div class="post-owner">
                        <p>by {{ post.user.name }}</p>
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
                sideHidden: this.value.thumbImagePath ? true : false,
            }
        },

        methods: {
            handleScroll () {
              if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.setHeight();
                }, 25); // delay
            },
            setHeight() {
                if (this.value.thumbImagePath) {
                    return window.scrollY > 375 ? this.sideHidden = false : this.sideHidden = true;
                }
            }
        },

        created () {
            window.addEventListener('scroll', this.handleScroll);
        },

        destroyed() {
            window.removeEventListener('scroll', this.handleScroll);
        },

    }
</script>
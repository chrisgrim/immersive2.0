<template>
    <div ref="dropdown">
        <div    
            @click="onSubmit"
            :style="`background:${hex}`" 
            class="cursor-pointer overflow-hidden flex justify-center items-center w-10 h-10 rounded-full">
            <template v-if="user.largeImagePath">
                <picture>
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${user.thumbImagePath}`"> 
                    <img 
                        class="w-12 h-12"
                        :src="`/storage/${user.thumbImagePath.slice(0, -4)}jpg`" 
                        :alt="user.name + `'s account`">
                </picture>
            </template>
            <template v-else-if="user.gravatar">
                <img 
                    :src="user.gravatar" 
                    class="w-10 h-10"
                    :alt="user.name + `'s account`">
            </template>
            <template v-else>
                <h2>
                    {{ user.name.charAt(0) }}
                </h2>
            </template>
        </div>
    </div>
</template>

<script>
    export default {

        props: ['user'],

        data() {
            return {
                hex: this.user ? this.user.hexColor : `#717171`,
            };
        },

        methods: {
            onSubmit() {
                window.location.href = '/account-settings';
            }
        }
    }
</script>




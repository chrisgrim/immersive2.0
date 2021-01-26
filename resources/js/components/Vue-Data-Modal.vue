<template>
    <div class="delete-box">
        <div class="delete-box__container">
            <div class="delete-box__close">
                <button @click="onClose">
                    <IconSvg type="delete" />
                </button>
            </div>
            <div class="delete-box__body">
                <slot></slot>
            </div>
            <div class="delete-box__input">
                <v-select 
                    v-model="user"
                    label="name"
                    placeholder="Enter User Name"
                    @search="onUserSearch"
                    @search:focus="onUserSearch"
                    :clearable="false"
                    :options="users" />
                <button 
                    @click="onSubmit"
                    :disabled='isDisabled'>
                    Submit
                </button>
            </div>
            <div class="delete-box__footer">
                <button @click="onClose">Close</button>
            </div>
        </div>
    </div>
</template>

<script>
    import IconSvg from './Svg-icon'
    
    export default {

        props: ['item'],

        components: { IconSvg },

        data() {
            return {
                isDisabled: false,
                user: '',
                users: [],
            }
        },

        methods: {
            onSubmit() {
                this.$emit('onSubmit', this.user);
            },

            onClose() {
                this.$emit('close', true);
            },

            onUserSearch (query) {
                axios.get('/api/admin/users/search', { params: { keywords: query } })
                .then( res => { this.users = res.data.data });
            },
        },

        watch: {

        }

    }
</script>
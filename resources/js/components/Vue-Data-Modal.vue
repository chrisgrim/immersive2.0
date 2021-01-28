<template>
    <div class="modal-box">
        <div class="modal-box__container">
            <div class="modal-box__close">
                <button @click="onClose">
                    <IconSvg type="delete" />
                </button>
            </div>
            <div class="modal-box__body">
                <slot></slot>
            </div>
            <div class="modal-box__input">
                <v-select 
                    v-model="data"
                    label="name"
                    placeholder="Enter Name"
                    @search="onSearch"
                    @search:focus="onSearch"
                    :options="options" />
                <button 
                    class="submit" 
                    @click="onSubmit"
                    :disabled='isDisabled'>
                    Submit
                </button>
            </div>
            <div class="modal-box__footer">
                <button @click="onClose">Close</button>
            </div>
        </div>
    </div>
</template>

<script>
    import IconSvg from './Svg-icon'
    
    export default {

        props: ['item', 'search'],

        components: { IconSvg },

        computed: {
            endpoint() {
                if (this.search === 'user') { return '/api/admin/users/search' }
                if (this.search ===  'organizer') { return '/api/admin/organizer/search' }
                return '/api/admin/users/search'
            },
        },

        data() {
            return {
                isDisabled: false,
                data: null,
                options: [],
            }
        },

        methods: {
            onSubmit() {
                this.$emit('onSubmit', this.data);
            },

            onClose() {
                this.$emit('close', true);
            },

            onSearch (query) {
                axios.get( this.endpoint, { params: { keywords: query } })
                .then( res => { 
                    this.options = res.data.data 
                });
            },
        },

        watch: {

        }

    }
</script>
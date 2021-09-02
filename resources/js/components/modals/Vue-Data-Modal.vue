<template>
    <div class="modal">
        <div class="wrapper">
            <div class="header">
                <button @click="onClose">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
            </div>
            <div class="body">
                <slot></slot>
                <div class="input">
                    <v-select 
                        v-model="data"
                        label="name"
                        placeholder="Enter Name"
                        @search="onSearch"
                        @search:focus="onSearch"
                        :options="options" />
                    <button 
                        class="btn-login btn-borderless" 
                        @click="onSubmit"
                        :disabled='disabled'>
                        Submit
                    </button>
                </div>
            </div>
            <div class="footer">
                <button 
                    class="btn-borderless" 
                    @click="onClose">
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<script>    
    export default {

        props: ['item', 'search'],

        computed: {
            endpoint() {
                if (this.search === 'user') { return '/api/admin/users/search' }
                if (this.search ===  'organizer') { return '/api/admin/organizer/search' }
                return '/api/admin/users/search'
            },
            disabled() {
                return this.data ? false : true;
            }
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

    }
</script>
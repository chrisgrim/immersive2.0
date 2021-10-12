<template>
    <div class="modal add-window">
        <div class="wrapper">
            <div class="header">
                <button @click="onClose">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
                <p>Add Dock Content</p>
            </div>
            <div class="body">
                <div class="field">
                    <select 
                        v-model="type"
                        placeholder="Dock Content Type">
                        <option value="shelf"> Shelf </option>
                        <option value="community"> Community </option>
                    </select>
                </div>
                <div>
                    <template v-if="type==='shelf'">
                        <v-select 
                            v-model="feature"
                            label="feature.model"
                            :get-option-label="feature => feature.model.name"
                            placeholder="Featured element"
                            @search="onSearch"
                            @search:focus="onSearch"
                            :options="options">
                            <template v-slot:option="option">
                                {{ option.model.name }} <span v-if="option.model.community">({{ option.model.community.name }})</span>
                            </template>
                        </v-select>
                    </template>
                    <template v-if="type==='community'">
                        <v-select 
                            v-model="feature"
                            label="feature.model"
                            :get-option-label="feature => feature.model.name"
                            placeholder="Featured element"
                            @search="onSearch"
                            @search:focus="onSearch"
                            :options="options">
                            <template v-slot:option="option">
                                {{ option.model.name }} <span v-if="option.model.community">({{ option.model.community.name }})</span>
                            </template>
                        </v-select>
                    </template>
                </div>

                <button 
                    :disabled="disabled" 
                    @click.prevent="onSubmit" 
                    class="btn-login btn-borderless">
                    Submit
                </button>
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
        props: ['dock'],

        components: {  },

        computed: {

        }, 

        data() {
            return {
                feature: null,
                type: 'shelf',
                disabled: false,
                options: [],

            }
        },

        methods: {
            async onSubmit() {
                await axios.post(`/admin/docks/${this.dock.id}/${this.type}/${this.type === 'shelf' ? this.feature.model.id : this.feature.model.slug}`)
                .then( res => { 
                    this.$emit('update', res.data);
                })
            },
            async onSearch (query) {
                await axios.get(`/api/search/${this.type}`, { params: { keywords: query } })
                .then( res => { 
                    this.options = res.data
                });
            },
            onClose() {
                this.$emit('close');
            },
        },
    }

</script>
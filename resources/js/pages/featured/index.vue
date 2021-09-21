<template>
    <div class="admin-docks">
        <div class="">
            <div class="title">
                <h1>Docks</h1>
            </div>
        </div>

        <template v-if="docks && docks.length">
            <div class="data-grid">
                <div class="data-grid__row header">
                    <p>Dock Location</p>
                    <p>Content</p>
                    <p>Update</p>
                </div>
                <div 
                    class="data-grid__row"
                    v-for="dock in docks"
                    :key="dock.id">
                    <div>
                        <p>{{ dock.name }}</p>
                    </div>
                    <div>
                        <p>{{ dock.featured[0].featureable.name }}</p>
                    </div>
                    <div>
                        <v-select 
                            v-model="feature"
                            label="feature.model"
                            :get-option-label="feature => feature.model.name"
                            placeholder="Featured element"
                            @search="onSearch"
                            @search:focus="onSearch"
                            @input="addFeature($event, dock)"
                            :options="options" />
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>

    export default {

        props: ['loaddocks'],

        data() {
            return {
                feature: null,
                options: [],
                docks: this.loaddocks,
            }
        },

        methods: {
            async addFeature(value, dock) {
                await axios.post(`/admin/docks/${dock.id}`, value.model)
                .then( res => {
                    this.docks = res.data
                })
            },

            async onSearch (query) {
                console.log(query);
                await axios.get('/admin/docks/search', { params: { keywords: query } })
                .then( res => { 
                    this.options = res.data 
                });
            },
        },

    }

</script>
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
                    <p>Dock Type</p>
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
                        <p v-if="dock.featured && dock.featured.length"> {{ dock.featured[0].featureable.name }} </p>
                        <p v-else> Name Here </p>
                    </div>
                    <div>
                        <p v-if="dock.type==='c'">Community</p>
                        <p v-if="dock.type==='s'">Shelf</p>
                    </div>
                    <div>
                        <v-select 
                            v-model="feature"
                            label="feature.model"
                            :get-option-label="feature => feature.model.name"
                            placeholder="Featured element"
                            @search="onSearch($event, dock)"
                            @search:focus="onSearch($event, dock)"
                            @input="addFeature($event, dock)"
                            :options="options">
                            <template v-slot:option="option">
                                {{ option.model.name }} <span v-if="option.model.community">({{ option.model.community.name }})</span>
                            </template>
                        </v-select>
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
                    this.feature = null
                })
            },

            async onSearch (query, dock) {
                const loc = dock.type === 'c' ? 'community' : 'shelf'
                await axios.get(`/api/search/${loc}`, { params: { keywords: query } })
                .then( res => { 
                    this.options = res.data 
                });
            },
        },

    }

</script>
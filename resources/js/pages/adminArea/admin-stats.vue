<template>
    <div class="admin-events">
        <div class="">
            <div class="admin-events__title">
                <h1>Stats</h1>
            </div>
        </div>
        <div>
            number of users: {{ usercount }}
        </div>
        <div>
            number of events: {{ eventcount }}
        </div>
        <br>
        <div>
            <h3>User Searches</h3>
        </div>
        <div>
            <v-select 
                v-model="searchType"
                :options="searchOptions"
                :clearable="false"
                placeholder="Search by type"
                :class="{ active: active == 'type' }"
                @search:blur="active = null"
                @search:focus="active = 'type'"
                @input="onLoad" />
        </div>
        <div>
            <apexchart 
                width="500" 
                type="bar" 
                :options="options" 
                :series="series" />
        </div>
    </div>
</template>

<script>
    import VueApexCharts from 'vue-apexcharts'
    Vue.use(VueApexCharts)
    Vue.component('apexchart', VueApexCharts)


    export default {
        props: ['eventcount', 'usercount'],

        computed: {
            submitObject() {
                return {type : this.searchType}
            }
        },

        data() {
            return {
                searchOptions: ['category', 'location', 'event', 'organizer'],
                searchType: 'category',
                active: '',
                options: {
                    chart: {
                      id: 'vuechart-example'
                    },
                    xaxis: {
                      categories: []
                    }
                },
                series: [{
                    name: 'series-1',
                    data: []
                }],
            }
        },

        methods: {
            onLoad() {
                axios.post('/admin/search/data', this.submitObject)
                .then( res => {
                    this.options = {
                        xaxis: {
                          categories: res.data.map(name => name.name.substring(0, 20)),
                        }
                    }
                    this.series = [{data: res.data.map(name => name.count), name: res.data.map(name => name.name.substring(0, 20))}]
                });
            },
                
        },

        created() {
            this.onLoad();
        },  

    }

</script>
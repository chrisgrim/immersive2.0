<template>
    <div>
        <h2 class="mb-8">Dashboard</h2>
        <p class="text-lg"> number of users: <span class="text-2xl font-semibold">{{ usercount }}</span></p>
        <p class="text-lg"> number of events: <span class="text-2xl font-semibold">{{ eventcount }}</span></p>
        <div class="my-12">
            <p>Search History</p>
            <div class="w-96">
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
        <div class="h-96 w-96">
            <p>Quick Event Info</p>
            <v-select 
                @input="submit"
                v-model="eventList"
                label="name"
                placeholder="Enter Name"
                @search="onSearch"
                @search:focus="onSearch"
                :options="events.data" />
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
                events: [],
                eventList: '',
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

            submit(value) {
                window.location.href = `/admin/events/show/${value.slug}`;
            },

            onSearch(events) {
                axios.get('/api/admin/events/search', { params: { keywords: events } })
                .then( res => { this.events = res.data });
                this.events.data.filter(a => console.log(a.name))
            },  
        },

        created() {
            this.onLoad();
        },  

    }

</script>
<template>
    <div class="admin-docks">
        <div class="">
            <div class="title">
                <h1>Docks</h1>
                <div class="add-button">
                    <button 
                        @click="onCreate"
                        class="add__icon">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div 
            :key="location.name"
            v-for="location in locations">
            <template v-if="hasDocks(location)">
                <h3>{{location.name}}</h3>
                <DockGrid 
                    @update="update"
                    :docks="filteredDocks(location)" />
                <br>
            </template>
        </div>
    </div>
</template>

<script>
    import DockGrid from './components/dock-grid'
    export default {

        components: { DockGrid },

        props: ['loaddocks'],

        computed: {
        },

        data() {
            return {
                docks: this.loaddocks,
                locations: [
                    { name: 'home' },
                    { name: 'search' },
                    { name: 'none' }, 
                ]
            }
        },

        methods: {
            async onCreate() {
                await axios.post(`/admin/docks`)
                .then( res => {
                    this.docks = res.data
                })
            },
            update(val) {
                this.docks = val
            },
            filteredDocks(location) {
                return this.docks.filter( dock => dock.location === location.name)
            },
            hasDocks(location) {
                return this.docks.filter( dock => dock.location === location.name).length
            }
        },

    }

</script>
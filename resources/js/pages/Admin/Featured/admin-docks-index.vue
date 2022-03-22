<template>
    <div class="admin-docks">
        <div class="flex justify-between mb-8 items-center">
            <h2>Docks</h2>
            <button 
                @click="onCreate"
                class="border-none w-20 h-20 flex p-0 rounded-full justify-center items-center hover:bg-slate-300">
                <svg class="w-16 h-16">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                </svg>
            </button>
        </div>

        <div 
            class="mb-20" 
            :key="location.name"
            v-for="location in locations">
            <template v-if="hasDocks(location)">
                <h3 class="mb-4">{{location.name}}</h3>
                <DockGrid 
                    @update="update"
                    :docks="filteredDocks(location)" />
            </template>
        </div>
    </div>
</template>

<script>
    import DockGrid from './Components/dock-component'
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
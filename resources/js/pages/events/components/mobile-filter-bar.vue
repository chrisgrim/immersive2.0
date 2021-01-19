<template>
    <div>
        <div 
            class="search__map--header"
            v-if="mobile">
            <div class="search__map--location" >
                <div class="search__map--city">
                    <button @click="showFilter('showlocation')">
                        {{city ? city : 'Add City'}}
                    </button>
                </div>
                <div class="search__map--dates">
                    <button @click="showFilter('showdates')">
                        <template v-if="$store.state.dates[0]">
                            {{ $store.state.dates[0] }}{{ $store.state.dates[1] ? ' - ' + $store.state.dates[1] : '' }}
                        </template>
                        <template v-else>
                            Add Dates
                        </template>
                    </button>
                </div>
                <div class="search__map--config">
                    <button @click="showFilter('onfilter')">
                        <svg 
                            xmlns="http://www.w3.org/2000/svg" 
                            xmlns:xlink="http://www.w3.org/1999/xlink" 
                            viewBox="0 0 104 104" 
                            width="30px" 
                            height="30px">
                            <path 
                                fill="#000000" d="M89,27c0,1.1-0.9,2-2,2H13c-1.1,0-2-0.9-2-2v-2c0-1.1,0.9-2,2-2h74c1.1,0,2,0.9,2,2V27z"/><path fill="#000000" d="M79,43c0,1.1-0.9,2-2,2H23c-1.1,0-2-0.9-2-2v-2c0-1.1,0.9-2,2-2h54c1.1,0,2,0.9,2,2V43z"/><path fill="#000000" d="M71,59c0,1.1-0.9,2-2,2H31c-1.1,0-2-0.9-2-2v-2c0-1.1,0.9-2,2-2h38c1.1,0,2,0.9,2,2V59z"/><path fill="#000000" d="M63,75c0,1.1-0.9,2-2,2H39c-1.1,0-2-0.9-2-2v-2c0-1.1,0.9-2,2-2h22c1.1,0,2,0.9,2,2V75z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

    export default {

    data() {
        return {
            mobile: window.innerWidth < 768,
            city: new URL(window.location.href).searchParams.get("city"),
        }
    },

    methods: {
        showFilter(value) {
            this.$store.commit(value, true);
        },
    }

    }
</script>
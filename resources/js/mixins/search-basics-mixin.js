import dayjs from 'dayjs'
export default {
    data() {
        return {
            url: this.initializeUrl(),
        }
    },

    methods: {
        initializeUrl() {
            return {
                city: new URL(window.location.href).searchParams.get("city"),
                lat: new URL(window.location.href).searchParams.get("lat"),
                lng: new URL(window.location.href).searchParams.get("lng"),
                category: new URL(window.location.href).searchParams.get("category"),
                tag: new URL(window.location.href).searchParams.get("tag"),
                dates: new URL(window.location.href).searchParams.get("start") ? [new URL(window.location.href).searchParams.get("start"), new URL(window.location.href).searchParams.get("end")] : '',
                price: new URL(window.location.href).searchParams.get("price0") ? [Number(new URL(window.location.href).searchParams.get("price0")), Number(new URL(window.location.href).searchParams.get("price1"))] : '',
                remote: new URL(window.location.href).searchParams.get("remote"),
                mapSearch: new URL(window.location.href).searchParams.get("mapsearch"),
                naturalDate: [],
            }
        },
        
        addPushState() {
            if (this.url.city) {
                var city = `city=${this.city}`
            }
            if (this.data.lat) {
                var lat = `lat=${this.data.lat}`
            }
            if (this.data.lng) {
                var lng = `lng=${this.data.lng}`
            }
            if (this.data.category.length) {
                var category = `category=${this.data.category}`
            }
            if (this.data.dates.length) {
                var dates = `start=${this.data.dates[0]}&end=${this.data.dates[1]}`
            }
            if (this.data.price.length) {
                var price = `price0=${this.data.price[0]}&price1=${this.data.price[1]}`
            }
            var page = `page=${this.$store.state.pagination}`
            if (this.data.tag.length) {
                var tag = `tag=${this.data.tag}`
            }
            if (this.data.mapboundary) {
                var mapboundary = `mapsearch=true&NElat=${this.data.mapboundary._northEast.lat}&NElng=${this.data.mapboundary._northEast.lng}&SWlat=${this.data.mapboundary._southWest.lat}&SWlng=${this.data.mapboundary._southWest.lng}&Clat=${this.center.lat}&Clng=${this.center.lng}&zoom=${this.zoom}`;
            }
            let content = `${city ? city : ''}&${lat ? lat : ''}&${lng ? lng : ''}&${category ? category : '' }&${dates ? dates : ''}&${price ? price : ''}&${tag ? tag : '' }&${mapboundary ? mapboundary : ''}&${page ? page : '' }`;
            if (this.data.lat || this.data.mapboundary) {
                history.pushState(null, null,`/index/search?${content}`)
            } else {
                if (this.filter === 'all') {
                    history.pushState(null, null,`/index/search-all?${content}`)
                } else {
                    history.pushState(null, null,`/index/search-online?${content}`)
                }
            }
        },

        getPushState() {
            this.$store.commit('searchtype', '');
            if (this.url.category) {
                let arr = this.url.category.split(',').map(Number);
                this.url.category = this.categories.filter(element => arr.includes(element.id));
                this.$store.commit('filterCategory', this.url.category)
            }
            if (this.url.tag) {
                let arr = this.url.tag.split(',');
                this.url.tag = this.tags.filter(element => arr.includes(element.name));
                this.$store.commit('filterTag', this.url.tag)
            }
            if (this.url.price) {
                this.$store.commit('filterPrice',  this.url.price)
            }
            if (this.url.dates) {
                this.$store.commit('filterDates', this.url.dates)
                this.$store.commit('filterNaturalDates', this.url.dates.map(date => dayjs(date).format("MMM D")));
                this.$store.commit('displaydates', this.url.dates.map(date => dayjs(date).format("MMM D")));
            }
            if (this.url.city) {
                this.city = this.url.city;
            }
        }

        // getPriceRange() {
        //     let prices = [] 
        //     this.eventList.forEach(event=>{ 
        //       event.priceranges.forEach(pricerange=>{ 
        //         prices.push(pricerange.price) 
        //       }) 
        //     })

        //     function compareNumbers(a, b) {
        //       return a - b;
        //     }

        //     let arr = Math.ceil(parseFloat(prices.sort(compareNumbers).slice(-1)[0]));

        //     prices.length ? this.price[1] = arr : this.price[1] = 1000;
        //     prices.length ? this.options.max = arr : this.options.max = 1000;
        // },
    }
}
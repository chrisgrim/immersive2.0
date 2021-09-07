export default {
    methods: {
        addPushState() {
            if (this.city) {
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
                var mapboundary = `mapsearch=true&NElat=${this.data.mapboundary._northEast.lat}&NElng=${this.data.mapboundary._northEast.lng}&SWlat=${this.data.mapboundary._southWest.lat}&SWlng=${this.data.mapboundary._southWest.lng}&Clat=${this.data.center.lat}&Clng=${this.data.center.lng}&zoom=${this.data.zoom}`;
            }
            let content = `${city ? city : ''}&${lat ? lat : ''}&${lng ? lng : ''}&${category ? category : '' }&${dates ? dates : ''}&${price ? price : ''}&${tag ? tag : '' }&${mapboundary ? mapboundary : ''}&${page ? page : '' }`;

            if (this.filter === 'location') {
                return history.pushState(null, null,`/index/search?${content}`)
            } 
            if (this.filter === 'all') {
                return history.pushState(null, null,`/index/search-all?${content}`)
            }
            return history.pushState(null, null,`/index/search-online?${content}`)
        },
    }
}
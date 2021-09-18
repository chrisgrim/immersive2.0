export default {
    methods: {
        addPushState() {

            let content = '';
            if (this.data && this.data.live) {
                content = content.concat(`live=${this.data.live}`)
            }
            if (this.city) {
                content = content.concat(`&city=${this.city}`)
            }
            if (this.data.lat && !this.data.live) {
                content = content.concat(`&lat=${this.data.lat}`)
            }
            if (this.data.lng && !this.data.live) {
                content = content.concat(`&lng=${this.data.lng}`)
            }
            if (this.data.category.length) {
                content = content.concat(`&category=${this.data.category}`)
            }
            if (this.data.dates.length) {
                content = content.concat(`&start=${this.data.dates[0]}&end=${this.data.dates[1]}`)
            }
            if (this.data.price.length) {
                content = content.concat(`&price0=${this.data.price[0]}&price1=${this.data.price[1]}`)
            }
            if (this.data.tag.length) {
                content = content.concat(`&tag=${this.data.tag}`)
            }
            if (this.data.mapboundary && this.data.live) {
                content = content.concat(`&NElat=${this.data.mapboundary._northEast.lat}&NElng=${this.data.mapboundary._northEast.lng}&SWlat=${this.data.mapboundary._southWest.lat}&SWlng=${this.data.mapboundary._southWest.lng}&Clat=${this.data.center.lat}&Clng=${this.data.center.lng}&zoom=${this.data.zoom}`)
            }
            content = content.concat(`&page=${this.inputVal}`)

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
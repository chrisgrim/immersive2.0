export default {
    methods: {
        addPushState() {

            let content = `searchType=${this.inputVal.searchType}`;
            if (this.inputVal && this.inputVal.location) {
                if (this.inputVal.location.live) {
                    content = content.concat(`&live=${this.inputVal.location.live}`)
                }
                if (this.inputVal.location.name) {
                    content = content.concat(`&city=${this.inputVal.location.name}`)
                }
                if (this.inputVal.location.center.lat && !this.inputVal.location.live) {
                    content = content.concat(`&lat=${this.inputVal.location.center.lat}`)
                }
                if (this.inputVal.location.center.lng && !this.inputVal.location.live) {
                    content = content.concat(`&lng=${this.inputVal.location.center.lng}`)
                }
                if (this.inputVal.location.mapboundary && this.inputVal.location.live) {
                    content = content.concat(`&NElat=${this.inputVal.location.mapboundary._northEast.lat}&NElng=${this.inputVal.location.mapboundary._northEast.lng}&SWlat=${this.inputVal.location.mapboundary._southWest.lat}&SWlng=${this.inputVal.location.mapboundary._southWest.lng}&Clat=${this.inputVal.location.center.lat}&Clng=${this.inputVal.location.center.lng}&zoom=${this.inputVal.location.zoom}`)
                }
            }
            if (this.inputVal.category.length) {
                content = content.concat(`&category=${this.inputVal.category.map( cat => cat.id)}`)
            }
            if (this.inputVal.tag.length) {
                content = content.concat(`&tag=${this.inputVal.tag.map( tag => tag.id)}`)
            }
            if (this.inputVal.searchDates.length) {
                content = content.concat(`&start=${this.inputVal.searchDates[0]}&end=${this.inputVal.searchDates[1]}`)
            }
            if (this.inputVal.price.length) {
                content = content.concat(`&price0=${this.inputVal.price[0]}&price1=${this.inputVal.price[1]}`)
            }
            content = content.concat(`&page=${this.inputVal.paginate}`)

            return history.pushState(null, null,`/index/search?${content}`)

        },
    }
}
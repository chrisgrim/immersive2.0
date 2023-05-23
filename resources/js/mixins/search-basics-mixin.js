export default {
    methods: {
        addPushState(value) {
            console.log(value);
            let content = `searchType=${value.searchType}`;
            if (value && value.location) {
                if (typeof value.location.live !== 'undefined') {
                    content = content.concat(`&live=${value.location.live}`)
                }
                if (value.location.name) {
                    content = content.concat(`&city=${value.location.name}`)
                }
                if (value.location.center.lat && !value.location.live) {
                    content = content.concat(`&lat=${value.location.center.lat}`)
                }
                if (value.location.center.lng && !value.location.live) {
                    content = content.concat(`&lng=${value.location.center.lng}`)
                }
                if (value.location.mapboundary && value.location.live) {
                    content = content.concat(`&NElat=${value.location.mapboundary._northEast.lat}&NElng=${value.location.mapboundary._northEast.lng}&SWlat=${value.location.mapboundary._southWest.lat}&SWlng=${value.location.mapboundary._southWest.lng}&Clat=${value.location.center.lat}&Clng=${value.location.center.lng}&zoom=${value.location.zoom}`)
                }
            }
            if (value.category.length) {
                content = content.concat(`&category=${value.category.map( cat => cat.id)}`)
            }
            if (value.tag.length) {
                content = content.concat(`&tag=${value.tag.map( tag => tag.id)}`)
            }
            if (value.searchDates.length) {
                content = content.concat(`&start=${value.searchDates[0]}&end=${value.searchDates[1]}`)
            }
            if (value.price.length) {
                content = content.concat(`&price0=${value.price[0]}&price1=${value.price[1]}`)
            }
            content = content.concat(`&page=${value.paginate}`)

            return history.pushState(null, null,`/index/search?${content}`)

        },
    }
}
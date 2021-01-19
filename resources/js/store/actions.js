 let actions = {

        createEvent({commit}, event) {
            axios.post('/api/posts', event)
                .then(res => {
                    commit('CREATE_EVENT', res.data)
                }).catch(err => {
                console.log(err)
            })

        },
        fetchEvents({commit}) {
            axios.get('/api/events')
                .then(res => {
                    commit('FETCH_EVENTS', res.data)
                }).catch(err => {
                console.log(err)
            })
        },
        searchEvents({commit}, search) {
            axios.post('/vuex/search', search)
                .then(res => {
                    commit('SEARCH_EVENTS', res.data)
                }).catch(err => {
                console.log(err)
            })
        },
        userSearchRequest({commit}, search) {
            commit('FETCH_SEARCH', search)
        },
        deleteEvent({commit}, event) {
            axios.delete(`/api/posts/${event.id}`)
                .then(res => {
                    if (res.data === 'ok')
                        commit('DELETE_EVENT', event)
                }).catch(err => {
                console.log(err)
            })
        },
    }

    export default actions
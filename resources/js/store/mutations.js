let mutations = {
        adduser(state, user) {
          state.user = user
        },
        addsearchtype(state, searchtype) {
          state.searchtype = searchtype
        },
        CREATE_EVENT(state, event) {
            state.events.unshift(event)
        },
        FETCH_EVENTS(state, events) {
            return state.events = events
        },
        FETCH_SEARCH(state, userSearchRequest) {
            return state.userSearchRequest = userSearchRequest
        },
        SEARCH_EVENTS(state, events) {
            return state.events = events
        },
        DELETE_EVENT(state, event) {
            let index = state.events.findIndex(item => item.id === event.id)
            state.events.splice(index, 1)
        },

    }
    export default mutations
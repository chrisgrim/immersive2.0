    let getters = {
        events: state => {
         return state.events
        },
        userSearchRequest: state => {
        	return state.userSearchRequest
        },
        user: state => state.user,
        searchtype: state => state.searchtype,
    }

    export default  getters
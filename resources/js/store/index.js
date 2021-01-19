    import Vue from 'vue'
    import Vuex from 'vuex'
    // import actions from './actions'
    // import mutations from './mutations'
    // import getters from './getters'
    // import state from "./state";

    // export default new Vuex.Store({
    //     state,
    //     mutations,
    //     getters,
    //     actions
    // })

    Vue.use(Vuex);

export default new Vuex.Store({
state: {
    user: {},
    map: false,
    mobile: true,
    searchtype: 'Immersive Online',
    navurl: null,
    presearch: [],
    bounds:'',
    filter: false,
    mobilelocation: false,
    mobiledates: false,
    dates: '',
  },


  
mutations: {
    adduser (state, user) {
        state.user = user;
    },
    showmap (state, map) {
        state.map = map
    },
    storeMapBounds (state, bounds) {
        state.bounds = bounds
    },
    ismobile (state, mobile) {
        state.mobile = mobile
    },
    searchtype (state, searchtype) {
        state.searchtype = searchtype
    },
    presearch (state, presearch) {
        state.presearch = presearch
    },
    onnavurl (state, navurl) {
        state.navurl = navurl
    },
    onfilter (state, filter) {
        state.filter = filter
    },
    showlocation (state, mobilelocation) {
        state.mobilelocation = mobilelocation
    },
    showdates (state, mobiledates) {
        state.mobiledates = mobiledates
    },
    displaydates (state, dates) {
        state.dates = dates
    },
},
actions: {
    async getContent ({ commit }) {
        try {
            let {data} = await axios.get('/api/search/navbar', { params: { keywords: '', type: 'category' } })
            commit('presearch', data)
        } catch(err) {
          console.log(err)
        }
    }
},
  
});
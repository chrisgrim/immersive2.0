let mutations = {
    adduser (state, user) {
        state.user = user;
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
    loginModal (state, loginModal) {
        state.loginModal = loginModal
    },
    notifyModal (state, notifyModal) {
        state.notifyModal = notifyModal
    },
    verifyModal (state, verifyModal) {
        state.verifyModal = verifyModal
    },
}

export default mutations
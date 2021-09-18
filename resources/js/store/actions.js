let actions = {
    async getContent ({ commit }) {
        try {
            let {data} = await axios.get('/api/search/navbar', { params: { keywords: '', type: 'category' } })
            commit('presearch', data)
        } catch(err) {
          console.log(err)
        }
    }
}

export default actions
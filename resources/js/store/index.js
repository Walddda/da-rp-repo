import { createStore } from 'vuex'

const store = createStore({
    state () {
      return {
        contract: '',
        beats: [],
      }
    },
    mutations: {
    }
  })

export default store;
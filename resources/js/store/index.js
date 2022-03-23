import { createStore } from 'vuex'
import { loadBlockchainData } from '@/services/blockchain'

const store = createStore({
    state () {
      return {
        contract: '',
        beats: [],
      }
    },
    mutations: {
    //   readContract (state) {
    //       loadBlockchainData()
    //         .then(response => {
    //             state.contract = response
    //         })
    //   }
    }
  })

export default store;
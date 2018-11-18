/**
 * Imports
 */
import Vue from 'vue'
import Vuex from 'vuex'

/**
 * Vuex
 */
Vue.use(Vuex)

/**
 * Global state
 */
import * as actions from './actions'
import * as getters from './getters'
import * as mutations from './mutations'

/**
 * State
 */
const __newBoard = [['', '', ''], ['', '', ''], ['', '', '']]

const state = {
    game: {
        board: __newBoard,
    },

    score: {
        human: 0,
        ai: 0,
    },

    emptyBoard: __newBoard,
}

/**
 * Store
 */
let store = new Vuex.Store({
    state,
    actions,
    getters,
    mutations,
})

store.dispatch('loadScore')

export default store

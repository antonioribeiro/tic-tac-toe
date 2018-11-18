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
 * Modules
 */
import environment from './modules/environment'
import ticTacToe from './modules/ticTacToe'

/**
 * State
 */
const state = {}

/**
 * Store
 */
let store = new Vuex.Store({
    state,
    actions,
    getters,
    mutations,
    modules: {
        ticTacToe,
        environment,
    },
})

export default store

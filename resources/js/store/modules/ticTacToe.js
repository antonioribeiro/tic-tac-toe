const __newBoard = [['', '', ''], ['', '', ''], ['', '', '']]

const state = {
    game: {
        board: __newBoard,
    },

    emptyBoard: __newBoard,
}

const getters = {}

const actions = {}

const mutations = {
    setGame(state, payload) {
        state.game = payload
    },

    restart(state) {
        state.game.board = __newBoard
    },
}

export default {
    state,
    getters,
    actions,
    mutations,
    namespaced: true,
}

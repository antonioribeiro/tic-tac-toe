export function setGame(state, payload) {
    state.game = payload
}

export function restart(state) {
    state.game.board = state.emptyBoard

    state.game.finished = false
}

export function addPointForHuman(state) {
    state.score.human = state.score.human + 1
}

export function addPointForAI(state) {
    state.score.ai = state.score.ai + 1
}

export function setScore(state, payload) {
    state.score = payload
}

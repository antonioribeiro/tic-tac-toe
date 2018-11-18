export function addPointForHuman(context) {
    context.commit('addPointForHuman')

    context.dispatch('saveScore')
}

export function addPointForAI(context) {
    context.commit('addPointForAI')

    context.dispatch('saveScore')
}

export function saveScore(context) {
    window.localStorage.setItem(
        'tictactoe.score',
        JSON.stringify(context.state.score),
    )
}

export function loadScore(context) {
    const score = window.localStorage.getItem('tictactoe.score')

    if (score) {
        context.commit('setScore', JSON.parse(score))
    }
}

<template>
    <div>
        <div class="container-fluid">
            <div class="row mt-5 ml-2 mr-2">
                <div class="col-12 col-sm-3 mb-3">
                    <table class="mx-auto mb-4">
                        <tr v-for="(row, rowKey) in board">
                            <td
                                v-for="(column, columnKey) in row"
                                class="text-center align-self-center"
                            >
                                <app-mark
                                    :player="column"
                                    :column="columnKey"
                                    :row="rowKey"
                                    @play="play($event)"
                                    :playing="
                                        playing.column === columnKey &&
                                            playing.row === rowKey
                                    "
                                ></app-mark>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-12 col-sm-9">
                    <div class="row">
                        <div class="col-sm-12 col-md-8">
                            <div class="card-deck mb-3 text-center">
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-header">
                                        <h5 class="my-0 font-weight-normal">
                                            Players and Score
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <h2
                                                    class="card-title pricing-card-title text-left"
                                                >
                                                    <div
                                                        class="btn btn-primary m-0"
                                                    >
                                                        <h2 class="m-0">X</h2>
                                                    </div>
                                                    Human
                                                    <small class="text-muted"
                                                        >({{
                                                            score.human
                                                        }})</small
                                                    >
                                                </h2>
                                                <h2
                                                    class="card-title pricing-card-title text-left"
                                                >
                                                    <div
                                                        class="btn btn-warning m-0"
                                                    >
                                                        <h2 class="m-0">O</h2>
                                                    </div>
                                                    AI
                                                    <small class="text-muted"
                                                        >({{ score.ai }})</small
                                                    >
                                                </h2>
                                            </div>
                                        </div>

                                        <button
                                            @click="restart()"
                                            :class="
                                                'mt-2 btn btn-block btn-' +
                                                    (playing || !started
                                                        ? 'secondary'
                                                        : 'danger')
                                            "
                                            :disabled="playing || !started"
                                        >
                                            <span v-if="playing"
                                                >thinking...</span
                                            >
                                            <span v-else-if="!started"
                                                >play a move now</span
                                            >
                                            <span v-else="!started"
                                                >restart</span
                                            >
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="col-sm-12 col-md-4"
                            v-if="game.finished && true"
                        >
                            <div
                                :class="
                                    'jumbotron text-white rounded text-center' +
                                        ' ' +
                                        (game.result === 'W'
                                            ? 'bg-primary'
                                            : game.result === 'L'
                                            ? 'bg-warning text-dark'
                                            : 'bg-dark')
                                "
                            >
                                <h4 class="font-italic">
                                    <span v-if="game.result === 'W'"
                                        >HUMAN WINS!</span
                                    >

                                    <span v-if="game.result === 'L'"
                                        >AI WINS!</span
                                    >

                                    <span v-if="game.result === 'D'"
                                        >IT'S A DRAW!</span
                                    >
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
    props: [],

    data() {
        return {
            playing: false,
        }
    },

    computed: {
        board() {
            return this.$store.state.game.board
        },

        game() {
            return this.$store.state.game
        },

        score() {
            return this.$store.state.score
        },

        started() {
            return this.$store.state.game.board !== this.$store.state.emptyBoard
        },
    },

    methods: {
        ...mapActions(['addPointForHuman', 'addPointForAI']),

        play(move) {
            const params = new URLSearchParams()

            params.append('board', this.board)
            params.append('column', move.column)
            params.append('row', move.row)
            params.append('player', 'X')

            this.playing = move

            axios.post('/play', params).then(response => {
                this.$store.commit('setGame', response.data)

                this.playing = false

                this.checkResult()
            })
        },

        restart() {
            if (!this.game.finished) {
                this.ai++
            }

            this.$store.commit('restart')
        },

        checkResult() {
            if (this.game.finished) {
                if (this.game.result === 'W') {
                    this.addPointForHuman()
                }

                if (this.game.result === 'L') {
                    this.addPointForAI()
                }

                if (this.game.result === 'D') {
                    this.addPointForHuman()
                    this.addPointForAI()
                }
            }
        },
    },
}
</script>

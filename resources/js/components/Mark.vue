<template>
    <div>
        <div
            @click="player ? doNothing() : play(column, row)"
            :class="
                'btn btn-lg btn-' +
                    (player
                        ? player === 'X'
                            ? 'primary'
                            : 'warning'
                        : 'light')
            "
            :style="{ cursor: player ? 'no-drop' : 'pointer' }"
        >
            <h1 class="m-0">
                <span
                    v-if="!playing"
                    v-html="player ? player : 'O'"
                    :style="{ opacity: player ? 1 : 0 }"
                ></span>

                <span v-if="playing"><i class="fa fa-spin">X</i> </span>
            </h1>
        </div>
    </div>
</template>

<script>
export default {
    props: ['player', 'move', 'column', 'row', 'playing'],

    methods: {
        play(column, row) {
            this.$emit('play', { column: column, row: row })
        },

        doNothing() {},
    },
}
</script>

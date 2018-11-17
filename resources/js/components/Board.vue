<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-6 content">
                <div class="container-fluid tic-container">
                    <div class="row tic-row">
                        <div class="col-4 tic-box"> 1 </div>
                        <div class="col-4 tic-box"> 2 </div>
                        <div class="col-4 tic-box"> 3 </div>
                    </div>
                    <div class="row tic-row">
                        <div class="col-4 tic-box"> 4 </div>
                        <div class="col-4 tic-box"> 5 </div>
                        <div class="col-4 tic-box"> 6 </div>
                    </div>
                    <div class="row tic-row">
                        <div class="col-4 tic-box"> 7 </div>
                        <div class="col-4 tic-box"> 8 </div>
                        <div class="col-4 tic-box"> 9 </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-2 tic-panel">
                PANEL-THINGY
                <br>Score:
                <br>Foo bar: stuff
                <br> Reset game and all that blah
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'value',
            'name',
            'label',
            'required',
            'form',
            'type',
            'dusk',
            'readonly',
        ],
    }
</script>

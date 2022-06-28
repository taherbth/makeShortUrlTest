<template>
    <div>
        <plyr ref="player">
            <video controls playsinline crossorigin :src="video.path" :data-poster="video.poster" :data-title="video.title"></video>
        </plyr>
    </div>
</template>
<script>
    import Plyr from 'vue-plyr'

    export default {
        data() {
            return {
                player: null
            }
        },
        props: {
            video: {
                type: Object
            }
        },
        components: {
            Plyr
        },
        mounted() {
            this.player = this.$refs.player.player;
        },
    }
</script>
<style scoped>

</style>


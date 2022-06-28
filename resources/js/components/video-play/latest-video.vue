<template>
    <div>
        <div class="history_video_container" @click="goToVideo">
            <img class="video-thumbnail" :src="thumbnail" />
            <div class="video-info-container">
                <div class="video-info">
                    <span class="badge badge-secondary video-duration">{{ video_duration }}</span><br>
                    <star-rating text-class="text-xs mt-1 text-disable" :increment="0.5" :inline="true" :read-only="true" :star-size="12" active-color="#FFAA38" :rating="rating" />
                    <span class="text-disable text-xs"><i class="fa fa-eye pl-2" aria-hidden="true"></i> {{ video_views }} views</span>

                    <h3 class="mt-1 text-white">{{ video_title }}</h3>
                    <p class="text-sm text-disable">{{ video_short_desc }}</p>
                    <p class="text-xs text-disable mb-0"> <img class="rounded-circle" style="width:20px" :src="video_uploader_logo" /> {{ video_uploader_name }} {{ video_upload_at }} ago </p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import StarRating from 'vue-star-rating'

    export default {
        data() {
            return {

            }
        },
        props: {
            video_link: {
                type: String,
            },
            video_duration: {
                type: String,
                default: '00:00'
            },
            thumbnail: {
                type: String,
            },
            rating: {
                type: Number,
            },
            video_views: {
                type: Number,
            },
            video_title: {
                type: String,
            },
            video_short_desc: {
                type: String,
            },
            video_uploader_logo: {
                type: String,
            },
            video_uploader_name: {
                type: String,
            },
            video_upload_at: {
                type: String,
            },
        },
        components: {
            StarRating
        },
        methods: {
            goToVideo: function (){
                this.$router.push(this.video_link)
            }
        },
        mounted() {

        },
    }
</script>
<style scoped>
    .history_video_container{
        position: relative;
        border-radius: 5px;
        overflow: hidden;
        cursor: pointer;
    }
    .history_video_container .video-thumbnail{
        width: 100%;
        height: auto;
        filter: brightness(50%);
    }
    .history_video_container .video-info-container{
        position: absolute;
        bottom: 0px;
        left: 0px;
        right: 0px;
    }
    .history_video_container .video-info-container .video-info{
        padding: 30px;
        padding-top: 0px;
    }
    .history_video_container .video-thumbnail{
        transition: transform .5s ease;
    }
    .history_video_container:hover .video-thumbnail{
        transform: scale(1.5);
    }

    @media(max-width: 991px){
        .history_video_container{
            height: 400px;
        }
        .history_video_container .video-thumbnail{
            height: 100%;
        }
    }
</style>

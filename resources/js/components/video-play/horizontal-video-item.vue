<template>
    <div>
        <div class="video_item">
            <img :src="thumbnail" class="video-thumbnail" @click="goToVideo" />
            <div class="video-info">
                <star-rating text-class="text-xs mt-1 text-secondary" :increment="0.5" :inline="true" :read-only="true" :star-size="12" active-color="#FFAA38" :rating="rating" />
                <span class="text-neutral text-xs"><i class="fa fa-eye pl-2" aria-hidden="true"></i> {{ video_views }} views</span>

                <p class="video-title mb-0 mt-1" @click="goToVideo">{{ video_title }}</p>
                <p class="text-sm text-secondary">{{ video_short_desc }}</p>

                <p class="video-category">
                    <span class="badge badge-icon text-xs">{{ topic }}</span>
                    <span class="badge badge-icon text-xs">Chapter {{ chapter }}</span>
                    <span class="badge badge-icon text-xs">Episode {{ episode }}</span>
                </p>
                <p class="text-xs text-icon"> <img class="rounded-circle" style="width:20px" :src="video_uploader_logo" /> {{ video_uploader_name }} {{ video_upload_at }} ago </p>
            </div>
            <span class="badge badge-secondary video-duration">{{ video_duration }}</span>
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
            topic: {
                type: String,
            },
            chapter: {
                type: Number,
            },
            episode: {
                type: Number,
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
            StarRating,
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
    .video_item{
        display: block;
        position: relative;
    }
    .video_item .video-thumbnail{
        width: 100%;
        height: auto;
        border-radius: 5px;
    }
    .video_item .video-info{
        margin-top: 20px;
    }
    .video_item .video-duration{
        position: absolute;
        top: 12px;
        left: 12px;
    }
    .video_item .video-thumbnail, .video_item .video-title{
        cursor: pointer;
    }
</style>

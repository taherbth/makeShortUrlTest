<template>
    <div class="response_item_container">
        <div class="response_item">
            <div class="d-block d-sm-flex">
                <img :src="image" class="rounded" style="width:116px;height:80px"/>
                <div class="ml-sm-4 video-info">
                    <router-link class="mb-0" :to="video_url">{{ title }}</router-link> <br>
                    <star-rating text-class="text-xs mt-1 text-secondary" :increment="0.5" :inline="true" :read-only="true" :star-size="12" active-color="#FFAA38" :rating="rating" />
                    <span class="text-neutral text-xs"><i class="fa fa-eye pl-2" aria-hidden="true"></i> {{ views }} views</span>
                    <p class="video-category-default">
                        <span class="badge badge-icon text-xs">{{ topic }}</span>
                        <span class="badge badge-icon text-xs">{{ chapter }}</span>
                        <span class="badge badge-icon text-xs">{{ episode }}</span>
                    </p>
                    <span class="text-icon text-sm">Last edited {{ last_edited }} ago</span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import StarRating from 'vue-star-rating'

    export default {
        data() {
            return {}
        },
        components: {
            StarRating
        },
        props: {
            video_url: {
                type: String
            },
            image:{
                type: String
            },
            title:{
                type: String
            },
            last_edited:{
                type: String
            },
            rating: {
                type: Number
            },
            views: {
                type: Number
            },
            topic: {
                type: String
            },
            chapter: {
                type: String
            },
            episode: {
                type: String
            },
        },
        methods: {

        },
        mounted() {

        },
    }
</script>
<style scoped>
    .response_item_container{
        display: block;
        background: #fff;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 8px;
    }
    .response_item{
        background: #fff;
        padding: 20px;
        border-radius: 5px;
    }
    .response_item_container:hover .response_item {
        background: #F7F8F9;
    }
    .response_item_container .response_item .video-info a{
        text-decoration: none;
        color: #234153;
    }
    @media (max-width: 750px) {
        .response_item{
            display: block;
        }
        .response_item img{
            width: 100% !important;
            height: auto !important;
        }
        .d-sm-flex{
            display: block !important;
        }
        .ml-sm-4{
            margin-left: 0 !important;
        }
        .video-info{
            margin-top: 10px;
        }
    }
</style>

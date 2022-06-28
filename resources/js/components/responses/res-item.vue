<template>
    <div class="response_item">
        <div class="left-side">
            <div class="d-block d-sm-flex">
                <img :src="image" class="rounded align-self-center" />
                <div class="align-self-center mt-1 ml-sm-4">
                    <div>
                        <router-link :to="url" class="response-title">{{ title }}</router-link>
                        <p class="video-category-default">
                            <span class="badge badge-icon text-xs">{{ topic }}</span>
                            <span class="badge badge-icon text-xs">{{ chapter }}</span>
                            <span class="badge badge-icon text-xs">{{ episode }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="right-side">
            <h3 class="font-weight-bold">{{ total_responses }}</h3>
            <p class="mt-2 ml-2">Responses</p>
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
            image:{
                type: String
            },
            title:{
                type: String
            },
            url:{
                type: String
            },
            topic:{
                type: String
            },
            chapter:{
                type: String
            },
            episode:{
                type: String
            },
            total_responses:{
                type: Number
            },

        },
        components: {
            StarRating
        },
        methods: {

        },
        mounted() {

        },
    }
</script>
<style scoped>
    .response_item{
        width: 100%;
        background: #fff;
        padding: 30px;
        margin-bottom: 8px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
    .response_item .left-side img{
        width: 119px;
        height: 77px;
    }
    .response_item .left-side .response-title{
        font-weight: 600;
        color: var(--primary-text);
        text-decoration: none;
    }
    .response_item .right-side{
        display: flex;
        align-self: center;
    }
    @media (max-width: 750px) {
        .response_item{
            display: block;
        }
        .response_item .left-side img{
            width: 100%;
            height: auto;
        }
        .d-sm-flex{
            display: block !important;
        }
        .ml-sm-4{
            margin-left: 0 !important;
        }
    }
</style>

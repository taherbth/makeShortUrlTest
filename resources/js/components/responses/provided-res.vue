<template>
    <div class="response_item">
        <div class="left-side">
            <div class="d-block d-sm-flex">
                <img :src="image" class="rounded align-self-center" />
                <div class="align-self-center mt-1 ml-sm-4">
                    <div>
                        <h5 class="mg-b-15">How to use technology to explore more.</h5>
                        <p class="video-category-default">
                            <span class="badge badge-icon text-xs">{{ topic }}</span>
                            <span class="badge badge-icon text-xs">{{ chapter }}</span>
                            <span class="badge badge-icon text-xs">{{ episode }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="right-side d-sm-flex">
            <div class="align-self-center">
                <star-rating text-class="text-xs mt-1 text-secondary" :increment="0.5" :inline="true" :read-only="true" :star-size="17" active-color="#FFAA38" :rating="anwswe_rating" />
                <button class="btn btn-border-primary ml-3">Rate Again</button>
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
            image:{
                type: String
            },
            anwswe_rating:{
                type: Number
            },
            topic:{
                type: String
            },
            chapter:{
                type: String
            },
            episode:{
                type: String
            }
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
    .response_item .right-side button{
        width: max-content;
    }
    @media (max-width: 750px) {
        .response_item{
            display: block;
        }
        .response_item .left-side img{
            width: 100%;
        }
        .d-sm-flex{
            display: block !important;
        }
        .ml-sm-4{
            margin-left: 0 !important;
        }
    }
</style>

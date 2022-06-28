<template>
    <div class="response_item_container">
        <p class="text-xs">
            <img class="rounded-circle" style="width:20px" :src="logo" /> {{ name }}
            <span class="text-icon" v-if="upload_at<24" >{{ upload_at }}h ago</span>
            <span class="text-icon" v-else-if="Math.floor(upload_at/24)===1" >{{ 1 }}day ago</span>
            <span class="text-icon" v-else >{{ Math.floor(upload_at/24) }}days ago</span>
        </p>
        <p>{{ response }}</p>
        <star-rating text-class="text-xs text-secondary mt-1" :increment="0.5" :inline="true" :read-only="true" :star-size="12" active-color="#FFAA38" :rating="rate" />
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
            logo:{
                type: String
            },
            name:{
                type: String
            },
            upload_at:{
                type: Number
            },
            response: {
                type: String
            },
            rate: {
                type: Number
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
    .response_item_container{
        background: #fff;
        padding: 30px;
        border-radius: 5px;
        margin-bottom: 8px;
    }
    .response_item_container p{
        margin-bottom: 8px;
    }
</style>

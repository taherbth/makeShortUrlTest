<template>
    <form class="response-container">

        <div class="d-flex mb-4">
            <img :src="ResponsesInfo.profile_picture || '/assets/images/user-avatar.png'" class="user-logo align-self-start" />
            <div class="align-self-start ml-2">
                <p class="text-sm mb-0">{{ ResponsesInfo.name }}</p>
                <p class="text-xs text-neutral mb-0">{{ ResponsesInfo.email }}</p>
                <span class="badge-level">O Level</span>
            </div>
        </div>

        <div v-for="responses in StdResponses" :key="responses[0].id">

            <!-- this is for radio, checkbox and options questions -->
            <div class="response-item-container mb-4" v-if="responses[0].question_type == 4">
                <div class="response-item">
                    <p class="mb-2">{{ responses[0].question }}</p>
                    <p class="text-sm text-secondary d-inline-block" v-for="(res, i) in responses" :key="i">
                        <span v-if="i < 1">{{ res.student_response }}</span>
                        <span v-else>, {{ res.student_response }}</span>
                    </p>
                </div>
                <div class="align-self-center">
                    <star-rating text-class="rating-text" :read-only="true" :inline="true" :glow="3" :star-size="17" active-color="#FFAA38" :rating="responses[0].is_rated===0?0:responses[0].rating" />
                </div>
            </div>

            <!-- this is for number and text questions -->
            <div class="response-item-container mb-4" v-else>
                <div class="response-item">
                    <p class="mb-2">{{ responses[0].question }}</p>
                    <p class="text-sm text-secondary">{{ responses[0].student_response }}</p>
                </div>
                <div class="align-self-center">
                    <star-rating text-class="rating-text" :read-only="true" :inline="true" :glow="3" :star-size="17" active-color="#FFAA38" :rating="responses[0].is_rated===0?0:responses[0].rating" />
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary-text" disabled>Confirm Rating</button>

    </form>
</template>
<script>
    import StarRating from 'vue-star-rating'

    export default {
        data() {
            return {
                ResponsesId: [],
                FilteredResponsesInfo: [],
                StdResponses: []
            }
        },
        props: {
            ResponsesInfo: {
                type: Object
            }
        },
        components: {
            StarRating
        },
        methods: {
            convert_responses: function (){
                for(let item in this.ResponsesInfo.question_id_array){
                    this.ResponsesId.push(this.ResponsesInfo.question_id_array[item])
                }
                this.filter_data()
                this.ResponsesId.forEach((item) => {
                    let filteredData = this.FilteredResponsesInfo.filter((question) => {
                        return question.question_id === item
                    })
                    this.StdResponses.push(filteredData)
                })
            },
            filter_data: function (datas){
                if (typeof this.ResponsesInfo.responses === "object") {
                    for(let item in this.ResponsesInfo.responses){
                        this.FilteredResponsesInfo.push(this.ResponsesInfo.responses[item])
                    }
                } else {
                    this.FilteredResponsesInfo = this.ResponsesInfo.responses
                }
            },
        },
        mounted() {
            this.convert_responses()
        },
    }
</script>
<style>
    .rating-text{
        position: absolute;
        right: 28px;
        color: #D3D9DC;
        font-size: 13px;
    }
</style>
<style scoped>
    .vue-star-rating{
        border: 1px solid #E2F4FE;
        padding: 10px 20px;
        border-radius: 30px;
    }



    .user-logo{
        width: 35px;
        height: 35px;
        border-radius: 50%;
    }
    .badge-level{
        background: #38B6FF;
        color: #fff;
        font-size: 11px;
        padding: 3px 6px;
        border-radius: 5px;
    }
    .response-container{
        background: #fff;
        border-radius: 5px;
        padding: 30px;
        margin-bottom: 20px;
    }
    .response-item{
        border-left: 4px solid #E8EBED;
        padding-left: 20px;
    }

    .response-item-container{
        display: flex;
        justify-content: space-between;
    }


    .response_item{
        width: 100%;
        background: #fff;
        padding: 30px;
        margin-bottom: 8px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
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
        }
        .d-sm-flex{
            display: block !important;
        }
        .ml-sm-4{
            margin-left: 0 !important;
        }
    }
</style>

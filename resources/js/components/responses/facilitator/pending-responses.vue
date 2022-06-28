<template>
    <div>
        <form class="response-container" @submit.prevent="SubmitResponsesRating">

            <div class="d-flex mb-4">
                <img :src="ResponsesInfo.profile_picture || '/assets/images/user-avatar.png'" class="user-logo align-self-start" />
                <div class="align-self-start ml-2">
                    <p class="text-sm mb-0">{{ ResponsesInfo.name }}</p>
                    <p class="text-xs text-neutral mb-0">{{ ResponsesInfo.email }}</p>
                    <span class="badge-level">O Level</span>
                </div>
            </div>

            <input type="hidden" name="course_id" :value="$route.params.id" required />
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
                        <star-rating text-class="rating-text" :increment="1" :inline="true" :glow="3" :star-size="17" active-color="#FFAA38" :read-only="responses[0].is_rated===0?false:true"  :rating="responses[0].is_rated===0?0:responses[0].rating">
                            <template v-slot:screen-reader="slotProps" v-if="responses[0].is_rated === 0">
                                <input class="d-none" type="number" v-for="res in responses" :key="res.id" :name="`rating[${res.id}]`" :value="slotProps.rating" min="1" required />
                            </template>
                        </star-rating>
                    </div>
                </div>

                <!-- this is for number and text questions -->
                <div class="response-item-container mb-4" v-else>
                    <div class="response-item">
                        <p class="mb-2">{{ responses[0].question }}</p>
                        <p class="text-sm text-secondary">{{ responses[0].student_response }}</p>
                    </div>
                    <div class="align-self-center">
                        <star-rating text-class="rating-text" :increment="1" :inline="true" :glow="3" :star-size="17" active-color="#FFAA38" :read-only="responses[0].is_rated===0?false:true"  :rating="responses[0].is_rated===0?0:responses[0].rating">
                            <template v-slot:screen-reader="slotProps" v-if="responses[0].is_rated === 0">
                                <input class="d-none" type="number" :name="`rating[${responses[0].id}]`" :value="slotProps.rating" min="1" required />
                            </template>
                        </star-rating>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary-text" :disabled="is_disable">Confirm Rating</button>

        </form>
        <div class="modal fade" id="video-rated" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-bottom-0">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center mb-5">
                            <img src="../assets/img/success.png" style="width:100%">
                            <h4>Great Job! Successfully Rated.</h4>
                            <p class="text-sm text-secondary">Your Rate has been submitted successfully. The facilitator can see your response and rate based on your answer.</p>
                        </div>
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
                ResponsesId: [],
                FilteredResponsesInfo: [],
                StdResponses: [],
                is_disable: true
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
            SubmitResponsesRating: function (el){
                let data = this.FilterForm(el.target, 'formdata')

                axios.post('api/facilitator/post_facilitator_rate_responses', data).then(response => {
                    $('#video-rated').modal('show')
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            isSubmitable: function (){
                let total_rating_count = 0
                this.StdResponses.forEach((item) => {
                    if(item[0].is_rated){
                        total_rating_count++
                    }
                })
                if(total_rating_count === this.StdResponses.length){
                    this.is_disable = true
                }else{
                    this.is_disable = false
                }
            }
        },
        mounted() {
            this.convert_responses()
            this.isSubmitable()
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

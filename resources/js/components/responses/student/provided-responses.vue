<template>
    <div class="response-container">
        <div class="row">
            <div class="col-lg-4">
                <div class="course-info">
                    <img  :src="image || '/assets/images/user-avatar.png'" class="rounded" />
                    <div>
                        <h5 class="mt-4">{{ title }}</h5>
                        <p class="video-category-default">
                            <span class="badge badge-icon text-xs">{{ topic }}</span>
                            <span class="badge badge-icon text-xs">{{ chapter }}</span>
                            <span class="badge badge-icon text-xs">{{ episode }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="mb-4">
                    <h4>Your Responses</h4>
                    <p class="text-xs text-neutral">{{ response_updtaed_at }}</p>
                </div>

                <div v-for="(item, index) in questions" :key="index">
                    <div class="response-item mb-4" v-if="item.question_type == 4">
                        <p class="mb-2">{{ item.question }}</p>
                        <p class="text-sm text-secondary mb-2 d-inline-block" v-for="(std_res, i) in item.course_question_responses" :key="i">
                            <span v-if="i < 1">{{ std_res.student_response }}</span>
                            <span v-else>, {{ std_res.student_response }}</span>
                        </p>
                        <star-rating v-if="item.course_question_responses[0].is_rated === 1" text-class="mt-1" :increment="1" :inline="false" :glow="3" :read-only="true" :star-size="17" active-color="#FFAA38" :rating="item.course_question_responses[0].rating" />
                        <star-rating v-else text-class="mt-1" :increment="1" :inline="false" :glow="3" :read-only="true" :star-size="17" active-color="#FFAA38" />
                    </div>

                    <div class="response-item mb-4" v-else>
                        <p class="mb-2">{{ item.question }}</p>
                        <p class="text-sm text-secondary mb-2">{{ item.course_question_responses[0].student_response }}</p>
                        <star-rating v-if="item.course_question_responses[0].is_rated === 1" text-class="mt-1" :increment="1" :inline="false" :glow="3" :read-only="true" :star-size="17" active-color="#FFAA38" :rating="item.course_question_responses[0].rating" />
                        <star-rating v-else text-class="mt-1" :increment="1" :inline="false" :glow="3" :read-only="true" :star-size="17" active-color="#FFAA38" />
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

            }
        },
        props: {
            image:{
                type: String
            },
            title:{
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
            response_updtaed_at: {
                type: String
            },
            questions: {
                type: Array
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
        margin-bottom: 12px;
    }
    .response-container h4{
        font-weight: 600;
    }
    .response-item{
        border-left: 4px solid #E8EBED;
        padding-left: 20px;
    }
    .course-info img{
        width: 100%;
    }
    @media (max-width: 992px) {
        .course-info{
            display: flex;
            margin-bottom: 30px;
        }
        .course-info img{
            width: 100px;
            height: 65px;
            margin-right: 30px;
        }
        .course-info div h5{
            margin-top: 0 !important;
        }
    }
</style>

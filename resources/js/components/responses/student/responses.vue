<template>
    <div>
        <Layout>
            <div class="tabs">
                <a :class="['btn', 'pending', tabActive==='pending'? 'active':'']" href="javascript:void(0)" @click="() => this.tabActive = 'pending'">
                    <font-awesome icon="star" /> {{ labelTextRes('Awaiting Ratings') }}
                </a>
                <a :class="['btn', 'provided', tabActive==='provided'? 'active':'']" href="javascript:void(0)" @click="() => this.tabActive = 'provided'">
                    <material-verified-user /> {{ labelTextRes('Rated Responses') }}
                </a>
            </div>
            <div v-if="tabActive === 'pending'">
                <div v-if="unrated_courses.length">
                    <pendingResponses v-for="(unrated_course, i) in unrated_courses" :key="i"
                        :image="unrated_course.thumbnail" :total_responses="unrated_course.responses_count"
                        :url="`/watch/play/${unrated_course.id}`" :title="unrated_course.title"
                        :topic="unrated_course.topic" :chapter="`Chapter ${unrated_course.chapter}`" :episode="`Episode ${unrated_course.episode}`"
                        :response_updtaed_at="unrated_course.response_time_latest" :questions="unrated_course.course_questions"
                    />
                </div>
                <div class="responses-empty" v-else-if="paginationInfo_unrated.current_page">
                    <div class="center">
                        <img src="../assets/img/response-logo.png" alt="">
                        <h5 class="font-weight-bold">No responses given!</h5>
                        <p class="text-secondary">Watch videos and write responses to get ratings. <br> Your responses are rated according to the depth of your answers.</p>
                    </div>
                </div>

                <pagination align="center" :show-disabled="true" :data="paginationInfo_unrated" @pagination-change-page="get_student_unrated_courses_response">
                    <span slot="prev-nav">Prev</span>
                    <span slot="next-nav">Next</span>
                </pagination>
            </div>
            <div v-if="tabActive === 'provided'">
                <div v-if="rated_courses.length">
                    <providedResponses v-for="(rated_course, i) in rated_courses" :key="i"
                        :image="rated_course.thumbnail" :total_responses="rated_course.responses_count"
                        :title="rated_course.title" :topic="rated_course.topic" :chapter="`Chapter ${rated_course.chapter}`" :episode="`Episode ${rated_course.episode}`"
                        :response_updtaed_at="rated_course.response_time_latest" :questions="rated_course.course_questions"
                    />
                </div>
                <div class="responses-empty" v-else-if="paginationInfo_rated.current_page">
                    <div class="center">
                        <img src="../assets/img/response-logo.png" alt="">
                        <h5 class="font-weight-bold">No responses given!</h5>
                        <p class="text-secondary">Watch videos and write responses to get ratings. <br> Your responses are rated according to the depth of your answers.</p>
                    </div>
                </div>

                <pagination align="center" :show-disabled="true" :data="paginationInfo_rated" @pagination-change-page="get_student_rated_courses_response">
                    <span slot="prev-nav">Prev</span>
                    <span slot="next-nav">Next</span>
                </pagination>
            </div>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout'
    import Pagination from 'laravel-vue-pagination'
    import pendingResponses from './pending-responses'
    import providedResponses from './provided-responses'
    import materialVerifiedUser from '../../wam-partial/icons/material-verified-user'

    export default {
        data() {
            return {
                paginationInfo_rated: {},
                paginationInfo_unrated: {},
                rated_courses: [],
                unrated_courses: [],
                tabActive: 'pending',
            }
        },
        components: {
            Layout,
            Pagination,
            pendingResponses,
            providedResponses,
            materialVerifiedUser
        },
        methods: {
            get_student_rated_courses_response: function (page = 1){
                axios.get(`/api/student/get_student_all_rated_responses?page=${page}`).then(res => {
                    this.rated_courses = res.data['rated courses'].data
                    this.paginationInfo_rated = res.data['rated courses']
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            get_student_unrated_courses_response: function (page = 1){
                axios.get(`/api/student/get_student_all_unrated_responses?page=${page}`).then(res => {
                    this.unrated_courses = res.data['unrated courses'].data
                    this.paginationInfo_unrated = res.data['unrated courses']
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
        },
        mounted() {
            this.get_student_rated_courses_response()
            this.get_student_unrated_courses_response()
        },
    }
</script>
<style scoped>
    .responses-empty{
        width: 100%;
        height: 80vh;
        position: relative;
        background: #fff;
        margin-bottom: 1rem;
    }
    .responses-empty .center {
        width: 100%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    .responses-empty .center img{
        max-width: 100%;
    }

    .tabs{
        background: #fff;
        padding: 8px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .tabs .pending, .tabs .provided{
        padding-left: 30px;
        padding-right: 30px;
    }
    @media (max-width: 470px) {
        .tabs .pending, .tabs .provided{
            padding-left: 15px;
            padding-right: 15px;
        }
    }
    .tabs .pending.active{ background: #FFF2E1;color: #234153; }
    .tabs .pending.active svg{ color: #FFAA38 !important; }
    .tabs .pending:hover svg{ color: #FFAA38; }

    .tabs .provided.active{ background: #E1F4FF;color: #234153; }
    .tabs .provided.active svg{ color: #38B6FF  ; }

</style>
<style>
    .tabs .provided.active svg .svg-icon{ fill: #38B6FF !important; }
    .tabs .provided:hover svg .svg-icon{ fill: #38B6FF; }
</style>

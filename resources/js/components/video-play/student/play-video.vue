<template>
    <div>
        <Layout>
            <div class="row">
                <div class="col-lg-7">
                    <div class="video-player-wrap">
                        <!-- <vue-core-video-player :src="`/${VideoInfo.path}`" :title="VideoInfo.title" :cover="VideoInfo.thumbnail" :logo="'/images/main-logo.png'"></vue-core-video-player> -->
                        <plyr-player v-if="VideoInfo.path" :video="{title: VideoInfo.title, path: VideoInfo.path, poster: VideoInfo.thumbnail}" />
                    </div>
                    <div class="video-info">
                        <div class="header-title">
                            <h4>{{ VideoInfo.title }}</h4>
                            <p>
                                <star-rating text-class="text-xs text-secondary mt-1" :increment="0.5" :inline="true" :read-only="true" :star-size="20" active-color="#FFAA38" :rating="video_rating" />
                                <span class="text-neutral text-xs"><i class="fa fa-eye pl-2" aria-hidden="true"></i> {{ VideoInfo.total_views }} views</span>
                            </p>
                        </div>
                        <p class="text-xs">
                            <img class="rounded-circle" style="width:20px" :src="VideoInfo.facilitator_pic || VideoInfo.institution_pic || '/assets/images/user-avatar.png'" />
                            {{ VideoInfo.facilitator_name || VideoInfo.institution_name }}
                            <span class="text-icon" v-if="VideoInfo.uploaded_hours_ago<24">{{ VideoInfo.uploaded_hours_ago }}h ago</span>
                            <span class="text-icon" v-else-if="Math.floor(VideoInfo.uploaded_hours_ago/24)===1">{{ 1 }}day ago</span>
                            <span class="text-icon" v-else>{{ Math.floor(VideoInfo.uploaded_hours_ago/24) }}days ago</span>
                        </p>
                        <p>{{ VideoInfo.details }}</p>
                        <p class="video-category">
                            <span class="badge badge-icon text-xs">{{ VideoInfo.topic }}</span>
                            <span class="badge badge-icon text-xs">Chapter {{ VideoInfo.chapter }}</span>
                            <span class="badge badge-icon text-xs">Episode {{ VideoInfo.episode }}</span>
                        </p>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="tabs">
                        <div class="row">
                            <div class="col-6 pr-0">
                                <a :class="['col-12', 'mb-0', 'btn', 'submit_response', tabActive==='submit_response'? 'active':'']" href="javascript:void(0)" @click="() => this.tabActive = 'submit_response'">
                                    <font-awesome icon="paper-plane" /> {{ labelTextRes('Submit Your Response') }}
                                </a>
                            </div>
                            <div class="col-6 pl-0">
                                <a :class="['col-12', 'mb-0', 'btn', 'responses', tabActive==='responses'? 'active':'']" href="javascript:void(0)" @click="() => this.tabActive = 'responses'">
                                    <font-awesome icon="star" /> {{ labelTextRes('All Responses') }}
                                </a>
                            </div>

                        </div>
                    </div>

                    <div v-if="tabActive === 'submit_response'">
                        <div class="submit_response_container">
                            <!-- this is for submit responses -->
                            <div v-if="submit_status === 0 && rating_status === 0">
                                <p>Submit Your Response</p>
                                <form @submit.prevent="submitResponses">
                                    <input type="hidden" name="course_id" :value="$route.params.id">
                                    <submit-responses v-for="question in VideoCourseQuestions" :key="question.id" :question="question" />

                                    <div class="custom-control custom-radio mb-4">
                                        <input type="radio" class="custom-control-input" id="public" name="is_public" value="1" required>
                                        <label class="custom-control-label" for="public">Everyone in your class</label>
                                        <div class="feedback text-secondary">Everyone can see the response.</div>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="private" name="is_public" value="0" checked required>
                                        <label class="custom-control-label" for="private">Private</label>
                                        <div class="feedback text-secondary">Authority can see the response.</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary-text col-12 mt-4">Submit Response </button>
                                </form>
                            </div>
                            <!-- this is for updte responses -->
                            <div v-if="submit_status === 1 && rating_status === 0">
                                <p>Update Your Response</p>
                                <form @submit.prevent="updateResponses">
                                    <update-responses v-for="question in VideoCourseQuestions" :key="question.id" :question="question" />

                                    <div class="custom-control custom-radio mb-4">
                                        <input type="radio" class="custom-control-input" id="public" name="is_public" value="1" :checked="public_status" required>
                                        <label class="custom-control-label" for="public">Everyone in your class</label>
                                        <div class="feedback text-secondary">Everyone can see the response.</div>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="private" name="is_public" value="0" :checked="public_status===0?1:0" required>
                                        <label class="custom-control-label" for="private">Private</label>
                                        <div class="feedback text-secondary">Authority can see the response.</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary-text col-12 mt-4">Update Response</button>
                                </form>
                            </div>
                            <!-- this is for show disable form -->
                            <div v-if="submit_status === 1 && rating_status === 1">
                                <p>Your Submitted Your Response</p>
                                <disabled-submitted-responses v-for="question in VideoCourseQuestions" :key="question.id" :question="question" />
                            </div>

                        </div>
                    </div>
                    <div v-if="tabActive === 'responses'">
                        <div v-if="VideoAllResponses.length">
                            <Response v-for="response in VideoAllResponses" :key="response.id"
                                :logo="response.student_profile_picture?response.student_profile_picture:'/assets/images/user-avatar.png'"
                                :name="response.student_name" :upload_at="response.uploaded_hours_ago"
                                :rate="response.response_rating" :response="response.student_response"
                            />
                        </div>
                        <div class="r-card-empty-top-responses" v-else>
                            <empty-top-responses class="mb-4"/>
                            <h6 class="font-weight-bold">This response list is empty!</h6>
                            <p class="text-secondary">You will see all your responses here after you <br> have made submissions.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="video-uploaded" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-bottom-0">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center mb-5">
                            <img src="../assets/img/responses-upload-success.png" style="width:100%">
                            <h4>Great Job! Submitting Your Response</h4>
                            <p class="text-sm text-secondary">Your response has been submitted successfully. The facilitator can see your response and rate based on your answer. You’ll be notified once the facilitator rates your response.</p>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    </div>
</template>

<script>
    import Layout from '../../layouts/layout'
    import StarRating from 'vue-star-rating'
    import Response from '../response-sm'
    import SubmitResponses from '../std-submit-responses'
    import UpdateResponses from '../std-update-responses'
    import DisabledSubmittedResponses from '../std-disabled-submitted-responses'
    import EmptyTopResponses from '../../wam-partial/icons/empty-top-responses'
    import PlyrPlayer from '../../wam-partial/vue-plyr/plyr-player'

    export default {
        data () {
            return {
                VideoInfo: {},
                VideoAllResponses: [],
                VideoCourseQuestions: [],
                video_rating: 0,
                tabActive: 'submit_response',
                submit_status: null,
                rating_status: null,
                public_status: null,
            }
        },
        components: {
            Layout,
            StarRating,
            Response,
            SubmitResponses,
            UpdateResponses,
            DisabledSubmittedResponses,
            EmptyTopResponses,
            PlyrPlayer
        },
        methods: {
            getVideoDetails: function (){
                axios.get(`/api/student/get_course_detail/${this.$route.params.id}`).then(res => {
                    this.VideoInfo = res.data['course details']
                    this.VideoAllResponses = res.data['course student responses']
                    this.VideoCourseQuestions = res.data['course_questions']
                    this.video_rating = res.data.video_rating
                    this.submit_status = res.data['submit_status']
                    this.rating_status = res.data['rating status']
                    this.public_status = res.data['public status']
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            prepareUpdateForm: function (){
                axios.get(`/api/student/get_course_detail/${this.$route.params.id}`).then(res => {
                    this.VideoCourseQuestions = res.data['course_questions']
                    this.submit_status = res.data['submit_status']
                    this.rating_status = res.data['rating status']
                    this.public_status = res.data['public status']
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            submitResponses: function (el){
                let data = this.FilterForm(el.target, 'formdata')

                axios.post('api/student/post_course_question_responses', data).then(response => {
                    $('#video-uploaded').modal('show')
                    this.prepareUpdateForm()
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            updateResponses: function (el){
                let data = this.FilterForm(el.target, 'formdata')
                axios.post('api/student/post_edit_course_responses', data).then(response => {
                    $('#video-uploaded').modal('show')
                    this.prepareUpdateForm()
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            StoreItAtHistory: function(){
                axios.post(`/api/student/store_watch_history/${this.$route.params.id}`).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
        },
        mounted() {
            setTimeout(() => {
                this.StoreItAtHistory();
            }, 3000);
        },
        beforeMount() {
            this.getVideoDetails()
        },
    }
</script>

<style scoped>
    .video-player-wrap {
        width: 100%;
        height: auto;
        border-radius: 5px;
        overflow: hidden;
    }
    .video-info{
        margin-top: 30px;
    }
    .header-title, .top-response{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
    .average-response-rating{
        display: flex;
        flex-direction: row;
    }
    .average-response-rating h3{
        margin-top: 18px;
        margin-right: 14px;
    }
    @media (max-width: 991px) {
        .header-title{
            display: block;
        }
    }

    .submit_response-progressbar{
        display: flex;
        justify-content: center;
    }
    .average-response-rating{
        margin: 2px 0 13px 0;
    }
    .top-response{
        margin-bottom: 20px;
    }

    .tabs{
        background: #fff;
        padding: 8px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .tabs .responses, .tabs .submit_response{
        padding-left: 15px;
        padding-right: 15px;
    }
    .tabs .submit_response.active{ background: #E1F4FF;color: #234153; }
    .tabs .submit_response.active svg{ color: #38B6FF ; }

    .tabs .responses.active{ background: #FFF2E1;color: #234153; }
    .tabs .responses.active svg{ color: #FFAA38; }

    @media (max-width: 470px) {
        .tabs a{
            display: block;
            margin-bottom: 5px;
        }
    }
    .submit_response_container{
        background: #fff;
        padding: 30px;
        border-radius: 5px;
        margin-bottom: 8px;
    }

    /* empty case */
    .r-card-empty-top-responses{
        background: #fff;
        text-align: center;
        padding: 200px 0px;
        border-radius: 5px;
    }
</style>

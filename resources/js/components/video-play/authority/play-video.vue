<template>
    <div>
        <Layout>
            <div class="row">
                <div class="col-lg-8">
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
                            <span class="text-icon" v-if="VideoInfo.uploaded_hours_ago<24" >{{ VideoInfo.uploaded_hours_ago }}h ago</span>
                            <span class="text-icon" v-else-if="Math.floor(VideoInfo.uploaded_hours_ago/24)===1" >{{ 1 }}day ago</span>
                            <span class="text-icon" v-else >{{ Math.floor(VideoInfo.uploaded_hours_ago/24) }}days ago</span>
                        </p>
                        <p>{{ VideoInfo.details }}</p>
                        <p class="video-category">
                            <span class="badge badge-icon text-xs">{{ VideoInfo.topic }}</span>
                            <span class="badge badge-icon text-xs">Chapter {{ VideoInfo.chapter }}</span>
                            <span class="badge badge-icon text-xs">Episode {{ VideoInfo.episode }}</span>
                        </p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="tabs">
                        <div class="row">
                            <div class="col-6 pr-0">
                                <a :class="['col-12', 'mb-0', 'btn', 'insights', tabActive==='insights'? 'active':'']" href="javascript:void(0)" @click="() => this.tabActive = 'insights'">
                                    <font-awesome icon="chart-pie" /> Insights
                                </a>
                            </div>
                            <div class="col-6 pl-0">
                                <a :class="['col-12', 'mb-0', 'btn', 'responses', tabActive==='responses'? 'active':'']" href="javascript:void(0)" @click="() => this.tabActive = 'responses'">
                                    <font-awesome icon="star" /> All Responses
                                </a>
                            </div>

                        </div>
                    </div>

                    <div v-if="tabActive === 'insights'">
                        <div class="insights_container">
                            <p class="mb-5">Insights</p>
                            <div class="insights-progressbar">
                                <div id="progress"></div>
                            </div>

                            <p class="mt-4">Top Responses</p>
                            <div class="top-response" v-for="(top_response, index) in VideoAllResponses" :key="index" v-if="index <= 2">
                                <p class="mb-0">
                                    <img class="rounded-circle" style="width:30px"
                                        :src="top_response.student_profile_picture?top_response.student_profile_picture:'/assets/images/user-avatar.png'"
                                    />
                                    {{ top_response.student_name }}
                                </p>
                                <star-rating text-class="text-xs text-secondary mt-1"
                                    :increment="0.5" :inline="true" :read-only="true" :star-size="12"
                                    active-color="#FFAA38" :rating="top_response.response_rating"
                                />
                            </div>


                            <p class="mb-0 mt-4">Average Response Rating</p>
                            <div class="average-response-rating">
                                <h3 class="font-weight-bold">{{ AvgRating || 0 }}</h3>
                                <star-rating text-class="d-none" :increment="0.5" :inline="true" :read-only="true" :star-size="30" active-color="#FFAA38" :rating="AvgRating" />
                            </div>

                            <p>Sentiments</p>
                            <div class="sentiments">
                                <p><font-awesome class="text-primary" icon="smile" /> {{ PositiveSentiment }}% <span class="text-secondary">Positive responses</span></p>
                                <p><font-awesome class="text-danger" icon="frown" /> {{ NegativeSentiment }}% <span class="text-secondary">Negative responses</span></p>
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

        </Layout>
    </div>
</template>

<script>
    import Layout from '../../layouts/layout'
    import StarRating from 'vue-star-rating'
    import Response from '../response-sm'
    import EmptyTopResponses from '../../wam-partial/icons/empty-top-responses'
    import PlyrPlayer from '../../wam-partial/vue-plyr/plyr-player'

    export default {
        data () {
            return {
                VideoInfo: {},
                VideoAllResponses: [],
                tabActive: 'insights',
                AvgRating: 0,
                video_insight: 0,
                video_rating: 0,
                PositiveSentiment: 0,
                NegativeSentiment: 0,
            }
        },
        components: {
            Layout,
            StarRating,
            Response,
            EmptyTopResponses,
            PlyrPlayer
        },
        methods: {
            getVideoDetails: function (){
                let self = this
                $(document).ready(function() {
                    axios.get(`/api/authority/get_course_detail/${self.$route.params.id}`).then(res => {
                        self.VideoInfo = res.data['course details']
                        self.VideoAllResponses = res.data['course student responses']
                        self.AvgRating = res.data['avg _rating']
                        self.video_rating = res.data.video_rating
                        self.PositiveSentiment = res.data.positive_sentiment
                        self.NegativeSentiment = res.data.negative_sentiment

                         $("#progress").percircle({
                            progressBarColor: "#15B33F",
                            percent: res.data.video_insight
                        })
                    }).catch(error => {
                        self.ValidtaeForm(error)
                    })
                })
            }
        },
        beforeMount () {
            this.getVideoDetails()
        }
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

    .insights-progressbar{
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
    .tabs .responses, .tabs .insights{
        padding-left: 15px;
        padding-right: 15px;
    }
    .tabs .insights.active{ background: #E1F4FF;color: #234153; }
    .tabs .insights.active svg{ color: #38B6FF ; }

    .tabs .responses.active{ background: #FFF2E1;color: #234153; }
    .tabs .responses.active svg{ color: #FFAA38; }

    @media (max-width: 470px) {
        .tabs a{
            display: block;
            margin-bottom: 5px;
        }
    }

    .insights_container{
        background: #fff;
        padding: 30px;
        border-radius: 5px;
        margin-bottom: 8px;
    }
    #progress{
        transform: scale(1.3);
    }

    /* empty case */
    .r-card-empty-top-responses{
        background: #fff;
        text-align: center;
        padding: 200px 0px;
        border-radius: 5px;
    }
</style>

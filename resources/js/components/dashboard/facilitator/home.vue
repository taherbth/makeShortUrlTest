<template lang="">
    <div>
        <Layout>
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="advertisement-container">
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-5 status-message">
                                <div>
                                    <h3>Welcome {{ nick_name(User.name) }}! Glad to see you.</h3>
                                    <p class="text-neutral">Upload videos and help more students to build their characters</p>
                                    <router-link to="/facilitator/video/upload" class="btn btn-danger">Upload Videos</router-link>
                                </div>
                            </div>
                            <div v-if="AdsInfo && AdsInfo.id" class="col-12 col-md-12 col-lg-7 status-media">
                                <img src="../assets/img/overlay-image.png" alt="" class="overlay-img">
                                <img v-if="is_desktop_image" :src="AdsInfo.thumbnail" class="advertiser-img">
                                <img v-else :src="AdsInfo.thumbnail" class="advertiser-img">
                                <div class="overlay-contents">
                                    <router-link :to="`facilitator/watch/play/${AdsInfo.id}`"><font-awesome size="5x" icon="play-circle" /></router-link>
                                    <h5>{{ advertisement_title_max_limit(AdsInfo.title || "") }}</h5>
                                    <p>{{ advertisement_desc_max_limit(AdsInfo.details || "") }}</p>
                                </div>
                            </div>
                            <div v-else class="col-12 col-md-12 col-lg-7 status-media">
                                <img src="../assets/img/overlay-image.png" alt="" class="overlay-img" />
                                <img v-if="is_desktop_image" src="../assets/img/thumbnail.jpg" alt="" class="advertiser-img" />
                                <img v-else src="../assets/img/thumbnail-mb.png"  alt="" class="advertiser-img" />
                                <div class="overlay-contents">
                                    <font-awesome size="5x" icon="play-circle" />
                                    <h5>Lorem Ipsum is simply dummy text of the </h5>
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="wrapper-body">
                        <p>Engagement This Week</p>
                        <div :class="[EngagementWeek?'d-block':'d-none']">
                            <div class="progress-bar-container">
                                <div id="progress"></div>
                            </div>
                            <div class="text-center">
                                <p class="mb-0">More students are submitting responses this week</p>
                                <p class="text-sm text-neutral">Keep it up and always explore.</p>
                            </div>
                        </div>
                        <div :class="['r-card-empty-progress', EngagementWeek?'d-none':'d-block']">
                            <empty-progress class="mb-4"/>
                            <h6 class="font-weight-bold">No progress to show</h6>
                            <p class="text-secondary">We'll show progress once you get <br> started!</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="wrapper-body">
                        <p>Latest Responses</p>
                        <div v-if="latest_responses.length">
                            <responseItem v-for="(item, index) in latest_responses" :key="index" :date="item.time" :title="item.question" :question_type="item.question_type" :response="item.student_response" :status="item.is_rated" :rating="item.rating" />
                            <router-link v-if="latest_responses.length" to="/facilitator/responses" class="text-info" >See more responses <font-awesome icon="angle-right" class="text-info" /></router-link>
                        </div>
                        <div class="r-card-empty-recent-responses" v-else>
                            <empty-recent-responses class="mb-4"/>
                            <h6 class="font-weight-bold">This response list is empty!</h6>
                            <p class="text-secondary">You will see all your responses here after you <br> have made submissions.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 mb-4">
                    <div class="wrapper-body chart-wrapper">
                        <p>Activity This Month</p>
                        <area-chart v-if="chart_load" :chartData="chart_data" :chartStyle="{height:'365px'}" />
                    </div>
                </div>
            </div>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout'
    import areaChart from '../area-chart'
    import responseItem from '../response-item'
    import EmptyRecentResponses from '../../wam-partial/icons/empty-recent-responses'
    import EmptyProgress from '../../wam-partial/icons/empty-progress'

    export default {
        data() {
            return {
                AdsInfo: {},
                chart_data:{},
                chart_load: false,
                is_desktop_image: true,
                EngagementWeek: 0,
                latest_responses: [],
                latest_responses: [],
            }
        },
        components: {
            Layout,
            areaChart,
            responseItem,
            EmptyProgress,
            EmptyRecentResponses,
        },
        methods: {
            getFacilitatorStatistics: function (){
                axios.get(`/api/facilitator/get_facilitator_statistics`).then(res => {
                    this.chart_data.labels = res.data['label']
                    this.chart_data.watched = res.data['watch_histories_count']
                    this.chart_data.responses = res.data['student_responses_count']
                    this.chart_load = true
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            getLatestResponses: function (){
                axios.get(`/api/facilitator/get_facilitator_latest_responses`).then(res => {
                    this.latest_responses = res.data['latest responses']
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            getLastPublishedVideo: function (){
                axios.get(`/api/facilitator/get_facilitator_last_published_video`).then(res => {
                    this.AdsInfo = res.data
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            nick_name: function(full_name=''){
                return full_name.split(" ")[0]
            },
            advertisement_title_max_limit: function (title=''){
                return title.substring(0,40)
            },
            advertisement_desc_max_limit: function (details=''){
                return details.substring(0,120)
            },
            InitProgress: function (){
                let self = this
                $(document).ready(function() {
                    axios.get(`/api/facilitator/get_facilitator_progress`).then(res => {
                        self.EngagementWeek = res.data
                        $("#progress").percircle({
                            progressBarColor: "#15B33F",
                            percent: res.data
                        })
                    }).catch(error => {
                        this.ValidtaeForm(error)
                    })
                })
            },
            SetImageForScreen: function (){
                if(window.innerWidth <= 700 || (window.innerWidth >=992 && window.innerWidth <=1400) ){
                    this.is_desktop_image = false
                }else{
                    this.is_desktop_image = true
                }
            }
        },
        mounted() {
            var self = this
            this.InitProgress()
            this.getLatestResponses()
            this.getFacilitatorStatistics()
            this.getLastPublishedVideo()
            window.addEventListener("resize", function(){
                self.SetImageForScreen()
            });
        }
    }
</script>
<style>
    #progress>span{
        color: #234153;
    }
</style>
<style scoped>
    .wrapper-body{
        display: block;
        overflow: hidden;
        background: #fff;
        width: 100%;
        padding: 30px;
        border-radius: 5px;
    }
    .wrapper-body.chart-wrapper{
        padding-bottom: 50px;
    }
    .progress-bar-container{
        display: flex !important;
        flex-direction: row;
        justify-content: center;
        margin: 60px 0;
    }
    #progress{
        transform: scale(1.5);
    }
    #monthly-activity{
        height: 300px;
    }

    /* this is for dashboard */

    .advertisement-container {
        display: block !important;
        background: #fff;
        /* min-height: 360px; */
        overflow: hidden;
        border-radius: 5px;
    }
    .advertisement-container .status-message {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .advertisement-container .status-message div{
        margin-left: 3rem;
    }
    .advertisement-container .status-media {
        position: relative;
        text-align: center;
    }
    .advertisement-container .status-media .advertiser-img {
        width: 100%;
        height: 400px;
        border-radius: 5px;
        filter: brightness(0.44) contrast(0.92);
        clip-path: polygon(100% 0%, 65% 0%, 2.4% 34.43%, 2.21% 34.72%, 2.09% 34.87%, 1.93% 35.33%, 1.92% 35.59%, 1.94% 36.08%, 2.04% 36.5%, 2.15% 36.89%, 2.34% 37.33%, 19.03% 99.76%, 100% 100%);
    }
    .advertisement-container .status-media .overlay-img {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 5px;
        clip-path: polygon(100% 0%, 39% 0.16%, 0.63% 65.85%, 0.45% 66.14%, 0.42% 66.45%, 0.37% 66.75%, 0.36% 67.17%, 0.38% 67.5%, 0.48% 68.08%, 0.59% 68.31%, 0.68% 68.59%, 25% 100%, 100% 100%);
    }
    .advertisement-container .status-media .overlay-contents {
        position: absolute;
        left: 20%;
        right: 20px;
        bottom: 15px;
    }
    .advertisement-container .status-media .overlay-contents svg{
        color: #fff;
        margin-bottom: 30px;
    }
    .advertisement-container .status-media .overlay-contents h5{
        color: #fff;
        font-weight: 700;
    }
    .advertisement-container .status-media .overlay-contents p{
        color: #fff;
        font-size: 13px;
        opacity: 0.7;
    }

    @media (max-width: 992px) {
        .advertisement-container .status-message div{
            margin: 0;
            padding: 20px;
            margin-bottom: 40px;
        }
    }

    /* end dashboard  */

    /* empty case */
    .r-card-empty-recent-responses{
        text-align: center;
        padding: 120px 0px;
    }
    .r-card-empty-progress{
        text-align: center;
        padding: 60px 0px;
    }
</style>

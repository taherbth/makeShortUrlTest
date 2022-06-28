<template lang="">
    <div>
        <Layout>
            <div class="row">
                <div class="col-lg-8 mb-4 pt-80 pb-80">
                    <div class="advertisement-container">
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-5 status-message">
                                <div>
                                    <h3>Welcome To Url Shortner</h3>
                                    <p class="text-neutral">Start learning new things and share your experience with others</p>
                                    <form @submit.prevent="makeShortUrl">
                                        <input type="text" name="url"  v-model="url">
                                        <button type="submit" class="btn btn-primary-text col-7">Make short url</button>
                                    </form>
                                </div>
                            </div>                                                        
                        </div>
                    </div>
                </div>                
            </div>
            
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout';

    export default {
        data() {
            return {
                url: ""
            }
        },
        components: {
            Layout,            
        },
        methods: {
            makeShortUrl: function(){
                axios.post('/api/makeShortlink', {url: this.url}).then(response => {
                                    
                }).catch(error => {                   
                    if (error.response.status === 422) {                        
                        this.$swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Please fill in all the required fields",
                        })
                    }
                });
            },
            getStudentStatistics: function (){
                axios.get(`/api/student/get_student_statistics`).then(res => {
                    this.chart_data.labels = res.data['label']
                    this.chart_data.watched = res.data['watch_histories_count']
                    this.chart_data.responses = res.data['student_responses_count']
                    this.chart_load = true
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            getLatestResponses: function (){
                axios.get(`/api/student/get_student_latest_responses`).then(res => {
                    this.latest_responses = res.data['latest responses']
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            getLastPublishedVideo: function (){
                axios.get(`/api/student/get_student_last_watched_video`).then(res => {
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
                    axios.get(`/api/student/get_student_progress`).then(res => {
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
            /* this.InitProgress()
            this.getLatestResponses()
            this.getStudentStatistics()
            this.getLastPublishedVideo()
            window.addEventListener("resize", function(){
                self.SetImageForScreen()
            }); */
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

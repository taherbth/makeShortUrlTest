<template>
    <div>
        <Layout>
            <div class="upload-steps">
                <div :class="[step >= 1? 'active':'']"><p>{{ labelTextRes('Write Description') }}</p></div>
                <div :class="[step >= 2? 'active':'']"><p>Add Questions</p></div>
                <div :class="[step === 3? 'active':'']"><p>Finish</p></div>
            </div>

            <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="Object.keys(errors).length">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <ul>
                    <li v-for="(err, key) in errors" :key="key" v-text="err.toString()"></li>
                </ul>
            </div>

            <form class="row" @submit.prevent="submitForm" id="video_info_upload">
                <div class="col-lg-6 mb-3">
                    <div class="card change-video">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <p class="m-0 mt-md-2"><font-awesome icon="video" class="text-danger" /> {{ videoName || 'Unknown' }} <span class="text-sm text-secondary">{{ videoSize || 'Unknown' }}MB</span></p>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <input class="d-none" type="file" name="video" id="video_upload_input" accept="video/mp4,video/mpeg,video/x-matroska,video/m4v" @change="uploadVideo" />
                                    <a class="btn btn-light" href="#" @click="changeVideo">Change File</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="video-banner-container" >
                        <img id="thumbnil-viewer" class="mb-1" :src="thumbnailShowing"/>
                        <div class="video-progress-bar" v-if="progress">
                            <div id="progress"></div>
                            <small class="text-secondary">Please wait while we upload the video</small>
                        </div>
                    </div>
                    <ThumbnailContainer :thumbnail_info="thumbnails" @getActiveThumbnail="renderBigThumbnail" @getBannerImage="setBannerImage"/>
                    <input type="hidden" name="thumbnail" id="thumbnailInput">
                </div>
                <div class="col-lg-6 mb-3">
                    <section :class="[step === 1? '':'d-none']">
                        <div class="card video-description">
                            <div class="card-body">
                                <h4 class="font-weight-bold m-0 pb-3">Video Description</h4>
                                <div slot="body">
                                    <vInput title="Video title" name="title" :errorMsg="errors.title" />
                                    <div class="form-group">
                                        <label>Add a message or a brief summary about the video <span class="text-danger">*</span></label>
                                        <textarea :class="['form-control', errors.details? 'is-invalid':'']" rows="8" name="details"></textarea>
                                        <small class="form-text text-danger text-left" v-if="errors.details">{{ errors.details[0] }}</small>
                                    </div>
                                    <vInput title="Topic" name="topic" :errorMsg="errors.topic" />
                                    <div class="row">
                                        <div class="col-md-6 mb-1">
                                            <vInput type="number" title="Chapter no." name="chapter" :errorMsg="errors.chapter" />
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <vInput type="number" title="Episode no." name="episode" :errorMsg="errors.episode" />
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <a href="#" class="btn btn-lg btn-light text-secondary">Cancel</a>
                                        <a href="#" class="btn btn-lg btn-secondary" @click="nextStep">Next</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section :class="[step === 2? '':'d-none']">
                        <div class="card add-questions">
                            <div class="card-header bg-white">
                                <h4 class="font-weight-bold m-0 p-0">Add Questions</h4>
                            </div>
                            <div class="card-body">
                                <div slot="body">
                                    <QuestionBox :errors="errors" />
                                    <div class="mt-4">
                                        <a href="#" class="btn btn-lg btn-light text-secondary" @click="previousStep">Back</a>
                                        <a href="#" class="btn btn-lg btn-light text-secondary">Cancel</a>
                                        <a href="#" class="btn btn-lg btn-primary-text" @click="nextStep">Next</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section :class="[step === 3? '':'d-none']">
                        <div class="card finish-upload">
                            <div class="card-body">
                                <h4 class="font-weight-bold m-0 mb-1 pb-3" >Finish</h4>

                                <div class="card notice-board border-0">
                                    <div class="card-body rounded">
                                        <p class="title">By publishing a video, you agree to the terms and conditions of WAM ACADEMY content policy.</p>
                                        <p class="text-secondary text-sm m-0">WAM will not be held responsible for the quality of contents or any spam, misleading, vulgar contents uploaded by anyone on behalf of your institution.</p>
                                    </div>
                                </div>
                                <input type="hidden" id="is_draft" name="is_draft">

                                <div class="mt-4">
                                    <a href="#" class="btn btn-lg btn-light text-secondary" @click="previousStep">Back</a>
                                    <a href="#" class="btn btn-lg btn-light text-secondary">Cancel</a>      
                                    <button class="btn btn-lg btn-danger text-white" type="submit" data-draft="0" @click="setDraftStatus" :disabled="submitting==0">
                                        <span class="spinner-border spinner-border-sm mb-1" role="status" aria-hidden="true" v-if="submitting == 0"></span> {{ submitting!=0?'Save':'Loading...' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </form>


            <div class="modal fade" id="video-uploaded" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-bottom-0">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center mb-5">
                            <img src="../assets/img/video-upload-success.png" style="width:100%">
                            <h4>Great Job! Publishing Your Video</h4>
                            <p class="text-sm text-secondary">You have successfully published your video. Students will be able to watch this video and leave their responses.</p>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout'
    import QuestionBox from '../question-box'
    import Card from '../../wam-partial/card'
    import ThumbnailContainer from './thumbnail-container'
    import {vInput} from '../../wam-partial/form-lib'

    export default {
        data(){
            return {
                step: 1,
                thumbnails: {},
                is_public: 1,
                progress: false,
                progressData: 0,
                errors: {},
                submitting: 2,
                thumbnailShowing: "/assets/images/background.png",
                videoName: null,
                videoSize: null,
                course_id:null,
            }

        },
        methods: {
            setDraftStatus: function (el){
                document.getElementById('is_draft').value = el.target.getAttribute('data-draft')
            },
            submitForm: function (el){
                this.errors = {};
                let data = this.FilterForm(el.target, 'formdata')
                this.submitting = data.get('is_draft')
                data.append('course_id', localStorage.getItem('course_id'))
                axios.post('/api/authority/upload_video_info', data).then(response => {
                    $('#video-uploaded').modal('show')
                    this.submitting = 2
                    //localStorage.removeItem('course_id');
                }).catch(error => {
                    //localStorage.removeItem('course_id');
                    this.submitting = 2
                    this.progress = false
                    this.progressData = 0
                    this.ValidtaeForm(error)
                    if (error.response.status === 422) {                        
                        this.$swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Please fill in all the required fields",
                        })
                    }
                });
            },
            changeVideo: function (){
                document.getElementById('video_upload_input').click()
            },
            uploadVideo: async function (el){
                this.thumbnails = {}
                this.progressData = 0
                this.progress = true
                this.thumbnailShowing = '/assets/images/background.png'

                let data = new FormData()
                if (this.$store.getters.preparedVideo === null) {
                    this.videoName = el.target.files[0].name
                    this.videoSize = (el.target.files[0].size/(1024*1024)).toFixed(2)
                    data.append('video', el.target.files[0])
                } else {
                    this.videoName = this.$store.getters.preparedVideo.name
                    this.videoSize = (this.$store.getters.preparedVideo.size/(1024*1024)).toFixed(2)
                    data.append('video', this.$store.getters.preparedVideo)
                    this.$store.dispatch('resetparedVideo')
                }

                let interval = setInterval(()=>{
                    let timing = [3, 5, 7, 9, 10]
                    if (this.progressData < 90) {
                        this.progressData += timing[Math.floor(Math.random()*timing.length)]
                        this.refreshVideoProgressBar()
                    }
                },5000)

                const config = {
                    onDownloadProgress: (progressEvent) => {
                        this.progressData = 100
                        this.refreshVideoProgressBar()
                        this.progress = false
                        clearInterval(interval);
                    }
                }

               await axios.post('/api/authority/upload_video', data, config).then(response => {
                    this.thumbnails = response.data;
                    localStorage.setItem('course_id', response.data.course_id);
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            refreshVideoProgressBar: function (){
                $("#progress").percircle({
                    progressBarColor: "#38B6FF",
                    percent: this.progressData
                })
            },
            setBannerImage: function (data){
                this.thumbnailShowing = data
                document.getElementById('thumbnailInput').value = data
            },
            renderBigThumbnail: function (img){
                document.getElementById('thumbnil-viewer').setAttribute('src', img)
                document.getElementById('thumbnailInput').value = img
            },
            setVideoPrivacy: function (el){
                this.is_public = el.target.value
            },
            nextStep: function () {
                if(this.step === 3){
                    return false
                }
                this.step++
            },
            previousStep: function () {
                if(this.step === 1){
                    return false
                }
                this.step--
            }
        },
        components: {
            Layout,
            Card,
            QuestionBox,
            ThumbnailContainer,
            vInput,
        },
        mounted() {
            /* let target = document.querySelector('.select-2')
            axios.get('api/institution/get_students').then((res)=>{
                let data = ''
                for (let index = 0; index < res.data.length; index++) {
                    data += `<option value="${res.data[index].id}">${res.data[index].name}</option>`
                }
                target.innerHTML = data
            }).catch((error)=>{
                this.ValidtaeForm(error)
            }); */

            if (this.$store.getters.preparedVideo !== null) {
                this.uploadVideo()
            }
            $(document).ready(function() {
                $('.select-2').select2();
            });
            
            axios.get('/api/authority/get_thumbnails', {course_id: localStorage.getItem('course_id')}).then( res => {
                let course_id = parseInt(res.data.video_id)
                let thumbnails = []
                for(let i=0; i<res.data.thumbnails.length;i++){
                    thumbnails[i] = res.data.thumbnails[i].replace(`/var/www/login/public/thumbnails/${course_id}/`, '')
                }
                let thumbnail_info = {
                    course_id: course_id,
                    course_thumbnails: Object.assign({}, thumbnails)
                }
                this.thumbnails = thumbnail_info
            }).catch( error => {
                if(this.progress === false){
                    this.$router.push('/authority/video/upload')
                }
            })

            let self = this
            $("#video-uploaded").on("hide.bs.modal", function(){
                self.$router.push('/authority/video/upload')
            });
        },
    }
</script>
<style scoped>
    .change-video>.card-body, .change-video>.card-header,
    .video-description>.card-body, .video-description>.card-header,
    .add-questions>.card-body, .add-questions>.card-header,
    .finish-upload>.card-body, .finish-upload>.card-header{
        padding: 0px !important;
    }
    .video-description, .finish-upload{
        padding: 30px;
    }
    .change-video{
        padding: 25px;
    }
    .add-questions>.card-body, .add-questions>.card-header{
        padding: 30px !important;
    }
    .edit-body{
        padding: 0.7rem .85rem;
    }
    #thumbnil-viewer{
        width: 100%;
        margin: 0;
        margin-top: 5px;
        padding: 0;
        border-radius: 5px;
    }
    .notice-board .card-body{
        background:#E1F4FF;
        padding:1.5rem;
        padding-left: 3rem;
    }
    .notice-board .card-body::before{
        background-image: url('../assets/img/info_circle.png');
        background-size: 19.2px 19.2px;
        display: inline-block;
        width: 19.2px;
        height: 19.2px;
        content:"";
        position: absolute;
        left: 25px;
        top: 27px;
    }
    .video-banner-container{
        position: relative;
    }
    .video-banner-container .video-progress-bar{
        position: absolute;
        left: 50%;
        top: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        display: grid;
    }
    .video-banner-container .video-progress-bar #progress{
        margin: 0 auto;
        margin-bottom: 35px;
    }
    .card{
        border: none;
    }
    .upload-steps{
        background: #fff;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        margin-top: -13px
    }
    @media(max-width: 1140px){
        .upload-steps{
            margin-top: -5px
        }
    }
    .upload-steps div{
        width: 100%;
        text-align: center;
        border-bottom: 4px solid #E1F4FF;
    }
    .upload-steps div.active{
        border-bottom: 4px solid #FFAA38;
    }
    .upload-steps div p{
        margin: 0;
        padding: 15px 0;
    }
</style>

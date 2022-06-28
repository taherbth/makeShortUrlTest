<template>
    <div>
        <Layout>
            <div class="row">
                <div class="col-lg-6">
                    <div class="tabs">
                        <div class="row">
                            <div class="col-4 pr-0">
                                <a :class="['col-12', 'mb-0', 'btn', 'drafts', tabActive==='drafts'? 'active':'']" href="javascript:void(0)" @click="() => this.tabActive = 'drafts'">
                                    <font-awesome icon="pencil-alt" /> Drafts
                                </a>
                            </div>
                            <div class="col-4 pl-0">
                                <a :class="['col-12', 'mb-0', 'btn', 'published', tabActive==='published'? 'active':'']" href="javascript:void(0)" @click="() => this.tabActive = 'published'">
                                    <material-cast-connected /> Published
                                </a>
                            </div>
                            <div class="col-4 pl-0">
                                <a :class="['col-12', 'mb-0', 'btn', 'demo', tabActive==='demo'? 'active':'']" href="javascript:void(0)" @click="() => this.tabActive = 'demo'">
                                    <material-cast-connected /> Demo video
                                </a>
                            </div>

                        </div>
                    </div>

                    <div class="video-container disable-scrollbars" v-if="tabActive === 'drafts'">
                        <div v-if="drafted_videos.length">
                            <VideoItem v-for="(item, key) in drafted_videos" :key="key" :video_url="`/institution/watch/play/${item.id}`" :image="item.thumbnail" :title="item.title" :last_edited="convertUploadDate(item)"
                                :topic="item.topic" :chapter="`Chapter ${item.chapter}`" :episode="`Episode ${item.episode}`"
                                :rating="item.rating" :views="item.viewes"
                            />
                        </div>
                        <div class="drafts-published-empty" v-else-if="paginationInfo_drafted.current_page">
                            <div class="center">
                                <img src="../assets/img/video-upload-success.png" alt="">
                                <h5 class="font-weight-bold">Find your saved videos here</h5>
                                <p class="text-secondary">This tab will contain videos that you have saved as drafts.</p>
                            </div>
                        </div>

                        <pagination align="center" :show-disabled="true" :data="paginationInfo_drafted" @pagination-change-page="getDraftedVideos">
                            <span slot="prev-nav">Prev</span>
                            <span slot="next-nav">Next</span>
                        </pagination>
                    </div>
                    <div class="video-container disable-scrollbars" v-if="tabActive === 'published'">
                        <div v-if="published_videos.length">
                            <VideoItem v-for="(item, key) in published_videos" :key="key" :video_url="`/institution/watch/play/${item.id}`" :image="item.thumbnail" :title="item.title" :last_edited="convertUploadDate(item)"
                                :topic="item.topic" :chapter="`Chapter ${item.chapter}`" :episode="`Episode ${item.episode}`"
                                :rating="item.rating" :views="item.viewes"
                            />
                        </div>
                        <div class="drafts-published-empty" v-else-if="paginationInfo_published.current_page">
                            <div class="center">
                                <img src="../assets/img/video-upload-success.png" alt="">
                                <h5 class="font-weight-bold">Find your published videos here</h5>
                                <p class="text-secondary">This tab will contain videos that you have published.</p>
                            </div>
                        </div>

                        <pagination align="center" :show-disabled="true" :data="paginationInfo_published" @pagination-change-page="getPublishedVideos">
                            <span slot="prev-nav">Prev</span>
                            <span slot="next-nav">Next</span>
                        </pagination>
                    </div>
                    <!-- Demo video goes here -->
                    <div class="video-container disable-scrollbars" v-if="tabActive === 'demo'">
                        <div v-if="demo_videos.length">
                            <VideoItem v-for="(item, key) in demo_videos" :key="key" :video_url="`/institution/watch/play/${item.id}`" :image="item.thumbnail" :title="item.title" :last_edited="convertUploadDate(item)"
                                :topic="item.topic" :chapter="`Chapter ${item.chapter}`" :episode="`Episode ${item.episode}`"
                                :rating="item.rating" :views="item.viewes"
                            />
                        </div>
                        <div class="drafts-published-empty" v-else-if="paginationInfo_demo.current_page">
                            <div class="center">
                                <img src="../assets/img/video-upload-success.png" alt="">
                                <h5 class="font-weight-bold">Find your demo videos here</h5>
                                <p class="text-secondary">This tab will contain videos that you have uploaded from authority.</p>
                            </div>
                        </div>

                        <pagination align="center" :show-disabled="true" :data="paginationInfo_demo" @pagination-change-page="getDemoVideos">
                            <span slot="prev-nav">Prev</span>
                            <span slot="next-nav">Next</span>
                        </pagination>
                    </div>
                </div>

                <div class="col-lg-6 order-first order-lg-last">
                    <div class="item">
                        <form data-validation="true" action="#" method="post" enctype="multipart/form-data">
                            <div class="item-inner">
                                <div class="item-content">
                                    <div class="file-upload">
                                        <label for="file_upload">
                                            <div class="dplay-tbl">
                                                <div class="dplay-tbl-cell">
                                                    <h5>Upload a new video</h5>
                                                    <h6>Drag and drop you video here or click on the button below</h6>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="document.getElementById('file_upload').click()" >Choose File</button>
                                                </div>
                                            </div>
                                            <!--upload-content-->
                                            <input type="file" name="video" id="file_upload" accept="video/mp4,video/mpeg,video/x-matroska,video/m4v" @change="SendFileToUpload" />
                                        </label>
                                    </div>
                                </div>
                                <!--item-content-->
                            </div>
                            <!--item-inner-->
                        </form>
                    </div>
                </div>
            </div>

        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout'
    import VideoItem from '../video-item'
    import Pagination from 'laravel-vue-pagination'
    import materialCastConnected from '../../wam-partial/icons/material-cast-connected'

    export default {
        data() {
            return {
                paginationInfo_drafted: {},
                paginationInfo_published: {},
                paginationInfo_demo: {},
                drafted_videos: [],
                published_videos: [],
                demo_videos: [],
                tabActive: 'drafts',
            }
        },
        props: {

        },
        components: {
            Layout,
            VideoItem,
            Pagination,
            materialCastConnected
        },
        methods: {
            SendFileToUpload: function(el){
                this.$store.dispatch('setparedVideo', el.target.files[0])
                this.$router.push('/institution/video/upload/info')
            },
            getDraftedVideos: function (page=1){
                axios.get(`/api/institution/get_course_drafted?page=${page}`).then(response => {
                    this.drafted_videos = response.data.data
                    this.paginationInfo_drafted = response.data
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            getPublishedVideos: function (page=1){
                axios.get(`/api/institution/get_course_published?page=${page}`).then(response => {
                    this.published_videos = response.data.data
                    this.paginationInfo_published = response.data
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            getDemoVideos: function (page=1){
                axios.get(`/api/institution/get_course_demo?page=${page}`).then(response => {
                    this.demo_videos = response.data.data
                    this.paginationInfo_demo = response.data
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            convertUploadDate: function (data){
                if(data.uploaded_days_ago == 0 || data.uploaded_days_ago == null){
                    return `${data.uploaded_hours_ago}h`
                }else if(data.uploaded_days_ago > 1){
                    return `${data.uploaded_days_ago}days`
                }else{
                    return `${data.uploaded_days_ago}day`
                }
            },
            responsiveHeight: function (){
                if(window.innerWidth >= 992 || window.innerHeight >= 800){
                    let el = document.querySelector('.file-upload')
                    let el1 = document.querySelector('.video-container')
                    el.style.height = `${window.innerHeight-227}px`
                    el.firstChild.style.marginTop = `${(((window.innerHeight-190)/2)-80) < 250? 250:((window.innerHeight-190)/2)-80}px`
                    el1.style.height = `${window.innerHeight-221}px`
                }
            },
        },
        mounted() {
            this.getDraftedVideos();
            this.getPublishedVideos();
            this.getDemoVideos();
            this.responsiveHeight();
        },
    }
</script>
<style scoped>
    @import url('../assets/css/drag-and-drop-file.css');

    @media(min-width: 992px){
        .video-container{
            min-height: 587px;
            height: 586px;
            overflow-y: auto;
        }
        .disable-scrollbars::-webkit-scrollbar {
            width: 0px;
            background: transparent; /* Chrome/Safari/Webkit */
        }

        .disable-scrollbars {
            scrollbar-width: none;
            -ms-overflow-style: none;
            -webkit-overflow-style: none;
            -moz-overflow-style: none;
            -o-overflow-style: none;
        }
    }

    .tabs{
        background: #fff;
        padding: 8px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .item{
        margin-bottom: 20px;
    }
    .tabs .drafts, .tabs .published{
        padding-left: 30px;
        padding-right: 30px;
    }
    @media (max-width: 470px) {
        .tabs a{
            display: block;
            margin-bottom: 5px;
        }
    }
    .drafts-published-empty{
        width: 100%;
        min-height: 20em;
        height: 97%;
        position: relative;
        background: #fff;
        margin-bottom: 1rem;
    }
    .drafts-published-empty .center {
        width: 100%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    .drafts-published-empty .center img{
        max-width: 100%;
    }
    .tabs .drafts.active{ background: #E1F4FF;color: #234153; }
    .tabs .drafts.active svg{ color: #38B6FF !important; }
    .tabs .drafts:hover svg{ color: #38B6FF; }

    .tabs .published.active{ background: #DCF4E2;color: #234153; }

</style>
<style>
    .tabs .published.active svg .svg-icon{ fill: #15B33F !important; }
    .tabs .published:hover svg .svg-icon{ fill: #15B33F; }
</style>



<template>
    <div>
        <Layout>
            <topics-list :topics="topics" />
            <div class="row" v-if="videos.length">
                <div class="col-lg-7" v-if="videos[0]">
                    <LatestVideo :video_link="`/institution/watch/play/${videos[0].id}`" :thumbnail="videos[0].thumbnail || 'https://via.placeholder.com/720x360.jpg?text=Thumbnail+not+found'"
                        :video_duration="videos[0].course_duration" :rating="videos[0].rating" :video_views="videos[0].viewes"
                        :video_title="videos[0].title" :video_short_desc="videos[0].course_short_details || ''"
                        :video_uploader_logo="videos[0].facilitator_profile_pic || videos[0].institution_profile_pic || '/assets/images/user-avatar.png'" :video_uploader_name="videos[0].facilitator_name || videos[0].institution_name" :video_upload_at="convertUploadDate(videos[0])"
                    />
                    <hr style="margin:2em 0;" v-if="mobile_view()" />
                </div>
                <div class="col-lg-5 row padding-right-sm-none">
                    <div class="col-12" v-if="videos[1]">
                        <VerticalVideoItem :video_link="`/institution/watch/play/${videos[1].id}`" :thumbnail="videos[1].thumbnail || 'https://via.placeholder.com/230x180.jpg?text=Thumbnail+not+found'"
                            :video_duration="videos[1].course_duration" :rating="videos[1].rating" :video_views="videos[1].viewes"
                            :video_title="videos[1].title" :video_short_desc="videos[1].course_short_details || ''"
                            :topic="videos[1].topic" :chapter="videos[1].chapter" :episode="videos[1].episode"
                            :video_uploader_logo="videos[1].facilitator_profile_pic || videos[1].institution_profile_pic || '/assets/images/user-avatar.png'" :video_uploader_name="videos[1].facilitator_name || videos[1].institution_name" :video_upload_at="convertUploadDate(videos[1])"
                        />
                        <hr style="margin:2em 0;" v-if="mobile_view()" />
                    </div>
                    <div class="col-12" v-if="videos[2]">
                        <VerticalVideoItem :video_link="`/institution/watch/play/${videos[2].id}`" :thumbnail="videos[2].thumbnail || 'https://via.placeholder.com/230x180.jpg?text=Thumbnail+not+found'"
                            :video_duration="videos[2].course_duration" :rating="videos[2].rating" :video_views="videos[2].viewes"
                            :video_title="videos[2].title" :video_short_desc="videos[2].course_short_details || ''"
                            :topic="videos[2].topic" :chapter="videos[2].chapter" :episode="videos[2].episode"
                            :video_uploader_logo="videos[2].facilitator_profile_pic || videos[2].institution_profile_pic || '/assets/images/user-avatar.png'" :video_uploader_name="videos[2].facilitator_name || videos[2].institution_name" :video_upload_at="convertUploadDate(videos[2])"
                        />
                        <hr style="margin:2em 0;" v-if="mobile_view()" />
                    </div>
                    <div class="col-12" v-if="videos[3]">
                        <VerticalVideoItem :video_link="`/institution/watch/play/${videos[3].id}`" :thumbnail="videos[3].thumbnail || 'https://via.placeholder.com/230x180.jpg?text=Thumbnail+not+found'"
                            :video_duration="videos[3].course_duration" :rating="videos[3].rating" :video_views="videos[3].viewes"
                            :video_title="videos[3].title" :video_short_desc="videos[3].course_short_details || ''"
                            :topic="videos[3].topic" :chapter="videos[3].chapter" :episode="videos[3].episode"
                            :video_uploader_logo="videos[3].facilitator_profile_pic || videos[3].institution_profile_pic || '/assets/images/user-avatar.png'" :video_uploader_name="videos[3].facilitator_name || videos[3].institution_name" :video_upload_at="convertUploadDate(videos[3])"
                        />
                        <hr style="margin:2em 0;" v-if="mobile_view()" />
                    </div>

                </div>
                <div class="display-contents" v-for="(video, index) in allVideos" :key="video.id">
                    <div class="col-12" v-if="index === 0 && desktop_view()">
                        <hr style="margin:2em 0;">
                    </div>

                    <div class="col-lg-3">
                        <HorizontalVideoItem :video_link="`/institution/watch/play/${video.id}`" :thumbnail="video.thumbnail || 'https://via.placeholder.com/230x180.jpg?text=Thumbnail+not+found'"
                            :video_duration="video.course_duration" :rating="video.rating" :video_views="video.viewes"
                            :video_title="video.title" :video_short_desc="video.course_short_details || ''"
                            :topic="video.topic" :chapter="video.chapter" :episode="video.episode"
                            :video_uploader_logo="video.facilitator_profile_pic || video.institution_profile_pic || '/assets/images/user-avatar.png'" :video_uploader_name="video.facilitator_name || video.institution_name" :video_upload_at="convertUploadDate(video)"
                        />
                        <hr style="margin:2em 0;" v-if="mobile_view()" />
                    </div>

                    <div class="col-12" v-if="((index+1)%4) === 0 && desktop_view()">
                        <hr style="margin:2em 0;">
                    </div>
                </div>
            </div>
            <div class="video-empty" v-else-if="paginationInfo.current_page">
                <div class="center">
                    <img src="../assets/img/video-logo.png" alt="">
                    <h5 class="font-weight-bold">No videos have been uploaded yet!</h5>
                    <p class="text-secondary">You watch tab is empty because no videos <br> have been uploaded yet.</p>
                </div>
            </div>
            <div class="video-empty" v-else-if="paginationInfo.message">
                <div class="center">
                    <img src="../assets/img/video-logo.png" alt="">
                    <h5 class="font-weight-bold">No Courses Found!</h5>
                    <p class="text-secondary">You watch tab is empty because no courses <br> have been founded.</p>
                </div>
            </div>

            <pagination align="center" :show-disabled="true" :data="paginationInfo" @pagination-change-page="getVideos">
                <span slot="prev-nav">Prev</span>
	            <span slot="next-nav">Next</span>
            </pagination>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout'
    import StarRating from 'vue-star-rating'
    import Pagination from 'laravel-vue-pagination'
    import LatestVideo from '../latest-video'
    import HorizontalVideoItem from '../horizontal-video-item'
    import VerticalVideoItem from '../vertical-video-item'
    import TopicsList from '../topics-list'

    export default {
        data() {
            return {
                paginationInfo: {},
                videos: [],
                topics: [],
                query: this.$route.query.query || "",
                topic: this.$route.query.topic || "",
                page:  this.$route.query.page || 1,
            }
        },
        props: {

        },
        components: {
            Layout,
            StarRating,
            Pagination,
            LatestVideo,
            HorizontalVideoItem,
            VerticalVideoItem,
            TopicsList
        },
        methods: {
            getVideos: function (page=null){
                this.page = page || this.page
                if (page !== null && this.query !== "" && this.topic !== ""){
                    this.$router.push(`/institution/watch?query=${this.query}&topic=${this.topic}&page=${this.page}`).catch(err => {})}
                axios.get(`/api/institution/get_courses?query=${this.query}&topic=${this.topic}&page=${this.page}`).then(response => {
                    this.paginationInfo = response.data
                    this.videos = response.data.data || []
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            getTopics: function (){
                axios.get('/api/institution/get_topics').then(sueess => {
                    this.topics = sueess.data
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
            mobile_view: function(){
                return window.innerWidth < 992
            },
            desktop_view: function(){
                return window.innerWidth >= 992
            }
        },
        computed: {
            allVideos: function (){
                return this.videos.slice(4)
            }
        },
        mounted() {
            this.getVideos()
            this.getTopics()
        },
    }
</script>
<style scoped>
    .display-contents{
        display: contents;
    }
    .padding-right-sm-none,
    .padding-right-sm-none>div{
        padding-right: 0;
    }
    .video-empty{
        width: 100%;
        height: 80vh;
        position: relative;
        background: #fff;
        margin-bottom: 1rem;
    }
    .video-empty .center {
        width: 100%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    .video-empty .center img{
        max-width: 100%;
    }
</style>

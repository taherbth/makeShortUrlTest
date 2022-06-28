<template>
    <div>
        <Layout>
            <div class="row">
                <div class="col-12">
                    <p class="go-back" @click="()=> {this.$router.back()}"><font-awesome icon="angle-left" /> Go Back</p>
                </div>
                <div class="col-lg-4">
                    <img :src="courseInfo.thumbnail" class="rounded" style="width:100%" />
                    <h5 class="mt-4">{{ courseInfo.title }}</h5>
                    <p class="video-category-default">
                        <span class="badge badge-icon text-xs">{{ courseInfo.topic }}</span>
                        <span class="badge badge-icon text-xs">chapter {{ courseInfo.chapter }}</span>
                        <span class="badge badge-icon text-xs">episode {{ courseInfo.episode }}</span>
                    </p>
                </div>
                <div class="col-lg-8">
                    <h4>Students Responses</h4>
                    <p class="text-xs text-neutral">{{ `Total Responses ${courseInfo.response_count}` }}</p>
                    <div v-if="this.$route.params.type === 'pending'">
                        <pendingResponses v-for="(responses_info, index) in responseStudentWise" :key="index" :ResponsesInfo="responses_info" />
                    </div>
                    <div v-if="this.$route.params.type === 'provided'">
                        <providedResponses v-for="(responses_info, index) in responseStudentWise" :key="index" :ResponsesInfo="responses_info" />
                    </div>
                </div>
            </div>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout'
    import pendingResponses from './pending-responses'
    import providedResponses from './provided-responses'

    export default {
        data() {
            return {
                responseStudentWise: [],
                courseInfo: {},
            }
        },
        components: {
            Layout,
            pendingResponses,
            providedResponses
        },
        methods: {
            getCourcesResponses: function (){
                axios.get(`/api/facilitator/get_facilitator_rated_or_non_rated_course_responses/${this.$route.params.id}`).then(res => {
                    this.responseStudentWise = res.data['course students and their responses']
                    this.courseInfo = res.data['courses']
                    this.checkCourseStatus()
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            checkCourseStatus: function (){
                if(this.courseInfo.ques_rated === 2 && this.$route.params.type === "pending"){
                    this.$router.push(`/facilitator/responses/${this.$route.params.id}/provided`)
                }
            },
        },
        mounted() {
            this.getCourcesResponses()
        },
    }
</script>
<style scoped>
    .go-back{
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
    }
</style>

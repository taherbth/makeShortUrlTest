<template>
    <div>
        <Layout>
            <div class="row">
                <div class="col-md-5 col-xl-3">
                     <div class="r-card">
                        <div class="r-card-body">
                            <ul class="profile-links">
                                <li>
                                    <router-link to="#/service"><font-awesome icon="rocket" class="profile-link-icon" /> Service</router-link>
                                </li>
                                <li>
                                    <router-link to="#/blog"><font-awesome icon="blog" class="profile-link-icon" /> Blog</router-link>
                                </li>
                                <li>
                                    <router-link to="#/faq"><font-awesome icon="question" class="profile-link-icon" /> Faq</router-link>
                                </li>
                                <li>
                                    <router-link to="#/contact"><font-awesome icon="envelope" class="profile-link-icon" /> Contact</router-link>
                                </li>
                                <li>
                                    <router-link to="#/text"><font-awesome icon="envelope" class="profile-link-icon" /> Text</router-link>
                                </li>
                                <li>
                                    <router-link to="#/feature"><font-awesome icon="envelope" class="profile-link-icon" /> Feature</router-link>
                                </li>
                                <li>
                                    <router-link to="#/featureimage"><font-awesome icon="envelope" class="profile-link-icon" /> Feature Image</router-link>
                                </li>
                                <li>
                                    <router-link to="#/partner"><font-awesome icon="envelope" class="profile-link-icon" /> Partner</router-link>
                                </li>
                                <li>
                                    <router-link to="#/email"><font-awesome icon="envelope" class="profile-link-icon" /> Admin Email to Get Notification</router-link>
                                </li>
                                <li>
                                    <router-link to="#/member"><font-awesome icon="envelope" class="profile-link-icon" /> Member</router-link>
                                </li>
                                <li>
                                    <router-link to="#/tnc"><font-awesome icon="envelope" class="profile-link-icon" /> Terms & Conditions</router-link>
                                </li>
                                <li>
                                    <router-link to="#/abouttext"><font-awesome icon="envelope" class="profile-link-icon" /> ABout text</router-link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-xl-9">
                    <div v-if='$route.hash === "#/service"'>
                        <service-page />
                    </div>
                    <div v-if='$route.hash === "#/blog"'>
                        <blog-page />
                    </div>
                    <div v-if='$route.hash === "#/faq"'>
                        <faq-page />
                    </div>
                    <div v-if='$route.hash === "#/contact"'>
                        <contact-page />
                    </div>
                    <div v-if='$route.hash === "#/text"'>
                        <text-page />
                    </div>
                    <div v-if='$route.hash === "#/feature"'>
                        <feature-page />
                    </div>
                    <div v-if='$route.hash === "#/featureimage"'>
                        <featureimage-page />
                    </div>
                    <div v-if='$route.hash === "#/partner"'>
                        <partner-page />
                    </div>
                    <div v-if='$route.hash === "#/email"'>
                        <email-page />
                    </div>
                    <div v-if='$route.hash === "#/member"'>
                        <member-page />
                    </div>
                    <div v-if='$route.hash === "#/tnc"'>
                        <tn-c-page />
                    </div>
                    <div v-if='$route.hash === "#/abouttext"'>
                        <abouttext-page />
                    </div>
                </div>
            </div>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout'
    import ServicePage from './service-page'
    import BlogPage from './blog-page'
    import FaqPage from './faq-page'
    import ContactPage from './contact-page'
    import TextPage from './text-page'
    import FeaturePage from './feature-page'
    import FeatureimagePage from './featureimage-page'
    import PartnerPage from './partner-page'
    import EmailPage from './email-page'
    import MemberPage from "./member-page";
    import TnCPage from "./tnc-page";
    import AbouttextPage from "./abouttext-page";

    export default {
        data() {
            return {
                PageStep: 1
            }
        },
        components: {
            Layout,
            ServicePage,
            BlogPage,
            FaqPage,
            ContactPage,
            TextPage,
            FeaturePage,
            FeatureimagePage,
            PartnerPage,
            EmailPage,
            MemberPage,
            TnCPage,
            AbouttextPage
        }
    }
</script>
<style scoped>
    .r-card{
        width: 100%;
        height: auto;
        background: #fff;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .r-card .r-card-header, .r-card .r-card-body{
        padding: 30px 0;
        margin: 0 30px;
    }
    .r-card .r-card-header{
        padding-bottom: 0;
        text-align: right;
    }
    .r-card .r-card-header span{
        background: #FFEED7;
        padding: 5px 25px;
        border-radius: 5px;
    }
    .profile-view, .profile-links{
        list-style-type: none;
        padding: 0;
    }
    .profile-view{
        text-align: center;
    }
    .profile-view .user-name{
        margin: 0;
        font-weight: 600;
    }
    .profile-view img{
        width: 64px;
        height: 64px;
        border-radius: 50%;
        margin-bottom: 20px;
    }
    .profile-links{
        list-style-type: none;
    }
    .profile-links li a{
        color: #234153;
        border-radius: 5px;
        display: block;
        padding: 20px 10px 20px 20px;
        text-decoration: none;
    }
    .profile-links li a .profile-link-icon{
        color: #38B6FF;
        margin-right: 10px;
    }
    .profile-links li .router-link-active, .profile-links li .router-link-active .profile-link-icon{
        background: #38B6FF;
        color: #fff;
    }
</style>

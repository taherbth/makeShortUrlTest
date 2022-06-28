<template lang="">
    <div>
        <ul class="list-group">
            <div v-for="(item, index) in notifications" :key="index">
                <!-- 1 -->
                <li :class="['list-items', 'cursor-pointer', item.is_read!=1?'active':'']" v-if="item.data.notification_type === 1"
                    @click="User.role===3?notification_redirect(`/institution/responses/${item.data.course_id}/pending`):notification_redirect(`/facilitator/responses/${item.data.course_id}/pending`)">
                    <img class="img-circle" :src="item.data.student_profile_pic || '/assets/images/user-avatar.png'" />
                    <div class="notification-info">
                        <p class="mb-4">{{ item.data.student_name }} <span class="text-secondary text-sm">Submitted a response</span></p>
                        <p class="text-warning text-xs">Awaiting your response</p>
                        <p class="notification-content text-sm text-secondary" v-html="item.data.course_title"></p>
                    </div>
                </li>

                <!-- 2 -->
                <li :class="['list-items', 'cursor-pointer', item.is_read!=1?'active':'']" v-else-if="item.data.notification_type === 2" @click="notification_redirect(`/responses`)">
                    <img class="img-circle" :src="item.data.publisher_dp || '/assets/images/user-avatar.png'" />
                    <div class="notification-info">
                        <p class="mb-3">{{ item.data.publisher_name }} <span class="text-secondary text-sm">rated your response</span></p>
                        <p class="notification-content text-sm text-secondary" v-html="item.data.course_title"></p>
                    </div>
                </li>

                <!-- 3 -->
                <li :class="['list-items', 'cursor-pointer', item.is_read!=1?'active':'']" v-else-if="item.data.notification_type === 3" @click="notification_redirect(`/watch/play/${item.data.course_id}`)">
                    <img class="img-circle" :src="item.data.course_thumbnail" />
                    <div class="notification-info">
                        <p class="text-neutral text-sm">A video has been published</p>
                        <p class="notification-content" v-html="item.data.course_title"></p>
                    </div>
                </li>

                <!-- 4 -->
                <li :class="['list-items', item.is_read!=1?'active':'']" v-else-if="item.data.notification_type === 4">
                    <hug-icon />
                    <div class="notification-info">
                        <p class="mb-4">Congratulations!!</p>
                        <p class="text-secondary text-xs">You’ve completed all {{ item.data.course_no_of_chapter }} chapters of</p>
                        <div class="d-flex" style="line-height:1">
                            <img class="img-radius" :src="item.data.course_thumbnail" />
                            <div class="ml-3">
                                <p class="mb-2" v-html="item.data.course_title"></p>
                                <p class="text-secondary text-xs">{{ item.data.course_no_of_chapter }} Chapters, {{ item.data.course_no_of_episode }} Episodes</p>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- 6 -->
                <li :class="['list-items', item.is_read!=1?'active':'']" v-else-if="item.data.notification_type === 6">
                    <img class="img-circle" :src="`https://ui-avatars.com/api/?name=${item.data.institution_name}`" />
                    <div class="notification-info">
                        <p class="text-neutral text-sm">{{ item.data.institution_name }}</p>
                        <p class="notification-content"> New Institution Signup</p>
                    </div>
                </li>

            </div>
        </ul>
    </div>
</template>
<script>
    import HugIcon from '../wam-partial/icons/hug';
    export default {
        props: {
            notifications: {
                type: Array,
            }
        },
        components: {
            HugIcon
        },
        methods: {
            notification_redirect: function (redirect_url){
                this.$router.push(redirect_url)
            }
        }
    }
</script>
<style scoped>
    .list-group .list-items.cursor-pointer{
        cursor: pointer;
    }
    .list-group .list-items.active{
        background: transparent linear-gradient(180deg, #23415300 0%, #EBF7FE 100%) 0% 0% no-repeat padding-box;
    }
</style>

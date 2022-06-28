<template>
    <div class="page-wrapper">
        <div class="text-center">
            <h3>Edit Profile</h3>
            <p class="text-secondary">Change your profile picture and name from here</p>
            <div class="ins-img-wrapper">
                <img :src="User.profile_picture || '/assets/images/user-avatar.png'" class="rounded-circle mb-4" id="image-preview" style="width:150px">
                <font-awesome icon="camera" @click="setAvatar" />
            </div>
            <div class="r-alert-warning"><tooltip content="Upload good quality picture" iconColor="#FFAA38" /> You can only upload .png, .jpeg, .jpg, .bmp files for the profile picture. File size must be within 100-200kb.</div>
            <form @submit.prevent="ChangeProfileInfo">
                <table class="text-left">
                    <input type="file" name="avatar" id="avatar" accept="image/*" class="d-none" @change="loadFile">
                    <tr>
                        <td colspan="2">
                            <div class="form-group">
                                <label>Full name</label>
                                <input name="name" type="text" class="form-control" placeholder="Jeremie Goyette" :value="ProfileInfo.admin_name" required />
                                <small class="text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button type="submit" class="btn btn-primary-text col-12 mr-1">Update Profile</button>
                        </td>
                        <td>
                            <a href="#/profile" class="btn btn-border-primary text-default col-12 ml-1" @click="ChangeStep">Cancel</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</template>
<script>
    import Tooltip from '../../wam-partial/tooltip'
    export default {
        data() {
            return {
                ProfileInfo: {},
                errors: {}
            }
        },
        components: {
            Tooltip,
        },
        methods: {
            ChangeStep: function (){
                this.$emit('change-step', 1)
            },
            getProfile: function (){
                axios.get('api/institution/get_admin_profile').then(res => {
                    this.ProfileInfo = res.data
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            ChangeProfileInfo: function (el){
                let data = this.FilterForm(el.target, 'formdata')
                axios.post('api/institution/edit_admin_profile', data).then(res => {
                    if (data.get('avatar') === '') { this.ChangeLocalStorageData({name: data.get('name')}, ["name"]) }
                    else { this.ChangeLocalStorageData({name: data.get('name') ,profile_picture: res.data.pic}, ["name", "profile_picture"]) }

                    this.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
                    }).then(() => {
                        this.$router.go()
                    })
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            setAvatar: function (){
                $('#avatar').click()
            },
            loadFile: function (el){
                var output = document.getElementById('image-preview');
                output.src = URL.createObjectURL(el.target.files[0]);
                output.onload = function() {
                    URL.revokeObjectURL(output.src) // free memory
                }
            }
        },
        mounted() {
            this.getProfile()
        },
    }
</script>
<style scoped>
    .page-wrapper{
        display: flex;
        justify-content: center;
        background: #fff;
        margin-bottom: 10px;
        padding: 20px;
        padding-bottom: 30px;
    }
    .page-wrapper h3{
        margin-top: 30px;
        font-weight: bold;
    }
    .page-wrapper .ins-img-wrapper{
        display: inline-block;
        position: relative;
    }
    .page-wrapper .ins-img-wrapper>svg{
        position: absolute;
        padding: 7px;
        background: #38B6FF;
        color: #fff;
        border-radius: 50%;
        border: 1px solid #fff;
        font-size: 31px;
        right: 10px;
        bottom: 31px;
        cursor: pointer;
    }
    .page-wrapper table{
        margin: 0 auto;
    }
    .page-wrapper table p{
        margin: 0;
    }
    .page-wrapper form label{
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 600;
    }
    .page-wrapper form input{
        border: 1px solid #E8EBED;
        box-shadow: 0px 1px 3px #38B6FF1A;
    }
    .r-alert-warning{
        background: #FFFCF8;
        color: #FFAA38;
        font-size: 12px;
        border-radius: 5px;
        margin: 25px 0;
        padding: 20px;
    }
</style>

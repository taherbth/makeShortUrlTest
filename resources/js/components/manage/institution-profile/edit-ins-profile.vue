<template>
    <div>
        <Layout>
            <div class="page-wrapper">
                <div class="text-center">
                    <h3 class="text-uppercase font-weight-bold">Edit institution profile</h3>
                    <p class="text-secondary">Change your institution information from here. <br> You can always change it from here</p>
                    <div class="ins-img-wrapper">
                        <img :src="InstitutionProfile.institution_profile_picture || '/assets/images/user-avatar.png'" class="rounded-circle mb-4" id="image-preview" style="width:150px">
                        <font-awesome icon="camera" @click="setAvatar" />
                    </div>
                    <form @submit.prevent="ChangeInstitutionProfileInfo">
                        <input type="file" name="avatar" id="avatar" accept="image/*" class="d-none" @change="loadFile">
                        <table class="text-left">
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>institution name</label>
                                        <input name="institution_name" type="text" class="form-control" :value="InstitutionProfile.institution_name" />
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label>established in</label>
                                        <input name="established_at" type="date" class="form-control" :value="InstitutionProfile.established_at" />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label>address</label>
                                        <input name="institution_address" type="text" class="form-control" placeholder="5283 McGlynn Grove, Abigailbury, Louisiana, USA" :value="InstitutionProfile.institution_address" />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button type="submit" class="btn btn-primary-text col-12 mt-4 mr-1">Update Profile</button>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-border-primary text-default col-12 mt-4 ml-1" @click="$router.push('/institution/manage/institution-profile')">Cancel</a>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout'
    export default {
        data() {
            return {
                InstitutionProfile: {}
            }
        },
        components: {
            Layout,
        },
        methods: {
            getInstitutionProfile: function (){
                axios.get(`api/institution/get_institution_profile`).then(res => {
                    this.InstitutionProfile = res.data
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            ChangeInstitutionProfileInfo: function (el){
                let data = this.FilterForm(el.target, 'formdata')
                axios.post('api/institution/edit_institution_profile', data).then(res => {
                    this.ChangeLocalStorageData({institution_name: data.get('institution_name')}, ["institution_name"])

                    this.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
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
            this.getInstitutionProfile()
        },
    }
</script>
<style scoped>
    .page-wrapper{
        display: flex;
        justify-content: center;
        height: 85vh;
        min-height: 850px;
        background: #fff;
        margin-bottom: 10px;
    }
    .page-wrapper h3{
        margin-top: 64px;
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
</style>

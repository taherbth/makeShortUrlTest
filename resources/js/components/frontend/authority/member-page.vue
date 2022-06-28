<template>
    <div class="page-wrapper">
        <div class="text-center" style="width: 100%" >
            <h3>Member</h3>
            <p class="text-secondary">You can publish your members from this section</p>
            
            <div style="margin-top:60px;">
                <div v-if="Step === 1">
                    <div class="space-between mb-3">
                        <h5> Member List </h5>
                        <a href="javascript:void(0)" class="btn btn-primary-text" @click="AddService">Add member</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-borderless r-table r-table-text-center">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th>Title</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, key) in serviceList" :key="key">
                                    <th scope="row">{{ key+1 }}</th>
                                    <td>{{ item.name }}</td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-warning" @click="GoToEdit(item.id)">Edit</a>
                                        <a href="javascript:void(0)" class="btn btn-danger" @click="DeleteService(item.id)">Delete</a>
                                    </td>
                                </tr>
                                <tr v-if="serviceList.length<1">
                                    <td class="r-card-empty" colspan="3">
                                        <empty-recent-responses class="mb-4"/>
                                        <h6 class="font-weight-bold">No Member to show</h6>
                                        <p class="text-secondary mb-0">We'll show Member once you get started!</p>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                <form @submit.prevent="SubmitForm" v-if="Step === 2">
                    <h5 class="text-left mb-3" v-text="ServiceInfo.id>=1? 'Update Member':'Create Member'"></h5>

                    <table class="text-left" style="width: 100%">
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" name="is_admin" type="checkbox" @change="inputChange" value="1" id="flexCheckDefault" v-model="ServiceInfo.is_admin">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Is Admin
                                    </label>
                                </div>
                                <div v-if="this.ServiceInfo.is_admin == 1">
                                    <textarea type="text" v-model="ServiceInfo.admin_message" class="form-control" name="admin_message" rows="5" cols="50"></textarea>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" v-model="ServiceInfo.name" />
                                    <small class="form-text text-danger text-left" v-if="errors.name"> {{ errors.name[0] }} </small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label>Role</label>
                                    <input type="text" class="form-control" name="role" v-model="ServiceInfo.role" />
                                    <small class="form-text text-danger text-left" v-if="errors.role"> {{ errors.role[0] }} </small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label>Facebook Link</label>
                                    <input type="url" class="form-control" name="facebook" v-model="ServiceInfo.facebook" />
                                    <small class="form-text text-danger text-left" v-if="errors.facebook"> {{ errors.facebook[0] }} </small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label>Linkedin Link</label>
                                    <input type="url" class="form-control" name="linkedin" v-model="ServiceInfo.linkedin" />
                                    <small class="form-text text-danger text-left" v-if="errors.linkedin"> {{ errors.linkedin[0] }} </small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label>Instagram Link</label>
                                    <input type="url" class="form-control" name="instagram" v-model="ServiceInfo.instagram" />
                                    <small class="form-text text-danger text-left" v-if="errors.instagram"> {{ errors.instagram[0] }} </small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label>Image</label>
                                    <input type="file" class="form-control" @change="onFileChange" name="image" />
                                    <small class="form-text text-danger text-left" v-if="errors.image"> {{ errors.image[0] }} </small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div id="preview">
                                        <img v-if="url" :src="url"  alt="banner"/>
                                        <img v-else-if="ServiceInfo.image" :src="ServiceInfo.image"  alt="banner"/>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="submit" class="btn btn-primary-text submit-button" v-text="ServiceInfo.id<1?'Create':'Update'"></button>
                                <button type="button" class="btn btn-danger" @click="ResetPage">Cancel</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

        </div>
    </div>
</template>
<script>
    import EmptyRecentResponses from '../../wam-partial/icons/empty-recent-responses'

    export default {
        data() {
            return {
                Step: 1,
                serviceList: [],
                ServiceInfo: {
                    id: 0,
                    title: "",
                    description: "",
                    button_name: "",
                    button_address: "",
                    banner: null,
                    is_admin: 0,
                    admin_message: "",
                },
                errors: {},
                url: null
            }
        },
        components: {
            EmptyRecentResponses,
        },
        methods: {
            inputChange(e){
                console.log(e.target.checked)
                if(e.target.checked == true){
                    this.ServiceInfo.is_admin = 1
                } else{
                    this.ServiceInfo.is_admin = 0
                }
                console.log(this.ServiceInfo.is_admin)
            },
            onFileChange(e) {
                const file = e.target.files[0];
                this.url = URL.createObjectURL(file);
            },
            GetServiceList: function (){
                this.Step = 1
                axios.get('api/authority/member').then(res => {
                    this.serviceList = res.data;
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            AddService: function (){
                this.Step = 2;
            },
            GoToEdit: function (id){
                axios.get(`api/authority/member/${id}`).then(res => {
                    this.ServiceInfo = {
                        'id': res.data.id,
                        'name': res.data.name,
                        'role': res.data.role,
                        'image': res.data.image,
                        'facebook': res.data.facebook,
                        'linkedin': res.data.linkedin,
                        'instagram': res.data.instagram,
                        'is_admin': res.data.is_admin,
                        'admin_message': res.data.admin_message
                    };
                });

                this.Step = 2;
            },
            SubmitForm: function (el){
                document.querySelector('.submit-button').setAttribute('disabled', 'true');
                if (this.ServiceInfo.id < 1) {
                    this.CreateService(el);
                } else {
                    this.UpdateService(el);
                }
            },
            CreateService: function (el){
                this.errors = {};
                let data = this.FilterForm(el.target, 'formdata')
                axios.post('api/authority/member', data).then(res => {
                    this.ResetPage();
                    this.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
                    });
                    document.querySelector('.submit-button').removeAttribute('disabled');
                }).catch(error => {
                    this.ValidtaeForm(error)
                    document.querySelector('.submit-button').removeAttribute('disabled');
                });
            },
            UpdateService: function (el){
                this.errors = {};
                let data = this.FilterForm(el.target, 'formdata')
                data.append('_method', "PUT");
                axios.post(`api/authority/member/${this.ServiceInfo.id}`, data).then(res => {
                    this.ResetPage();
                    this.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
                    });
                    document.querySelector('.submit-button').removeAttribute('disabled');
                }).catch(error => {
                    this.ValidtaeForm(error)
                    document.querySelector('.submit-button').removeAttribute('disabled');
                });
            },
            DeleteService: function (id){
                this.$swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post(`api/authority/member/${id}`, {_method: "DELETE"}).then(res => {
                            this.GetServiceList();
                            this.$swal.fire('Deleted!', 'Your member has been deleted.', 'success')
                        });
                    }
                })
            },
            ResetPage: function (){
                this.Step = 1;
                document.querySelector('form').reset();
                this.ServiceInfo = {
                    id: -1,
                    title: "",
                    article: ""
                };
                this.errors = {};

                this.GetServiceList();
            }
        },
        mounted() {
            this.GetServiceList();
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
    .r-table th {
        color: #909FA8;
        font-weight: normal;
        padding-top: 0;
    }
    .r-table tr th, .r-table tr td{
        border-bottom: 1px solid #E8EBED;
    }
    .r-table.r-table-text-center tr th, .r-table.r-table-text-center tr td{
        text-align: center;
    }
    .r-table tr:last-child td{
        border-bottom: 0;
    }

    .space-between {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
    }
    .r-card-empty {
        text-align: center;
        padding: 15vh 0px;
    }
</style>

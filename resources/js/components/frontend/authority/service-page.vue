<template>
    <div class="page-wrapper">
        <div class="text-center" style="width: 100%" >
            <h3>Service</h3>
            <p class="text-secondary">You can publish your services from this section</p>


            <div style="margin-top:60px;">
                <div v-if="Step === 1">
                    <div class="space-between mb-3">
                        <h5> Service List </h5>
                        <a href="javascript:void(0)" class="btn btn-primary-text" @click="AddService">Add Service</a>
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
                                    <td>{{ item.title }}</td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-warning" @click="GoToEdit(item.id)">Edit</a>
                                        <a href="javascript:void(0)" class="btn btn-danger" @click="DeleteService(item.id)">Delete</a>
                                    </td>
                                </tr>
                                <tr v-if="serviceList.length<1">
                                    <td class="r-card-empty" colspan="3">
                                        <empty-recent-responses class="mb-4"/>
                                        <h6 class="font-weight-bold">No Service to show</h6>
                                        <p class="text-secondary mb-0">We'll show Service once you get started!</p>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                <form @submit.prevent="SubmitForm" v-if="Step === 2">
                    <h5 class="text-left mb-3" v-text="ServiceInfo.id>=1? 'Update Service':'Create Service'"></h5>

                    <table class="text-left" style="width: 100%">
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" name="title" v-model="ServiceInfo.title" />
                                    <small class="form-text text-danger text-left" v-if="errors.title"> {{ errors.title[0] }} </small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description" v-model="ServiceInfo.description" />
                                    <small class="form-text text-danger text-left" v-if="errors.description"> {{ errors.description[0] }} </small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label>Button Title</label>
                                    <input type="text" class="form-control" name="button_name" v-model="ServiceInfo.button_name" />
                                    <small class="form-text text-danger text-left" v-if="errors.button_name"> {{ errors.button_name[0] }} </small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label>Button Link</label>
                                    <input type="url" class="form-control" name="button_address" v-model="ServiceInfo.button_address" />
                                    <small class="form-text text-danger text-left" v-if="errors.button_address"> {{ errors.button_address[0] }} </small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label>Banner</label>
                                    <input type="file" class="form-control" @change="onFileChange" name="banner" />
                                    <small class="form-text text-danger text-left" v-if="errors.banner"> {{ errors.banner[0] }} </small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div id="preview">
                                        <img v-if="url" :src="url"  alt="banner"/>
                                        <img v-else-if="ServiceInfo.banner" :src="ServiceInfo.banner"  alt="banner"/>
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
                    id: -1,
                    title: "",
                    description: "",
                    button_name: "",
                    button_address: "",
                    banner: null
                },
                errors: {},
                url: null
            }
        },
        components: {
            EmptyRecentResponses,
        },
        methods: {
            onFileChange(e) {
                const file = e.target.files[0];
                this.url = URL.createObjectURL(file);
            },
            GetServiceList: function (){
                this.Step = 1
                axios.get('api/authority/service').then(res => {
                    this.serviceList = res.data;
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            AddService: function (){
                this.Step = 2;
            },
            GoToEdit: function (id){
                axios.get(`api/authority/service/${id}`).then(res => {
                    this.ServiceInfo = {
                        'id': res.data.id,
                        'title': res.data.title,
                        'description': res.data.description,
                        'banner': res.data.banner,
                        'button_name': res.data.button_name,
                        'button_address': res.data.button_address
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
                axios.post('api/authority/service', data).then(res => {
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
                axios.post(`api/authority/service/${this.ServiceInfo.id}`, data).then(res => {
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
                        axios.post(`api/authority/service/${id}`, {_method: "DELETE"}).then(res => {
                            this.GetServiceList();
                            this.$swal.fire('Deleted!', 'Your service has been deleted.', 'success')
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

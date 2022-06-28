<template>
    <div class="page-wrapper">
        <div class="text-center" style="width: 100%" >
            <h3>Blog</h3>
            <p class="text-secondary">You can publish your blogs from this section</p>


            <div style="margin-top:60px;">
                <div v-if="Step === 1">
                    <div class="space-between mb-3">
                        <h5> Blog List </h5>
                        <a href="javascript:void(0)" class="btn btn-primary-text" @click="AddBlog">Add Blog</a>
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
                                <tr v-for="(item, key) in blogList.data" :key="key">
                                    <th>{{ key+1 }}</th>
                                    <td>{{ item.title }}</td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-warning" @click="GoToEdit(item.id)">Edit</a>
                                        <a href="javascript:void(0)" class="btn btn-danger" @click="DeleteBlog(item.id)">Delete</a>
                                    </td>
                                </tr>
                                <tr v-if="blogList.data.length<1">
                                    <td class="r-card-empty" colspan="3">
                                        <empty-recent-responses class="mb-4"/>
                                        <h6 class="font-weight-bold">No Blog to show</h6>
                                        <p class="text-secondary mb-0">We'll show Blog once you get started!</p>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <pagination :data="blogList" @pagination-change-page="GetBlogList"></pagination>
                    </div>
                </div>

                <form @submit.prevent="SubmitForm" v-if="Step === 2">
                    <h5 class="text-left mb-3" v-text="BlogInfo.id>=1? 'Update Blog':'Create Blog'"></h5>

                    <table class="text-left" style="width: 100%">
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" name="title" v-model="BlogInfo.title" />
                                    <small class="form-text text-danger text-left" v-if="errors.title"> {{ errors.title[0] }} </small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label>Blog</label>
                                    <ckeditor :editor="editor" v-model="BlogInfo.article" :config="editorConfig"></ckeditor>
                                    <textarea class="form-control d-none" name="article" v-model="BlogInfo.article" />
                                    <small class="form-text text-danger text-left" v-if="errors.article"> {{ errors.article[0] }} </small>
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
                                        <img v-else-if="BlogInfo.banner" :src="BlogInfo.banner"  alt="banner"/>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="submit" class="btn btn-primary-text submit-button" v-text="BlogInfo.id<1?'Create':'Update'"></button>
                                <button type="button" class="btn btn-primary-text loading-button d-none">
                                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                    Loading...
                                </button>
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
    import Pagination from 'laravel-vue-pagination';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        data() {
            return {
                Step: 1,
                blogList: {
                    data: []
                },
                BlogInfo: {
                    id: -1,
                    title: "",
                    article: "",
                    banner: null
                },
                errors: {},

                // CK Editor
                editor: ClassicEditor,
                editorData: '<p>Content of the editor.</p>',
                editorConfig: {},
                url: null
            }
        },
        components: {
            EmptyRecentResponses,
            Pagination
        },
        methods: {
            onFileChange(e) {
                const file = e.target.files[0];
                this.url = URL.createObjectURL(file);
            },
            GetBlogList: function (page = 1){
                this.Step = 1
                axios.get('api/authority/blog?page=' + page).then(res => {
                    this.blogList = res.data;
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            AddBlog: function (){
                this.Step = 2;
            },
            GoToEdit: function (id){
                axios.get(`api/authority/blog/${id}`).then(res => {
                    this.BlogInfo = {
                        'id': res.data.id,
                        'title': res.data.title,
                        'article': res.data.article,
                        'banner': res.data.banner
                    };
                });

                this.Step = 2;
            },
            SubmitForm: function (el){
                this.submitButtonStatus(false);
                if (this.BlogInfo.id < 1) {
                    this.CreateBlog(el);
                } else {
                    this.UpdateBlog(el);
                }
            },
            CreateBlog: function (el){
                this.errors = {};
                let data = this.FilterForm(el.target, 'formdata')
                axios.post('api/authority/blog', data).then(res => {
                    this.ResetPage();
                    this.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
                    });
                    this.submitButtonStatus(true);
                }).catch(error => {
                    this.ValidtaeForm(error)
                    this.submitButtonStatus(true);
                });
            },
            UpdateBlog: function (el){
                this.errors = {};
                let data = this.FilterForm(el.target, 'formdata')
                data.append('_method', "PUT");
                axios.post(`api/authority/blog/${this.BlogInfo.id}`, data).then(res => {
                    this.ResetPage();
                    this.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
                    });
                    this.submitButtonStatus(true);
                }).catch(error => {
                    this.ValidtaeForm(error)
                    this.submitButtonStatus(true);
                });
            },
            DeleteBlog: function (id){
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
                        axios.post(`api/authority/blog/${id}`, {_method: "DELETE"}).then(res => {
                            this.GetBlogList();
                            this.$swal.fire('Deleted!', 'Your blog has been deleted.', 'success')
                        });
                    }
                })
            },
            ResetPage: function (){
                this.Step = 1;
                document.querySelector('form').reset();
                this.BlogInfo = {
                    id: -1,
                    title: "",
                    article: ""
                };
                this.errors = {};

                this.GetBlogList();
            },
            submitButtonStatus: function (status = true){
                if (status) {
                    document.querySelector('.submit-button').classList.remove('d-none');
                    document.querySelector('.loading-button').classList.add('d-none');
                } else {
                    document.querySelector('.submit-button').classList.add('d-none');
                    document.querySelector('.loading-button').classList.remove('d-none');
                }
            }
        },
        mounted() {
            this.GetBlogList();
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

<style>
    .ck-editor__editable_inline {
    min-height: 200px !important;
}
</style>

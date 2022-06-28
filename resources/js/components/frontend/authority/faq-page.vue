<template>
    <div class="page-wrapper">
        <div class="text-center" style="width: 100%" >
            <h3>FAQ</h3>
            <p class="text-secondary">You can publish your FAQ's from this section</p>


            <div style="margin-top:60px;">
                <div v-if="Step === 1">
                    <div class="space-between mb-3">
                        <h5> FAQ List </h5>
                        <a href="javascript:void(0)" class="btn btn-primary-text" @click="AddFaq">Add FAQ</a>
                    </div>
                    <table class="table table-borderless r-table r-table-text-center">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th>Faq Question</th>BLOG
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, key) in faqList" :key="key">
                                <th scope="row">{{ key+1 }}</th>
                                <td>{{ item.question }}</td>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-warning" @click="GoToEdit(item.id)">Edit</a>
                                    <a href="javascript:void(0)" class="btn btn-danger" @click="DeleteFaq(item.id)">Delete</a>
                                </td>
                            </tr>
                            <tr v-if="faqList.length<1">
                                <td class="r-card-empty" colspan="3">
                                    <empty-recent-responses class="mb-4"/>
                                    <h6 class="font-weight-bold">No FAQ to show</h6>
                                    <p class="text-secondary mb-0">We'll show FAQ once you get started!</p>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <form @submit.prevent="SubmitForm" v-if="Step === 2">
                    <h5 class="text-left mb-3" v-text="FaqInfo.id>=1? 'Update FAQ':'Create FAQ'"></h5>

                    <table class="text-left" style="width: 100%">
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label>FAQ Question</label>
                                    <input type="text" class="form-control" name="question" v-model="FaqInfo.question" />
                                    <small class="form-text text-danger text-left" v-if="errors.question"> {{ errors.question[0] }} </small>
                                </div>
                            </td>
                        </tr>
<!--                        <tr>-->
<!--                            <td>-->
<!--                                <div class="form-group">-->
<!--                                    <label>FAQ Answer</label>-->
<!--                                    <textarea type="text" class="form-control" name="answer" v-model="FaqInfo.answer" />-->
<!--                                    <small class="form-text text-danger text-left" v-if="errors.answer"> {{ errors.answer[0] }} </small>-->
<!--                                </div>-->
<!--                            </td>-->
<!--                        </tr>-->
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label>FAQ Answer</label>
                                    <ckeditor :editor="editor" v-model="FaqInfo.answer" :config="editorConfig"></ckeditor>
                                    <textarea class="form-control d-none" name="answer" v-model="FaqInfo.answer" />
                                    <small class="form-text text-danger text-left" v-if="errors.answer"> {{ errors.answer[0] }} </small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="submit" class="btn btn-primary-text submit-button" v-text="FaqInfo.id<1?'Create':'Update'"></button>
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
    import EmptyRecentResponses from '../../wam-partial/icons/empty-recent-responses';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        data() {
            return {
                Step: 1,
                faqList: [],
                FaqInfo: {
                    id: -1,
                    question: "",
                    answer: ""
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
            EmptyRecentResponses
        },
        methods: {
            GetFaqList: function (){
                this.Step = 1
                axios.get('api/authority/faq').then(res => {
                    this.faqList = res.data;
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            AddFaq: function (){
                this.Step = 2;
            },
            GoToEdit: function (id){
                axios.get(`api/authority/faq/${id}`).then(res => {
                    this.FaqInfo = {
                        'id': res.data.id,
                        'question': res.data.question,
                        'answer': res.data.answer
                    };
                });

                this.Step = 2;
            },
            SubmitForm: function (el){
                this.submitButtonStatus(false);
                if (this.FaqInfo.id < 1) {
                    this.CreateFaq(el);
                } else {
                    this.UpdateFaq(el);
                }
            },
            CreateFaq: function (el){
                this.errors = {};
                let data = this.FilterForm(el.target, 'formdata')
                axios.post('api/authority/faq', data).then(res => {
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
            UpdateFaq: function (el){
                this.errors = {};
                let data = this.FilterForm(el.target, 'formdata')
                data.append('_method', "PUT");
                axios.post(`api/authority/faq/${this.FaqInfo.id}`, data).then(res => {
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
            DeleteFaq: function (id){
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
                        axios.post(`api/authority/faq/${id}`, {_method: "DELETE"}).then(res => {
                            this.GetFaqList();
                            this.$swal.fire('Deleted!', 'Your Faq has been deleted.', 'success')
                        });
                    }
                })
            },
            ResetPage: function (){
                this.Step = 1;
                document.querySelector('form').reset();
                this.FaqInfo = {
                    id: -1,
                    question: "",
                    answer: ""
                };
                this.errors = {};

                this.GetFaqList();
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
            this.GetFaqList();
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

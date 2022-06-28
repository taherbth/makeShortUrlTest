<template>
    <div class="page-wrapper">
        <div class="text-center" style="width: 100%" >
            <h3>About WAM Academy Text</h3>
            <p class="text-secondary">You can change your About page information from this section</p>


            <div style="margin-top:60px;">
                <form @submit.prevent="UpdateOrCreateContact">
                    <h5 class="text-left mb-3">Update About WAM Academy Text</h5>

                    <table class="text-left" style="width: 100%">
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label>Text</label>
                                    <ckeditor :editor="editor" v-model="ContactInfo.text" :config="{}"></ckeditor>
                                    <textarea class="form-control d-none" name="text" v-model="ContactInfo.text" />
                                    <small class="form-text text-danger text-left" v-if="errors.text"> {{ errors.text[0] }} </small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="submit" class="btn btn-primary-text">Update</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

        </div>
    </div>
</template>
<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    export default {
        data() {
            return {
                Step: 1,
                ContactInfo: {},
                errors: {},
                editor: ClassicEditor,
                editorData: '<p>Content of the editor.</p>',
                editorConfig: {},
                url: null
            }
        },
        methods: {
            GetContactList: function (){
                this.Step = 1
                axios.get('api/authority/contact').then(res => {
                    this.ContactInfo = res.data;
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            UpdateOrCreateContact: function (el){
                this.errors = {};
                let data = this.FilterForm(el.target, 'formdata')
                axios.post('api/authority/contact', data).then(res => {
                    this.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
                    });
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
        },
        mounted() {
            this.GetContactList();
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

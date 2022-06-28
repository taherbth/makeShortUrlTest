<template>
    <form @submit.prevent="SubmitForm" >
        <h5 class="text-left mb-3" v-text="ServiceInfo.id>=1? 'Update Feature Image':'Create Feature Image'"></h5>

        <table class="text-left" style="width: 100%">

            <tr>
                <td>
                    <div class="form-group">
                        <label>Feature Image</label>
                        <input type="file" class="form-control" @change="onFileChange" name="feature_image" />
                        <small class="form-text text-danger text-left" v-if="errors.feature_image"> {{ errors.feature_image[0] }} </small>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <div id="preview">
                            <img v-if="url" :src="url"  alt="banner"/>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <button type="submit" class="btn btn-primary-text submit-button">Update</button>
                    <button type="button" class="btn btn-danger" @click="ResetPage">Cancel</button>
                </td>
            </tr>
        </table>
    </form>
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
                banner: ""
            },
            errors: {},
            url: "https://wam.academy/frontend/images/wam-application-view.PNG"
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
            axios.get('api/authority/feature_image').then(res => {
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

                this.CreateService(el);

        },
        CreateService: function (el){
            this.errors = {};
            let data = this.FilterForm(el.target, 'formdata')
            axios.post('api/authority/feature_image', data).then(res => {
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
            this.$router.push('/authority/frontend')
        }
    },
    mounted() {
        this.GetServiceList();
    },
}
</script>

<style scoped>

</style>

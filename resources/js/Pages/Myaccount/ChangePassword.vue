<template>
    <form @submit.prevent="changePassword" class="theme-form">
        
        <div class="form-row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="name">Old Password</label>
                    <input v-model="form.old_password" required type="password" class="form-control" id="old_password" placeholder="Enter Your Old Password" >
                </div>
            </div>

             <div class="col-md-12">
                <div class="form-group">
                    <label for="name">New Password</label>
                    <input v-model="form.password" required type="password" class="form-control" id="password" placeholder="Enter Your New Password" >
                </div>
            </div>


                        <div class="col-md-12">
                <div class="form-group">
                    <label for="name">Confirm New Password</label>
                    <input v-model="form.password_confirmation" required type="password" class="form-control" id="password_confirmation" placeholder="Retype New Password" >
                </div>
            </div>

            <div class="col-md-12">
                <button class="btn btn-warning" type="submit">Change My Password</button>
            </div>
        </div>
    </form>
</template>

<script>
    import Layout from '@/Shared/Layout'
    export default {
        props: ['error-msg'],
        components:{
            Layout,
        },
        data(){
            return {
                form:{
                    old_password: '',
                    password: '',
                    password_confirmation: ''
                }
            }
        },

        methods:{
            changePassword(){
                this.$inertia.post('/dashboard/password/change', this.form).then(()=>{

                });
            }
        }


    }
</script>
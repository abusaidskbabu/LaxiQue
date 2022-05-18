<template>
<layout :categorys2="categorys2">
<div>


        <!-- BREADCRUMBS SETCTION START -->
        <div class="breadcrumbs-section plr-200 mb-80 section">
            <div class="breadcrumbs overlay-bg" :style="'background: url('+base_url()+`frontend/img/breadcrumb/1.png` + ')no-repeat;background-position: center;background-size: cover;' ">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="breadcrumbs-inner">
                                <h1 class="breadcrumbs-title">Login</h1>
                                <ul class="breadcrumb-list">
                                    <li><inertia-link href="/">home</inertia-link></li>
                                    <li>Login</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- BREADCRUMBS SETCTION END -->






        <!-- Start page content -->
        <div id="page-content" class="page-wrapper section">
            <div class="login-section mb-80">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div v-if="Object.keys(errors).length > 0 " class="alert alert-danger errorMessage">
                                    <!-- {{ errors[Object.keys(errors)[0]][0] }} -->
                                    {{ errors[Object.keys(errors)[0]] }}
                                </div>
                                <div v-if="successMessage" class="alert alert-success successMessage">
                                    {{ successMessage }}
                                </div>
                                <div v-if="errorMessage" class="alert alert-danger errorMessage">
                                    {{ errorMessage }}
                                </div>
                            </div>
                            <div class="registered-customers">
                                <h6 class="widget-title border-left mb-50">LOGIN CUSTOMERS</h6>
                                <form @submit.prevent="submit">
                                    <div class="login-account p-30 box-shadow">
                                        <p>If you have an account with us, Please log in.</p>
                                        <div class="input-group mb-4">
                                            <input v-model="form.phone_number" class="form-control" type="text" placeholder="Mobile Number" aria-label="Mobile Number" aria-describedby="basic-addon2" required="">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary h-100" @click.prevent="generate_otp" type="button">Generate OTP</button>
                                            </div>
                                        </div>
                                        <input v-model="form.otp" type="text" placeholder="OTP" required="">
                                        <!-- <input type="text"  v-model="form.email" placeholder="Email / Phone"> -->
                                        <!-- <input type="password" v-model="form.password" placeholder="Password"> -->
                                        <p style="width: 300px;margin: 0;float: left;"><inertia-link :href="route('password.request')" id="fgpwd">Forgot password?</inertia-link></p>
                                        <p><inertia-link :href="route('register')" class="float-right">Create an Account</inertia-link></p>
                                        <br>
                                        <button class="submit-btn-1 btn-hover-1" type="submit">login</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                       <div class="col-lg-3"></div>
                       
                    </div>
                </div>
            </div>
        </div>
        <!-- End page content -->



</div>
</layout>
</template>
<style scoped>
.errorMessage {
    border-radius: 0;
    background-color: #ffdcdc;
    border: 1px solid #ffdcdc;
    color: #000;
    font-size: 17px;
}
.successMessage{
border-radius: 0;
    background-color: #bdfba4;
    border: 1px solid #bdfba4;
    color: #000;
    font-size: 17px;
}
</style>

<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from '@/Jetstream/Checkbox'
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'
    import Layout from '@/Shared/Layout'

    export default {
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors,
            Layout
        },

        props: {
            canResetPassword: Boolean,
            status: String
        },
        props: ['errors', 'successMessage', 'errorMessage', 'categorys2'],  
        
        
        data() {
            return {
                form: this.$inertia.form({
                    phone_number: '',
                    otp: '',
                    remember: false
                })
            }
        },

        methods: {
            submit() {
                this.form
                    .transform(data => ({
                        ... data,
                        remember: this.form.remember ? 'on' : ''
                    }))
                    .post(this.route('web_login'), {
                        onFinish: () => this.form.reset('password'),
                    })
            },
            generate_otp() {
                this.form.post(this.route('generate_otp'), {
                    // onFinish: () => this.form.reset('password', 'password_confirmation'),

                })
            }
        }
    }
</script>

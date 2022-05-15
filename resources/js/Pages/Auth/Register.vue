<template>
<layout>
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
                        <h6 class="widget-title border-left mb-50">CUSTOMER REGISTRATION</h6>
                        <form @submit.prevent="submit">
                            <div class="login-account p-30 box-shadow">
                                <p>If you dont have an account with us, Please Create an Account.</p>
                                <input v-model="form.name" type="text" placeholder="Full Name">
                                <input v-model="form.phone_number" type="text" placeholder="Mobile Number">
                                <input v-model="form.email" type="text" placeholder="Email">
                                <input v-model="form.password" type="text" placeholder="Password">
                                <input v-model="form.password_confirmation" type="text" placeholder="Confirm Password">
                                <p>Already have an account? <inertia-link :href="route('login')"> Click</inertia-link> here to &nbsp;<inertia-link :href="route('login')">login</inertia-link> </p>
                                <button class="submit-btn-1 btn-hover-1" type="submit">Create Account</button>
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
    import JetCheckbox from "@/Jetstream/Checkbox";
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'
    import Layout from '@/Shared/Layout'

    export default {
        props: ['errors', 'successMessage', 'errorMessage'],
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

        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                    email: '',
                    phone_number: '',
                    password: '',
                    password_confirmation: '',
                    terms: false,
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('register_web'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                })
            }
        }
    }
</script>


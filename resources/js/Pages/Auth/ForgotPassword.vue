<!--
<template>
    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo />
        </template>

        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <jet-validation-errors class="mb-4" />

        <form @submit.prevent="submit">
            <div>
                <jet-label for="email" value="Email" />
                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Email Password Reset Link
                </jet-button>
            </div>
        </form>
    </jet-authentication-card>
</template>

<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'

    export default {
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetLabel,
            JetValidationErrors
        },

        props: {
            status: String
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: ''
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('password.email'))
            }
        }
    }
</script>
-->


<template>
<layout>
<div>
<!-- breadcrumb start -->
<div class="breadcrumb-main ">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-contain">
                    <div>
                        <h2>login</h2>
                        <ul>
                            <li><a href="#">home</a></li>
                            <li><i class="fa fa-angle-double-right"></i></li>
                            <li><a href="#">login</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb End -->

<!--section start-->
<section class="login-page section-big-py-space bg-light">
    <div class="custom-container">
        <div class="row">
            <div class="col-lg-4 offset-lg-4">
                <div class="col-md-12 p-0">
                    <div v-if="status" class="alert alert-success successMessage">
                        {{ status }}
                    </div>

                    <jet-validation-errors class="alert alert-danger errorMessage mb-2"/>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-8 offset-xl-4 offset-lg-3 offset-md-2">
                <div class="theme-card">
                    <h3 class="text-center">Login</h3>
                    <form class="theme-form" @submit.prevent="submit">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input v-model="form.email" type="text" class="form-control" id="email" placeholder="Email" required="">
                        </div>
                
                        <jet-button class="btn btn-normal p-3" :disabled="form.processing">
                            Email Password Reset Link
                        </jet-button>
                        <br>
                        <br>
                        <a>Click here to <inertia-link :href="route('login')">Login</inertia-link></a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!--Section ends-->
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

        // props: {
        //     canResetPassword: Boolean,
        //     status: String
        // },
        props: ['errors', 'successMessage', 'errorMessage', 'status'],  
        

        data() {
            return {
                form: this.$inertia.form({
                    email: ''
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('password.email'))
            }
        }
    }
</script>


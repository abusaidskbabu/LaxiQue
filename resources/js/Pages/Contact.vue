<template>
<layout  :categories="cat" :categorys2="categorys2">
    <div>
        <!-- BREADCRUMBS SETCTION START -->
        <div class="breadcrumbs-section plr-200 mb-80 section">
            <div class="breadcrumbs overlay-bg" :style="'background: url('+base_url()+`frontend/img/breadcrumb/1.png` + ')no-repeat;background-position: center;background-size: cover;' ">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="breadcrumbs-inner">
                                <h1 class="breadcrumbs-title">Contact</h1>
                                <ul class="breadcrumb-list">
                                    <li><a href="index.html">Home</a></li>
                                    <li>Contact</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- BREADCRUMBS SETCTION END -->

        <!-- Start page content -->
        <section id="page-content" class="page-wrapper section plr-185">

            <!-- ADDRESS SECTION START -->
            <div class="address-section mb-80">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="contact-address box-shadow">
                                <i class="zmdi zmdi-pin"></i>
                                <h6>House - 21/1, Urdho road, Chand mansion, Jumrat Tower, Lalbagh, Dhaka 1211, Bangladesh</h6>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="contact-address box-shadow">
                                <i class="zmdi zmdi-phone"></i>
                                <h6><a href="tel:01949-168823">LP: +880 1949-168823</a></h6>
                                <h6><a href="tel:+8801871774466">HL: +880 1871-774466</a></h6>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="contact-address box-shadow">
                                <i class="zmdi zmdi-email"></i>
                                <h6>luxiquebd@gmail.com</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ADDRESS SECTION END --> 
            
            <!-- GOOGLE MAP SECTION START -->
            <div class="google-map-section">
                <div class="container-fluid">
                    <div class="google-map">
                        <div id="googleMap">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.058766311897!2d90.4146119153631!3d23.780921593525687!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c70a4905a4b5%3A0xdded3506a5707b91!2sTech%20Hut!5e0!3m2!1sen!2sbd!4v1629706394580!5m2!1sen!2sbd" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <!-- GOOGLE MAP SECTION END -->
            
            <!-- MESSAGE BOX SECTION START -->
            <div class="message-box-section mt--50 mb-80">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="message-box box-shadow white-bg">
                                <form @submit.prevent="contactSubmit" id="contact-form">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h4 class="blog-section-title border-left mb-30">get in touch</h4>
                                        </div>
                                        <div class="col-lg-6">
                                            <input v-model="form.name" type="text" name="name" placeholder="Your name here">
                                        </div>
                                        <div class="col-lg-6">
                                            <input v-model="form.email" type="text" name="email" placeholder="Your email here">
                                        </div>
                                        <div class="col-lg-6">
                                            <input v-model="form.subject" type="text" name="subject" placeholder="Subject here">
                                        </div>
                                        <div class="col-lg-6">
                                            <input v-model="form.phone" type="text" name="phone" placeholder="Your phone here">
                                        </div>
                                        <div class="col-lg-12">
                                            <textarea v-model="form.message" class="custom-textarea" name="message" placeholder="Message"></textarea>
                                            <div class="row return_message">
                                                <div class="col-md-12">
                                                    <div v-if="Object.keys(errors).length > 0 " class="alert alert-danger errorMessage">
                                                        {{ errors[Object.keys(errors)[0]] }}
                                                    </div>
                                                    <div v-if="successMessage" class="alert alert-success successMessage">
                                                        {{ successMessage }}
                                                    </div>
                                                    <div v-if="errorMessage" class="alert alert-danger errorMessage">
                                                        {{ errorMessage }}
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="submit-btn-1 mt-30 btn-hover-1" type="submit">submit message</button>
                                        </div>
                                    </div>
                                </form>
                                <p class="form-messege"></p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- MESSAGE BOX SECTION END --> 
        </section>
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
    import Layout from '@/Shared/Layout'
    export default {
        props: ['errors', 'successMessage', 'errorMessage', 'cat', 'categorys2'],
        components:{
            Layout,
        },
        data(){
            return {
                form:{
                    name: '',
                    subject: '',
                    phone: '',
                    email: '',
                    message: '',
                }
            }
        },

        methods:{
            contactSubmit(){
                this.$inertia.post('/contact/submit', this.form).then(()=>{

                });
            }
        }


    }
</script>

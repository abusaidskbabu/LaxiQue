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
                                <h1 class="breadcrumbs-title">About</h1>
                                <ul class="breadcrumb-list">
                                    <li><a href="#">Home</a></li>
                                    <li>About</li>
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

            <!-- ABOUT SECTION START -->
            <div class="about-section mb-80">
                <div class="container-fluid">
                  
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="about-photo p-20 bg-img-1">
                                <img  :src="base_url()+`frontend/img/others/about.png`" alt="">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="about-description mt-4" v-html="about"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ABOUT SECTION END -->           
        </section>
        <!-- End page content -->
 
    </div>
</layout>
</template>




<script>
    import Layout from '@/Shared/Layout'
    export default {
      props: ['user', 'cat','about','categorys2'],
        components:{
            Layout,
        }
    }
</script>

<template>
<layout>
    <div>
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2>Other Policy</h2>
                            <ul>
                                <li><inertia-link href="/">home</inertia-link></li>
                                <li><i class="fa fa-angle-double-right"></i></li>
                                <li><a href="#">Other Policy</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="about-page section-big-py-space dy_page">
        <div class="custom-container">
            <div class="row">
            <div class="col-lg-12" v-html="data"></div> 
        </div>
       </div>
    </section>
    </div>
</layout>
</template>




<script>
    import Layout from '@/Shared/Layout'
    export default {
      props: ['data'],
        components:{
            Layout,
        }
    }
</script>

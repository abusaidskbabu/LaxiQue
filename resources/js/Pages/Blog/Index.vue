<template>
<layout  :categories="cat">
<div class="mt-5">
            <!-- BLOG SECTION START -->
            <div class="blog-section mb-50 plr-185">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title text-left mb-40">
                                <h2 v-if="category_name"> Category: {{ category_name }} </h2>
                                <h2 v-else class="uppercase">Latest blog </h2>
                                <h6>There are many variations of blogs available</h6>
                            </div>
                        </div>
                    </div>
                    <div class="blog">
                        <div class="row active-blog">
                            <!-- blog-item start -->
                            <div  v-for="(blog, index) in blogs" :key="index" class="col-lg-12">
                                <div class="blog-item">
                                    <img :src="parent_url()+'/uploads/images/blogs/'+blog.image" alt="">
                                    <div class="blog-desc">
                                        <h5 class="blog-title"> <inertia-link :href="route('blog', blog.slug)"> {{ blog.title }}</inertia-link></h5>
                                        <p>{{blog.sort_description}}</p>
                                        <div class="read-more">
                                             <inertia-link :href="route('blog', blog.slug)">Read more</inertia-link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- BLOG SECTION END -->  



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
        props: ['errors', 'successMessage', 'errorMessage', 'cat','blogs', 'category_name'],
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

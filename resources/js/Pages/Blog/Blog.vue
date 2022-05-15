<template>
    <layout>
        <div class="blog-section mb-50 pb-60 plr-185 mt-5">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="blog-details-area">
                                <!-- blog-details-photo -->
                                <div class="blog-details-photo bg-img-1 mb-30">
                                    <img :src="parent_url()+'/uploads/images/blogs/'+blog.image" alt="">
                                    <div class="today-date bg-img-1">
                                        <span v-if="date" class="meta-date">{{ date }}</span>
                                        <span v-if="month" class="meta-month">{{ month }}</span>
                                    </div>
                                </div>
                                
                                <!-- 
                                <ul class="blog-like-share mb-20">
                                    <li><a href="#"><i class="zmdi zmdi-comments"></i>59 Comments</a></li>
                                </ul>
                                 -->
                                <h3 class="blog-details-title mb-30 text-uppercase">{{blog.title ?? ''}}</h3>
                                <!-- blog-description -->
                                <div class="blog-description mb-60" v-html="blog.description"></div> 
                                <!-- blog-share-tags -->
                                <!-- <div class="blog-share-tags box-shadow clearfix mb-60">
                                    <div class="blog-share f-left">
                                        <p class="share-tags-title f-left">share</p>
                                        <ul class="footer-social f-left">
                                            <li>
                                                <a class="facebook" href="" title="Facebook"><i class="zmdi zmdi-facebook"></i></a>
                                            </li>
                                            <li>
                                                <a class="google-plus" href="" title="Google Plus"><i class="zmdi zmdi-google-plus"></i></a>
                                            </li>
                                            <li>
                                                <a class="twitter" href="" title="Twitter"><i class="zmdi zmdi-twitter"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div> -->
                                <!-- author-post -->
                                <!-- <div class="media author-post box-shadow mb-60">
                                    <div class="media-left pr-20">
                                        <a href="#">
                                            <img class="media-object" src="img/author/1.jpg" alt="#">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="media-heading">
                                            <a href="#">Subas Chandra Das</a>
                                        </h6>
                                        <p class="mb-0">No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursu pleasure rationally encounter conseques ences that are extremely painful.</p>
                                    </div>
                                </div> -->
                                <!-- comments on t this post -->
                                <div class="post-comments mb-60">
                                    <h4 class="blog-section-title border-left mb-30">comments on this blog</h4>
                                   
                                    <!-- single-comments start-->
                                    <span v-if="comments.length > 0">
                                    <div v-for="comment in comments" :key="comment" class="media mt-30">
                                        <div class="media-left pr-30 comment_logo">
                                            <a href="#"><img class="media-object" :src="base_url()+`frontend/img/author/demo4.png`" alt="#"></a>
                                        </div>
                                        <div class="media-body">
                                            <div class="clearfix">
                                                <div class="name-commenter f-left">
                                                    <h6 class="media-heading"><a href="#">{{ comment.name }}</a></h6>
                                                    <p class="mb-10">{{comment.comment_created_at}}</p>
                                                </div>
                                                <!-- <ul class="reply-delate f-right">
                                                    <li><a href="#">Reply</a></li>
                                                    <li>/</li>
                                                    <li><a href="#">Delate</a></li>
                                                </ul> -->
                                            </div>
                                            <p class="mb-0">{{ comment.comment }}</p>
                                        </div>
                                    </div>
                                    </span>
                                    <span v-else class="media mt-30 text-center">
                                        <h5>No comments in this post.</h5>
                                    </span>
                                    <!-- single-comments end-->
 
                                </div>
                                <!-- leave your comment -->
                                <form  @submit.prevent="commentSubmit">
                                <div class="leave-comment">
                                    <h4 class="blog-section-title border-left mb-30">leave your comment</h4>
                                    <span v-if="user">
                                    <div class="row">
                                        <!-- <div class="col-lg-6">
                                            <input type="text" name="name" placeholder="Subas Chandra Das">
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" name="email" placeholder="Email address here...">
                                        </div>
                                        <div class="col-lg-12">
                                            <input type="text" name="subject" placeholder="Subject here...">
                                        </div> -->
                                        <div class="col-lg-12">
                                            <input type="hidden" v-model="comment_form.slug">
                                            <textarea class="custom-textarea" v-model="comment_form.comment" placeholder="Your comment here..."></textarea>
                                        </div>
                                    </div>
                                    <button class="submit-btn-1 black-bg mt-30 btn-hover-2" type="submit">submit comment</button>
                                    </span>
                                    <span v-else class="media mt-30 text-center">
                                        <h5>Please <inertia-link href="/login" style="color:#ff7f00;text-decoration:underline;">Login</inertia-link> first to leave a comment.</h5>
                                    </span>
                                </div>
                                </form>
                                <!--  -->
                            </div>
                        </div>
                        <div class="col-lg-3">

                            <!-- widget-categories -->
                            <aside class="widget widget-categories box-shadow mb-30">
                                <h6 class="widget-title border-left mb-20">Categories</h6>
                                <div id="cat-treeview" class="product-cat">
                                    <ul class="treeview">
                                        <li v-for="category in computedObj" :key="category" class="closed expandable">
                                            <a  @click="categoryWise(category.id)" > {{ category.title }} </a>
                                        </li> 

                                        <li v-if="limit != null">
                                            <button @click="limit = null" class="btn btn-warning btn-sm mt-3">Show More <i class="fa fa-caret-down"></i></button>
                                        </li>
                                        <li v-else>
                                                <button @click="limit = 15" class="btn btn-warning btn-sm mt-3">Show Less <i class="fa fa-caret-up"></i></button>
                                        </li>

                                    </ul>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>
        
    </layout>
</template>

<style scoped>
.comment_logo img{
    width: 65px;
    height: auto;
}
</style>
<script>
    import Layout from '@/Shared/Layout'
    export default {
        props: ['blog', 'date', 'month', 'comments', 'categories'],
        components:{
            Layout,
        },
        data(){
            return {
                comment_form:{
                    comment: '',
                    slug: this.blog.slug
                },
                limit: 15
            }
        },

        methods:{
            commentSubmit(){
                this.$inertia.post('/comment', this.comment_form).then(()=>{

                });
            }, 
            categoryWise(category_id){
                this.$inertia.post('/category-wise-blog',{category_id:category_id},{preserveState: false});
            }
        },
        computed:{
            computedObj(){
                return this.limit ? this.categories.slice(0,this.limit) : this.categories
            }
        }


    }
</script>
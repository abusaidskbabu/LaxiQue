<template>
<layout>
<div>
        <!-- START SLIDER AREA -->
        <div class="slider-area plr-185 mb-80 section">
            <div class="container">
                <div class="slider-content">
                    <div class="active-slider-1 slick-arrow-1 slick-dots-1">
                        <!-- layer-1 Start -->
                        <div v-for="data in slider" :key="data.id">
                            <div class="col-lg-12">
                                <div class="layer-1">
                                    <div class="slider-img">
                                        <img :src="parent_url()+`images/slider/${data.image}`" alt="">
                                    </div>
                                    <div class="slider-info gray-bg">
                                        <div class="slider-info-inner">
                                            <h1 class="slider-title-1 text-uppercase text-black-1"> {{ data.title }} </h1>
                                            <div class="slider-brief text-black-2">
                                                <p>{{ data.exert }}</p>
                                            </div>
                                            <inertia-link :href="data.btn_link" class="button extra-small button-black">
                                                <span class="text-uppercase">{{ data.btn_text }}</span>
                                            </inertia-link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- layer-1 end -->

                    </div>
                </div>
            </div>
        </div>
        <!-- END SLIDER AREA -->

        <!-- NEW ARRIVAL PRODUCT SECTION START -->
            <div class="featured-product-section mb-50">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title text-left mb-40">
                                <h4 class="uppercase">New Products</h4>
                                
                            </div>
                        </div>
                    </div>
                    <div class="featured-product">
                        <div class="row active-featured-product slick-arrow-2" id="myfeatured_product">
                            
                            <!-- product-item start -->
                            <div v-for="data in new_arrival_product" :key="data.id" class="col-lg-12" >
                                <div class="product-item">
                                    <div class="product-img">
                                        <inertia-link :href="'/shop/'+data.slug">
                                            <!-- <img :src="base_url()+`frontend/img/product/1.jpg`" alt=""> -->
                                            <span v-for="img in (data.image.split(',').slice(0, 1))" :key="img">
                                                <img :src="parent_url()+`images/product/`+img" class="img-fluid lazy" >
                                            </span>
                                        </inertia-link>
                                    </div>
                                    <div class="product-info">
                                        <h6 class="product-title">
                                            <inertia-link :href="'/shop/'+data.slug"> {{ data.name }}</inertia-link>
                                        </h6>
										

										<span class="detail-price" v-if="data.deal">
												<h3 class="pro-price">BDT {{ data.deal.price }} <del>BDT {{ data.price }}</del></h3>
											</span>
											   <span class="detail-price" v-else-if="data.promotion_price && data.promotion == 1">
													<h3 class="pro-price">BDT {{ data.promotion_price }} <del>BDT {{ data.price }}</del></h3>
												</span>

											<span class="detail-price" v-else>
												<h3 class="pro-price">BDT {{ data.price }}</h3>
										</span> 
										
                                        <ul class="action-button">
                                            <li>
                                                <a @click="addOrRemoveWishlist(data.id)" href="javascript:void(0)" title="Wishlist"><i class="zmdi zmdi-favorite"></i></a>
                                            </li>
                                            <li>
                                                <a href="#" data-toggle="modal"  data-target="#productModal" title="Quickview"><i class="zmdi zmdi-zoom-in"></i></a>
                                            </li>
                                            <li>
                                                <a @click="addToCompare(data.id)" href="javascript:void(0)" title="Compare"><i class="zmdi zmdi-refresh"></i></a>
                                            </li>
                                            <li>
                                                <inertia-link v-if="data.is_variant == 1" :href="'/shop/'+data.slug"><i class="zmdi zmdi-shopping-cart-plus"></i> </inertia-link>
                                                <a v-else @click="addToCart(data.id,1)" href="#" title="Add to cart"><i class="zmdi zmdi-shopping-cart-plus"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- product-item end -->


                        </div>
                    </div>          
                </div>            
            </div>
            <!-- NEW ARRIVAL PRODUCT SECTION END -->

        <!--  Featured category start -->
            <div class="product-tab-section mb-50">
                <div class="container">
                    <div class="row">
                        <div class="section-title text-left mb-40 ml-4">
                            <h4 class="uppercase">Featured Categories</h4>
                            
                        </div>
                    </div>
                    <div class="row">
                        <ul>
                            <li v-for="(category, index) in featuredCategories" :key="index" class="mytab" >
                            <inertia-link :href="`/category/`+category.slug" data-toggle="tab"> <div class="cat_img"> <img :src="parent_url()+`images/category/`+category.image"> </div>  <div class="cat_name">{{category.name}}</div></inertia-link>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <inertia-link  href="/categories" class="button extra-small button-black"> <span class="text-uppercase">More Category</span> </inertia-link>
                        </div>
                    </div>
                </div>
            </div>
            <!--Featured category end-->

        <!-- Start page content -->
        <section id="page-content" class="page-wrapper section plr-185">
          <!-- FEATURED PRODUCT SECTION START -->
            <div class="featured-product-section mb-20">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3">
                            <aside class="widget operating-system box-shadow mb-30">
                                <h6 class="widget-title border-left mb-20 mt-20">Categories</h6>
                                <div v-for="(category, index) in categorys" :key="index">
                                    <div class="single-brand-product">
                                        <inertia-link :href="`/category/`+category.slug"> 
                                            <i class="fa fa-long-arrow-right" style="font-size: x-small;"></i>
                                            {{category.name}}
                                        </inertia-link>
                                    </div>
                                </div>
                            </aside> 

                            <aside class="widget operating-system box-shadow mb-30">
                                <h6 class="widget-title border-left mb-20 mt-20">Brands</h6>
                                <div v-for="data in brands" :key="data.id" class="">
                                    <div class="single-brand-product">
                                        <inertia-link href="javacript:void(0)" @click="shopInBrand(data.id)"> 
                                            <i class="fa fa-long-arrow-right" style="font-size: x-small;"></i>
                                            {{data.title}}
                                        </inertia-link>
                                    </div>
                                </div>
                            </aside>
                        </div>
                        <div class="col-lg-9">
                            <div class="section-title text-left mb-20">
                                <h4 class="uppercase">Featured product</h4>
                            </div>
                            <div class="featured-product">
                                <div class="row " id="">
                                    
                                    <!-- product-item start -->
                                    <div v-for="data in featured_product" :key="data.id" class="col-lg-4" >
                                        <div class="product-item">
                                            <div class="product-img">
                                                <inertia-link :href="'/shop/'+data.slug">
                                                    <!-- <img :src="base_url()+`frontend/img/product/1.jpg`" alt=""> -->
                                                    <span v-for="img in (data.image.split(',').slice(0, 1))" :key="img">
                                                        <img :src="parent_url()+`images/product/`+img" class="img-fluid lazy" >
                                                    </span>
                                                </inertia-link>
                                            </div>
                                            <div class="product-info">
                                                <h6 class="product-title">
                                                    <inertia-link :href="'/shop/'+data.slug"> {{ data.name }}</inertia-link>
                                                </h6>

                                                <span class="detail-price" v-if="data.deal">
                                                        <h3 class="pro-price">BDT {{ data.deal.price }} <del>BDT {{ data.price }}</del></h3>
                                                    </span>
                                                    <span class="detail-price" v-else-if="data.promotion_price && data.promotion == 1">
                                                            <h3 class="pro-price">BDT {{ data.promotion_price }} <del>BDT {{ data.price }}</del></h3>
                                                        </span>

                                                    <span class="detail-price" v-else>
                                                        <h3 class="pro-price">BDT {{ data.price }}</h3>
                                                </span> 
                                                
                                                <ul class="action-button">
                                                    <li>
                                                        <a @click="addOrRemoveWishlist(data.id)" href="javascript:void(0)" title="Wishlist"><i class="zmdi zmdi-favorite"></i></a>
                                                    </li>
                                                    <li>
                                                        <a href="#" data-toggle="modal"  data-target="#productModal" title="Quickview"><i class="zmdi zmdi-zoom-in"></i></a>
                                                    </li>
                                                    <li>
                                                        <a @click="addToCompare(data.id)" href="javascript:void(0)" title="Compare"><i class="zmdi zmdi-refresh"></i></a>
                                                    </li>
                                                    <li>
                                                        <inertia-link v-if="data.is_variant == 1" :href="'/shop/'+data.slug"><i class="zmdi zmdi-shopping-cart-plus"></i> </inertia-link>
                                                        <a v-else @click="addToCart(data.id,1)"  href="#" title="Add to cart"><i class="zmdi zmdi-shopping-cart-plus"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- product-item end -->
                                </div>
                                
                            </div>  
                        </div>
                    </div> 
                </div>            
            </div>
              <!-- FEATURED PRODUCT SECTION END -->
			  
			<div class="col-lg-12 text-center"><inertia-link class="button extra-small button-black" href="/shop"><span class="text-uppercase">All Products</span></inertia-link></div>
			 

           

            <!-- BY BRAND SECTION START-->
            <!-- <div class="by-brand-section mb-80">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title text-left mb-40">
                                <h2 class="uppercase">Shop By Brands</h2>
                                
                            </div>
                        </div>
                    </div>
                    <div class="by-brand-product">
                        <div class="row active-by-brand slick-arrow-2">
                            
                            <div v-for="data in brands" :key="data.id" class="col-lg-6 single-brand-product-parent">
                                <div class="single-brand-product">
                                    <inertia-link href="javacript:void(0)" @click="shopInBrand(data.id)"> 
                                        
                                        <img v-if="data.image" :src="parent_url()+`images/brand/${data.image}`">
                                        <img v-else :src="parent_url()+`images/no-image-available.png`">
                                    </inertia-link>

                                </div>
                            </div>
                            

                        </div>
                    </div>
                </div>
            </div> -->
            <!-- BY BRAND SECTION END -->

          


            <!-- BLOG SECTION START -->
            <div class="blog-section mb-50">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title text-left mb-20">
                                <h4 class="uppercase">Latest blog</h4>
                                
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
                            <!-- blog-item end -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- BLOG SECTION END -->                
        </section>
        <!-- End page content -->    
</div>
</layout>
</template>

<script>
    import Layout from '@/Shared/Layout';
    export default {
      props: ['slider', 'brands', 'new_arrival_product','featuredCategories','featured_product', 'deal_percentage_data','deal_percentage_price','deal_date','deal_price_data', 'deal_price', 'blogs','categorys'],
        components:{
            Layout
        },

        
        methods: {
            showCatMenu(){
               //document.getElementById('navbarToggleExternalContent').classList.add("show");
            },
           
            
            addToCart(product_id,qty){
                // console.log($('meta[name="csrf-token"]').attr('content'));
                this.$inertia.post('/add-to-cart',{product_id:product_id,count:qty,_token: $('meta[name="csrf-token"]').attr('content')},{preserveState: false});
            },
            addOrRemoveWishlist(product_id){
                this.$inertia.post('/add-or-remove-wishlist',{product_id:product_id,_token: $('meta[name="csrf-token"]').attr('content')},{preserveState: false});
            },
            method1: function method1() {
             $("#button0").trigger('click');
            },
            addToCompare(product_id){
                this.$inertia.post('/add-to-compare',{product_id:product_id,_token: $('meta[name="csrf-token"]').attr('content')},{preserveState: false});
            },
            shopInBrand(brands){
			    brands = parseInt(brands);
                this.$inertia.get('/shop',{brands:brands},{preserveState: false});
            },
            myFunction(){
               $('.no_product').closest('.slick-active').hide();
            },

        },
        mounted() {
            this.showCatMenu();
            this.myFunction();
            this.method1();
            
        }
    }
  
</script>






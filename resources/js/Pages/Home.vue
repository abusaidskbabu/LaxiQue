<template>
<layout :categorys2="categorys2">
<div>
        <!-- START SLIDER AREA -->
        <div class="slider-area section mb-50">
            <div class="container-fluid p-0">
                <div class="slider-content">
                    <div class="active-slider-1 slick-arrow-1 slick-dots-1">
                        <!-- layer-1 Start -->
                        <div v-for="data in slider" :key="data.id">
                            <div class="col-lg-12 p-0">
                                <div class="layer-1">
                                    <div class="slider-img">
                                        <img :src="parent_url()+`images/slider/${data.image}`" alt="" >
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

        <!-- category banner start -->
        <div class="featured-product-section mb-50">
            <div class="container">
                <div class="row">
                    <div v-for="fcat in feature_category" :key="fcat.id" class="col-lg-3 col-6 category_banner_imgbox mb-10">
                        <inertia-link :href="`/category/`+fcat.slug" >
                            <img :src="parent_url()+`images/category/`+fcat.image" class="category_banner_img">
                        </inertia-link>
                    </div>
                </div>
            </div>
        </div>
        <!-- category banner end -->

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


        <!-- static banner section start  -->
        <div class="featured-product-section mb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-12 category_banner_imgbox">
                        <a><img :src="parent_url()+`images/slider/${thambnail_image.homepage_banner}`" class="category_banner_img" ></a>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- static banner section start  -->

         <!-- NEW ARRIVAL PRODUCT SECTION START -->
        <div class="featured-product-section mb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title text-left mb-40">
                            <h4 class="uppercase">FLASH SALE</h4>
                            
                        </div>
                    </div>
                </div>
                <div class="featured-product">
                    <div class="row active-featured-product slick-arrow-2" id="myfeatured_product">
                        
                        <!-- product-item start -->
                        <div v-for="product in flash_sale" :key="product.id" class="col-lg-12" >
                            <div class="product-item">
                                <div class="product-img">
                                    <inertia-link :href="'/shop/'+product.slug"> 
                                        <img  v-if="product.image[0]" :src="parent_url()+'images/product/'+product.image[0]" alt="">
                                        <img  v-else :src="'/frontend/static/no_image.jpg'" alt="">
                                    </inertia-link>
                                </div>
                                <div class="product-info">
                                    <h6 class="product-title">
                                        <inertia-link :href="'/shop/'+product.slug">{{product.name}}</inertia-link>
                                        {{product.data}}
                                    </h6>
                                    
                                    <div class="pro-rating">
                                        <a href="#"><i class="zmdi zmdi-star"></i></a>
                                        <a href="#"><i class="zmdi zmdi-star"></i></a>
                                        <a href="#"><i class="zmdi zmdi-star"></i></a>
                                        <a href="#"><i class="zmdi zmdi-star-half"></i></a>
                                        <a href="#"><i class="zmdi zmdi-star-outline"></i></a>
                                    </div>
                                    
                                        <span class="detail-price" v-if="product.deal">
                                        <h3 class="pro-price" v-if="product.deal.price">BDT {{ product.deal.price }}</h3>
                                        <h3 class="pro-price" v-else>BDT {{ product.price*(product.deal.percentage/100)}}</h3>
                                    </span>
                                    <span class="detail-price" v-else-if="product.promotion_price">
                                        <h3 class="pro-price">BDT {{ product.promotion_price }}</h3>
                                    </span>

                                    <span class="detail-price">
                                        <h3 class="pro-price"><del>BDT {{ product.price }} </del></h3>
                                    </span> 


                                    <ul class="action-button">
                                        <li>
                                            <a @click="addOrRemoveWishlist(product.id)" href="#" title="Wishlist"><i class="zmdi zmdi-favorite"></i></a>
                                        </li>
                                        <li>
                                            <a href="#" data-toggle="modal"  data-target="#productModal" title="Quickview"><i class="zmdi zmdi-zoom-in"></i></a>
                                        </li>
                                        <li>
                                            <a @click="addToCompare(product.id)" href="#" title="Compare"><i class="zmdi zmdi-refresh"></i></a>
                                        </li>
                                        <li>
                                            <inertia-link v-if="product.is_variant == 1" :href="'/shop/'+product.slug"><i class="zmdi zmdi-shopping-cart-plus"></i> </inertia-link>
                                            <a v-else @click="addToCart(product.id,1)" href="#"  title="Add to cart"><i class="zmdi zmdi-shopping-cart-plus"></i></a>
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
        <div class="product-tab-section mb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class=" nav-pills mb-3 text-center" id="pills-tab" role="tablist">
                            <li v-for="(fcat, index ) in feature_category" :key="index" class="nav-item" role="presentation">
                                <button class="nav-link" :class="[index ? 0 : 'active bg-lq']" :id="'pills-'+fcat.slug+'-tab'" data-bs-toggle="pill" :data-bs-target="'#'+fcat.slug" type="button" role="tab" :aria-controls="'pills-'+fcat.slug" aria-selected="false">{{fcat.name}}</button>
                            </li>
                        </ul>
                        <div class="tab-content categorytabContent" id="pills-tabContent">
                            <div v-for="(fcat, index ) in feature_category" :key="index" class="tab-pane fade " :class="[index ? 0 : 'show active']" :id="fcat.slug" role="tabpanel" :aria-labelledby="'pills-'+fcat.slug+'-tab'">
                                <div class="row active-blog slick-arrow-2">
                                    <!-- product-item start -->
                                    <div v-for="data in fcat.products" :key="data.id" class="" >
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
                </div>
            </div>
        </div>

        
        <!--  Featured category start -->
            <!-- <div class="product-tab-section mb-50">
                <div class="container">
                    <div class="row">
                        <div class="section-title text-left mb-40 ml-4">
                            <h4 class="uppercase">Featured Categories</h4>
                            
                        </div>
                    </div>
                    <div class="row">
                        <ul>
                            <li v-for="(category, index) in categorys2" :key="index" class="mytab" >
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
            </div> -->
            <!--Featured category end-->

            <!-- BY BRAND SECTION START-->
            <div class="by-brand-section mb-80">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title text-left mb-40">
                                <h4 class="uppercase">Shop By Brands</h4>
                            </div>
                        </div>
                    </div>
                    <div class="by-brand-product">
                        <div class="row active-by-brand slick-arrow-2">
                            <!-- single-brand-product start -->
                            <div v-for="data in brands" :key="data.id" class="col-lg-6 single-brand-product-parent">
                                <div class="single-brand-product">
                                    <inertia-link href="javacript:void(0)" @click="shopInBrand(data.id)"> 
                                        <!-- <img :src="base_url()+`frontend/img/product/5.jpg`" alt="">  -->
                                        <img v-if="data.image" :src="parent_url()+`images/brand/${data.image}`">
                                        <img v-else :src="parent_url()+`images/no-image-available.png`">
                                    </inertia-link>

                                </div>
                            </div>
                            <!-- single-brand-product end -->

                        </div>
                    </div>
                </div>
            </div>
            <!-- BY BRAND SECTION END -->

            <!-- Testimonial section start -->
            <div class="product-tab-section mb-50">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title text-left mb-40">
                                <h4 class="uppercase">Our Happy Clients</h4>
                            </div>
                        </div>
                    </div>
                    <div class="testimonials">
                        <div class="active-team-member slick-arrow-2" >
                            <div v-for="testimonial in testimonials" :key="testimonial.id" class="testimonial-area">
                                <div class="face back-face">
                                    <div class="comment">
                                        <i class="fas fa-quote-left"></i>
                                        {{testimonial.testimonial}}
                                        <i class="fas fa-quote-right"></i>
                                    </div>
                                    
                                    <p class="text-right text-uppercase"><b>- {{ testimonial.name }} -</b></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </layout>
</template>

<script>
    import Layout from '@/Shared/Layout';
    export default {
      props: ['slider', 'brands', 'new_arrival_product','featuredCategories','featured_product', 'deal_percentage_data','deal_percentage_price','deal_date','deal_price_data', 'deal_price', 'blogs','categorys2','flash_sale','feature_category','thambnail_image','testimonials'],
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






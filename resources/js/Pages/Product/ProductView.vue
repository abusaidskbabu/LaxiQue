<template>
<layout>
        <!-- BREADCRUMBS SETCTION START -->
        <div class="breadcrumbs-section plr-200 mb-80 section">
            <div class="breadcrumbs overlay-bg" :style="'background: url('+base_url()+`frontend/img/breadcrumb/1.png` + ')no-repeat;background-position: center;background-size: cover;' ">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="breadcrumbs-inner">
                                <h1 class="breadcrumbs-title">{{product.name}}</h1>
                                <ul class="breadcrumb-list">
                                    <li><inertia-link href="/">home</inertia-link></li>
                                    <li>{{product.name}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- BREADCRUMBS SETCTION END -->

        <!-- Start page content -->
        <section id="page-content" class="page-wrapper section">
            <!-- SHOP SECTION START -->
            <div class="shop-section mb-80">
                <div class="container-fluid plr-185">
                    <div class="row">
                        <div class="col-lg-9">
                            <!-- single-product-area start -->
                            <div class="single-product-area mb-80">
                                <div class="row">
                                    <!-- imgs-zoom-area start -->
                                    <div class="col-lg-5">
                                        <div class="imgs-zoom-area">
                                            <img v-for="images in product_img_array.slice(0, 1)" :key="images" id="zoom_03" :src="parent_url()+'images/product/'+images" :data-zoom-image="parent_url()+'images/product/'+images" alt="">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div id="gallery_01" class="carousel-btn slick-arrow-3 mt-30">
                                                        <div v-for="images in product_img_array" :key="images" class="p-c">
                                                            <a href="#" :data-image="parent_url()+'images/product/'+images" :data-zoom-image="parent_url()+'images/product/'+images">
                                                                <img class="zoom_03" :src="parent_url()+'images/product/'+images"  alt="">
                                                            </a>
                                                        </div>
                                         
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- imgs-zoom-area end -->
                                    <!-- single-product-info start -->
                                    <div class="col-lg-7"> 
                                        <div class="single-product-info">
                                            <h3 class="text-black-1">{{product.name}}</h3>
                                            <div v-if="product.type == 'combo'" class="combo_details pt-3">
                                                <div v-if="product.product_list">
                                                    <h3 class="text-black-1">This Combo Product Contains:</h3>
                                                    <table class="table">
                                                        <tr style="display:block" v-for="(combo,index) in product.product_list" :key="index">
                                                            <td>
                                                            <span v-for="img in (combo.image.split(',').slice(0, 1))" :key="img">
                                                                <img :src="parent_url()+`images/product/`+img" style="width: 35px;" class="lazy" >
                                                            </span>
                                                            </td>
                                                            <td style="width:300px">1x <inertia-link :href="'/shop/'+combo.slug">{{combo.name}}</inertia-link></td>
                                                            <td>Price: BDT {{combo.price}}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div v-else>
                                                <h6 class="brand-name-2">Brand: {{ brand }}</h6>
                                                <h6 class="brand-name-2">Category: {{ category }}</h6>
                                            </div>
                                            <div v-if="product.deal" class="detail-right">
                                                <div class="price">
                                                    <input type="hidden" id="productPrice" :value="product.deal.price">
                                                    <h6><strong>Price:</strong>  <span id="updatedPrice"> BDT {{product.deal.price}} </span> <del class="check-price"> BDT {{product.price}}</del></h6> 
                                                </div>
                                            </div>
                                            <div v-else-if="product.promotion_price && product.promotion == 1" class="detail-right">
                                                <div class="price">
                                                    <input type="hidden" id="productPrice" :value="product.promotion_price">
                                                    <h6><strong>Price:</strong>  <span id="updatedPrice"> BDT {{product.promotion_price}} </span> <del class="check-price"> BDT {{product.price}}</del></h6> 
                                                </div>
                                            </div>
                                            <div v-else class="detail-right">
                                                <div class="price ml-0">
                                                    <input type="hidden" id="productPrice" :value="product.price">
                                                <h6><strong>Price:</strong> <span id="updatedPrice">BDT {{product.price}} </span></h6> 
                                                </div>
                                            </div>
                                            <div class="row product-accordion">
                                                <div class="col-sm-12">
                                                <div v-if="product.qty > 0">
                                                    <div v-if="size.length > 0" class="size_section">
                                                        <h5 class="product-title">Select Size </h5>
                                                        <ul> 
                                                            <li v-for="size in size" :key="size"> 
                                                                <input :data-qty-available="size.qty" v-model="variant_id" type="radio" name="size" :value="size.variant_id" :data-add-price="size.additional_price" class="size"> <div class="varient_text">{{ size.variant_name }}</div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    
                                                    <div v-if="color.length > 0" class="size_section">
                                                        <h5 class="product-title">Select Color</h5>
                                                        <ul> 
                                                            <li  v-for="color in color" :key="color">
                                                                <input :data-qty-available="color.qty" v-model="variant_id" type="radio" name="color" :value="color.variant_id" :data-add-price="color.additional_price" class="size"> <div class="varient_text">{{ color.variant_name }}</div>
                                                            </li> 
                                                        </ul>
                                                    </div>
                                                    <div v-if="weight.length > 0" class="size_section">
                                                        <h5 class="product-title">Select weight</h5>
                                                        <ul> 
                                                            <li  v-for="weight in weight" :key="weight">
                                                                <input :data-qty-available="weight.qty" v-model="variant_id" type="radio" name="weight" :value="weight.variant_id" :data-add-price="weight.additional_price" class="size"> <div class="varient_text">{{ weight.variant_name }}</div>
                                                            </li> 
                                                        </ul>
                                                    </div>
                                                </div>   

                                                <div v-if="product.qty > 0 || product.allow_backorder == 1">
                                                    <h3 class="text-black-1">Quantity</h3>
                                                    <p class="in_stock" v-if="product.qty > 0" style="margin:0;"><span id="dynamic_qty">{{product.qty}}</span> Product(s) Available</p>
                                                    <h4 class="text-danger mt-2 mb-2 out_of_stock">Out of Stock</h4>
                                                </div>
                                                <div v-else>
                                                    <h4 class="text-danger mt-2 mb-2">Out of Stock</h4>
                                                </div>
 
                                                <div v-if="product.qty > 0 || product.allow_backorder == 1" class="pb-3 pt-2">
                                                    <input type="number" ref="qty" name="qty" min="1" value="1" style="width: 90px;text-align: center;">
                                                </div>
                                                
                                                <div class="product-buttons">
                                                    <a v-if="product.qty > 0 || product.allow_backorder == 1" @click="addToCart(product.id)" class="button extra-small button-black to-cart" tabindex="-1"><span class="text-uppercase">Add to Cart</span></a>
													
													<a v-if="product.qty < 1 && restock.allow_backorder == 0 && restock.already_requested == 0" @click="requestForRestock(product.id)" class="button extra-small button-black mt-2" tabindex="-1"><span class="text-uppercase">Request for Restock</span></a>
													
                                                    <a @click="addOrRemoveWishlist(product.id)" class="button extra-small button-black mt-2" tabindex="-1"><span class="text-uppercase">Add To WishList</span></a>
                                                </div>
                                                </div>
                                            </div>
                                        </div>    
                                    </div>
                                    <!-- single-product-info end -->
                                </div>
								<!-- single-product-tab -->
								<div class="row">
									<div class="col-lg-12">
										<!-- hr -->
										<hr>
										<div class="single-product-tab reviews-tab">
											<ul class="nav mb-20">
												<li><a class="active" href="#description" data-toggle="tab">description</a></li>
												<li ><a href="#information" data-toggle="tab">Specification</a></li>
												<li ><a href="#reviews" data-toggle="tab">reviews</a></li>
											</ul>
											<div class="tab-content">
												<div role="tabpanel" class="tab-pane active show" id="description">
													<div v-html="product.product_details"></div>
                                                </div>
												
												<div role="tabpanel" class="tab-pane" id="information">
													<div v-html="product.product_specification"></div>
												</div>
												
												<div role="tabpanel" class="tab-pane" id="reviews">
													<div class="reviews-tab-desc">
                                                       <!--Review Start-->
                                                        <div v-if="reviews" class="review_list">
                                                            <p class="review_title">Recent Reviews</p>
                                                            <div class="review-block">
                                                                <div v-for="(review,index) in reviews" :key="index" class="row">
                                                                    <div class="col-sm-1">
                                                                        <div class="review-block-name serial_rev"><span>{{index+1}}</span></div>
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <div class="review-block-name">{{review.user_name}}</div>
                                                                        <div class="review-block-date">{{review.created_at}}</div>
                                                                    </div>
                                                                    <div class="col-sm-9">
                                                                        <div class="review-block-rate">
                                                                            <span v-for="index in review.rate" :key="index"  aria-label="Left Align">
                                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                                            </span>
                                                                            <span v-for="index in (5-review.rate)" :key="index"  aria-label="Left Align">
                                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                                            </span>

                                                                        </div>
                                                                        <div class="review-block-description">{{review.testimonial}}</div>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                            </div>
                                                        </div>
                                                        <div v-if="$page.props.user.name">
                                                            <div v-if="is_reviewable">
                                                                <p class="review_title">Add a Review</p>
                                                                <form  @submit.prevent="reviewSubmit" class="theme-form product_details">
                                                                    <div class="form-row">
                                                                        <div class="col-md-12">
                                                                        <h5 class="m-3">Rating</h5>
                                                                            <div class="media review_rate mt-1">
                                                                                <div class="media-body ml-3">
                                                                                        <ul>
                                                                                            <input v-model="form.rate" type="radio" name="star" value="5" required="">
                                                                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                                                            </ul>
                                                                                            <ul>
                                                                                            <input v-model="form.rate" type="radio" name="star" value="4" required="">
                                                                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                                                            </ul>
                                                                                            <ul>
                                                                                            <input v-model="form.rate" type="radio" name="star" value="3" required="">
                                                                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                                                            </ul>
                                                                                            <ul>
                                                                                            <input v-model="form.rate" type="radio" name="star" value="2" required="">
                                                                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                                                            </ul>
                                                                                            <ul>
                                                                                            <input v-model="form.rate" type="radio" name="star" value="1" required="">
                                                                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                                                        </ul>
                                                                                    </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 mt-2">
                                                                            <textarea v-model="form.testimonial" class="form-control" name="testimonial" placeholder="Wrire Your Testimonial Here" id="exampleFormControlTextarea1" required rows="3"></textarea>
                                                                            <input type="hidden" name="product_id" v-model="form.product_id">
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <button class="btn btn-normal" type="submit">Submit Your Review</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div v-else><h4 style="padding: 50px 15px;">You have to purchase at least one item of this product to share your review.</h4></div>

                                                        </div>
                                                        <div v-else><h4 style="padding: 50px 15px;">You have to login first to review this product.</h4></div>
                                                        <!--Review end-->
													</div>
												</div>
											</div>
										</div>
										<!--  hr -->
										<hr>
									</div>
								</div>
                            </div>
                            <!-- single-product-area end -->
                            <div class="related-product-area">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="section-title text-left mb-40">
                                            <h2 class="uppercase">related product</h2>
                                            <h6>There are many variations of passages of brands available,</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="active-related-product">
                                    <!-- product-item start -->
                                    <div v-for="product in related_product" :key="product" class="col-lg-12">
                                        <div class="product-item">
                                            <div class="product-img">
                                                <inertia-link :href="'/shop/'+product.slug">
                                                    <img v-if="product.image[0]" :src="parent_url()+'images/product/'+product.image[0]" alt="">
                                                    <img v-else :src="'/frontend/static/no_image.jpg'" alt="">
                                                </inertia-link>
                                            </div>
                                            <div class="product-info">
                                                <h6 class="product-title">
                                                    <inertia-link :href="'/shop/'+product.slug"> {{product.name}} </inertia-link>
                                                </h6>


                                                <span class="detail-price" v-if="product.deal">
                                                    <h3 class="pro-price" v-if="product.deal.price">BDT {{ product.deal.price }}</h3>
                                                    <h3 class="pro-price" v-else>BDT {{ product.price*(product.deal.percentage/100)}}</h3>
                                                </span>
                                                <span class="detail-price" v-else-if="product.promotion_price && product.promotion == 1">
                                                    <h3 class="pro-price">BDT {{ product.promotion_price }}</h3>
                                                </span>
                                                <span class="detail-price" v-else>
                                                    <h3 class="pro-price">BDT {{ product.price }}</h3>
                                                </span>
                                                <ul class="action-button">
                                                    <li>
                                                        <a @click="addOrRemoveWishlist(product.id)" title="Wishlist"><i class="zmdi zmdi-favorite"></i></a>
                                                    </li>
                     
                                                    <li>
                                                        <a @click="addToCompare(product.id)" title="Compare"><i class="zmdi zmdi-refresh"></i></a>
                                                    </li>
                                                    <li>
                                                        <inertia-link v-if="product.is_variant == 1" :href="'/shop/'+product.slug"><i class="zmdi zmdi-shopping-cart-plus"></i> </inertia-link>
                                                        <a v-else @click="addToCart(product.id,1)"  title="Add to cart"><i class="zmdi zmdi-shopping-cart-plus"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- product-item end -->
                                  
                                </div>
                            </div>
                        </div>



                        <div class="col-lg-3 order-lg-1 order-2">
                     
                                <!-- featued-item start -->
                                <aside class="widget widget-product box-shadow">
                                    <h6 class="widget-title border-left mb-20">featued products</h6>
                                    <div v-for="(product, index) in featuedProducts.slice(0, 3)" :key="index" class="product-item">
                                        <div class="product-img">
                                            <inertia-link :href="'/shop/'+product.slug">
                                                <img v-if="product.image[0]" :src="parent_url()+'images/product/'+product.image[0]" alt="">
                                                <img v-else src="/frontend/images/product-sidebar/002.jpg" alt="">
                                            </inertia-link>
                                        </div>
                                        <div class="product-info">
                                            <h6 class="product-title">
                                                <inertia-link :href="'/shop/'+product.slug">{{product.name}}</inertia-link>
                                            </h6>
                                            <h3 v-if="product.deal" class="pro-price">${{product.deal}}</h3>
                                            <h3 v-else-if="product.promotion_price && product.promotion == 1" class="pro-price">BDT {{product.promotion_price}}</h3>
                                            <h3 v-else class="pro-price"> BDT {{product.price}}</h3>
                                        </div>
                                    </div>
                                </aside>
                                <!-- featued-item end --> 

                                <!-- recent-item start -->
                                <aside class="widget widget-product box-shadow mt-3">
                                    <h6 class="widget-title border-left mb-20">recent products</h6>
                                    <div v-for="(product, index) in newproducts.slice(0, 3)" :key="index" class="product-item">
                                        <div class="product-img">
                                            <inertia-link :href="'/shop/'+product.slug">
                                                <img class="img-fluid" v-if="product.image[0]" :src="parent_url()+'images/product/'+product.image[0]" alt="">
                                                <img class="img-fluid" v-else src="/frontend/images/product-sidebar/002.jpg" alt="">
                                            </inertia-link>
                                        </div>
                                        <div class="product-info">
                                            <h6 class="product-title">
                                                <inertia-link :href="'/shop/'+product.slug">{{product.name}}</inertia-link>
                                            </h6>
                                            <h3 v-if="product.deal" class="pro-price">${{product.deal}}</h3>
                                            <h3 v-else-if="product.promotion_price && product.promotion == 1" class="pro-price">${{product.promotion_price}}</h3>
                                            <h3 v-else class="pro-price"> BDT {{product.price}}</h3>
                                        </div>
                                    </div>
                                </aside>
                                <!-- recent-item end -->  
                        </div>
    
                    </div>
                </div>
            </div>
            <!-- SHOP SECTION END -->             

        </section>
        <!-- End page content -->
</layout>
</template>
<style scoped>
.product-title {
    text-transform: capitalize !important;
}
.pro-rating {
    margin-bottom: 0px !important;
}
.brand-name-2 {
    font-size: 13px !important;
    padding-top: 5px !important;
}
.qty-box{
        display: inline-block;
    margin: 20px 0px;
}
.size_section{
    margin: 10px 0px;
}
.size_section input[type="radio"]{
    height: 22px;
    width: 16px;
    float: left;
    margin-right: 5px;
}
.size_section ul li {
margin: 0px 10px 0px 0px;
width: 130px;
display: inline-block;
}

.detail-right h6 {
    font-size: 17px;
    margin-bottom: 0;
    font-weight: 400;
    color: #000;
    margin: 10px 0px;
}

.slider-nav img{
    height: 120px !important;
    object-fit: contain;
}
#big_slide img{
    height: 400px;
    object-fit: contain;
}
.product .product-box .product-detail .icon-detail a i {
    padding: 0;
    border: none;
}
</style>
<script>
    import Layout from '@/Shared/Layout';
    export default {
        props: ['product', 'size', 'color', 'weight', 'product_img_array', 'related_product','is_reviewable','reviews','brand','category','brands','newproducts', 'featuedProducts','restock'],
        components:{
            Layout
        },
        data(){
            return{
                variant_id:'',
                form:{
                    rate: '',
                    testimonial: '',
                    product_id: this.product.id
                },

                searchForm:{
                    s:'',
                },
                fitlerForm:{
                    brands:[],
                    price:[],
                },
                object:[], 
                limit: 10

            }
        },
        methods: {
            addToCart(product_id){
                let qty = this.$refs.qty.value;
                this.$inertia.post('/add-to-cart',{product_id:product_id,count:qty, variant_id:this.variant_id},{preserveState: false});
            },
            addOrRemoveWishlist(product_id){
                this.$inertia.post('/add-or-remove-wishlist',{product_id:product_id},{preserveState: false});
            },
			requestForRestock(product_id){
                this.$inertia.post('/request-for-restock',{product_id:product_id},{preserveState: false});
            },
            addToCompare(product_id){
                this.$inertia.post('/add-to-compare',{product_id:product_id},{preserveState: false});
            },
            reviewSubmit(){
                this.$inertia.post('/review-submit',this.form,{preserveState: false});
            },
            filterSubmit(){
                this.$inertia.post('/shop',this.fitlerForm,{preserveState: false});
            },
            searchSubmit(){
                this.$inertia.post('/shop', this.searchForm).then(()=>{}); 
            }
        },
        computed:{
            computedObj(){
                return this.limit ? this.brands.slice(0,this.limit) : this.brands
            }
        }
        
    }
</script>



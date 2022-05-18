<template>
<layout :categorys2="categorys2">
    <div>
 
        <!-- Start page content -->
        <div id="page-content" class="page-wrapper section">

            <!-- SHOP SECTION START -->
            <div class="shop-section mb-80">
                <div class="container-fluid plr-200">
                   <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title text-left mb-40">
                                <h2 v-if="category_name"> Category: {{ category_name }} </h2>
                                <h2 v-else class="uppercase">Speacial Deals</h2>
                                <h6>Choose your desired products from speacial deals!</h6>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 order-lg-2 order-1">
                            <div class="shop-content">
                                <!-- shop-option start -->
                             
                                <!-- shop-option end -->
                                <!-- Tab Content start -->
                                <div class="tab-content">
                                    <!-- grid-view -->
                                    <div id="grid-view" class="tab-pane active show" role="tabpanel">
                                        <div class="row">

                                            <!-- product-item start -->
                                            <div v-for="product in products.data" :key="product" class="col-lg-3 col-md-3">
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
                                <!-- Tab Content end -->
                                <!-- shop-pagination start -->
                                
                                <ul class="shop-pagination  text-center ptblr-10-30">
                                    <Paginator :paginator="products" />
                                </ul>
                                <!-- shop-pagination end -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- SHOP SECTION END -->             

        </div>
        <!-- End page content -->

    </div>
</layout>
</template>

<style scoped>


#product_shop_page .product-imgbox{
    height: 320px !important;
}

</style>


<script>
    import Layout from '@/Shared/Layout';
    import Paginator from "@/Components/Paginator";
    import FilterBar from "@/Pages/Product/FilterBar";
    
    export default {
        props: ['products','brands','newproducts', 'featuedProducts','category_name','search_query','categorys2'],
        components:{
            Layout,Paginator,FilterBar
        },
        methods: {
            addToCart(product_id,qty){
                this.$inertia.post('/add-to-cart',{product_id:product_id,count:qty},{preserveState: false});
            },
            addOrRemoveWishlist(product_id){
                this.$inertia.post('/add-or-remove-wishlist',{product_id:product_id},{preserveState: false});
            },
            addToCompare(product_id){
                this.$inertia.post('/add-to-compare',{product_id:product_id},{preserveState: false});
            },
            allProduct(){
                this.$inertia.post('/shop',{allProduct:1},{preserveState: false});
            },
            filterbyproduct(){
                this.$inertia.post('/shop',{filterbyproduct: $("#filterbyproduct").val()},{preserveState: false});
            }

        }
        
    }
</script>

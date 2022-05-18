<template>
<layout :categorys2="categorys2">
    <div>
        <!-- BREADCRUMBS SETCTION START -->
        <div v-if="category_name" class="breadcrumbs-section plr-200 mb-80 section">
            <div class="breadcrumbs overlay-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="breadcrumbs-inner">
                                <h1 class="breadcrumbs-title">{{category_name}}</h1>
                                <ul class="breadcrumb-list">
                                    <li><inertia-link href="/">home</inertia-link></li>
                                    <li>{{category_name}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- BREADCRUMBS SETCTION END -->

        
        <!-- Search Start -->
        <div v-if="search_query" class="breadcrumb-main search_result">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="breadcrumb-contain">
                            <div>
                                <h2>Search Results for: "{{search_query}}"</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <!-- Search end -->

        <!-- Start page content -->
        <div id="page-content" class="page-wrapper section pt-3 plr-185">

            <!-- SHOP SECTION START -->
            <div class="shop-section mb-80">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-9 order-lg-2 order-1">
                            <div class="shop-content">

                                <!-- Tab Content start -->
                                <div class="tab-content">
                                    <!-- grid-view -->
                                    <div id="grid-view" class="tab-pane active show" role="tabpanel">
                                        <div v-if="Object.keys(products.data).length != 0" class="row">
                                            <!-- product-item start -->
                                            <div v-for="product in products.data" :key="product" class="col-lg-3 col-md-4">
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
                                                        
                                                      
                                                         <span class="detail-price" v-if="product.deal">
                                                            <h3 class="pro-price">BDT {{ product.deal.price }} <del>BDT {{ product.price }}</del></h3>
                                                        </span>
														   <span class="detail-price" v-else-if="product.promotion_price && product.promotion == 1">
																<h3 class="pro-price">BDT {{ product.promotion_price }} <del>BDT {{ product.price }}</del></h3>
															</span>

                                                        <span class="detail-price" v-else>
                                                            <h3 class="pro-price">BDT {{ product.price }}</h3>
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
                                                <!-- <ProductModal :product="product" /> -->
                                            </div>
                                            <!-- product-item end -->
                                        </div>
                                        <div v-else class="mt-5">
                                           <p class="text-center"> <img style="max-width: 100%;" src="/frontend/img/product/no_item2.png"></p>
                                        </div>                                  
                                    </div>
                                </div>
                                <!-- Tab Content end -->
                                <!-- shop-pagination start -->
                                
                                <ul class="shop-pagination text-center ptblr-10-30">
                                    <Paginator :paginator="products" />
                                </ul>
                                <!-- shop-pagination end -->
                            </div>
                        </div>
                        <!--Filter start-->
                        <FilterBar :brands="brands" :newproducts="newproducts" :featuedProducts="featuedProducts" :categories="categories"/>
                         <!--Filter start-->
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
    import ProductModal from "@/Pages/Product/ProductModal";
    
    export default {
        props: ['products','brands','newproducts', 'featuedProducts','categories','search_query','categorys2'],
        components:{
            Layout,Paginator,FilterBar,ProductModal
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
            }

        }
        
    }
</script>

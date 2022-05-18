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
                                <h1 class="breadcrumbs-title">compare</h1>
                                <ul class="breadcrumb-list">
                                    <li><inertia-link href="/">home</inertia-link></li>
                                    <li>Compare</li>
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
                <div class="container">
                    <div class="row">

                        <div class="col-lg-2">
                            <ul class="cart-tab">
                                <li>
                                    <inertia-link href="/cart" class="active" data-toggle="tab">
                                        <span>{{$page.props.cart_count ?? 0}}</span> cart
                                    </inertia-link>
                                </li>
                                <li>
                                    <inertia-link href="/dashboard/my-wishlist" class="active" data-toggle="tab">
                                        <span>{{$page.props.wishlist.length ?? 0}}</span>  Wishlist
                                    </inertia-link>
                                </li>
                                 <li>
                                    <inertia-link href="/compare" class="active" data-toggle="tab">
                                        <span>{{$page.props.compare_count ?? 0}}</span> Compare List
                                    </inertia-link>
                                </li>
                                <li>
                                    <inertia-link href="/checkout" class="active" data-toggle="tab">
                                        <span>{{$page.props.cart_count ?? 0}}</span> checkout
                                    </inertia-link>
                                </li>
                            </ul>
                        </div>


                        <div class="col-lg-10">
                            <!-- Tab panes -->
                            <div class="tab-content">
                          
                                <!-- wishlist start -->
                                <div class="tab-pane active" id="wishlist">
                                    <div class="wishlist-content">
                                        <form action="#">
                                            <div class="table-content table-responsive mb-50">
                                                <table class="text-center">
                                                    <thead>
                                                        <tr>
                                                            <th class="product-thumbnail">product</th>
                                                            <th class="product-price">price</th>
                                                            <th class="product-stock">Stock status</th>
                                                            <th class="product-add-cart">add to cart</th>
                                                            <th class="product-remove">remove</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="mycompare">

                                                        <tr v-for="product in $page.props.compare.products" :key="product">
                                                            <td class="product-thumbnail">
                                                                
                                                                <div class="pro-thumbnail-img">
                                                                    <inertia-link :href="product.slug">
                                                                        <img :src="product.image" alt="">
                                                                     </inertia-link>
                                                                </div>
                                                                
                                                                <div class="pro-thumbnail-info text-left">
                                                                    <h6 class="product-title-2">
                                                                        <inertia-link :href="product.slug"> {{product.product_name}}</inertia-link>
                                                                    </h6>
                                                                    <p>Brand: {{product.brand}}</p>
                                                                    <p>Category: {{product.category}}</p>
                                                                </div>
                                                            </td>
                                                            <td class="product-price">BDT {{product.price}}</td>

                                                            <td v-if="qty > 0" class="product-stock text-uppercase">in stock</td>
                                                            <td v-else class="product-stock text-uppercase text-danger">Out of stock</td>

                                                            <td class="product-add-cart">
                                                                <inertia-link v-if="product.is_variant == 1" :href="'/shop/'+product.slug"><i class="zmdi zmdi-shopping-cart-plus"></i> </inertia-link>
                                                                <a v-else @click="addToCart(product.product_id,1)"  title="Add to cart"><i class="zmdi zmdi-shopping-cart-plus"></i></a>
                                                            </td>
                                                            <td class="product-remove">
                                                                <a @click="removeCompareItem(product.product_id)" href="#"><i class="zmdi zmdi-close"></i></a>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- wishlist end -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- SHOP SECTION END -->             

        </section>
        <!-- End page content -->
</div>
</layout>
</template>
<style scoped>
.remove-compare{
    padding: 4px 10px  !important;
    color: #fff;
    cursor: pointer;
    text-align: center;
    margin: 0 auto;
    background: #bf8305 !important;
}
.custom{
    background-color: #f1b63a !important;
    margin-left: 5px;
}
</style>
<script>
    import Layout from '@/Shared/Layout';
    export default {
        props: ['compare_count','categorys2'],
        components:{
            Layout
        },
        methods: {
            addToCart(product_id,qty){
                this.$inertia.post('/add-to-cart',{product_id:product_id,count:qty},{preserveState: false});
            },
            addOrRemoveWishlist(product_id){
                this.$inertia.post('/add-or-remove-wishlist',{product_id:product_id},{preserveState: false});
            },
            removeCompareItem(product_id){
               this.$inertia.post('/remove-from-compare', {product_id:product_id},{preserveState: false})
            }            
        }
        
    }
</script>

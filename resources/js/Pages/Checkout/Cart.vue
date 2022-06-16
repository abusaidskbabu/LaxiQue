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
                            <h1 class="breadcrumbs-title">Shopping Cart</h1>
                            <ul class="breadcrumb-list">
                                <li><inertia-link href="/">Home</inertia-link></li>
                                <li>Shopping Cart</li>
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
        <div v-if="$page.props.cart" class="shop-section mb-80">
            <div class="container-fluid plr-185">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <!-- shopping-cart start -->
                            <div class="tab-pane active" id="shopping-cart">
                                <div class="shopping-cart-content">
                                    <form action="#">
                                        <div class="table-content table-responsive mb-50">
                                            <table class="text-center">
                                                <thead>
                                                    <tr>
                                                        <th class="product-thumbnail">product</th>
                                                        <th class="product-price">price</th>
                                                        <th class="product-quantity">Quantity</th>
                                                        <th class="product-subtotal">total</th>
                                                        <th class="product-remove">remove</th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody v-if="$page.props.cart_count" id="mycompare">
                                                    <!-- tr -->
                                                    <tr v-for="(item, index) in $page.props.cart.product" :key="item">
                                                        <td class="product-thumbnail">
                                                            <div class="pro-thumbnail-img">
                                                                <img :src="parent_url()+item.image" alt="">
                                                            </div>
                                                            <div class="pro-thumbnail-info text-left">
                                                                <h6 class="product-title-2">
                                                                    <inertia-link :href="'/shop/'+item.slug">{{item.name}}</inertia-link>
                                                                </h6>
                                                                <p>Brand: {{item.brand}}</p>
                                                                <p v-if="item.is_variant == 1">Variant: {{item.variant_name}}</p>
                                                            </div>
                                                        </td>
                                                        <td v-if="item.deal" class="product-price">BDT {{item.price}}</td>
                                                        <td v-else-if="item.promotion_price" class="product-price">BDT {{item.price}}</td>
                                                        <td v-else class="product-price">BDT {{item.price}}</td>
                                                            <td class="product-quantity">
                                                                <input type="number" min="1" name="quantity" ref="product_ids" :value="item.count" @change="addToCart(item.product_id)" style="width: 90px;text-align: center;">
                                                            </td>
                                                        
                                                        <td v-if="item.deal" class="product-subtotal">{{item.count * item.price}}</td>
                                                        <td v-else-if="item.promotion_price" class="product-subtotal">{{item.count * item.promotion_price}}</td>
                                                        <td v-else class="product-subtotal">BDT {{item.count * item.price}}</td>

                                                        <td class="product-remove">
                                                            <a @click="removeCartItem(index)" href="#"><i class="zmdi zmdi-close"></i></a>
                                                        </td>
                                                    </tr>
                                                    
                                                    <!-- tr -->
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="coupon-discount box-shadow p-30 mb-50">
                                                    <h6 class="widget-title border-left mb-20">coupon discount</h6>
                                                    <p>Enter your coupon code if you have one!</p>
                                                    <input type="text" name="name" placeholder="Enter your code here.">
                                                    <button class="submit-btn-1 black-bg btn-hover-2" type="submit">apply coupon</button>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="payment-details box-shadow p-30 mb-50">
                                                    <h6 class="widget-title border-left mb-20">payment details</h6>
                                                    <table>
                                                        <tr>
                                                            <td class="td-title-1">Cart Subtotal</td>
                                                            <span v-if="$page.props.cart_count">
                                                                <td class="td-title-2">BDT {{$page.props.cart.sub_total}}</td>
                                                            </span>
                                                            <span v-else>
                                                                <td class="td-title-2">0</td>
                                                            </span>

                                                        </tr>
                                                        <tr>
                                                            <td class="td-title-1"></td>
                                                            <td class="text-right">  <inertia-link href="/checkout" alt="checkout"> <button class="submit-btn-1 bg-lq btn-hover-2" type="submit">Checkout</button> </inertia-link></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!--
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="culculate-shipping box-shadow p-30">
                                                    <h6 class="widget-title border-left mb-20">culculate shipping</h6>
                                                    <p>Enter your coupon code if you have one!</p>
                                                    <div class="row">
                                                        <div class="col-sm-4 col-xs-12">
                                                            <input type="text"  placeholder="Country">
                                                        </div>
                                                        <div class="col-sm-4 col-xs-12">
                                                            <input type="text"  placeholder="Region / State">
                                                        </div>
                                                        <div class="col-sm-4 col-xs-12">
                                                            <input type="text"  placeholder="Post code">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <button class="submit-btn-1 black-bg btn-hover-2">get a quote</button>   
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        -->
                                    </form>
                                </div>
                            </div>
                            <!-- shopping-cart end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="shop-section mt-80 mb-80 text-center">
            <div class="container text-center">
                <h2>No item in cart. <inertia-link href="/shop" style="color:green;">Continue</inertia-link> shopping.</h2>
            </div>
        </div>
        <!-- SHOP SECTION END -->             

    </section>
    <!-- End page content -->


</div>
</layout>
</template>

<script>
    import Layout from '@/Shared/Layout';
    export default {

        props: ['cat','categorys2'],
        components:{
            Layout
        },
        methods: {
            addToCart(product_id){
                let qty = this.$refs.product_ids.value;
                this.$inertia.post('/add-to-cart',{product_id:product_id,count:qty});
            },
             removeCartItem(product_id){
               this.$inertia.post('/remove-from-cart', {product_id:product_id})
            }
        }
    }
</script>
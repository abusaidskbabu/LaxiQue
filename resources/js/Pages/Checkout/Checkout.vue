<template>
<layout :categorys2="categorys2">
        <!-- BREADCRUMBS SETCTION START -->
        <div class="breadcrumbs-section plr-200 mb-80 section">
            <div class="breadcrumbs overlay-bg" :style="'background: url('+base_url()+`frontend/img/breadcrumb/1.png` + ')no-repeat;background-position: center;background-size: cover;' ">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="breadcrumbs-inner">
                                <h1 class="breadcrumbs-title">Checkout</h1>
                                <ul class="breadcrumb-list">
                                    <li><inertia-link href="/">Home</inertia-link></li>
                                    <li>Checkout</li>
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
            <div v-if="total_items > 0" class="shop-section mb-80">
                <div class="container-fluid plr-185">
                    <div class="row">

                        <div class="col-md-12">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!-- checkout start -->
                                <div class="tab-pane active" id="checkout">
                                    <div class="checkout-content box-shadow p-30">
                                        <form method="post" action="/pay">
                                            <div class="row">
                                                <!-- billing details -->
                                                <div class="col-md-6">
                                                    <div class="billing-details pr-10">
                                                        <h6 class="widget-title border-left mb-20">billing details</h6>
                                                        <input type="text"  name="name" v-model="checkoutForm.name" placeholder="Full Name.." required >
                                                        <input type="text"  name="phone_number" v-model="checkoutForm.phone_number" placeholder="Moblile Number.." required>
                                                        <input type="email" name="email" v-model="checkoutForm.email" placeholder="Email.." >
														<input type="text" name="postal_code" v-model="checkoutForm.postal_code" placeholder="Postal Code.." required>
														<input type="text" name="city" v-model="checkoutForm.city" placeholder="Thana.." required>
														<input type="text" name="district" v-model="checkoutForm.district" placeholder="District.." required>
														<input type="text" name="state" v-model="checkoutForm.state" placeholder="Division.." required>
														<textarea type="text" name="address" v-model="checkoutForm.address" required placeholder="Full Address.." class="custom-textarea"></textarea>
                                                        <textarea type="text" name="notes" v-model="checkoutForm.notes" placeholder="Write order note here.." class="custom-textarea"></textarea>
                                                        
                                                        <input type="checkbox" name="account" v-model="checkoutForm.account" id="shipping-option"> &ensp;
                                                        <label for="account-option">Want to ship in different address?</label>


                                                        <div class="shipping_details">
                                                            <label style="color:#000;font-size:16px; padding-bottom:5px;">Shipping Details</label>
															<input type="text"  name="shipping_name" v-model="checkoutForm.shipping_name" placeholder="Full Name.."  >
															<input type="text"  name="shipping_phone_number" v-model="checkoutForm.shipping_phone_number" placeholder="Moblile Number.." >
                                                            <input type="text" name="shipping_postal_code" v-model="checkoutForm.shipping_postal_code" placeholder="Shipping Postal Code..">
															<input type="text" name="shipping_city" v-model="checkoutForm.shipping_city" placeholder="Shipping Thana..">
															<input type="text" name="shipping_district" v-model="checkoutForm.shipping_district" placeholder="Shipping District..">
															 <input type="text" name="shipping_state" v-model="checkoutForm.shipping_state" placeholder="Shipping Division..">
                                                            <textarea type="text" name="shipping_address" v-model="checkoutForm.shipping_address" placeholder="Your specific address.." class="custom-textarea">
                                                            </textarea>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <!-- our order -->
                                                    <div class="payment-details pl-10 mb-50">
                                                        <h6 class="widget-title border-left mb-20">Your order</h6>
														
						
                                                        <table>

                                                            <tr v-for="(item,index) in $page.props.cart.product" :key="item"> 
                                                                <td class="td-title-1"><b style="font-size: 22px;vertical-align: bottom;">&#8226;</b> {{item.name}} x {{item.count}}</td>
                                                                <td v-if="item.deal" class="td-title-2">BDT {{item.count * item.deal}}</td>
                                                                <td v-else-if="item.promotion_price" class="td-title-2">BDT {{item.count * item.promotion_price}}</td>
                                                                <td v-else class="td-title-2">BDT {{item.count * item.price}}</td>
                                                            </tr>
                                      

                                                            <tr>
                                                                <td class="td-title-1"><b>Cart Subtotal</b></td>
                                                                <td class="td-title-2">BDT {{$page.props.cart.sub_total}}</td>
                                                            </tr>
															
															<tr v-if="couponCart">
                                                                <td id="dataCouponValue" :data-coupon-value="couponCart.value" class="td-title-1"><b>Coupon Discount</b> (Code: {{couponCart.code}}) <p> <a class="btn btn-dark" href="javascript:void(0)" @click="removeCoupon()">Remove Coupon</a> </p></td>
                                                                <td class="td-title-2">BDT {{couponCart.value}}</td>
                                                            </tr>
															
															
                                                            <tr>
                                                                <td class="td-title-1 "><b>Shipping Charge</b></td>
                                                                <td class="td-title-2 ship_cost_text">

																	
																	<div  v-if="$page.props.free_shipping == '1'" class="shipping">
																		<div class="shopping-option">
                                                                            <input type="radio" name="shipping_charge" :data-price="$page.props.cart.sub_total" class="shipping_charge" data-charge="0" v-model="checkoutForm.shipping_charge" value="freeshipping" required checked>
                                                                            <label for="local-pickup">Free Shipping BDT 0</label>
                                                                        </div>
                                                                    </div>
																	
																	<div v-else class="shipping">
                                                                        <div class="shopping-option">
                                                                            <input type="radio" name="shipping_charge" :data-price="$page.props.cart.sub_total" class="shipping_charge trigger_gd1" data-charge="50" v-model="checkoutForm.shipping_charge"  value="insidedhaka" required>
                                                                            <label for="free-shipping">Inside Dhaka BDT 50 </label>
                                                                        </div>
                                                                        <div class="shopping-option">
                                                                            <input type="radio" name="shipping_charge" :data-price="$page.props.cart.sub_total" class="shipping_charge trigger_gd" data-charge="100" v-model="checkoutForm.shipping_charge" value="outsidedhaka" required>
                                                                            <label for="local-pickup">Outside Dhaka BDT 100</label>
                                                                        </div>
																		<div v-if="$page.props.free_shipping == '1'" class="shopping-option">
                                                                            <input type="radio" name="shipping_charge" :data-price="$page.props.cart.free_shipping" class="shipping_charge" data-charge="0" v-model="checkoutForm.shipping_charge" value="freeshipping" required>
                                                                            <label for="local-pickup">Free Shipping BDT 0</label>
                                                                        </div>
																		
                                                                    </div>
																	
																	
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="order-total"><b>Order Total</b></td>
																
                                                                <td v-if="couponCart" class="order-total-price" id="checkout_total">BDT {{$page.props.cart.sub_total - couponCart.value}}</td>
                                                                <td v-else class="order-total-price" id="checkout_total">BDT {{$page.props.cart.sub_total}}</td>
                                                            </tr>
                                                        </table>
                                                    </div> 
                                                    <!-- payment-method -->
                                                    <div class="payment-method ml-3">
                                                        <h6 class="widget-title border-left mb-20">payment method</h6>
                                                        <div class="pay_method">
                                                            <div class="shopping-option payment-1">
                                                                <input type="radio" name="payment_method" id="payment-1" checked="checked" v-model="checkoutForm.payment_method" value="ssl" required>
                                                                <label for="payment-1">Pay Now</label>
                                                            </div>
                                                            <div class="shopping-option payment-2">
                                                                <input type="radio" name="payment_method" id="payment-2" v-model="checkoutForm.payment_method" value='cod' required>
                                                                    <label for="payment-2">Cash On Delivery</label>
                                                            </div>
                                                        </div>
                                                    </div>
													
													<div class="payment-method ml-3 mt-5">
                                                        <h6 class="widget-title border-left mb-20">Coupon Code</h6>
														<input style="width:70%;" type="text" name="coupon" id="coupon_code" v-model="checkoutForm.coupon" placeholder="Enter coupon code.." >
														<a id="coupon_code_btn" @click="couponCheck()" style="height: 41px;padding: 5px 14px;margin-left: -4px;border-radius: 0 8px 8px 0;" href="javascript:void(0)" class="btn btn-dark">Update</a>
													</div>
													

													<div class="ml-3 mt-5">
														<h6 class="widget-title border-left mb-2">Terms and Conditions</h6>
														<input  type="checkbox" name="aggree" value="1" required > I agree with the <a href="/terms-and-conditions" target="_blank">Terms and Conditions</a>, <a href="/privacy-policy" target="_blank">Privacy Policy</a> and <a href="/other-policy" target="_blank">Other Policies</a>.
													</div>
                                                    <!-- payment-method end -->
                                                    <button class="submit-btn-1 mt-30 ml-4 btn-hover-1" type="submit">place order</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- checkout end -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div v-else class="shop-section mt-30 mb-80 text-center">
                <div class="container text-center">
                    <h2>No item in checkout. <inertia-link href="/shop" style="color:green;">Continue</inertia-link> shopping.</h2>
                </div>
            </div>
            <!-- SHOP SECTION END -->             

        </section>
        <!-- End page content -->
</layout>
</template>

<script>
    import Layout from '@/Shared/Layout';
    export default {
        props: ['errors','total_items','user_data','free_shipping','couponCart','categorys2'],
        components:{
            Layout
        },
        data() {
            return {
                 checkoutForm:{
                    name: this.$props.user_data ? this.$props.user_data.name : '',
                    phone_number: this.$props.user_data ? this.$props.user_data.phone_number : '',
                    email: this.$props.user_data ? this.$props.user_data.email : '',
                    state: this.$props.user_data ? this.$props.user_data.state : '',
                    district: this.$props.user_data ? this.$props.user_data.district : '',
                    city: this.$props.user_data ? this.$props.user_data.city : '',
                    address: this.$props.user_data ? this.$props.user_data.address : '',
                    postal_code: this.$props.user_data ? this.$props.user_data.postal_code : '',
					shipping_name: '',
					shipping_state: '',
                    shipping_district: '',
                    shipping_city: '',
                    shipping_address: '',
                    shipping_postal_code:  '',
					shipping_phone_number: '',
					shipping_email: '',
                    shipping_charge:'',
                    payment_method:'',
                    notes:''
                }
            }
        },

        
        methods: {
            addToCart(product_id,quantity){
                this.$inertia.post('/add-to-cart',{product_id:product_id,count:quantity});
            },
             removeCartItem(product_id){
               this.$inertia.post('/remove-from-cart', {product_id:product_id})
            },
			couponCheck(coupon_code){
				var coupon = jQuery('#coupon_code').val();
                this.$inertia.post('/update-coupon-code',{coupon:coupon},{preserveState: false});
            },
			removeCoupon(){
                this.$inertia.post('/remove-coupon',{test:1},{preserveState: false});
            },
			
        },  
    }
</script>
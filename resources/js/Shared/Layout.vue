<template class="temp">
<main>
<div  v-if="$page.props.flash.success" class="success"> 
	<div class="msg_content">
		<span><img class="msg_logo"  src="/frontend/img/logo/logo.png"></span><br>
		<span v-html="$page.props.flash.success"></span>
		<i @click.prevent="$page.props.flash.success = null" class="fa fa-close close-btn fa-close-btn" ref="close_btn" ></i> 
	</div>
	
	
</div>


<div  v-if="$page.props.flash.error" class="error"> 
	<div class="msg_content">
		<span><img class="msg_logo" src="/frontend/img/logo/logo.png"></span><br>
		<span v-html="$page.props.flash.error"></span> 
		<i @click.prevent="$page.props.flash.error = null" class="fa fa-close close-btn fa-close-btn" ref="close_btn" ></i>
	</div>
</div>

<div class="wrapper">
<!-- START HEADER AREA -->
<header class="header-area header-wrapper">
<!-- header-top-bar -->
<div class="header-top-bar plr-185">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-6 d-none d-md-block">
                <div class="call-us">
                    <p class="mb-0 roboto"><a class="text-light" href="tel:+8801949168823"><i class="zmdi zmdi-phone"></i>+8801949-168823</a></p>
                </div>

            </div>
            <div class="col-lg-6 col-md-6">
                <div class="top-link clearfix">
                    <ul class="link f-right">

                        <li>
                           <span v-if="$page.props.wishlist.length > 0" id="count_icon">{{$page.props.wishlist.length}}</span>
                            <inertia-link href="/dashboard/my-wishlist">
                                <i class="zmdi zmdi-favorite" id="myicon"></i>
                                <span v-if="$page.props.wishlist.length > 0">Wish List ({{$page.props.wishlist.length}})</span>
                                <span v-else>Wish List (0)</span>
                            </inertia-link>
                        </li>
                        <li>
                            <span v-if="$page.props.compare_count > 0" id="count_icon">{{$page.props.compare_count}}</span>
                            <inertia-link href="/compare">
                                <i class="zmdi zmdi-refresh" id="myicon"></i>
                                <span v-if="$page.props.compare_count > 0">Compare List ({{$page.props.compare_count}})</span>
                                <span v-else>Compare List (0)</span>
                            </inertia-link>
                        </li>
                        <li>
						
							<span v-if="$page.props.user != null" >
								<span v-if="Object.keys($page.props.user).length != 0">
									<inertia-link href="/dashboard"> <i class="zmdi zmdi-account"></i> {{$page.props.user.name}}</inertia-link> 
								</span>
								<span v-else>
									<inertia-link href="/login"> <i class="zmdi zmdi-account"></i> Login</inertia-link> 
								</span>
							</span>
							<span v-else>
								<inertia-link href="/login"> <i class="zmdi zmdi-account"></i> Login</inertia-link> 
							</span>
						
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- header-middle-area -->
<div id="sticky-header" class="header-middle-area plr-185 br_bb">
    <div class="container-fluid">
        <div class="full-width-mega-dropdown">
            <div class="row">
                <!-- logo -->
                <div class="col-lg-12 col-md-4 pl-0 col-4 text-lg-center">
                    <div class="logo">
                        <inertia-link href="/">
                            <img :src="base_url()+`frontend/img/logo/logo.png`" alt="main logo">
                        </inertia-link>
                    </div>
                </div>
                <!-- primary-menu -->
                <div class="col-lg-10 d-none d-lg-block">
                    <nav id="primary-menu">
                        <ul class="main-menu text-center">
             
                            <li><inertia-link href="/">Home </inertia-link></li>
                            <li class="mega-parent"><a href="#">Category</a>
                                <div class="mega-menu-area clearfix">
                                    <div class="mega-menu-link f-left">
                                        <ul v-for="(category, index) in categories" :key="index" class="single-mega-item mb-4">
                                            <li class="menu-title">
                                                <inertia-link :href="`/category/`+category.slug" style="font-weight:700;"> 
                                                    {{category.name}}
                                                </inertia-link>
                                            </li>
                                            <li>
                                                <a href="#">All Mobile Phones</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li> <inertia-link :href="route('shop.page')">Products</inertia-link></li>
                            <li> <inertia-link :href="route('blog.page')">News feed</inertia-link></li>
                            <li> <inertia-link :href="route('about.page')">About Us</inertia-link> </li>
                            <li><inertia-link :href="route('contact.page')">Contact Us</inertia-link> </li>
                            <li><inertia-link href="/deals" class="deals" >Special Deals</inertia-link> </li>
                        </ul>
                    </nav>
                </div>
                <!-- header-search & total-cart -->
                <div class="col-lg-2 col-md-8 col-8">
                    <div class="search-top-cart  f-right">
                        <!-- header-search -->
                        <div class="header-search f-left">
                            <div class="header-search-inner">
                                <button class="search-toggle">
                                <i class="zmdi zmdi-search"></i>
                                </button>
                                <form @submit.prevent="searchSubmit">
                                    <div class="top-search-box">
                                        <input v-model="searchForm.s" type="text" placeholder="Search...">
                                        <button @click="searchSubmit()" href="#" type="submit">
                                            <i class="zmdi zmdi-search" style="cursor:pointer;"></i>
                                        </button>
                                    </div>
                                </form> 
                            </div>
                        </div>
                        <!-- total-cart -->
                        <div v-if="$page.props.cart" class="total-cart f-left">
                            <div class="total-cart-in">
                                <div class="cart-toggler">
                                    <a href="#">
                                        <span class="cart-quantity">{{cart.total_items ?? 0}}</span><br>
                                        <inertia-link href="/cart"> 
											<span class="cart-icon">
												<i class="zmdi zmdi-shopping-cart-plus"></i>
											</span>
										</inertia-link>
                                    </a>                            
                                </div>
                                <ul>
                                    <li>
                                        <div class="top-cart-inner your-cart">
                                            <h5 class="text-capitalize">Your Cart</h5>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="total-cart-pro">
                                            <!-- single-cart -->
                                            <div v-for="(item,index) in $page.props.cart.product" :key="item" class="single-cart clearfix">
                                                <div class="cart-img f-left">
                                                    <inertia-link :href="'/shop/'+item.slug">
                                                        <img :src="parent_url()+item.image" alt="">
                                                    </inertia-link>
                                                    <div class="del-icon">
                                                        <a @click="removeCartItem(index)" href="#">
                                                            <i class="zmdi zmdi-close"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="cart-info f-left">
                                                    <h6 class="text-capitalize">
                                                        <inertia-link :href="'/shop/'+item.slug">{{item.name}}</inertia-link>
                                                    </h6>
                                                    <p> Brand : {{item.brand}}  </p>
                                                    <p> Category : {{item.category}}   </p>
                                                    <p v-if="item.deal"> <span>Price <strong>:</strong></span>BDT {{item.price}}  </p>
                                                    <p v-else-if="item.promotion_price"> <span>Price <strong>:</strong></span>BDT {{item.promotion_price}}  </p>
                                                    <p v-else> <span>Price <strong>:</strong></span>BDT {{item.price}}  </p>
                                                </div>
                                            </div>
                                            <!-- single-cart -->
                                     
                                        </div>
                                    </li>
                                    <li>
                                        <div class="top-cart-inner subtotal">
                                            <p class="text-uppercase g-font-2">
                                                Sub Total : BDT {{$page.props.cart.sub_total}}
                                            </p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="top-cart-inner view-cart">
                                            <span v-if="cart.total_items > 0" class="text-uppercase">
                                                <inertia-link href="/cart" alt="View Cart" class="btn btn-dark btn-sm mr-2">View cart</inertia-link>
                                            </span>

                                            <span v-if="cart.total_items > 0" class="text-uppercase">
                                                 <inertia-link href="/checkout" alt="checkout" class="btn btn-dark btn-sm">Checkout</inertia-link>
                                            </span>

                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <div v-else class="total-cart f-left">
                            <div class="total-cart-in">
                                <div class="cart-toggler">
                                        <a href="#">
                                            <span class="cart-quantity">0</span><br>
                                            <span class="cart-icon">
                                                <i class="zmdi zmdi-shopping-cart-plus"></i>
                                            </span>
                                        </a>                            
                                </div>
                                <ul>
                                    <li>
                                        <div class="top-cart-inner your-cart">
                                            <h5 class="text-capitalize">No item found in your cart.</h5>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</header>
<!-- END HEADER AREA -->
<!-- START MOBILE MENU AREA -->
<div class="mobile-menu-area d-block d-lg-none section">
<div class="container-fluid bars_prarent">
    <div class="row bar_row">
        <div class="col-lg-12">
		
			<div v-if = "route().current() == 'shop.page'" class="bar_filter show_filter"> <i class="fa fa-sliders" aria-hidden="true"></i></div>
            <div class="bar_child"> <i class="fa fa-bars" aria-hidden="true"></i></div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 mymobile_menu">
            <div class="mobile-menu">
                <nav id="dropdown">
                    <ul>
                        <li><inertia-link href="/">Home </inertia-link></li>
                        <li><a href="#">Category</a>
                            <ul style="display: none;">
                                <li v-for="(category, index) in categories" :key="index" >
                                    <inertia-link :href="`/category/`+category.slug" > 
                                        {{category.name}}
                                    </inertia-link>
                                </li>
                            </ul>
                            <a class="mean-expand" href="#" style="font-size: 18px">+</a>
                        </li>
                        <li> <inertia-link :href="route('shop.page')">Products</inertia-link></li>
                        <li> <inertia-link :href="route('blog.page')"> Blog</inertia-link></li>
                        <li> <inertia-link :href="route('about.page')">About Us</inertia-link> </li>
                        <li><inertia-link :href="route('contact.page')">Contact Us</inertia-link> </li>
                        <li><inertia-link href="/deals" class="deals" >Special Deals</inertia-link> </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
</div>
<!-- END MOBILE MENU AREA -->
<!-- @yeild here -->
<div class="main-content">
    <slot/>
</div>
<!-- end @yeild -->
        <!-- START FOOTER AREA -->
        <footer id="footer" class="footer-area section gray-bg">
            <div class="footer-top">
                <div class="container-fluid">
                    <div class="plr-185">
                        <div class="footer-top-inner">
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <div class="single-footer footer-about">
                                        <div class="footer-logo">
                                            <img :src="base_url()+`frontend/img/logo/logo_footer.png`" alt="">
                                        </div>
                                        <div class="footer-brief">
                                            <p style="margin:0;">An online market place of tech product.</p>
                                        </div>
                                        <div class="footer_payments">
                                            <img :src="base_url()+`frontend/images/payments.png`" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2">
                                    <div class="single-footer">
                                        <h4 class="footer-title border-left">Quick links</h4>
                                        <ul class="footer-menu link_child">
                                            <li><inertia-link href="/about"><i class="zmdi zmdi-circle"></i><span>About Us</span></inertia-link></li>
                                            <li><inertia-link href="/dashboard/my-orders"><i class="zmdi zmdi-circle"></i><span>Track Your Order</span></inertia-link></li>
                                            <li><inertia-link href="/dashboard/edit-account-information"><i class="zmdi zmdi-circle"></i><span>Shipping Details</span></inertia-link></li>

                                        </ul>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2">
                                    <div class="single-footer">
                                        <h4 class="footer-title border-left">my account</h4>
                                        <ul class="footer-menu link_child">
  
                                            <li>
                                                <inertia-link href="/register"><i class="zmdi zmdi-circle"></i><span>Register</span></inertia-link>
                                            </li>
                                            <li>
                                                <inertia-link href="/dashboard"><i class="zmdi zmdi-circle"></i><span>My Account</span></inertia-link>
                                            </li>

                                            <li>
                                               <inertia-link href="/dashboard/my-wishlist"><i class="zmdi zmdi-circle"></i><span>Wishlist</span></inertia-link>
                                            </li>
                                           
                                        </ul>
                                    </div>
                                </div>

                                 <div class="col-xl-2 col-lg-2 col-md-2">
                                    <div class="single-footer">
                                        <h4 class="footer-title border-left">Policies</h4>
										<ul class="footer-menu link_child">
                                            <li><inertia-link href="/privacy-policy"><i class="zmdi zmdi-circle"></i><span>Privacy Policy</span></inertia-link></li>
											<li><inertia-link href="/terms-and-conditions"><i class="zmdi zmdi-circle"></i><span>Terms & Conditions</span></inertia-link></li>
                                            <li><inertia-link href="/other-policy"><i class="zmdi zmdi-circle"></i><span>Other Policy</span></inertia-link></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <div class="single-footer single-footer-c">
                                        <h4 style="cursor:pointer" class="footer-title border-left ">Get in touch <span class="carat_ico"><i class="zmdi zmdi-caret-down"></i></span></h4>
                                        <div class="footer-message contact_us_form">
                                            <form @submit.prevent="contactSubmit2">
                                                <input v-model="contactForm.name"  type="text" name="name" placeholder="Your name here...">
                                                <input v-model="contactForm.email" type="text" name="email" placeholder="Your email here...">
                                                <textarea v-model="contactForm.message" class="height-80" name="message" placeholder="Your messege here..."></textarea>
                                                <div class="row return_message">
                                                    <div class="col-md-12">
                                                        <div v-if="successMessage" class="alert alert-success successMessage">
                                                            {{ successMessage }}
                                                        </div>
                                                        <div v-if="errorMessage" class="alert alert-danger errorMessage">
                                                            {{ errorMessage }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="submit-btn-1 mt-4 btn-hover-1 mb-3" type="submit">submit message</button> 
                                            </form>
                                        </div>
                                    </div>
                                     <ul class="footer-social pb-2">
                                            <li>
                                                <a target="_blank" class="facebook" href="https://www.facebook.com/techhut.com.bd/" title="Facebook"><i class="zmdi zmdi-facebook"></i></a>
                                            </li>
                                            <li>
                                                <a target="_blank" class="twitter" href="" title="Twitter"><i class="zmdi zmdi-twitter"></i></a>
                                            </li>
                                            <li>
                                                <a target="_blank" class="youtube" href="https://www.youtube.com/channel/UCZvFCTcVBPDRNbx_P5RtEcQ" title="Youtube"><i class="zmdi zmdi-youtube"></i></a>
                                            </li>
											<li>
                                                <a target="_blank" class="facebook" href="https://www.linkedin.com/company/techhut-com-bd/" ><i class="zmdi zmdi-linkedin"></i></a>
                                            </li>
											<li>
                                                <a target="_blank" class="youtube" href="https://www.instagram.com/techhut.com.bd/" title="RSS"><i class="zmdi zmdi-instagram"></i></a>
                                            </li>
                                        </ul>

                                        <ul class="apps_logo pt-3">
										    <li>
                                                <a class="ecab pr-3" href="http://e-cab.net/" title="ecab" target="_blank"> <img :src="base_url()+`frontend/images/ecab.png`" alt=""> </a>
                                            </li>
                                            <li>
                                                <a class="android pr-3" href="https://play.google.com/store/apps/details?id=com.vmsl.techhut" title="android"  target="_blank"> <img :src="base_url()+`frontend/images/android.png`" alt="" > </a>
                                            </li>
                                            <li>
                                                <a class="apple" href="https://apps.apple.com/us/app/techhut/id1592282019" title="apple" target="_blank" ><img :src="base_url()+`frontend/images/apple.png`" alt="" ></a>
                                            </li>
                                        </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom black-bg plr-185">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="copyright-text">
                                <p class="copy-text slim_text">© 2021 LuxiQue. All Rights Reserved. Designed by <a href="https://vmsl.com.bd/" target="_blank"> VMSL </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- END FOOTER AREA -->
		
		<div class="feedback_section">
			<div class="feedback">
				<ul class="ul-f">
					<li>
						<a href="#">FEEDBACK</a>
					</li>
				</ul>
			</div>
			<div class="feedback_details">
				<div class="feedback_details_content">
						<p><img style="width:150px;" src="/frontend/img/logo/logo.png"></p>
						
						<div class="first_content">
							<h3>YOUR EXPERIENCE</h3>
							<p>Don’t hold back. Good or bad - tell it like it is.</p>
							<p>How likely are you to recommend <b>TechHut.com.bd</b> to a friend? *</p>
							<form class="feedback_form" @submit.prevent="feedbackSubmit" >
								<p><span>Very unlikely</span> <span class="f_right">Very likely</span></p>
								<input type="radio" name="feedback" value="1" v-model="feedbackForm.feedback" required>
								<input type="radio" name="feedback" value="2" v-model="feedbackForm.feedback" required>
								<input type="radio" name="feedback" value="3" v-model="feedbackForm.feedback" required>
								<input type="radio" name="feedback" value="4" v-model="feedbackForm.feedback" required>
								<input type="radio" name="feedback" value="5" v-model="feedbackForm.feedback" required>
								<input type="radio" name="feedback" value="6" v-model="feedbackForm.feedback" required>
								<input type="radio" name="feedback" value="7" v-model="feedbackForm.feedback" required>
								<input type="radio" name="feedback" value="8" v-model="feedbackForm.feedback" required>
								<input type="radio" name="feedback" value="9" v-model="feedbackForm.feedback" required>
								<input type="radio" name="feedback" value="10" v-model="feedbackForm.feedback" required>
								
								<br><p><b>Your Age</b> <br><input type="text" name="age" v-model="feedbackForm.age" required></p>
								
								<p class="comment_lavel">What could we do better on <b>TechHut.com.bd </b>?</p>
								<textarea name="feedback_comment" style="min-height:120px;" placeholder="Your Comments.." v-model="feedbackForm.feedback_comment"></textarea>
								<button  @click="feedbackSubmit()" class="deals feedback_submit">Submit</button>
							</form>
						</div>
						
				
				</div>
			</div>
		</div>

</div>
</main>
</template>


<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from '@/Jetstream/Checkbox'
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'
    import Button from '../Jetstream/Button.vue'



    export default {
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors,
            Button,
        },

        props:['errors', 'successMessage', 'errorMessage', 'categories','flush','cart','wishlist', 'compare', 'deals','product'],

        data() {
            return {
                searchForm:{
                    s:'',
                },
                contactForm:{
                    name: '',
                    email: '',
                    message: '',
                },
				feedbackForm:{
                    feedback: '',
                    age: '',
                    feedback_comment: '',
                },

                 newslatter:{
                    email: '',
                },
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false
                }),
                sets: [[ 1, 2, 3, 4, 5 ], [6, 7, 8, 9, 10]],
                visible: true
            }
        },

        methods: {
            logout() {
                this.$inertia.post(route('logout'));
            },
            contactSubmit2(){
                this.$inertia.post('/footer_contact', this.contactForm).then(()=>{

                });
            },
            removeCartItem(product_id){
               this.$inertia.post('/remove-from-cart', {product_id:product_id,_token: $('meta[name="csrf-token"]').attr('content')},{preserveState: false})
            },
            addToCart(product_id,qty){
                this.$inertia.post('/add-to-cart',{product_id:product_id,count:qty},{preserveState: false});
            },
            addOrRemoveWishlist(product_id){
                this.$inertia.post('/add-or-remove-wishlist',{product_id:product_id},{preserveState: false});
            },
            newslatterSubmit(){
                this.$inertia.post('/newslatter/submit', this.newslatter,{preserveState: false});
                const elem = this.$refs.close_btn_news
                elem.click();
            },
			feedbackSubmit(){
                this.$inertia.post('/feedback', this.feedbackForm).then(()=>{});
            },
            submit() {
                this.$inertia.post(this.route('login'), this.form,{preserveState: false}).then(()=>{
                   // openAccount(); 
                });
            },
            searchSubmit(){
                this.$inertia.post('/shop', this.searchForm).then(()=>{}); 
            }
        },
        mounted() {
            const plugin = document.createElement("script");
            plugin.setAttribute( "src","https://www.techhut.com.bd/frontend/js/main.js");
            plugin.async = true;
            document.body.appendChild(plugin);
        }
        
  
    }
</script>



<template>
<layout :categorys2="categorys2">
<div>
<!-- breadcrumb start -->
<div class="breadcrumb-main ">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-contain">
                    <div>
                        <h2>dashboard</h2>
                        <ul>
                            <li><a href="#">home</a></li>
                            <li><i class="fa fa-angle-double-right"></i></li>
                            <li><a href="#">{{pageTitle}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb End -->

<!-- section start -->
<section class="section-big-py-space bg-light plr-185 pt-5 pb-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3">
                <div class="account-sidebar"><a class="popup-btn">my account</a></div>
                <div class="dashboard-left">
                    <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> back</span></div>
                    <div class="block-content ">
                        <ul>
                            <li class="active"><inertia-link href="/dashboard"> Account Info</inertia-link></li>
                            <li><inertia-link href="/dashboard/my-orders">Order History</inertia-link></li>
                            <li><inertia-link href="/dashboard/my-wishlist">My Wishlist</inertia-link></li>
                            <li><inertia-link href="/dashboard/my-coupon">My Coupon</inertia-link></li>
                            <li><inertia-link href="/dashboard/change-password">Change Password</inertia-link></li>
                            <li class="last"><a>
                                <form @submit.prevent="logout">
                                    <button type="submit" class="pl-0 px-0 mybtn">Log Out</button>
                                </form>
                            </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <span v-if="pageSlug == null"><Home :user="user" /></span>
                        <span v-if="pageSlug == 'my-orders'"><MyOrders :orders="orders" /></span>
                        <span v-if="pageSlug == 'order-details'"><OrderDetails :singleOrder="singleOrder" /></span>
                        <span v-if="pageSlug == 'track-order'"><TrackOrder :singleOrder="singleOrder" /></span>
                        <span v-if="pageSlug == 'my-wishlist'"><MyWishlist /></span>
                        <span v-if="pageSlug == 'my-coupon'"><MyCoupon :myCoupon="myCoupon"  :myRef="myRef" /></span>
                        <span v-if="pageSlug == 'change-password'"><ChangePassword /></span>
                        <span v-if="pageSlug == 'edit-account-information'"><EditInfo :user="user" /></span>
                         
                    </div>
                </div>
            </div>


        </div>
    </div>
</section>
<!-- section end -->
</div>
</layout>
</template>

<style scoped>
button{
    border: none;
    background: none;
    padding: 0 !important;    
}
</style>



<script>
    import Layout from '@/Shared/Layout';
    import Home from '@/Pages/Myaccount/Home';
    import MyOrders from '@/Pages/Myaccount/MyOrders';
    import OrderDetails from '@/Pages/Myaccount/OrderDetails';
    import TrackOrder from '@/Pages/Myaccount/TrackOrder';
    import MyWishlist from '@/Pages/Myaccount/MyWishlist';
    import MyCoupon from '@/Pages/Myaccount/MyCoupon';
    import ChangePassword from '@/Pages/Myaccount/ChangePassword';
    import EditInfo from '@/Pages/Myaccount/EditInfo';


    export default {
        props: ['pageSlug','user','orders','pageTitle','singleOrder','myCoupon','myRef','categorys2'],
        components: {Layout,Home,MyOrders,OrderDetails,TrackOrder,MyWishlist,MyCoupon,ChangePassword,EditInfo},
        methods: {
            logout() {
                this.$inertia.post(route('logout'));
            },
        }
    }
</script>
<template>
    
    <div class="col-lg-3 order-lg-1 order-2">
		
        <form id="filter_bar_mobile" @submit.prevent="filterSubmit">
        <!-- widget-search -->
		<p class="close_filter" > <a href="javascript:void(0)">Close Filter</a></p>
        <aside class="widget-search mb-30">
            <div class="short-by w-100">
                <select id="filterbyproduct" class="w-100" v-on:change="filterbyproduct()">
                    <option selected disabled>Filter By</option>
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                    <option value="lowtohigh">Price: Low to High</option>
                    <option value="hightolow">Price: High to Low</option>
                </select> 
            </div> 
        </aside>
		
		<aside class="widget-search mb-30">
            <div class="short-by w-100">
                <select id="filterByCategory" name="category" class="w-100" v-on:change="filterByCategory()">
                    <option selected disabled>Filter By Category</option>
					<option value="-1">All Category</option>
                    <option v-for="category in categories" :key="category" :value="category.id">{{category.name}}</option>
                </select> 
            </div> 
        </aside>

        <!-- operating-system -->
        <aside v-if="brands" class="widget operating-system box-shadow mb-30">
            <h6 class="widget-title border-left mb-20 mt-60">Brands</h6>
            <label v-for="brand in computedObj" :key="brand">
                <input v-model="fitlerForm.brands" :value="brand.id" type="checkbox" name="operating-1" >{{brand.title}}
            </label>
            <label v-if="limit != null">
                <button @click="limit = null" class="btn btn-warning btn-sm mt-3 show_more_btn">Show More <i class="fa fa-caret-down"></i></button>
            </label>
            <label v-else>
                 <button @click="limit = 10" class="btn btn-warning btn-sm mt-3 show_more_btn">Show Less <i class="fa fa-caret-up"></i></button>
            </label>
            <div class="row">
                <input type="submit" class="btn btn-primary fitlerbuttton" value="Filter Now">
            </div>
        </aside>
        <aside class="widget operating-system box-shadow mb-30">
            <h6 class="widget-title border-left mb-20">Price</h6>
            <label><input type="radio"  id="hundred"      v-model="fitlerForm.price" value="100-1000">&nbsp;&nbsp;BDT 100 - BDT 1000</label><br>
            <label><input type="radio"  id="twohundred"   v-model="fitlerForm.price" value="1000-2000">&nbsp;&nbsp;BDT 1000 - BDT 2000</label><br>
            <label><input type="radio"  id="threehundred" v-model="fitlerForm.price" value="2000-3000">&nbsp;&nbsp;BDT 2000 - BDT 3000</label><br>
            <label><input type="radio"  id="fourhundred"  v-model="fitlerForm.price" value="3000-4000">&nbsp;&nbsp;BDT 3000 - BDT 4000</label><br>
            <label><input type="radio"  id="fourhundredabove" v-model="fitlerForm.price" value="4000-1000000">&nbsp;&nbsp;BDT 4000 above</label><br>
            <div class="row">
                <input type="submit" class="btn btn-primary fitlerbuttton" value="Filter Now">
            </div>
        </aside>
        </form>
        <!-- featured-item start -->
        <aside class="widget widget-product box-shadow">
            <h6 class="widget-title border-left mb-20">featured products</h6>
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
                    <h3 v-else-if="product.promotion_price" class="pro-price">BDT {{product.promotion_price}}</h3>
                    <h3 v-else class="pro-price"> BDT {{product.price}}</h3>
                </div>
            </div>
        </aside>
        <!-- featured-item end --> 

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
                    <h3 v-if="product.deal" class="pro-price">BDT {{product.deal}}</h3>
                    <h3 v-else-if="product.promotion_price" class="pro-price">BDT {{product.promotion_price}}</h3>
                    <h3 v-else class="pro-price">BDT {{product.price}}</h3>
                </div>
            </div>
        </aside>
        <!-- recent-item end -->  
    </div>
    
</template>


<script>

    export default {
        props: ['brands','newproducts', 'featuedProducts','categories','check_brand'],
        data(){
            return {
                searchForm:{
                    s:'',
                },
                fitlerForm:{
                    brands:[],
                    price: [],
					category:'',
                },
                object:[], 
                limit: 10
            }
        },
        methods: {
            addToCart(product_id,qty){
                this.$inertia.post('/add-to-cart',{product_id:product_id,count:qty},{preserveState: false});
            },
            filterSubmit(){
                this.$inertia.post('/shop',this.fitlerForm,{preserveState: false});
            },
            searchSubmit(){
                this.$inertia.post('/shop', this.searchForm).then(()=>{}); 
            },
             filterbyproduct(){
                this.$inertia.post('/shop',{filterbyproduct: $("#filterbyproduct").val()},{preserveState: false});
            },
			filterByCategory(){
                this.$inertia.post('/shop',{filterByCategory: $("#filterByCategory").val()},{preserveState: false});
            }
			
        },
        computed:{
            computedObj(){
                return this.limit ? this.brands.slice(0,this.limit) : this.brands
            }
        }
        
    }


</script>

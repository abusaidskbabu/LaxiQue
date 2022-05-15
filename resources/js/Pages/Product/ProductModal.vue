<template>
    <div class="modal fade bd-example-modal-lg theme-modal" :id="'quick-view'+product.id" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content quick-view-modal">
                    <div class="modal-body">
                        <button  ref="close_btn" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="row">
                            <div class="col-lg-6 col-xs-12">
                                <div class="quick-view-img"><img id="image" :src="parent_url()+'images/product/'+product.image" alt="quick" class="img-fluid "></div>
                            </div>
                            <div class="col-lg-6 rtl-text">
                                <div class="product-right">
                                    <h2 id="title">{{product.name}}</h2>
                                    <h3 id="price">BDT {{product.price}}</h3>
     
                                    <div class="border-product">
                                        <h6 class="product-title">Product details</h6>
                                        <div id="description" v-html="product.product_details" ></div>
                                    </div>
                                    <div class="product-description border-product">
                                        <h6 class="product-title">quantity</h6>
                                        <div class="qty-box">
                                        <div class="input-group">
                                            <span class="input-group-prepend"><button @click="decrement()" type="button" class="btn quantity-left-minus" data-type="minus" data-field=""><i class="ti-angle-left"></i></button></span>
                                            <input type="text" name="quantity" class="form-control input-number" :value="quantity" readonly> 
                                            <span class="input-group-prepend"><button @click="increment()" type="button" class="btn quantity-right-plus" data-type="plus" data-field=""><i class="ti-angle-right"></i></button></span></div>
                                        </div>
                                    </div>
                                    <div class="product-buttons"><a href="#" @click="addToCart(product.id)" class="btn btn-normal">add to cart</a> 
                                    <inertia-link @click="hideModal()" :href="'/shop/'+product.slug" class="btn btn-normal">view detail</inertia-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>



<script>
    export default {
        data () {
            return {
                quantity: 1
            }
        },
        props: ['product'],
        methods: {
            hideModal(){
               const elem = this.$refs.close_btn
                elem.click()
            },
            increment () {
                this.quantity++;
            },
            decrement () {
                if(this.quantity === 1) {
                    alert('Negative quantity not allowed!');
                } else {
                    this.quantity--;
                }
            },
            addToCart(product_id){
                this.$inertia.post('/add-to-cart',{product_id:product_id,count:this.quantity},{preserveState: false});
                this.hideModal();
            }
        }
    }
</script>
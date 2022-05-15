<template>
<div >
    <div class="container-fluid"> 
      <table class="table wish_list">
        <thead class="thead-dark">
          <tr>
            <th>Serial</th>
            <th>Product Name</th>
            <th>Image</th>
            <th>Price</th>
            <th style="text-align:center">Action</th>
          </tr>
        </thead>

        <tbody v-if="Object.keys($page.props.wishlist).length != 0" >
          <tr v-for="(item,index) in $page.props.wishlist" :key="item">
            <td>{{index+1}}</td>
            <td>{{item.name}}</td>
            <td><img :src="parent_url()+'/images/product/'+item.image" style="width:70px"> </td>
            <td>BDT {{item.price}}</td>
            <td style="text-align:center" >
              <inertia-link :href="'/shop/'+item.slug" class="btn btn-dark btn-sm mr-2" >View Product</inertia-link>
              <button @click="addOrRemoveWishlist(item.product_id)" class="btn btn-danger btn-sm"  >Remove</button>
            </td>
          </tr>
        </tbody>
        <tbody v-else >
          <tr>
            <td colspan="6"><h4 class="text-center mt-5">No product found to your wishlist!</h4></td>
          </tr>
        </tbody>

      </table>
  </div>
</div>

</template>


<script>
    export default {
        props: ['wishlist'],
        methods:{
             addToCart(product_id,qty){
                this.$inertia.post('/add-to-cart',{product_id:product_id,count:qty});
            },
            addOrRemoveWishlist(product_id){
                this.$inertia.post('/add-or-remove-wishlist',{product_id:product_id});
            },
        }
    }

</script>
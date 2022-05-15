<template>
	<div class="container-fluid order_details_page">
	
			<div v-for="order in singleOrder" :key="order" class="row">
				<div class="col-md-12">
              
              <div class="delivered_delails">
                <p class="msg">Order delivery status is {{order.delivery_status}}!</p>
               <!-- <p class="mb-0">Delivered On</p>
                <p class="date_row">July 3, 2021</p> -->
              </div>

              <div v-for="details in order.order_details" :key="details" class="product_delails">
                    <div class="media">
                    <inertia-link :href="'/shop/'+details.product_slug"><img style="width: 95px;" class="mr-3" :src="parent_url()+'images/product/'+details.product_image"></inertia-link>
                    <div class="media-body">
                      <h5 class="mt-0 text-uppercase"><inertia-link :href="'/shop/'+details.product_slug">{{details.product_name}}</inertia-link></h5>
                      <p>Item ID: {{details.id}}</p>
                      <p>Variant: {{details.variant_name}}</p>
                      <p>Price: BDT {{details.product_price}}</p>
                    </div>
                  </div>
              </div><hr>

             <div class="payment_delails">
               <p><strong>Payment Details</strong></p>
                <div class="row">
                  <div class="col-md-4">
                    <p class="text-uppercase">Order</p>
                    <p>Order ID: <span>TH{{order.formated_id}}</span></p>
                    <p>Transaction ID: <span>{{order.transaction_id}}</span></p>
                    <p>Transaction Date: <span>{{order.placement_time}}</span></p>
                  </div>

                  <div class="col-md-4">
                    <p class="text-uppercase">Payment</p>
                    <p>Payment Method: 
						<span v-if="order.type == 'cod'">Cash On Deliverey</span>
						<span v-else>Ssl Commerz Payment Gateway</span>
					
					</p>
                  </div>

                  <div class="col-md-4">
                    <p class="text-uppercase">Shipping</p>
                    <p>Shipping Area:
					<span v-if="order.shipping_charge == '80'">Inside Dhaka</span>
					<span v-else>Outside Dhaka</span>
					
					</p>
                  </div>

                </div>
              </div> <hr>
			  
			 <div class="row">
				 <div class="col-md-6">
				   <div class="shipping_delails">
						<p><strong>Shipping Address</strong></p>
						<p>{{order.shipping_name}}<br>
						  {{order.shipping_address}},<br>
						  {{order.shipping_postalCode}}, {{order.shipping_district}}, {{order.shipping_division}}, Bangladesh<br>
						  {{order.shipping_phone_number}}<br>
						  {{order.shipping_email}}
						</p>
					</div>
				 </div>
				 <div class="col-md-6">
				 	<div class="shipping_delails">
						<p><strong>Order Note</strong></p>
						<p>{{order.notes}}<br>
						</p>
					</div>
				 
				 
				 </div>
				 
			 </div>

          <hr>
			
			
			
           <div class="order_delails">
                <p><strong>Order total</strong></p>
                <table>
                    <tr>
                      <td>Subtotal</td>
                      <td>BDT {{order.sub_total}}</td>
                    </tr>
                     <tr>
                      <td>Discount</td>
                      <td>BDT {{order.discounted_amount}}</td>
                    </tr>
                    <tr>
                      <td>Shipping Charge</td>
                      <td>BDT {{order.shipping_charge}}</td>
                    </tr>
                     <tr>
                      <td><strong>Total Paid Amount</strong></td>
                      <td><strong>BDT {{order.amount}}</strong></td>
                    </tr>
                </table>
            </div>


				</div>
			</div>
	</div>

</template>


<script>
    export default {
        props: ['singleOrder']
    }
</script>
<template>
	<div class="container-fluid">
			<div v-if="Object.keys(orders).length != 0" >
				<div v-for="order in orders" :key="order">
					<div class="row">
						<div class="col-md-12">
						<p class="fs_15 mb-0">Looking for track an order?</p>
						<p ><inertia-link class="track_order" :href="'/dashboard/track-order?id='+order.id">TRACK YOUR ORDER</inertia-link></p>
						<p class="your_order">YOUR ORDER: TH{{order.formated_id}}</p>
						<p class="font-12" href="javascript:void(0)"  >Payment Status: 
						
							<span v-if="order.status =='Processing' || order.status =='Confirmed' " class="text-success" >{{order.status}}</span>
							<span v-else class="text-danger" >{{order.status}}</span>
						
						</p>
						<p class="date_row">{{order.placement_time}} | BDT {{order.amount}} | {{order.order_details.length}} item(s)</p>
						  <div class="row">
							<div class="col-md-8">
							
							<div class="pi" v-if="order.order_details.length">
							  <div v-for="details in order.order_details" :key="details" >
								<inertia-link :href="'/shop/'+details.product_slug">
								  <img class="o_p_i" :src="parent_url()+'images/product/'+details.product_image" alt="product logo">
								  <p>{{details.product_name}}</p>
								</inertia-link>
							  </div>
							</div>
							</div>
							<div class="col-md-4">
							<form method="post" action="/pay-again">
								<p class="text-right mt-5">
									<input type="hidden" name="transaction_id" :value="order.transaction_id">
									<inertia-link class="btn-animate" :href="'/dashboard/order-details?id='+order.id"><span>VIEW DETAILS</span></inertia-link>
									<button href="javascript:void(0)" class="btn-animate pay_now" v-if="order.type == 'ssl' && (order.status == 'Failed' || order.status == 'Canceled' || order.status == 'Pending')" ><span>PAY NOW</span></button>
								</p>
							</form>
							</div>
						  </div>
						</div>
					  </div> <hr>
				</div>
			</div>
			<div v-else>
				<br><h4 class="text-center mt-5 mb-5 pt-5 mb-5">No order found!</h4><br>
			</div>

	</div>

</template>


<script>
    export default {
        props: ['orders']
    }

</script>
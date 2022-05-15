<template>
	<div class="container-fluid">
				<div v-for="order in singleOrder" class="card">
					<div class="card-body">
						<h6>Order ID: TH{{order.formated_id}}</h6>
						<article class="card">
							<div class="card-body row">
								<div class="col"> <strong>Estimated Delivery Time</strong> <br>{{order.delivery_time}}</div>
								<div class="col"> <strong>Shipping BY</strong> <br> TechHut</div>
								<div class="col"> <strong>Status</strong> <br> {{order.status}} </div>
							</div>
						</article>
						<div class="track">
							<div class="step active"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Order Placed</span> </div>
							<div v-if="order.delivery_status == 'Shipped'" class="step active"> <span class="icon"> <i class="fa fa-user"></i> </span> <span class="text"> Order Picked</span> </div>
							<div v-else class="step"> <span class="icon"> <i class="fa fa-user"></i> </span> <span class="text"> Order Picked</span> </div>
		
							<div v-if="order.delivery_status == 'Shipped'" class="step active"> <span class="icon"> <i class="fa fa-truck"></i> </span> <span class="text"> Out for Delivery</span> </div>
							<div v-else class="step"> <span class="icon"> <i class="fa fa-truck"></i> </span> <span class="text"> Out for Delivery</span> </div>
							<div v-if="order.delivery_status == 'Confirmed'" class="step active"> <span class="icon"> <i class="fa fa-smile-o"></i> </span> <span class="text">Delivered</span> </div>
							<div v-else class="step"> <span class="icon"> <i class="fa fa-smile-o"></i> </span> <span class="text">Delivered</span> </div>
						</div>
						<hr>
						<ul class="row">
						
				              <li v-for="details in order.order_details" :key="details" class="product_delails ml-3">
									<div class="media">
									<img style="width: 95px;" class="mr-3" :src="parent_url()+'images/product/'+details.product_image">
									<div class="media-body">
									  <h5 class="mt-0 text-uppercase"><inertia-link :href="'/shop/'+details.product_name">{{details.product_name}}</inertia-link></h5>
									  <p>Item ID: {{details.id}}</p>
									  <p>Variant: {{details.variant_name}}</p>
									  <p>Price: BDT {{details.product_price}}</p>
									</div>
								  </div>
							  </li>

						</ul>
						<hr> <inertia-link href="/dashboard/my-orders" class="btn btn-warning" data-abc="true"> <i class="fa fa-chevron-left"></i> Back to orders</inertia-link>
					</div>
				</div>

	</div>

</template>

<script>
    export default {
        props: ['singleOrder']
    }
</script>
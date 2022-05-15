<template>
<div class="page-title">
        <h2>My Dashboard</h2></div>
    <div class="welcome-msg">
        <p class="text-uppercase">Hello, {{user.name}} !</p>
    </div>
    <div class="box-account box-info">

        <div class="row">
            <div class="col-sm-6">
                <div class="box">
                    <div class="box-title">
                        <h3>Contact Information</h3></div>
                    <div class="box-content">
                        <h6>Name: {{user.name}}</h6>
                        <h6>Email: {{user.email}}</h6>
                        <h6>Mobile Number: {{user.phone_number}}</h6>
                    </div>
                </div>
            </div>
        </div><br>

        <div>
            <div class="box">
                <div class="box-title">
                    <h3>Address Book</h3>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <h6><b>Default Billing Address</b></h6>
							Post Code : {{user.postal_code}}<br>
							City: {{user.city}}<br>
							District: {{user.district}}<br>
							Division: {{user.state}}<br>
							Full Address: {{user.address}}<br>
					</div>
                    <div class="col-sm-6">
                        <h6><b>Default Shipping Address</b></h6>
							Post Code: {{user.shipping_postalCode}}<br>
							City: {{user.shipping_city}}<br>
							District: {{user.shipping_district}}<br>
							Division: {{user.shipping_state}}<br>
							Full Address: {{user.address}}<br>
						</div>
                </div><br>
                <div class="row">
                    <div class="col-md-12">
                        <inertia-link class="btn btn-dark" href="/dashboard/edit-account-information">Edit Account Info</inertia-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user']
    }

</script>



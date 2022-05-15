<template>
    <form @submit.prevent="editMyaccountInfo" class="theme-form">
        
        <div class="form-row">

            <div class="col-md-12">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input v-model="form.name" required type="text" class="form-control" id="name" placeholder="Enter Your Name" >
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="name">Email</label>
                    <input v-model="form.email"  type="text" class="form-control" id="email" readonly>
                </div>
            </div>



            <div class="col-md-12">
                <div class="form-group">
                    <label for="name">Mobile</label>
                    <input v-model="form.phone_number" required type="text" class="form-control" id="phone_number" placeholder="Enter Your Mobile Number" disabled>
                </div>
            </div>


            <div class="col-md-12">
                <div class="form-group">
                    <label for="name">Default Billing Address</label>
                    
					<input type="text" name="postal_code" v-model="form.postal_code" placeholder="Postal Code.." required>
					<input type="text" name="city" v-model="form.city" placeholder="Thana.." required>
					<input type="text" name="district" v-model="form.district" placeholder="District.." required>
					<input type="text" name="state" v-model="form.state" placeholder="Division.." required>
					<textarea type="text" name="address" v-model="form.address" required placeholder="Full Address.." class="custom-textarea"></textarea>

                </div>
            </div>

            <div class="col-md-12 shipping">
                <div class="form-group">
                    <label for="name">Default Shipping Address </label> <span id="same_as_billing" ><input type="checkbox"> Same as Billing Address</span>
					<input type="text" name="shipping_postalCode" v-model="form.shipping_postalCode" placeholder="Shipping Postal Code..">
					<input type="text" name="shipping_city" v-model="form.shipping_city" placeholder="Shipping Thana..">
					<input type="text" name="shipping_district" v-model="form.shipping_district" placeholder="Shipping District..">
					<input type="text" name="shipping_state" v-model="form.shipping_state" placeholder="Shipping Division..">
					<textarea type="text" name="shipping_address" v-model="form.shipping_address" placeholder="Full Address.." class="custom-textarea"></textarea>
                </div>
            </div>


            <div class="col-md-12">
                <button class="btn btn-warning" type="submit">Update Account Info</button>
            </div>
        </div>
    </form>
</template>

<script>
    import Layout from '@/Shared/Layout'
    export default {
        props: ['user'],
        components:{
            Layout,
        },
        data(){
            return {
                form:{
                    name: this.user.name,
                    email: this.user.email,
                    phone_number: this.user.phone_number,
                    state: this.user.state,
                    district: this.user.district,
                    city: this.user.city,
                    address: this.user.address,
                    postal_code: this.user.postal_code,
					shipping_state: this.user.shipping_state,
                    shipping_district: this.user.shipping_district,
                    shipping_city: this.user.shipping_city,
                    shipping_address: this.user.shipping_address,
                    shipping_postalCode: this.user.shipping_postalCode,
                }
            }
        },

        methods:{
            editMyaccountInfo(){
                this.$inertia.post('/dashboard/account/change', this.form).then(()=>{});
            }
        }


    }
</script>
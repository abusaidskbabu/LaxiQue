<template>
<div >

    <div class="container-fluid">
		<p>Referral Link: <code id="p1">https://techhut.com.bd?ref={{myRef}}</code> <button class="btn btn-dark" onclick="copyToClipboard('#p1')">Copy Link</button></p>
		<p>Or Use Referral Code: <code id="p2">{{myRef}}</code> <button class="btn btn-dark" onclick="copyToClipboard('#p2')">Copy Link</button></p>
	</div>
    <div class="container-fluid"> 
      <table class="table wish_list">
        <thead class="thead-dark">
          <tr>
            <th>Serial</th>
            <th>Coupon Code</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Minimum Amount</th>
            <th>Qty</th>
            <th>Used</th>
            <th>Expire Date</th>
            <th>Status</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="(item,index) in myCoupon" :key="item">
            <td>{{index+1}}</td>
            <td>{{item.code}}</td>
            <td>{{item.type}}</td>
            <td>{{item.amount}}</td>
            <td>{{item.minimum_amount}}</td>
            <td>{{item.quantity}}</td>
            <td>{{item.used}}</td>
            <td>{{item.expired_date}}</td>
            <td><span v-if="item.is_active == 1">Active</span><span v-else>Inactive</span></td>
          </tr>
        </tbody>

      </table>
  </div>
</div>

</template>


<script>
    export default {
        props: ['myCoupon','myRef'],
        methods:{

        }
    }

</script>
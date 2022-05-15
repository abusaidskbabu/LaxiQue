<?php

namespace App\Http\Controllers;

use App\Coupon;
use App\Customer;
use App\Order;
use App\OrderDetails;
use App\Product;
use App\Product_Warehouse;
use App\ProductVariant;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;
use Auth;

class SslCommerzPaymentController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth:customer')->only([
            'exampleHostedCheckout', 'index',
        ]);
    }

    public function exampleEasyCheckout()
    {
        return view('exampleEasycheckout');
    }

    


    public function exampleHostedCheckout()
    {
        if (session()->has('shipping_cost') && session()->has('cart')) {
            return view('frontend.checkout');
        } else {
            $cart = session()->get('cart');
            return redirect('/cart')->with('msg', 'Please add products and choose shipping location');
        }

    }

    
 public function apiindex(Request $request){
	 
        $customer = Customer::find($request->user_id);
        $total = $request->cart_total;
        $cart =  $request->cart;
		$user_id = $request->user_id;
        $count = count($cart) ?? [];
        if ($count <= 0 || $total <= 0) {
            return response()->json(['msg' => 'An error occurred while processing your order!'], 404);
        }
        if ($request->payment_method != 'ssl' && $request->payment_method != 'cod') {
            return response()->json(['msg' => 'An error occurred while processing your order!'], 404);
        }
        if ($request->shipping_address) {
            $request->validate([
               'shipping_address' => 'required',
               'shipping_city' => 'required',
               'shipping_district' => 'required',
               'shipping_state' => 'required',
               'shipping_postal_code' => 'required',
            ]);
        }
       
	   /* $customer->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'city' => $request->city,
            'district' => $request->district,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
        ]); */
		
		
        $notes = $request->notes;

        $shipping_cost = $request->shipping_cost ?? null;
        $sub_total     = $request->sub_total ?? null;
        
        # Here you have to receive all the order data to initate the payment.
        # Let's say, your oder transaction informations are saving in a table called "orders"
        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.
        $post_data = array();
        $post_data['total_amount'] = $total; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique
        # CUSTOMER INFORMATION
        $post_data['cus_name'] =  $request->name ?? "";
        $post_data['cus_email'] =  $request->email ?? "";
        $post_data['cus_add1'] = $request->address ?? "";
        $post_data['cus_add2'] = $request->address ?? "";
        $post_data['cus_city'] =  $request->city ?? "";
        $post_data['cus_state'] = $request->state ?? "";
        $post_data['cus_postcode'] = $request->postal_code ?? "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] =  $request->phone_number ?? "";;
        $post_data['cus_fax'] = "";
        # SHIPMENT INFORMATION
        $post_data['ship_name'] =  $request->shipping_name ?? $request->name;
        $post_data['ship_add1'] =  $request->shipping_address ?? $request->address;
        $post_data['ship_add2'] = $request->shipping_address ?? $request->address;
        $post_data['ship_city'] = $request->shipping_city ?? $request->city;
        $post_data['ship_district'] = $request->shipping_district ?? $request->district;
        $post_data['ship_state'] = $request->shipping_state ?? $request->state;
        $post_data['ship_postcode'] = $request->shipping_postal_code ?? $request->postal_code;
        $post_data['ship_phone'] = $request->shipping_phone_number ?? $request->phone_number;
        $post_data['ship_email'] = $request->shipping_email ?? $request->email;
        $post_data['ship_country'] = "Bangladesh";
        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "TechHut";
        $post_data['product_category'] = "TechHut";
        $post_data['product_profile'] = "TechHut";
        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";
        #Before  going to initiate the payment order status need to insert or update as Pending.
		$discounted_amount = 0;
		$used_coupon = '';
		$coupon_referrer_id = null;
		if($request->couponCart){
			$couponCart = strtoupper($request->couponCart);
			if($couponCart == 'WELCOME50'){
				$discounted_amount = $request->discounted_amount;
				$coupon = Coupon::where('code', 'WELCOME50')->where('customer_id',$user_id)->first();
				
				if($coupon){
					$coupon->used += 1;
					$coupon->save();
					$already_used_count = DB::table('orders')->where('customer_id',$user_id)->where('used_coupon','WELCOME50')->count();
					if($already_used_count < 1){
						$coupon_referrer_user = DB::table('customers')->where('id',$user_id)->first();
						$coupon_referrer_id = $coupon_referrer_user->referrer_id;
					}
				}else{
					return response()->json(['msg' => 'An error occurred while processing your order with coupon!'], 404);
				}
		
			}elseif($couponCart == 'WELCOME100'){
				$coupon = Coupon::where('code', 'WELCOME100')->where('customer_id',$user_id)->first();
				if($coupon){
					$coupon->used += 1;
					$coupon->save();
				}else{
					return response()->json(['msg' => 'An error occurred while processing your order with coupon!'], 404);
				}
				
			}else{
				$coupon = Coupon::where('code', $couponCart)->first();
				if($coupon){
					$coupon->used += 1;
					$coupon->save();
				}else{
					return response()->json(['msg' => 'An error occurred while processing your order with coupon!'], 404);
				}

			}
		}

		
		
        $update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'phone' => $post_data['cus_phone'],
                'amount' => $post_data['total_amount'],
                'status' => 'Pending',
                'address' => $post_data['cus_add1'],
                'city' => $post_data['cus_city'],
                'district' => $post_data['ship_district'],
                'state' => $post_data['cus_state'],
                'postal_code' => $post_data['cus_postcode'],
                'country' => 'Bangladesh',
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency'],
                'customer_id' => $customer->id,
                'origin' => 'Website',
                'notes' => $notes,
                'shipping_name'       => $post_data['ship_name'],
                'shipping_division'      => $post_data['ship_state'],
                'shipping_district'   => $post_data['ship_district'],
                'shipping_city'       => $post_data['ship_city'],
                'shipping_postalCode'=> $post_data['ship_postcode'],
                'shipping_address'    => $post_data['ship_add1'],
                'shipping_phone_number'=> $post_data['ship_phone'],
                'shipping_email'      => $post_data['ship_email'],
                'shipping_charge'      => $shipping_cost,
                'sub_total'          => $sub_total,
				'discounted_amount' => $discounted_amount ?? 0,
				'used_coupon' => $used_coupon ?? '',
				'coupon_referrer_id' => $coupon_referrer_id ?? null
            ]);
            $order = Order::where('transaction_id', $post_data['tran_id'])->first();
		
            for ($i = 0; $i < $count; $i++) {
                OrderDetails::create([
                    'order_id'   => $order->id,
                    'product_id' => $cart[$i]['product_id'],
                    'count'      => $cart[$i]['count'],
                    'color_id'   => $cart[$i]['color_id'],
                    'size_id'    => $cart[$i]['size_id'],
                    'weight_id'  => $cart[$i]['weight_id'],
                    'type_id'    => $cart[$i]['type_id'],
                    'amount'    => $post_data['total_amount']
                ]);
                
    
                $warehouse = DB::table('product_warehouse')->where('product_id', $cart[$i]['product_id'])->first();
                $product = Product::find($cart[$i]['product_id']);
				if($warehouse){
					$warehouse_data = DB::table('product_warehouse')->where([
						['product_id', $product->id],
						['warehouse_id', $warehouse->warehouse_id],
					]);
		
					$product_quantity = $product->qty - $cart[$i]['count'];
					$warehouse_count = $warehouse_data->first()->qty - $cart[$i]['count'];
				  
					$warehouse_data->update([
						'qty' => $warehouse_count
					]);
					$product->update([
						'qty' => $product_quantity
					]);
				}

    

                
                if($product->is_variant == 1){
                    if($cart[$i]['size_id']){
                        $product_variant_data = DB::table('product_variants')->select('id', 'variant_id', 'qty')
                        ->where('product_id', $product->id)->where('id', $cart[$i]['size_id']);
                    }elseif($cart[$i]['weight_id']){
                        $product_variant_data = DB::table('product_variants')->select('id', 'variant_id', 'qty')
                        ->where('product_id', $product->id)->where('id', $cart[$i]['weight_id']);
                    }elseif($cart[$i]['color_id']){
                        $product_variant_data = DB::table('product_variants')->select('id', 'variant_id', 'qty')
                        ->where('product_id', $product->id)->where('id', $cart[$i]['color_id']);
                    }
                    
    
                    //deduct product variant quantity if exist
                    if($product_variant_data->first()) {
                        $variant_count = $product_variant_data->first()->qty - $cart[$i]['count'];
                        $product_variant_data->update([
                            'qty' => $variant_count
                        ]);
                    }
                }
    
            }

        switch ($request->payment_method){
            case 'cod':
                $order->update([
                   'type' => 'cod'
                ]);
				
				//Send SMS Confirmation
            	
				if($request->phone_number){
					$mobileNumber = $request->phone_number;
					$message = 'Thanks for your order. Order ID: TH'.date('ymd',strtotime($order->created_at)).$order->id.', Transaction ID #'.$post_data['tran_id'].' Visit techhut.com.bd/login to track your order.';
					Helper::sendSms($mobileNumber,$message);
				}
				
				
                return response()->json(['msg' => 'Order placed successfully!'], 200);
                break;
            case 'ssl':
                $order->update([
                    'type' => 'ssl'
                ]);
				
				//Send SMS Confirmation
            	
				if($request->phone_number){
					$mobileNumber = $request->phone_number;
					$message = 'Thanks for your order. Order ID: TH'.date('ymd',strtotime($order->created_at)).$order->id.', Transaction ID #'.$post_data['tran_id'].' Visit techhut.com.bd/login to track your order.';
					Helper::sendSms($mobileNumber,$message);
				}
				
                $sslc = new SslCommerzNotification();
                # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
                $payment_options = $sslc->makePayment($post_data, 'hosted');
                if (!is_array($payment_options)) {
                    print_r($payment_options);
                    $payment_options = array();
                }
                break;
            default:
                return response()->json(['msg' => 'An error occurred while processing your order!'], 404);
        }
    }


    public function checkout_submit(Request $request){
		
        if (!session()->has('cart')) {
			session()->flash('error', 'Invalid Session, unable to process your order!');
            return redirect('/');
        }
        $customer = Auth::user();
		if(!$customer){
			session()->flash('error', 'Please login first to place a order!');
			return redirect('/login');
		}
		
        $cart = session()->get('cart');
        $count = count($cart['product']) ?? [];
		$shipping_cost = 100;
		if($request->shipping_charge == 'insidedhaka'){
			$shipping_cost = 60;
		}elseif($request->shipping_charge == 'freeshipping'){
			$shipping_cost = 0;
		}else{
			$shipping_cost = 100;
		}
		
        $sub_total     = $cart['sub_total'] ?? 0;
		$total = $shipping_cost+$sub_total;
		$discounted_amount = 0;
		$used_coupon = '';
		if(session()->get('couponCart')){
			$total = $total - session()->get('couponCart')['value'];
			$discounted_amount = session()->get('couponCart')['value'];
			$used_coupon = session()->get('couponCart')['code'];
		}
 

        if ($count <= 0) {
            session()->flash('error', 'An error occurred while processing your order!');
            session()->forget('cart');
            return redirect('/');
        }

        if ($request->payment_method != 'ssl' && $request->payment_method != 'cod') {
            session()->flash('error', 'An error occurred while processing your order!');
            session()->forget('cart');
            return redirect('/');
        }


      /*    if ($request->shipping_address) {
            $request->validate([
               'shipping_name' => 'required',
               'shipping_phone_number' => 'required',
               'shipping_address' => 'required',
               'shipping_city' => 'required',
               'shipping_district' => 'required',
               'shipping_state' => 'required',
               'shipping_postal_code' => 'required',
            ]);
        }

        $customer->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'city' => $request->city,
            'district' => $request->district,
            'state' => $request->state,
            'postal_code' => $request->postal_code,

        ]); */

        $notes = $request->notes;


        # Here you have to receive all the order data to initate the payment.
        # Let's say, your oder transaction informations are saving in a table called "orders"
        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.
        $post_data = array();
        $post_data['total_amount'] = $total; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique
        # CUSTOMER INFORMATION
        $post_data['cus_name'] =  $request->name ?? "";
        $post_data['cus_email'] =  $request->email ?? "";
        $post_data['cus_add1'] = $request->address ?? "";
        $post_data['cus_add2'] = $request->address ?? "";
        $post_data['cus_city'] =  $request->city ?? "";
        $post_data['cus_state'] = $request->state ?? "";
        $post_data['cus_postcode'] = $request->postal_code ?? "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] =  $request->phone_number ?? "";;
        $post_data['cus_fax'] = "";
        # SHIPMENT INFORMATION
        $post_data['ship_name'] =  $request->shipping_name ?? $request->name;
        $post_data['ship_add1'] =  $request->shipping_address ?? $request->address;
        $post_data['ship_add2'] = $request->shipping_address ?? $request->address;
        $post_data['ship_city'] = $request->shipping_city ?? $request->city;
        $post_data['ship_district'] = $request->shipping_district ?? $request->district;
        $post_data['ship_state'] = $request->shipping_state ?? $request->state;
        $post_data['ship_postcode'] = $request->shipping_postal_code ?? $request->postal_code;
        $post_data['ship_phone'] = $request->shipping_phone_number ?? $request->phone_number;
        $post_data['ship_email'] = $request->shipping_email ?? $request->email;
        $post_data['ship_country'] = "Bangladesh";
        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Technology Goods";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";
        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";
		
		
		
		if (session()->has('couponCart')) {
            $couponCart = session()->get('couponCart');
			
			if($couponCart['code'] == 'WELCOME50'){
				$user = Auth::user();
				//Deduct number of coupon Who Registered
				$coupon = Coupon::where('code', 'WELCOME50')->where('customer_id',Auth::id())->first();
				$coupon->used += 1;
				$coupon->save();
				
				$already_used_count = DB::table('orders')->where('customer_id',Auth::id())->where('used_coupon','WELCOME50')->count();
				if($already_used_count < 1){
					$coupon_referrer_user = DB::table('customers')->where('id',Auth::id())->first();
					$coupon_referrer_id = $coupon_referrer_user->referrer_id;
				}
				

				
			}elseif($couponCart['code'] == 'WELCOME100'){
				$coupon = Coupon::where('code', 'WELCOME100')->where('customer_id',Auth::id())->first();
				$coupon->used += 1;
				$coupon->save();
			}else{
				$coupon = Coupon::where('code', $couponCart['code'])->first();
				$coupon->used += 1;
				$coupon->save();
			}
			
			session()->forget('couponCart');
        }
		
		


        #Before  going to initiate the payment order status need to insert or update as Pending.
        $update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'phone' => $post_data['cus_phone'],
                'amount' => $post_data['total_amount'],
                'status' => 'Pending',
                'address' => $post_data['cus_add1'],
                'city' => $post_data['cus_city'],
                'district' => $post_data['ship_district'],
                'state' => $post_data['cus_state'],
                'postal_code' => $post_data['cus_postcode'],
                'country' => 'Bangladesh',
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency'],
                'customer_id' => $customer->id,
                'origin' => 'Website',
                'notes' => $notes,
                'shipping_name'       => $post_data['ship_name'],
                'shipping_division'      => $post_data['ship_state'],
                'shipping_district'   => $post_data['ship_district'],
                'shipping_city'       => $post_data['ship_city'],
                'shipping_postalCode'=> $post_data['ship_postcode'],
                'shipping_address'    => $post_data['ship_add1'],
                'shipping_phone_number'=> $post_data['ship_phone'],
                'shipping_email'      => $post_data['ship_email'],
                'shipping_charge'      => $shipping_cost,
                'sub_total'          => $sub_total,
				'discounted_amount' => $discounted_amount,
				'used_coupon' => $used_coupon,
				'coupon_referrer_id' => $coupon_referrer_id ?? null
            ]);

        $order = Order::where('transaction_id', $post_data['tran_id'])->first();


		foreach($cart['product'] as $key=>$cartProduct ){
            OrderDetails::create([
                'order_id'   => $order->id,
                'product_id' => $cartProduct['product_id'],
                'count'      => $cartProduct['count'],
                'variant_id'   => $cartProduct['variant_id'] ?? null,
                'color_id'   => $cartProduct['color_id'] ?? null,
                'size_id'    => $cartProduct['size_id'] ?? null,
                'weight_id'  => $cartProduct['weight_id'] ?? null,
                'type_id'    => $cartProduct['type_id'] ?? null,
				'amount'    => $post_data['total_amount']
            ]);
			

            $warehouse = DB::table('product_warehouse')->where('product_id', $cartProduct['product_id'])->first();
            $product = Product::find($cartProduct['product_id']);
          
            if($warehouse){
                $warehouse_data = DB::table('product_warehouse')->where([
                    ['product_id', $cartProduct['product_id']],
                    ['warehouse_id', $warehouse->warehouse_id],
                ]);
    
                $product_quantity = $product->qty - $cartProduct['count'];
				
                $warehouse_count = $warehouse_data->first()->qty - $cartProduct['count'];
              
                $warehouse_data->update([
                    'qty' => $warehouse_count
                ]);
                
                $product->update([
                    'qty' => $product_quantity
                ]);
                    
            }
			
	
			if($cartProduct['is_variant'] == 1){
				$product_variant_data = DB::table('product_variants')->select('id', 'variant_id', 'qty')
					->where('product_id', $cartProduct['product_id'])->where('variant_id', $cartProduct['variant_id']);
					
				//deduct product variant quantity if exist
				if($product_variant_data->first()) {
					$variant_count = $product_variant_data->first()->qty - $cartProduct['count'];
					$product_variant_data->update([
						'qty' => $variant_count
					]);
				}
			}
			
			
           if($product->type == 'combo'){
				$ids = explode(',', $product->product_list);
				$qty_list = explode(',', $product->qty_list);
				  
				foreach($ids as $key => $id){
				    $ComboProduct = Product::find($id);
				    $product_quantity = $ComboProduct->qty - $qty_list[$key];
    				$ComboProduct->update([
                        'qty' => $product_quantity
                    ]);
				} 
			}

        }


        switch ($request->payment_method){
            case 'cod':
                $order->update([
                   'type' => 'cod'
                ]);
                //Send order confirmation email

                $order_info = DB::table('orders')->where('transaction_id', $post_data['tran_id'])->first();

				//Send SMS Confirmation
            	
				if($request->phone_number){
					$mobileNumber = $request->phone_number;
					$message = 'Thanks for your order. Order ID: TH'.date('ymd',strtotime($order->created_at)).$order_info->id.', Transaction ID #'.$post_data['tran_id'].' Visit techhut.com.bd/login to track your order.';
					Helper::sendSms($mobileNumber,$message);
				}

				//Send Email Confirmation
				$data = [];
                $data['order_id']     = 'TH'.$order_info->id;
                $data['shipping_cost']= $order_info->shipping_charge;
                $data['sub_total']   = $order_info->sub_total;

                $data['user_name']  = $order_info->name;
                $data['user_email'] = $order_info->email;
                $data['user_phone'] = $order_info->phone;
                $data['amount']     = $order_info->amount;
                $data['state']      = $order_info->state;
                $data['district']   = $order_info->district;
                $data['postal_code']= $order_info->postal_code;
                $data['address']    = $order_info->address;
                $data['currency']   = $order_info->currency;
                $data['created_at'] = $order_info->created_at;
                $data['tran_id']    =  $post_data['tran_id'];
                $recipient          = $order_info->email;
                $message = view('email.order',array('data'=> $data));
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: '.'TECHHUT'.' <info@techhut.com.bd>' . "\r\n";
                $send = mail($recipient, 'Thank You For Your Order.', $message, $headers);
                session()->flash('success', 'Order placed successfully!');
                session()->forget('cart'); 
				return redirect('/');
                break;
				
            case 'ssl':
			
                $order->update([
                    'type' => 'ssl'
                ]);

                $sslc = new SslCommerzNotification();
                # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
                $payment_options = $sslc->makePayment($post_data, 'hosted');

                if (!is_array($payment_options)) {
                    print_r($payment_options);
                    $payment_options = array();
                }
                break;
            default:
                session()->flash('error', 'An error occurred while processing your order!');
                session()->forget('cart');
                return redirect('/');
        }


    }

    
    public function pay_again(Request $request){

        $transaction_id = $request->transaction_id;
        $order = Order::where('transaction_id', $transaction_id)->first();
        $new_transaction_id = uniqid();

        if(!$order){
            session()->put('payment_message', 'An error occurred while processing your order! Please make a new order!');
            return back();
        }

        $post_data = array();
        $post_data['total_amount'] = $order->amount; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = $transaction_id; // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] =  $order->name ?? "";
        $post_data['cus_email'] =  $order->email ?? "";
        $post_data['cus_add1'] = $order->address ?? "";
        $post_data['cus_add2'] = $order->address ?? "";
        $post_data['cus_city'] =  $order->city ?? "";
        $post_data['cus_state'] = $order->state ?? "";
        $post_data['cus_postcode'] = $order->postal_code ?? "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] =  $order->phone ?? "";;
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] =  $order->shipping_name ?? $order->name;
        $post_data['ship_add1'] =  $order->shipping_address ?? $order->address;
        $post_data['ship_add2'] = $order->shipping_address ?? $order->address;
        $post_data['ship_city'] = $order->shipping_city ?? $order->city;
        $post_data['ship_district'] = $order->shipping_district ?? $order->district;
        $post_data['ship_state'] = $order->shipping_state ?? $order->state;
        $post_data['ship_postcode'] = $order->shipping_postal_code ?? $order->postal_code;
        $post_data['ship_phone'] = $order->shipping_phone_number ?? $order->phone;
        $post_data['ship_email'] = $order->shipping_email ?? $order->email;

		$post_data['ship_country'] = "Bangladesh";
        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Technology Goods";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";

        $shipping_cost = $order->shipping_cost ?? null;
        $sub_total     = $order->sub_total ?? null;
        
        switch ($order->type){

            case 'ssl':
                $order->update([
                    'type' => 'ssl'
                ]);


                $sslc = new SslCommerzNotification();
                # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
                $payment_options = $sslc->makePayment($post_data, 'hosted');

                if (!is_array($payment_options)) {
                    print_r($payment_options);
                    $payment_options = array();
                }
                break;
            default:
                session()->put('payment_message', 'An error occurred while processing your order!');
                return view('frontend.payment');
        }

    }

    


    public function success(Request $request){

        $tran_id = $request->input('tran_id');
        $amount  = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification();

        #Check order status in order tabel against the transaction id or order id.
        $order_detials = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();
	
        if ($order_detials->status == 'Pending' || $order_detials->status == 'Failed') {
            $validation = $sslc->orderValidate($tran_id, $amount, $currency, $request->all());
		
            if ($validation == TRUE) {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successfull transaction to customer
                */
                $update_product = DB::table('orders')
                    ->where('transaction_id', $tran_id)
                    ->update(['status' => 'Processing']);

               session()->flash('success', 'Transaction is successfully Completed');
                // echo "<br >Transaction is successfully Completed";
                $order_info       = DB::table('orders')->where('transaction_id', $tran_id)->first();

				if($order_info->phone){
					$mobileNumber = $order_info->phone;
					$message = 'Thanks for your order. Order ID: TH'.date('ymd',strtotime($order->created_at)).$order_info->id.', Transaction ID #'.$tran_id.' Visit techhut.com.bd/login to track your order.';
					Helper::sendSms($mobileNumber,$message);
				}

				
				//Send Email Confirmation
                $data = [];
                $data['order_id']   = $order_info->id;
                $data['shipping_cost']= $order_info->shipping_charge;
                $data['sub_total']   = $order_info->sub_total;
                $data['user_name']  = $order_info->name;
                $data['user_email'] = $order_info->email;
                $data['user_phone'] = $order_info->phone;
                $data['amount']     = $order_info->amount;
                $data['state']      = $order_info->state;
                $data['district']   = $order_info->district;
                $data['postal_code']= $order_info->postal_code;
                $data['address']    = $order_info->address;
                $data['currency']   = $order_info->currency;
                $data['created_at'] = $order_info->created_at;
                $data['tran_id']    =  $tran_id;
                $recipient = $order_info->email;
                $message = view('email.order',array('data'=> $data));
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: '.'TECHHUT'.' <info@techhut.com.bd>' . "\r\n";
                mail($recipient, 'Thank You For Your Order.', $message, $headers);
            } else {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel and Transation validation failed.
                Here you need to update order status as Failed in order table.
                */
                $update_product = DB::table('orders')
                    ->where('transaction_id', $tran_id)
                    ->update(['status' => 'Failed']);
                session()->flash('error', 'Validation Fail');
            }
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            /*
             That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
             */

			session()->flash('success', 'Transaction is successfully Completed');

        } else {
            #That means something wrong happened. You can redirect customer to your product page.
            session()->flash('error', 'Invalid Transaction');
        }
		session()->forget('cart'); 
        return redirect('/');

    }

    public function fail(Request $request){
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_detials->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Failed']);
			session()->flash('error', 'Invalid Transaction');
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
			session()->flash('success', 'Transaction is already Successful');
        } else {
			session()->flash('error', 'Transaction is Invalid');
        }
		session()->forget('cart'); 
		return redirect('/');
    }

    public function cancel(Request $request){
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_detials->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Canceled']);
			 session()->flash('error', 'Transaction is Canceled!');
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
           session()->flash('success', 'Transaction is already Successful');
        } else {
            session()->flash('error', 'Transaction is Invalid');
        }
		session()->forget('cart'); 
		return redirect('/');

    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'status', 'currency', 'amount')->first();
				
            if ($order_details->status == 'Pending' || $order_details->status == 'Failed') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($tran_id, $order_details->amount, $order_details->currency, $request->all());
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Processing']);

					session()->flash('success', 'Transaction is successfully Completed');
                } else {
                    /*
                    That means IPN worked, but Transation validation failed.
                    Here you need to update order status as Failed in order table.
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Failed']);

					 session()->flash('error', 'validation Fail');
					
                }

            } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {

                #That means Order status already updated. No need to udate database.

					session()->flash('success', 'Transaction is already successfully Completed');
            } else {
                #That means something wrong happened. You can redirect customer to your product page.
				session()->flash('error', 'Transaction is Invalid');
            }
        } else {
            session()->flash('error', 'Data is Invalid');
        }
		
		return redirect('/'); 
    } 

}

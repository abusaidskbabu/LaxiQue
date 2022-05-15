<?php

namespace App\Http\Controllers;

use App\AmarCare;
use App\Brand;
use App\Category;
use App\Coupon;
use App\Customer;
use App\Deal;
use App\FeaturedProduct;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Order;
use App\OrderDetails;
use App\Product;
use App\Sale;
use App\Size;
use App\Slider;
use App\SubCategory;
use App\Tag;
use App\Wishlist;
use Carbon\Carbon;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;

class ApiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:customer-api')->only([
            'wishlist', 'my_account', 'update_account', 'update_address', 'logout',
            'add_wishlist', 'remove_wishlist', 'cash_on_delivery', 'online_payment',
            'rate_product', 'coupon'
        ]);
    }


    public function products()
    {
        return Product::where('hide_on_website', 0)->with(['category', 'deal', 'variant', 'comments', 'brand']);
    }
   
    public function sliders()
    {
        $sliders = Slider::latest()->get();

        if ($sliders) {
            return response()->json($sliders, 200);
        } else {
            return response()->json('Sliders not found!', 404);
        }


    }
	public function all_products(){
		$products = Product::where('is_active',1)->where('hide_on_website', 0)->with(['category', 'deal', 'variant', 'comments', 'brand'])->paginate(12);
		 return response()->json($products, 200);
	}
	
	public function app_version(){
		$app_version = 0;
		$lims_general_setting_data = DB::table('general_settings')->where('id',1)->first();
		if($lims_general_setting_data){
			$app_version = $lims_general_setting_data->currency;
		}
		 return response()->json($app_version, 200);
	}
	
	
	
	public function products_by_brand(Request $request){
		$brand_id = $request->brand_id ;
		$products = Product::where('is_active',1)->where('hide_on_website', 0)->where('brand_id',$brand_id)->with(['category', 'deal', 'variant', 'comments', 'brand'])->paginate(12);
		return response()->json($products, 200);
	}
	
	
	public function all_brands(){
		$products = Brand::where('is_active',1)->get();
		 return response()->json($products, 200);
	}
	
	

    public function shops()
    {
        $shops = Category::all();

        if ($shops) {
            return response()->json($shops, 200);
        } else {
            return response()->json('Shops not found!', 404);
        }

    }

    public function new_arrivals()
    {

        $newProducts = $this->products()->where('is_active', 1)->where('hide_on_website', 0)->orderBy('id', 'DESC')->with('deal')->limit(15)->get();

        if ($newProducts->count() <= 0) {
            $newProducts = $this->products()->latest()->limit(20)->get();
        }

        foreach($newProducts as $p){
            $p->description = strip_tags($p->description);
            $p->short_description = strip_tags($p->short_description);
        }

        if ($newProducts) {
            return response()->json($newProducts, 200);
        } else {
            return response()->json('New products not found!', 404);
        }
    }

    public function shop_products($id)
    {
        $products = $this->products()->where('category_id', $id)
            ->with(['category', 'deal', 'variant', 'comments', 'brand'])
            ->get();

        foreach($products as $p){
            $p->description = strip_tags($p->description);
            $p->short_description = strip_tags($p->short_description);
        }

        if ($products) {
            return response()->json($products, 200);
        } else {
            return response()->json('Products not found!', 404);
        }

    }

    public function featured_products()
    {

        $featuredProdIds = Product::where('featured', 1)->where('hide_on_website', 0)->pluck('id');
        $featuredCatIds = Product::where('featured', 1)->where('hide_on_website', 0)->pluck('category_id');
        $featuredCategories = Category::where('is_active',1)->whereIn('id', $featuredCatIds)->get();

        $featuredProducts = $this->products()->whereIn('id', $featuredProdIds)->where('hide_on_website', 0);

        $data = [];

        foreach ($featuredCategories as $i => $fc) {
            $data[$i]['categoryName'] = $fc->name;
            $data[$i]['Products'] = $fc->products()->whereIn('id', $featuredProdIds)
                ->with(['category', 'deal', 'variant', 'comments', 'brand'])
                ->get();
        }

        foreach ($data as $d) {
            foreach ($d['Products'] as $p) {
                $p->description = strip_tags($p->description);
                $p->short_description = strip_tags($p->short_description);
            }
        }

        if ($data) {
            return response()->json($data, 200);
        } else {
            return response()->json('Featured products not found!', 404);
        }

    }

    public function search_products($query)
    {

        $products = $this->products()->where('name', 'LIKE', '%'.$query.'%')->where('hide_on_website', 0)->get();

        foreach($products as $p){
            $p->description = strip_tags($p->description);
            $p->short_description = strip_tags($p->short_description);
        }

        if ($products) {
            return response()->json($products, 200);
        } else {
            return response()->json('Search products not found!', 404);
        }
    }

    public function tag_search($tagName)
    {
        $tag = Tag::where('name', $tagName)->first();

        $products = $tag->products()->where('hide_on_website', 0)->with(['category', 'sale', 'specifications', 'colors', 'sizes', 'tags', 'comments'])->get();

        foreach($products as $p){
            $p->description = strip_tags($p->description);
            $p->short_description = strip_tags($p->short_description);
        }

        if ($products) {
            return response()->json($products, 200);
        } else {
            return response()->json('Tag products not found!', 404);
        }

    }

    public function wishlist()
    {
        $customer = auth('customer-api')->user();

        $prodIds = $customer->wishlist()->pluck('product_id');
        $wishIds = $customer->wishlist()->pluck('id');

        $wishlist = $this->products()->where('hide_on_website', 0)->whereIn('id', $prodIds)->get();

        foreach($wishlist as $i => $p) {
            $p->description = strip_tags($p->description);
            $p->short_description = strip_tags($p->short_description);
            $p->wish_id = $wishIds[$i];
        }

        if ($wishlist) {
            return response()->json($wishlist, 200);
        } else {
            return response()->json('Wishlist products not found!', 404);
        }

    }

    public function amarcare()
    {
        $vlogs = Category::with('vlogs')->get();

        foreach ($vlogs as $vlog) {
            foreach ($vlog->vlogs as $v) {
                $v->description = strip_tags($v->description);
            }
        }

        if ($vlogs) {
            return response()->json($vlogs, 200);
        } else {
            return response()->json('Vlogs not found!', 404);
        }


    }

    public function filter_product(Request $request)
    {
        $category_id = $request->category_id;
        $brand_id = $request->brand_id;

        $minPrice = (int)$request->min_amount;
        $maxPrice = (int)$request->max_amount;

        $data = $this->products()->where('hide_on_website', 0)->whereBetween('price', [$minPrice, $maxPrice]);

        if ($category_id != -1) {
            $data->where('category_id', $category_id);
        }

        if ($brand_id != -1) {
            $data->where('brand_id', $brand_id);
        }

        $data = $data->get();

        foreach($data as $p){
            $p->description = strip_tags($p->description);
            $p->short_description = strip_tags($p->short_description);
        }

        if ($data) {
            return response()->json($data, 200);
        } else {
            return response()->json('Filter products not found!', 404);
        }

    }

    public function sale_products()
    {
        $saleProdIds = Deal::latest()->where('expire', '>', now())->where('percentage', '!=', 'NULL')->pluck('product_id');

        $saleProducts = $this->products()->where('hide_on_website', 0)->whereIn('id', $saleProdIds)->get();

        foreach($saleProducts as $p){
            $p->description = strip_tags($p->description);
            $p->short_description = strip_tags($p->short_description);
        }

        if ($saleProducts) {
            return response()->json($saleProducts, 200);
        } else {
            return response()->json('Sale products not found!', 404);
        }
    }

    public function deal_products()
    {
        $dealProdIds = Deal::latest()->where('expire', '>', now())->where('price', '!=', 'NULL')->pluck('product_id');

        $dealProducts = $this->products()->where('hide_on_website', 0)->whereIn('id', $dealProdIds)->get();

        foreach($dealProducts as $p){
            $p->description = strip_tags($p->description);
            $p->short_description = strip_tags($p->short_description);
        }

        if ($dealProducts) {
            return response()->json($dealProducts, 200);
        } else {
            return response()->json('Deal products not found!', 404);
        }
    }

    public function my_account(Request $request)
    {
        $customer = auth('customer-api')->user();

        foreach($customer->orders as $order){
            $order->notes = strip_tags($order->notes);
        }
		
        if ($customer) {
            return response()->json($customer, 200);
        } else {
            return response()->json('Customer not found!', 404);
        }
    }
	
	public function order_details(Request $request)
    {
        $customer = auth('customer-api')->user();
		$order_id = $request->order_id;
		$customer_id = $request->customer_id;
		
		$singleOrder = Order::where('customer_id',$customer_id)->where('id',$order_id)->with('order_details')->get();
		foreach($singleOrder as $o){
			if($o->order_details){
				foreach($o->order_details as $details){
					$details->product_name = Product::find($details->product_id)->name;
					$details->product_slug = Product::find($details->product_id)->slug;
					$details->product_image = Product::find($details->product_id)->image;
					$details->product_price = \Helper::getPrice( $details->product_id, Product::find($details->product_id)->price);
					if($details->variant_id){
						$details->variant_name = \Helper::getVarinat($details->variant_id)->variant_name.' ('.\Helper::getVarinat($details->variant_id)->item_code.')';
					}else{
						$details->variant_name = 'N/A';
					}
					
				}
			}
			
			if($o->updated_at){
			   $o->placement_time = $o->updated_at->format('d F, Y');
			}
		}
		
		
        if ($singleOrder) {
            return response()->json($singleOrder, 200);
        } else {
            return response()->json('Customer not found!', 404);
        }
    }
	
	
	

    public function filter_attributes()
    {
        $brands = Brand::all();
        $categories = Category::all();

        return response()->json([
            'brands' => $brands,
            'categories' => $categories,
        ], 200);
    }

    public function random_products()
    {
        $products = $this->products()->where('hide_on_website', 0)->limit(6)->get();

        foreach($products as $p){
            $p->description = strip_tags($p->description);
            $p->short_description = strip_tags($p->short_description);
        }

        if ($products) {
            return response()->json($products, 200);
        } else {
            return response()->json('Random products not found!', 404);
        }

    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'Login failed'], 404);
        }

        try {

			if(is_numeric($request->get('email'))){
				$credentials = ['phone_number'=>$request->get('email'),'password'=>$request->get('password')];
			}elseif(filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
				$credentials = ['email' => $request->get('email'), 'password'=>$request->get('password')];
			}
			
			if (! $token = auth('customer-api')->attempt($credentials)) {
				return response()->json(['msg' => 'Credentials not found!'], 404);
			}
		
        } catch (JWTException $e) {
            return response()->json(['msg' => 'Token creation failed!'], 404);
        }

        return response()->json([
            'customer' => \auth('customer-api')->user(),
            'token' => $token,
            'expire' => auth('customer-api')->factory()->getTTL() * 6000
        ], 200);
    }
    
    

    

    public function social_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|required',
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'Login failed'], 404);
        }

        $name = $request->input('name');
        $email = $request->input('email');

        // check if they're an existing user
        $existingCustomer = Customer::where('email', $email)->first();

        if($existingCustomer){
            // log them in
            $token = auth('customer-api')->login($existingCustomer, true);
        } else {
            // create a new user
            $newCustomer = Customer::create([
               'name' => $name,
               'email' => $email,
            ]);

            $token = auth('customer-api')->login($newCustomer, true);
        }

        if (!$token) {
            return response()->json(['msg' => 'Credentials not found!'], 404);
        }

        return response()->json([
            'customer' => \auth('customer-api')->user(),
            'token' => $token,
            'expire' => auth('customer-api')->factory()->getTTL() * 60
        ], 200);

    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'email|required|unique:customers',
            'password' => 'required|confirmed|min:6',
            'name' => 'required',
            'phone_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'Registration failed'], 404);
        }

        $data = $request->validate([
            'email' => 'email|required|unique:customers',
            'password' => 'required|confirmed|min:6',
            'name' => 'required',
            'phone_number' => 'required',
        ]);
		
		
		if ($request->referral_code) {
			$referral_id = base64_decode($request->referral_code);
			
			if(!DB::table('customers')->where('id',$referral_id)->exists()){
				return response()->json('Invalid Referral Code!', 404);
			}

			$has_coupon = DB::table('coupons')->where('customer_id',$referral_id)->where('code','WELCOME50')->first();
			
			if(!$has_coupon){
				$coupon_data['code'] = 'WELCOME50';
				$coupon_data['type'] = 'fixed';
				$coupon_data['amount'] = 50;
				$coupon_data['minimum_amount'] = 500;
				$coupon_data['quantity'] = 0;
				$coupon_data['used'] = 0;
				$coupon_data['expired_date'] =  date('Y-m-d H:i:s',strtotime('+5 years',strtotime('01/01/2021'))) . PHP_EOL;;
				$coupon_data['user_id'] = 1;
				$coupon_data['customer_id'] = $referral_id;
				$coupon_data['coupon_type'] = 'individual';
				$coupon_data['is_active'] = 1;
				$coupon_data['created_at'] = date('Y-m-d H:i:s');
				$coupon_data['updated_at'] = date('Y-m-d H:i:s');
				DB::table('coupons')->insert($coupon_data);
			}
        }
		

        $customer = DB::table('customers')->insertGetId([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'name' => $data['name'],
            'phone_number' => $data['phone_number'],
            'customer_group_id' => 3,
            'is_active' => 1,
			'registration_from' => 'app',
			'referrer_id' => $referral_id ?? null
        ]);
		
		
		if ($request->referral_code) {
			$coupon_data['code'] = 'WELCOME50';
			$coupon_data['type'] = 'fixed';
			$coupon_data['amount'] = 50;
			$coupon_data['minimum_amount'] = 500;
			$coupon_data['quantity'] = 1;
			$coupon_data['used'] = 0;
			$coupon_data['expired_date'] =  date('Y-m-d H:i:s',strtotime('+5 years',strtotime('01/01/2021'))) . PHP_EOL;;
			$coupon_data['user_id'] = 1;
			$coupon_data['customer_id'] = $customer;
			$coupon_data['coupon_type'] = 'individual';
			$coupon_data['is_active'] = 1;
			$coupon_data['created_at'] = date('Y-m-d H:i:s');
			$coupon_data['updated_at'] = date('Y-m-d H:i:s');
			DB::table('coupons')->insert($coupon_data);
			
			$coupon_data['code'] = 'WELCOME100';
			$coupon_data['amount'] = 100;
			DB::table('coupons')->insert($coupon_data);
        }else{
			$coupon_data['code'] = 'WELCOME100';
			$coupon_data['amount'] = 100;
			$coupon_data['type'] = 'fixed';
			$coupon_data['minimum_amount'] = 500;
			$coupon_data['quantity'] = 1;
			$coupon_data['used'] = 0;
			$coupon_data['expired_date'] =  date('Y-m-d H:i:s',strtotime('+5 years',strtotime('01/01/2021'))) . PHP_EOL;;
			$coupon_data['user_id'] = 1;
			$coupon_data['customer_id'] = $customer;
			$coupon_data['coupon_type'] = 'individual';
			$coupon_data['is_active'] = 1;
			$coupon_data['created_at'] = date('Y-m-d H:i:s');
			$coupon_data['updated_at'] = date('Y-m-d H:i:s');
			DB::table('coupons')->insert($coupon_data);
		}
		
		
        if ($customer) {
            return response()->json(['msg' => 'Registration successful'], 200);
        } else {
            return response()->json('Registration failed', 404);
        }

    }

    public function logout()
    {
        auth('customer-api')->logout();

        return response()->json(['msg' => 'Logged out successfully'], 200);
    }

    public function update_account(Request $request)
    {

        $customer = auth('customer-api')->user();

        $validator = Validator::make($request->all(), [
            'email' => 'unique:customers,email,'.$customer->id,
            //'password' => 'required|min:6',
            'name' => 'required',
            'phone_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'Update failed'], 404);
        }

        $receive_offer = $request->receive_offer ? true : false;
        $newsletter = $request->newsletter ? true : false;

        $response = $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            //'password' => bcrypt($request->password),
            'birthdate' => $request->birthdate,
            'receive_offer' => $receive_offer,
            'newsletter' => $newsletter,

        ]);

        if ($response) {
            return response()->json([
                'customer' => $customer,
                'msg'=>'Account updated successfully'
            ], 200);
        } else {
            return response()->json('Update failed', 404);
        }


    }

    public function update_address(Request $request)
    {

        $customer = auth('customer-api')->user();

        $customer->update([
            'country' => 'bangladesh',
            'state' => $request->state,
            'district' => $request->district,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
        ]);

        if ($customer) {
            return response()->json([
                'customer' => $customer,
                'msg'=>'Account updated successfully'
            ], 200);
        } else {
            return response()->json('Update failed', 404);
        }
    }

    public function add_wishlist(Request $request)
    {
        $customer = auth('customer-api')->user();

        $wishlist = Wishlist::firstOrCreate([
            'customer_id' => $customer->id,
            'product_id' => $request->productId
        ]);

        $wishlistCount = $customer->wishlist()->count();

        if ($wishlist) {
            return response()->json([
                'msg' => 'Product added to wishlist',
                'wishlistCount' => $wishlistCount
            ], 200);
        } else {
            return response()->json('Add to wishlist failed', 404);
        }

    }

    public function remove_wishlist(Request $request)
    {
        $wishId = $request->wish_id;
        $wishlist = Wishlist::findOrFail($wishId);
        $response = $wishlist->delete();

        $wishlistCount = \auth('customer-api')->user()->wishlist()->count();

        if ($response) {
            return response()->json([
                'msg' => 'Product removed from wishlist',
                'wishlistCount' => $wishlistCount
            ], 200);
        } else {
            return response()->json('Remove from wishlist failed', 404);
        }

    }


    public function cash_on_delivery(Request $request)
    {

        $customer = auth('customer-api')->user();


         $cart = json_decode($request->cart);

         $count = count($cart);

         $total = $request->total;



        if (!$cart) {
            return response()->json([
                'msg' => 'Please add products and choose shipping location'
            ], 404);
        }

        if ($count <= 0 || $total <= 0) {
            return response()->json([
                'msg' => 'An error occurred while processing your order!'
            ], 404);
        }

        if ($request->payment_method != 'cod') {
            return response()->json([
                'msg' => 'An error occurred while processing your order!'
            ], 404);
        }

        $billing_address = $request->street . '+';
        $billing_address .= $request->city . '+';
        $billing_address .= $request->district . '+';
        $billing_address .= ucfirst($request->division);



        if ($request->shipping_address) {
            $request->validate([
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

        ]);

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
        $post_data['product_name'] = "Mridha Enterprise";
        $post_data['product_category'] = "Mridha Enterprise";
        $post_data['product_profile'] = "Mridha Enterprise";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";


        #Before  going to initiate the payment order status need to insert or update as Pending.
        $update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'name' => $post_data['ship_name'],
                'email' => $post_data['ship_email'],
                'phone' => $post_data['ship_phone'],
                'amount' => $post_data['total_amount'],
                'status' => 'Pending',
                'address' => $post_data['ship_add1'],
                'city' => $post_data['ship_city'],
                'district' => $post_data['ship_district'],
                'state' => $post_data['ship_state'],
                'postal_code' => $post_data['ship_postcode'],
                'country' => 'Bangladesh',
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency'],
                'customer_id' => $customer->id,
                'origin' => 'Website',
                'notes' => $notes,
                'created_at' => now(),
            ]);

        $order = Order::where('transaction_id', $post_data['tran_id'])->first();

        $warehouse = DB::table('warehouses')->where('name', '=', 'Shop')->first();


        for ($i = 0; $i < $count; $i++) {
            OrderDetails::create([
                'order_id' => $order->id,
                'product_id' => $cart[$i]['product_id'],
                'count' => $cart[$i]['count'],
                'color_id' => $cart[$i]['color_id'],
                'size_id' => $cart[$i]['size_id'],
            ]);

            $product = Product::find($cart[$i]['product_id']);

            $warehouse_data = DB::table('product_warehouse')->where([
                ['product_id', $product->id],
                ['warehouse_id', $warehouse->id ],
            ]);


            $product_quantity = $product->qty - $cart[$i]['count'];
            $warehouse_count = $warehouse_data->first()->qty - $cart[$i]['count'];

            $warehouse_data->update([
                'qty' => $warehouse_count
            ]);

            $product->update([
                'qty' => $product_quantity
            ]);



            $product_variant_data = DB::table('product_variants')->select('id', 'variant_id', 'qty')
                ->where('product_id', $product->id)->where('id', $cart[$i]['size_id']);

            //deduct product variant quantity if exist
            if($product_variant_data->first()) {
                $variant_count = $product_variant_data->first()->qty - $cart[$i]['count'];
                $product_variant_data->update([
                    'qty' => $variant_count
                ]);
            }

        }

        $couponCart = $request->couponCart;

        if ($couponCart) {
            $coupon = Coupon::where('code', $couponCart['code'])->first();
            $coupon->used += 1;
            $coupon->save();
        }

        switch ($request->payment_method)
        {
            case 'cod':
                $order->update([
                    'type' => 'cod'
                ]);

                return response()->json([
                    'msg' => 'Order placed successfully!'
                ], 200);

                break;

            default:

                return response()->json([
                    'msg' => 'An error occurred while processing your order!'
                ], 404);

        }
    }


    public function online_payment(Request $request)
    {

        $customer = auth('customer-api')->user();


        $cart = json_decode($request->cart);

        $count = count($cart);

        $total = $request->total;



        if (!$cart) {
            return response()->json([
                'msg' => 'Please add products and choose shipping location'
            ], 404);
        }

        if ($count <= 0 || $total <= 0) {
            return response()->json([
                'msg' => 'An error occurred while processing your order!'
            ], 404);
        }

        if ($request->payment_method != 'cod') {
            return response()->json([
                'msg' => 'An error occurred while processing your order!'
            ], 404);
        }

        $billing_address = $request->street . '+';
        $billing_address .= $request->city . '+';
        $billing_address .= $request->district . '+';
        $billing_address .= ucfirst($request->division);



        if ($request->shipping_address) {
            $request->validate([
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

        ]);

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
        $post_data['product_name'] = "Mridha Enterprise";
        $post_data['product_category'] = "Mridha Enterprise";
        $post_data['product_profile'] = "Mridha Enterprise";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";


        #Before  going to initiate the payment order status need to insert or update as Pending.
        $update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'name' => $post_data['ship_name'],
                'email' => $post_data['ship_email'],
                'phone' => $post_data['ship_phone'],
                'amount' => $post_data['total_amount'],
                'status' => 'Pending',
                'address' => $post_data['ship_add1'],
                'city' => $post_data['ship_city'],
                'district' => $post_data['ship_district'],
                'state' => $post_data['ship_state'],
                'postal_code' => $post_data['ship_postcode'],
                'country' => 'Bangladesh',
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency'],
                'customer_id' => $customer->id,
                'origin' => 'Website',
                'notes' => $notes,
                'created_at' => now(),
            ]);

        $order = Order::where('transaction_id', $post_data['tran_id'])->first();

        $warehouse = DB::table('warehouses')->where('name', '=', 'Shop')->first();


        for ($i = 0; $i < $count; $i++) {
            OrderDetails::create([
                'order_id' => $order->id,
                'product_id' => $cart[$i]['product_id'],
                'count' => $cart[$i]['count'],
                'color_id' => $cart[$i]['color_id'],
                'size_id' => $cart[$i]['size_id'],
            ]);

            $product = Product::find($cart[$i]['product_id']);

            $warehouse_data = DB::table('product_warehouse')->where([
                ['product_id', $product->id],
                ['warehouse_id', $warehouse->id ],
            ]);


            $product_quantity = $product->qty - $cart[$i]['count'];
            $warehouse_count = $warehouse_data->first()->qty - $cart[$i]['count'];

            $warehouse_data->update([
                'qty' => $warehouse_count
            ]);

            $product->update([
                'qty' => $product_quantity
            ]);



            $product_variant_data = DB::table('product_variants')->select('id', 'variant_id', 'qty')
                ->where('product_id', $product->id)->where('id', $cart[$i]['size_id']);

            //deduct product variant quantity if exist
            if($product_variant_data->first()) {
                $variant_count = $product_variant_data->first()->qty - $cart[$i]['count'];
                $product_variant_data->update([
                    'qty' => $variant_count
                ]);
            }

        }

        $couponCart = $request->couponCart;

        if ($couponCart) {
            $coupon = Coupon::where('code', $couponCart['code'])->first();
            $coupon->used += 1;
            $coupon->save();
        }


        switch ($request->payment_method)
        {
            case 'ssl':
                $order->update([
                    'type' => 'ssl'
                ]);

                $sslc = new SslCommerzNotification();

                $payment_options = $sslc->makePayment($post_data);

                if (!is_array($payment_options)) {
                    print_r($payment_options);
                    $payment_options = array();
                }

                break;

            default:

                return response()->json([
                    'msg' => 'An error occurred while processing your order!'
                ], 404);

        }
    }

    public function similar_products($shopId)
    {
        $related_products = $this->products()->where('category_id', $shopId)->limit(20)->get();

        foreach ($related_products as $p) {
            $p->description = strip_tags($p->description);
            $p->short_description = strip_tags($p->short_description);
        }

        if ($related_products) {
            return response()->json($related_products, 200);
        } else {
            return response()->json([
                'msg' => 'No related products found!'
            ], 404);
        }
    }

    public function rate_product(Request $request)
    {

        $product = Product::findOrFail($request->product_id);
        $star = $request->star;
        $comment = $request->comment;
        $customer = auth('customer-api')->user();

        $customer->comment($product, $comment, $star);

        $comments = $product->comments;

        return response()->json([
            'comments' => $comments,
        ], 200);

    }

    public function coupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->coupon)->first();
        $cart_sub_total = $request->cart_sub_total;

        if (!$coupon) {
            return response()->json([
                'msg' => 'Invalid Coupon',
            ], 404);
        }
        else {
            if ($coupon->expired_date <= Carbon::now()) {
                return response()->json([
                    'msg' => 'Coupon Expired!',
                ], 404);
            }
            if ($coupon->minimum_amount > $cart_sub_total) {
                return response()->json([
                    'msg' => 'Minimum amount for this coupon is not reached!',
                ], 404);
            }
            if ($coupon->user_id != \auth('customer-api')->id()) {
                return response()->json([
                    'msg' => 'You are not eligible for this coupon!',
                ], 404);
            }
            if ($coupon->quantity <= $coupon->used) {
                return response()->json([
                    'msg' => 'You have used all of your coupons!',
                ], 404);
            }
            else {
                switch ($coupon->type) {
                    case 'fixed':
                        $coupon_value = $coupon->amount;
                        break;
                    case 'percentage':
                        $coupon_value = ($cart_sub_total * $coupon->amount) / 100;
                }
                $validCoupon = [
                    'code' => $coupon->code,
                    'value' => $coupon_value,
                ];

                return response()->json([
                    'validCoupon' => $validCoupon,
                ], 200);
            }
        }

    }
	
	
	public function feedback(Request $request){
        $request->validate([
            'feedback' => 'required',
            'age' => 'required | numeric'
        ]);
		
        $data['feedback']   = $request->feedback;
        $data['age']    	= $request->age;
        $data['comment']    = $request->feedback_comment;
        DB::table('feedback')->insert($data);
		return response()->json('success', 200);
    }
	
	public function request_for_restock(Request $request) {
		if(!$request->user_id){
			return response()->json('Please login to request for restock a product!', 400);
        }
        $product = Product::find($request->product_id);
		$data['user_id'] = $request->user_id;
		$data['product_id'] = $request->product_id;
		$data['product_name'] = $product ->name;
		DB::table('product_restock')->insert($data);
        return response()->json('Product has been successfully requested for restock..!', 200);
    }
	
	public function apply_coupon(Request $request){
		
		
		$cart_sub_total = $request->sub_total;
		$user_id = $request->user_id;
		$coupon_code = strtoupper($request->coupon);

		if($coupon_code == 'WELCOME50'){
			$coupon = Coupon::where('code', $request->coupon)->where('customer_id',$user_id)->first();
		}elseif($coupon_code == 'WELCOME100'){
			$coupon = Coupon::where('code', $request->coupon)->where('customer_id',$user_id)->first();
		}else{
			$coupon = Coupon::where('code', $request->coupon)->first();
		}
		

        if (!$coupon) {
			return response()->json('Invalid Coupon!', 400);
        }
		
        else {
			
            if ($coupon->expired_date <= Carbon::now()) {
				return response()->json('Coupon Expired!', 400);
            }
            if ($coupon->minimum_amount > $cart_sub_total) {
				return response()->json('Minimum amount for this coupon is not reached!', 400);
            }

            if ($coupon->quantity <= $coupon->used) {
				return response()->json('All coupons are already used!', 400);
            }
			

			
			if ($coupon->is_active != 1) {
				return response()->json('Invalid Coupon!', 400);
            }else {
                switch ($coupon->type) {
                    case 'fixed':
                        $coupon_value = $coupon->amount;
                        break;
                    case 'percentage':
                        $coupon_value = ($cart_sub_total * $coupon->amount) / 100;
                }
                $couponCart = [
                  'code' => $coupon->code,
                  'value' => $coupon_value,
                ];

				return response()->json($couponCart, 200);
            }
        }
	}
	
	public function user_coupon_list(Request $request){
		$user_id = $request->user_id;
		$data['coupon_list'] = DB::table('coupons')->where('customer_id',$user_id)->get();
		$data['referral_code'] = base64_encode($user_id);
		return response()->json($data, 200);
	}
	
	
	
	function product_details(Request $request){
		$slug = $request->slug;
		$user_id = $request->user_id;
		
        $products = Product::where('slug',$slug)->with('deal')->where('is_active',1)->where('hide_on_website', 0)->first();
        if($products){
            $reviews = DB::table('comments')->where('commentable_type','App\Product')->where('commentable_id',$products->id)->latest()->limit(15)->get();
			$already_requested = DB::table('product_restock')->where('user_id',$user_id)->where('product_id',$products->id)->first();
			
            foreach($reviews as $review){
                $review->created_at = date('d M, Y', strtotime($review->created_at));
            }


            $comboData = [];
            if($products->type == 'combo'){
                $combolistids = explode(',',$products->product_list);
                foreach($combolistids as $pid){
                    $comboData[] = Product::find($pid);
                }
                $products->product_list = $comboData;
            }

           $size   = DB::table('product_variants')->where('product_id',$products->id)->where('variant_by','size')->get();
           $color  = DB::table('product_variants')->where('product_id',$products->id)->where('variant_by','color')->get();
           $weight = DB::table('product_variants')->where('product_id',$products->id)->where('variant_by','weight')->get();

           $brands   = DB::table('brands')->where('id', $products->brand_id)->first();
           if($brands){
            $brand = $brands->title;
           }

           $categorys= DB::table('categories')->where('id', $products->category_id)->first();
           if($categorys){
            $category = $categorys->name;
           }

           $related_product = Product::latest()->where('category_id',$products->category_id)->with('deal')->where('is_active',1)->where('hide_on_website', 0)->get();
           
           foreach($related_product as $product){
                $product->image = explode(',',$product->image);
            }

            $is_reviewable = false;
            $order_for_current_user = DB::table('orders')
            ->select('orders.user_id','order_details.product_id')
            ->join('order_details','order_details.order_id','=','orders.id')
            ->where(['orders.user_id' => $user_id, 'order_details.product_id' => $products->id])
            ->get();

 
            if(count($order_for_current_user) > 0){
                $is_reviewable = true; 
            }

            $brands = DB::table('brands')->where('is_active',1)->orderBy('sort_order','asc')->get();
            $newproducts = Product::where('is_active',1)->where('hide_on_website', 0)->latest()->limit(3)->get();
            $featuedProducts = Product::where('is_active',1)->where('hide_on_website', 0)->where('featured',1)->latest()->limit(3)->get();
    
    
            foreach($newproducts as $product){
                $product->image = explode(',',$product->image);
            }
            foreach($featuedProducts as $product){
                $product->image = explode(',',$product->image);
            }

            $product_img_array = explode(',',$products->image);
			
            $data = [
                'product' => $products,
                'newproducts' => $newproducts,
                'featuedProducts' => $featuedProducts,
                'brands' => $brands,
                'product_img_array' => $product_img_array,
                'related_product' => $related_product,
                'brand'   => $brand??null,
                'category'=> $category??null,
                'size'    => $size??null,
                'color'   => $color??null,
                'weight'  => $weight??null,
                'is_reviewable' => $is_reviewable,
                'reviews'  => $reviews,
				'restock'	=> [
					'allow_backorder' => $products->allow_backorder,
					'already_requested' => $already_requested ? 1 : 0
				
				]
            ];
			
			return response()->json( $data, 200);
			
        }else{
            return response()->json( 'No product Found!', 400);
        }

    }

}

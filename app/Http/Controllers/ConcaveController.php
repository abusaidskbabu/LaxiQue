<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Inertia\Inertia;
use Auth;
use Session;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Coupon;
use App\Models\Blog;
use Validator;
use Hash;

class ConcaveController extends Controller{

    public function dashboard($slug = null,Request $request){
        $user = Auth::user();
		$singleOrder = [];
        $orders = Order::where('customer_id',Auth::id())->with('order_details')->orderBy('id','desc')->get();
        foreach($orders as $order){
            if($order->order_details){
                foreach($order->order_details as $details){
                    $details->product_name = Product::find($details->product_id)->name ?? ''; 
                    $details->product_image = Product::find($details->product_id)->image ?? '';
					$details->product_slug = Product::find($details->product_id)->slug ?? '';
					
                }
            }
			
			$order->formated_id = $order->updated_at->format('ymd').$order->id; 
			
			if($order->updated_at){
			   $order->placement_time = $order->updated_at->format('d F,Y');
			}

        }
        if(!$slug){
            $pageTitle = 'Account Details';
        }else{
            $pageTitle = str_replace('-',' ',$slug);
        }
        
		if($slug == 'order-details'){
			$order_id = $request->id;
			$singleOrder = Order::where('customer_id',Auth::id())->where('id',$order_id)->with('order_details')->get();
			
				foreach($singleOrder as $o){
					$o->formated_id = $o->updated_at->format('ymd').$order->id; 
					if($o->order_details){
						foreach($o->order_details as $details){
							$details->product_name = Product::find($details->product_id)->name;
							$details->product_image = Product::find($details->product_id)->image;
							$details->product_slug = Product::find($details->product_id)->slug ?? '';
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
		}
		

		$myCoupon = DB::table('coupons')->where('customer_id',Auth::id())->where('is_active',1)->get();
		$myRef = base64_encode($user->id);

		
		if($slug == 'track-order'){
			$order_id = $request->id;
			$singleOrder = Order::where('customer_id',Auth::id())->where('id',$order_id)->with('order_details')->get();
				foreach($singleOrder as $o){
					$o->formated_id = $o->updated_at->format('ymd').$order->id; 
					if($o->order_details){
						foreach($o->order_details as $details){
							$details->product_name = Product::find($details->product_id)->name;
							$details->product_image = Product::find($details->product_id)->image;
							$details->product_slug = Product::find($details->product_id)->slug ?? '';
							$details->product_price = \Helper::getPrice( $details->product_id, Product::find($details->product_id)->price);
							if($details->variant_id){
								$details->variant_name = \Helper::getVarinat($details->variant_id)->variant_name.' ('.\Helper::getVarinat($details->variant_id)->item_code.')';
							}else{
								$details->variant_name = 'N/A';
							}
							
						}
					}
					
					if($o->updated_at){
						$o->updated_at->addDays(5);
					   $o->delivery_time = $order->updated_at->format('d F, Y');
					}
				}
		}
		
        return Inertia::render('Dashboard', [
            'pageSlug'  => $slug,
            'user'      => $user,
            'orders'    => $orders,
            'singleOrder' => $singleOrder,
            'pageTitle' => $pageTitle,
            'myCoupon' => $myCoupon,
			'myRef'	=> $myRef
        ]);
    }

    public function change_password(Request $request){
        $old_password = $request->old_password;
        $new_password = $request->new_password;
        $confirm_password = $request->confirm_password;
        $rules = [
            'old_password' => 'required',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required | min:6'
        ];
        $message = [];

        $validator = Validator::make($request->all(), $rules,$message);
        
        if ($validator->fails()) {
            $msg =  implode('<br>',$validator->errors()->all());
            session()->flash('error', $msg); 
            return redirect()->back();
        }else{
            $current_password = Auth::User()->password;           
            if(Hash::check($request->old_password, $current_password)){          
                $user_id = Auth::User()->id;                       
                $user = DB::table('web_users')->where('id',$user_id)->update(['password' =>Hash::make($request->password) ]);
                session()->flash('success', 'Your password has been successfully changed!'); 
                return redirect()->back();
            }
            else{           
                session()->flash('error', 'Please enter correct old password!'); 
                return redirect()->back();
            }

        }
    }

    public function change_account_detals(Request $request){
        $data = [];
        $data['name'] =  $request->name;
        $data['district'] =  $request->district;
        $data['state'] =  $request->state;
        $data['city'] =  $request->city;
        $data['address'] =  $request->address;
        $data['postal_code'] =  $request->postal_code;
        $data['shipping_state'] =  $request->shipping_state;
        $data['shipping_district'] =  $request->shipping_district;
        $data['shipping_city'] =  $request->shipping_city;
        $data['shipping_address'] =  $request->shipping_address;
        $data['shipping_postalCode'] =  $request->shipping_postalCode;
        DB::table('customers')->where('id',Auth::id())->update($data);
        session()->flash('success', 'Your account information has been successfully updated!'); 
        return redirect()->back();

    }
    
    

    public function home($feature_cat_id=null){
		
		if(isset($_GET['ref'])){
			$user_id = base64_decode($_GET['ref']);
			$user = DB::table('customers')->where('id',$user_id)->first();
			if($user){
				session()->put('account_registration_referral_id',$user_id);
				return redirect('/register');
			}
		}
		
        $brands = DB::table('brands')->where('is_active', 1)->orderBy('sort_order','asc')->get() ?? null;
        $sliders = DB::table('sliders')->get() ?? null;
        $new_arrival = Product::where('is_active', 1)->where('hide_on_website', 0)->orderBy('id', 'DESC')->with('deal')->limit(15)->get() ?? null;
        $featuredCatIds =  Product::where('featured', 1)->where('hide_on_website', 0)->where('is_active', 1)->with('deal')->pluck('category_id');
        $featuredCategories = DB::table('categories')->whereIn('id', $featuredCatIds)->where('is_active', 1)->limit(16)->get();
        $categorys= DB::table('categories')->where('is_active', 1)->orderby('name', 'ASC')->get();
        $featured_product =  Product::where('featured', 1)->where('hide_on_website', 0)->where('is_active', 1)->with('deal')->get();

        $blogs = DB::table('blogs')->where('is_active', 1)->orderBy('id', 'DESC')->get() ?? null;


        $d_percentage = DB::table('deals')->where('percentage', '!=', Null)->where('expire', '>=', \Carbon\Carbon::now())->orderBy('id', 'DESC')->first();
  
        if($d_percentage){
            $deal_date = date('Y/h/d', strtotime($d_percentage->expire));
            if($d_percentage->percentage){
               $deal_percentage_data = Product::where('is_active', 1)->where('hide_on_website', 0)->where('id', $d_percentage->product_id)->first() ?? null;
                $deal_percentage_data->image = explode(',',$deal_percentage_data->image);
               if($deal_percentage_data->promotion_price){
                  $dpp = ($d_percentage->percentage / 100) * $deal_percentage_data->promotion_price ?? null;
                  $deal_percentage_price = $deal_percentage_data->promotion_price - $dpp;
               }elseif($deal_percentage_data->price){
                 $dp = ($d_percentage->percentage / 100) * $deal_percentage_data->price ?? null;
                 $deal_percentage_price = $deal_percentage_data->price - $dp;
               }

            }elseif($d_percentage->price){
                $deal_price_data = Product::where('is_active', 1)->where('hide_on_website', 0)->where('id', $d_percentage->product_id)->first() ?? null;
                $deal_price_data->image = explode(',',$deal_price_data->image);
                $deal_price = $d_percentage->price ?? null;
            }
        }

        $d_price = DB::table('deals')->where('price', '!=', Null)->where('expire', '>=', \Carbon\Carbon::now())->orderBy('id', 'DESC')->first();
       
        if($d_price){
         if($d_price->price){
                $deal_price_data = Product::where('is_active', 1)->where('hide_on_website', 0)->where('id', $d_price->product_id)->first() ?? null;
                // var_dump($deal_price_data->slug);
                // exit;
                $deal_price_data->image = explode(',',$deal_price_data->image);
                $deal_price = $d_price->price ?? null;
            }
        }


        if(Auth::user()){
            $user = Auth::user();
        }else{
            $user = null;
        }
		
		$metadata = [
			'meta_title' => 'LuxiQue - Home',
			'meta_keywords' => 'technology,product,LuxiQue,apple,xiomi,anker,sony',
			'meta_description' => 'LuxiQue is an online market place of tech products. It is established on the aim to provide 100% genuine technological products with the highest customer satisfaction priority to the tech lover consumers in Bangladesh.',
			'meta_image' => null
		];
		
        return Inertia::render('Home', [
            'user'      => $user,
            'slider' => $sliders,
            'brands' => $brands,
            'categorys' => $categorys,
            'new_arrival_product'   => $new_arrival,
            'featuredCategories'    => $featuredCategories,
            'featured_product'      => $featured_product,
            'deal_percentage_data'  => $deal_percentage_data ?? null,
            'deal_percentage_price' => $deal_percentage_price ?? null,
            'deal_price_data'       => $deal_price_data ?? null,
            'deal_price'            => $deal_price ?? null,
            'deal_date'             => $deal_date ?? null,
            'blogs'                 => $blogs ?? null,
        ])->withViewData($metadata);
    }






    public function fetured($cat_id){
        if($cat_id){
            $id = $cat_id;
        }else{
            $id = 1; 
        }
   

    }


   
    public function about(){
        $categories = DB::table('categories')->where('is_active', 1)->get();
        $about = DB::table('categories')->where('is_active', 1)->get();
        if(Auth::user()){
            $user = Auth::user();
        }else{
            $user = null;
        }
		
		$about = \DB::table('pages')->first();

        return Inertia::render('About', [
            'user'      => $user,
            'cat' => $categories,
            'about' => $about->about_us,
        ]);
    }  
    public function contact(){
        return Inertia::render('Contact');
    }  

    public function register(){
        return Inertia::render('Myaccount');
    }

    

    
    public function signup(){
        return Inertia::render('Signup');
    }

    public function contact_submit(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'min:11',
            'message' => 'required',
        ]);
        $data['name']   = $request->name;
        $data['email']    = $request->email;
        $data['phone']   = $request->phone;
        $data['subject'] = $request->subject;
        $data['message'] = $request->message;
        DB::table('con_contact')->insert($data);
        session()->flash('successMessage', 'Message send successfully..!');
        return back();
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
		return back();
    }  
	
	
    

    public function contact_submit2(Request $request){
        if(!$request->name){
            session()->flash('errorMessage', 'Name field in required.');
            return back();
        }
        if(!$request->email){
            session()->flash('errorMessage', 'Email field in required.');
            return back();
        }
        if(!$request->message){
            session()->flash('errorMessage', 'Message field in required.');
            return back();
        }

        $data['name']   = $request->name;
        $data['email']    = $request->email;
        $data['message'] = $request->message;

        DB::table('con_contact')->insert($data);
        session()->flash('successMessage', 'Message send successfully..!');
        return back();
    } 


    public function register_web(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email|unique:customers',
            'phone_number' => 'required|min:11|max:11|unique:customers',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required | min:6'
        ]);
		
		if($user_id = session()->get('account_registration_referral_id')){
			
			$has_coupon = DB::table('coupons')->where('customer_id',$user_id)->where('code','WELCOME50')->first();
			
			if(!$has_coupon){
				$coupon_data['code'] = 'WELCOME50';
				$coupon_data['type'] = 'fixed';
				$coupon_data['amount'] = 50;
				$coupon_data['minimum_amount'] = 500;
				$coupon_data['quantity'] = 0;
				$coupon_data['used'] = 0;
				$coupon_data['expired_date'] =  date('Y-m-d H:i:s',strtotime('+5 years',strtotime('01/01/2021'))) . PHP_EOL;;
				$coupon_data['user_id'] = 1;
				$coupon_data['customer_id'] = $user_id;
				$coupon_data['coupon_type'] = 'individual';
				$coupon_data['is_active'] = 1;
				$coupon_data['created_at'] = date('Y-m-d H:i:s');
				$coupon_data['updated_at'] = date('Y-m-d H:i:s');
				DB::table('coupons')->insert($coupon_data);
			}
			$data['referrer_id'] = $user_id;
		}

        $data['name']        = $request->name;
        $data['email']       = $request->email;
        $data['phone_number']   = $request->phone_number;
        $data['customer_group_id']   = 3;
        $data['password']    = Hash::make($request->password);

        $created_user_id = DB::table('customers')->insertGetId($data);
		
		if($user_id = session()->get('account_registration_referral_id')){
			
			$coupon_data['customer_id'] = $created_user_id;
			$coupon_data['code'] = 'WELCOME50';
			$coupon_data['type'] = 'fixed';
			$coupon_data['amount'] = 50;
			$coupon_data['minimum_amount'] = 500;
			$coupon_data['quantity'] = 1;
			$coupon_data['used'] = 0;
			$coupon_data['expired_date'] =  date('Y-m-d H:i:s',strtotime('+5 years',strtotime('01/01/2021'))) . PHP_EOL;;
			$coupon_data['user_id'] = 1;
			$coupon_data['coupon_type'] = 'individual';
			$coupon_data['is_active'] = 1;
			$coupon_data['created_at'] = date('Y-m-d H:i:s');
			$coupon_data['updated_at'] = date('Y-m-d H:i:s');;

			DB::table('coupons')->insert($coupon_data);
		}
		session()->forget('account_registration_referral_id');
        session()->flash('success', 'You account has been successfully created!');
        return redirect('/login');
    }


    public function web_login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if(is_numeric($request->get('email'))){
            $credentials = ['phone_number'=>$request->get('email'),'password'=>$request->get('password')];
        }elseif(filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $request->get('email'), 'password'=>$request->get('password')];
        }

        if (Auth::attempt($credentials)) {
            return redirect('/dashboard');
        }else{
            session()->flash('error', 'Opps! You have entered invalid credentials');
            return back();
        }
    }

 

    public function newslatter_submit(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);
        $data['email']   = $request->email;
        $data['ip']      = $request->ip();
        $data['status']  = 1;
        $email = DB::table('con_neswlatter')->where('email', $request->email)->first();
        if($email){
            session()->flash('error', 'Sorry..! This eamil already exist.');
            return back();
        }else{
            DB::table('con_neswlatter')->insert($data);
            session()->flash('success', 'Thank you for your subscription!');
            return back();
        }

    }  


    
    public function compare(){

        $compare = session()->get('compare');
        $compare_count = count($compare['products']);
 
        return Inertia::render('Product/Compare', [
            'compare_count'      => $compare_count,
        ]);
    }

    public function redirectToProvider($provider){
        switch ($provider) {
            case 'google':
                return Socialite::driver('google')->redirect();
                break;
            case 'facebook':
                return Socialite::driver('facebook')->redirect();
                break;
        }

    }




//Blog



public function blog_page(){
    $blogs = DB::table('blogs')->where('is_active', 1)->orderBy('id', 'DESC')->limit(12)->get() ?? null;
    $user = Auth::user();

    return Inertia::render('Blog/Index', [
        'user'      => $user,
        'blogs'            => $blogs ?? null,
    ]); 
}



    public function handleProviderCallback($provider)
    {
        switch ($provider) {
            case 'google':
                try {
                    $user = Socialite::driver('google')->user();
                } catch (\Exception $e) {
                    return redirect('/login');
                }
                break;
            case 'facebook':
                try {
                    $user = Socialite::driver('facebook')->user();
                } catch (\Exception $e) {
                    return redirect('/login');
                }
                break;
        }


        // check if they're an existing user
        $existingCustomer = User::where('email', $user->email)->first();

        if($existingCustomer){
            // log them in
            Auth::login($existingCustomer, true);
        } else {
            // create a new user
            $newCustomer                  = new Customer;
            $newCustomer->name            = $user->name;
            $newCustomer->email           = $user->email;
            $newCustomer->save();

            Auth::login($newCustomer, true);
        }

        return redirect()->to('/dashboard');

    }
	
	public function apply_coupon(Request $request){
		
        
		if($request->coupon == 'WELCOME50'){
			$coupon = Coupon::where('code', $request->coupon)->where('customer_id',Auth::id())->first();
		}elseif($request->coupon == 'WELCOME100'){
			$coupon = Coupon::where('code', $request->coupon)->where('customer_id',Auth::id())->first();
		}else{
			$coupon = Coupon::where('code', $request->coupon)->first();
		}
		
		
        $cart = session()->get('cart');
		
		if($cart){
			$cart_sub_total = $cart['sub_total'];
		}else{
			 return redirect()->back()->with('error', 'No Items on Cart!');
		}
		
        if (!$coupon) {
            return redirect()->back()->with('error', 'Invalid Coupon');
        }
        else {
			
            if ($coupon->expired_date <= Carbon::now()) {
                return redirect()->back()->with('error', 'Coupon Expired!');
            }
            if ($coupon->minimum_amount > $cart_sub_total) {
                return redirect()->back()->with('error', 'Minimum amount for this coupon is not reached!');
            }

            if ($coupon->quantity <= $coupon->used) {
                return redirect()->back()->with('error', 'All coupons are already used!');
            }
			
			if ($coupon->is_active != 1) {
                return redirect()->back()->with('error', 'Invalid Coupon!');
            }
			
			
            else {
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
                session()->put('couponCart', $couponCart);
                return redirect(route('checkout'));
            }
        }
	}
	
	
	public function remove_coupon(){
        session()->forget('couponCart');
        return redirect(route('checkout'));
    }
    
    

}

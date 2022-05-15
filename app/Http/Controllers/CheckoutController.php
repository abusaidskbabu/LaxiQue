<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;
use DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Library\SslCommerz\SslCommerzNotification;
use Auth;


class CheckoutController extends Controller
{
    
    public function index(){
        $cart = session()->get('cart');
        
        return Inertia::render('Checkout/Cart', [
            'cart' => $cart
        ]);
    }

    public function checkout(){
        $cart = session()->get('cart');
		if($cart){
			$total_items = $cart['total_items'];
			return Inertia::render('Checkout/Checkout', [
				'total_items' => $total_items,
				'user_data' => Auth::user(),
				'couponCart' => session()->get('couponCart') ? session()->get('couponCart') : ''
			]);
		}else{
				session()->flash('error', 'There is no product in your cart!');
				return redirect('/');
		}

    }


}

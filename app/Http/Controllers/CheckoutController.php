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
use App\Category;


class CheckoutController extends Controller
{
    
    public function index(){
        $cart = session()->get('cart');
        
		$categorys2= Category::where('is_active', 1)->where('is_feture',1)->get();
        foreach($categorys2 as $item){
            $item->sub_category = Category::where('is_active', 1)->where('parent_id', $item->id)->orderby('name', 'ASC')->get();

            foreach($item->sub_category as $item1){
                
                $item1->child_sub_category = Category::where('is_active', 1)->where('parent_id', $item1->id)->orderby('name', 'ASC')->get();

                foreach($item1->child_sub_category as $item2){
                    $item2->child_child_sub_category = Category::where('is_active', 1)->where('parent_id', $item2->id)->orderby('name', 'ASC')->get();
                }
            }
        }

        return Inertia::render('Checkout/Cart', [
            'cart' => $cart,
			'categorys2' => $categorys2,
        ]);
    }

    public function checkout(){
        $cart = session()->get('cart');

		$categorys2= Category::where('is_active', 1)->where('is_feture',1)->get();
        foreach($categorys2 as $item){
            $item->sub_category = Category::where('is_active', 1)->where('parent_id', $item->id)->orderby('name', 'ASC')->get();

            foreach($item->sub_category as $item1){
                
                $item1->child_sub_category = Category::where('is_active', 1)->where('parent_id', $item1->id)->orderby('name', 'ASC')->get();

                foreach($item1->child_sub_category as $item2){
                    $item2->child_child_sub_category = Category::where('is_active', 1)->where('parent_id', $item2->id)->orderby('name', 'ASC')->get();
                }
            }
        }

		if($cart){
			$total_items = $cart['total_items'];
			return Inertia::render('Checkout/Checkout', [
				'total_items' => $total_items,
				'categorys2'  => $categorys2,
				'user_data' => Auth::user(),
				'couponCart' => session()->get('couponCart') ? session()->get('couponCart') : ''
			]);
		}else{
				session()->flash('error', 'There is no product in your cart!');
				return redirect('/');
		}

    }


}

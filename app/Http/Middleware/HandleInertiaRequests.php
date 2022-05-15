<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Product;
use DB;
use Auth;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request){


        $data['customer_id'] = Auth::id();
        $data['product_id'] = $request->product_id;

        $wishlist = [];

        if(Auth::id()){
            $wishlists = DB::table('wishlists')->where('customer_id',Auth::id())->get();

            foreach($wishlists as $w){
                $product = Product::find($w->product_id);
                if($product){
                    $product_price = \Helper::getPrice($w->product_id, $product->price, $product->starting_date, $product->last_date);
                    $wishlist[] = [
                        'product_id'  => $w->product_id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => $product_price,
                        'image' => ($product->image) ? explode(',',$product->image) : [],
                    ];
                }
            }
        }

        $compare = session()->get('compare');
        if($compare){
            $compare_count = count($compare['products']);
        }
        $mycat = session()->get('cart');
        if($mycat){
            $cart_count = count($mycat['product']);
        }
		
		$freeShipping = 0;
		if(session()->get('cart')){
			$freeShipping = 0;
			foreach(session()->get('cart')['product'] as $singleCart){
				if(\Helper::getFreeShipping($singleCart['product_id'])){
					$freeShipping = 1;
					continue;
				}
			}
		}
		
        return array_merge(parent::share($request), [
            'compare_count' => fn () => $compare_count??null,
            'cart_count' => fn () => $cart_count??null,
            'user' => fn () => Auth::user() ? : [],
            'cart' => fn () => session()->get('cart') ? : null,
            'compare' => fn () => session()->get('compare') ? : [],
            'wishlist' => fn () => $wishlist,
            'free_shipping' => fn () => $freeShipping,
            'categories'  => fn () => DB::table('categories')->where('is_active', 1)->get() ?? null,
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ]
        ]);
    }
}

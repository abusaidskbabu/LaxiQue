<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
// use App\Models\Category;
use Inertia\Inertia;
use App\Category;
use DB;
use Auth;
use Session;
use Carbon\Carbon;
use \Illuminate\Support\Str;

//session_start();

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
     
        $query = '';
        if($request->brands || $request->price ){
            $products = $this->filter_submit($request);
        }elseif($request->s){
            $query = $request->s;
            $products = $this->search($request);
        }elseif($request->allProduct){
            $products = $this->allProduct($request);
        }elseif($request->filterbyproduct){
            $products = $this->filterbyproduct($request);
        }elseif($filterByCategory = $request->filterByCategory){
			if($filterByCategory != -1){
				$products = Product::where('is_active',1)->where('hide_on_website', 0)->where('category_id',$filterByCategory)->latest()->paginate(20);
			}else{
				$products = Product::where('is_active',1)->where('hide_on_website', 0)->with('deal')->latest()->paginate(20);
			}
			
		}else{
            $products = Product::where('is_active',1)->where('hide_on_website', 0)->with('deal')->latest()->paginate(20);
        }

        $brands = DB::table('brands')->where('is_active',1)->orderBy('sort_order','asc')->get();
        $newproducts = Product::where('is_active',1)->where('hide_on_website', 0)->latest()->limit(10)->get();
        $featuedProducts = Product::where('is_active',1)->where('hide_on_website', 0)->where('featured',1)->latest()->limit(10)->get();

        foreach($products as $product){
            $product->image = explode(',',$product->image);
        }
        foreach($newproducts as $product){
            $product->image = explode(',',$product->image);
        }
        foreach($featuedProducts as $product){
            $product->image = explode(',',$product->image);
        }

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

        return Inertia::render('Product/ProductList', [
            'products'          => $products,
            'brands'            => $brands,
            'categories'            => $categorys2,
            'newproducts'       => $newproducts,
            'featuedProducts'   => $featuedProducts,
            'search_query'      => $query,
            'categorys2' => $categorys2
        ]);

    }

    private function filter_submit($request){

        if(!is_array($request->brands)){
            $brands = array($request->brands);
         }else{
             $brands = $request->brands;
         }
		 
        $min_price = 1;
        $max_price = 1000000;
		
		
        if($request->price){
			$price = explode('-',$request->price);
			$min_price = (int) $price[0]; 
			$max_price = (int) $price[1]; 
		}
		
        if($brands){
            $products = Product::where('is_active',1)->where('hide_on_website', 0)->whereIn('brand_id', $brands)->with('deal')->whereBetween('price', [$min_price, $max_price])->latest()->paginate(20);
        }else{
            $products = Product::where('is_active',1)->where('hide_on_website', 0)->whereBetween('price', [$min_price, $max_price])->with('deal')->latest()->paginate(20);
        }
        return $products;

    }
    

  
    public function addToCart(Request $request) {
		$variant_name = '';
        $cart = session()->get('cart');
        $product = Product::find($request->product_id);
		if(!$product){
			session()->flash('error', 'Product is not available!');
			return redirect()->back();
		} 
        $product_price = \Helper::getPrice($product->id, $product->price, $product->starting_date, $product->last_date);
        $image = 'images/product/'.explode(',',$product->image)[0];
        $variant_id = ($request->variant_id) ? $request->variant_id : null;

        $count  = $request->count;
        if($count < 1) $count = 1 ;

        //Check is Variant or not
        if($product->is_variant == 1 && $variant_id){
            $stockItems = DB::table('product_purchases')->where('product_id',$request->product_id)->where('variant_id',$variant_id)->sum('qty');
            
			if($product->allow_backorder == 0){
				if($stockItems < $count){
					session()->flash('error', 'Limited stock found! You can not add more than '.$stockItems.' items of this product!'); 
					return redirect()->back();
				}
			}
			
			$variant_name = \Helper::getVarinat($variant_id)->variant_name;

        }else{
            $stockItems = DB::table('product_purchases')->where('product_id',$request->product_id)->sum('qty');
			if($product->allow_backorder == 0){
				if($stockItems < $count){
					session()->flash('error', 'Limited stock found! You can not add more than '.$stockItems.' items of this product!'); 
					return redirect()->back();
				}
			}
        }

        if($product->is_variant == 1 && $request->variant_id < 1){
            session()->flash('error', 'Please select any variant first.'); 
            return redirect()->back();    
        }
         
        $variant_data = DB::table('product_variants')->where('variant_id', $request->variant_id)->first();
        if($variant_data){
            $variant_price = $variant_data->additional_price;
        }else{
            $variant_price = 0;
        }
      
        $cart['product'][]= [
            'cart_id' => uniqid(),
            'product_id' => $request->product_id,
            'is_variant' => $product->is_variant,
            'free_shipping' => $product->free_shipping,
            'variant_id' => $request->variant_id,
            'variant_name' => $variant_name,
            'count' => $count,
            'price' => $product_price+$variant_price,
            'image' => $image,
            'name' => $product->name,
            'brand' =>  DB::table('brands')->where('id', $product->brand_id)->first()->title,
            'category' => DB::table('categories')->where('id', $product->category_id)->first()->name,
            'slug' => $product->slug,
        ];



        $cart['total_items'] = count($cart['product']);
        $cart['sub_total'] = 0;
		
        foreach($cart['product'] as $c ){
            $cart['sub_total'] += $c['price']*$c['count'];
        }
        session()->put('cart', $cart);
		session()->flash('success', '<p>Product has been successfully added to your cart!</p><p><a class="msg_btn" href="/cart">View Cart</a> <a class="msg_btn" href="/checkout">View Checkout</a></p>');
        return redirect()->back();
    }


    public function removeCartItem(Request $request){
        $product_id = $request->product_id;
        $cart = (session()->get('cart')) ? session()->get('cart') : [];

        if($cart){
           if(isset($cart['product'][$product_id])){
                unset($cart['product'][$product_id]);
                $cart['total_items'] = count($cart['product']);
           }
        }

        $cart['sub_total'] = 0;
        foreach($cart['product'] as $c ){
            $cart['sub_total'] += $c['price']*$c['count'];
        }

        session()->put('cart', $cart);
        session()->flash('success', 'Product has been successfully removed from your cart!'); 
        return redirect()->back();
    }

    

    function show($slug){
        $products = Product::where('slug',$slug)->with('deal')->where('is_active',1)->where('hide_on_website', 0)->first();
        if($products){
            $reviews = DB::table('comments')->where('commentable_type','App\Product')->where('commentable_id',$products->id)->latest()->limit(15)->get();
			$already_requested = DB::table('product_restock')->where('user_id',Auth::id())->where('product_id',$products->id)->first();
			
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
            ->where(['orders.user_id' => Auth::id(), 'order_details.product_id' => $products->id])
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
			
			$metakeyWord = explode(' ',$products->name);
			$metakeyWord = implode(',',$metakeyWord);

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
			
			$metadata = [
				'meta_title' => 'LuxiQue - '.$products->name,
				'meta_keywords' => $metakeyWord,
				'meta_description' => substr($products->description,0,150).'...',
				'meta_image' => 'https://pos.techhut.com.bd/images/product/'.$product_img_array[0]

			];
            return Inertia::render('Product/ProductView', [
                'product' => $products,
                'newproducts' => $newproducts,
                'featuedProducts' => $featuedProducts,
                'brands' => $brands,
                'product_img_array' => $product_img_array,
                'related_product' => $related_product,
                'categorys2' => $categorys2,
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
            ])->withViewData($metadata);
        }else{
            return redirect()->back();
        }

    }


    //WishList
    public function addOrRemoveWishList(Request $request) {
        if(!Auth::id()){
            session()->flash('error', 'Please login to add a product to your wishlist!'); 
            return redirect()->back();
        }
        $data = [];
        $data['customer_id'] = Auth::id();
        $data['product_id'] = $request->product_id;
        if(!Auth::id()){
            session()->flash('error', 'Please login to add a product to your wishlist!'); 
        }

        $alreadyInWishlist = DB::table('wishlists')->where('customer_id',Auth::id())->where('product_id',$request->product_id)->first();
        if($alreadyInWishlist){
            DB::table('wishlists')->where('customer_id',Auth::id())->where('product_id',$request->product_id)->delete();
            session()->flash('error', 'Product has been successfully removed from your wishlist!'); 
        }else{
            DB::table('wishlists')->insert($data);
            session()->flash('success', 'Product has been successfully added to your wishlist!'); 
        }
        return redirect()->back();
    }


    //Compare 
    public function add_to_compare(Request $request) {
        $compare = (session()->get('compare')) ? session()->get('compare') : [];

        if(isset($compare['products'][$request->product_id])){
            session()->flash('error', 'Already added in compare list.');
            return redirect()->back();
        }

        $compare = session()->get('compare');
        $product = Product::find($request->product_id);
        $compare['products'][$request->product_id] = [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'slug' => $product->slug,
            'brand' =>  DB::table('brands')->where('id', $product->brand_id)->first()->title,
            'category' => DB::table('categories')->where('id', $product->category_id)->first()->name,
            'product_details' => strip_tags($product->product_details),
            'qty' => $product->qty,
            'price' => \Helper::getPrice($product->id, $product->price, $product->starting_date, $product->last_date),
            'image' => env('PARENT_URL').'images/product/'.explode(',',$product->image)[0],
        ];
        session()->put('compare', $compare);
        session()->flash('success', '<p>Product has been successfully added to your compare list..!</p><p><a class="msg_btn" href="/compare">Go to Compare</a></p>');
        return redirect()->back();
    }
	
	public function request_for_restock(Request $request) {
		if(!Auth::id()){
			session()->flash('error', 'Please login to request for restock a product!'); 
			return redirect()->back();
        }
		
        $product = Product::find($request->product_id);
		$data['user_id'] = Auth::id();
		$data['product_id'] = $request->product_id;
		$data['product_name'] = $product ->name;
		DB::table('product_restock')->insert($data);
        session()->flash('success', '<p>Product has been successfully requested for restock..!</p>');
        return redirect()->back();
    }
	
	
	


    public function removeComparetItem(Request $request){
        $product_id = $request->product_id;
        $compare = (session()->get('compare')) ? session()->get('compare') : [];

        if($compare){
           if(isset($compare['products'][$product_id])){
                unset($compare['products'][$product_id]);
           }
        }
        session()->put('compare', $compare);
        session()->flash('success', 'Product has been successfully removed from your compare list!'); 
        return redirect()->back();
    }

    
    public function category_page(Request $request){
        $categories = DB::table('categories')->where('is_active', 1)->get();
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
        return Inertia::render('Product/Category', [
            'categories'          => $categories,
            'categorys2' => $categorys2,
        ]);
    }


    public function category(Request $request){
        
        $category_slug = $request->categoryslug;
        $category = DB::table('categories')->where('slug',$category_slug)->where('is_active',1)->first();
        $category_name = null;
    
        if($request->brands || $request->price ){
            $products = $this->filter_submit($request);
        }else{
            if($category){
                $category_id = $category->id;
                $category_name = $category->name;
                $products = Product::where('category_id',$category_id)->where('is_active',1)->where('hide_on_website', 0)->latest()->paginate(20);
            }else{
                $products = Product::where('is_active',1)->where('hide_on_website', 0)->latest()->paginate(20);
            }
        }

        $brands = DB::table('brands')->where('is_active',1)->orderBy('sort_order','asc')->get();
        $newproducts = Product::where('is_active',1)->where('hide_on_website', 0)->latest()->limit(10)->get();
        $featuedProducts = Product::where('is_active',1)->where('hide_on_website', 0)->where('featured',1)->latest()->limit(10)->get();

        foreach($products as $product){
            $product->image = explode(',',$product->image);
        }
        foreach($newproducts as $product){
            $product->image = explode(',',$product->image);
        }
        foreach($featuedProducts as $product){
            $product->image = explode(',',$product->image);
        }

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
        return Inertia::render('Product/ProductList', [
            'products'      => $products,
            'brands'        => $brands,
            'newproducts'   => $newproducts,
            'featuedProducts'   => $featuedProducts,
            'category_name'     =>  $category_name,
            'categorys2'    =>$categorys2,
        ]);
        
    }


    public function deals(Request $request){
        
        $deals = DB::table('deals')->whereDate('expire', '>', Carbon::now())->get();
        $dealProductIds = [];

        if($deals){
            foreach($deals as $deal){
                $dealProductIds[] = $deal->product_id;
            }
        }


        if($request->brands || $request->price ){
            $products = $this->filter_submit($request);
        }else{
            $products = Product::where('is_active',1)->where('hide_on_website', 0)->with('deal')->whereIn('id',$dealProductIds)->latest()->paginate(20);
        }

        $brands = DB::table('brands')->where('is_active',1)->orderBy('sort_order','asc')->get();
        $newproducts = Product::where('is_active',1)->where('hide_on_website', 0)->latest()->limit(10)->get();
        $featuedProducts = Product::where('is_active',1)->where('hide_on_website', 0)->where('featured',1)->latest()->limit(10)->get();

        foreach($products as $product){
            $product->image = explode(',',$product->image);
        }
        foreach($newproducts as $product){
            $product->image = explode(',',$product->image);
        }
        foreach($featuedProducts as $product){
            $product->image = explode(',',$product->image);
        }

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

        return Inertia::render('Product/ProductList', [
            'products'      => $products,
            'brands'        => $brands,
            'newproducts'   => $newproducts,
            'featuedProducts'   => $featuedProducts,
            'categorys2' => $categorys2,
        ]);
        
    }

    public function review_submit(Request $request){

        $request->validate([
            'rate' => 'required|numeric|max:5',
            'product_id' => 'required|numeric',
            'testimonial' => 'required|string'
        ]);

        $rate = $request->rate;
        $testimonial = Str::limit($request->testimonial, 290, $end='...');
        $product_id = $request->product_id;

        DB::table('product_review')->updateOrInsert(
            ['product_id'=>$product_id,'user_id'=>Auth::id()],
            ['product_id'=>$product_id,'user_id'=>Auth::id(),'user_name'=>Auth::user()->name,'testimonial' => $testimonial,'rate'=>$rate]
        );
        session()->flash('success', 'Thanks for provide your feedback!'); 
        return redirect()->back();
    }


    private function search(Request $request){
        $query = $request->s;
        if($query){
            $products = Product::where('hide_on_website', 0)
			->where('name','like','%'.$query.'%')
			->where('is_active',1)
            ->with('deal')
            ->latest()
            ->paginate(20);
        }
        return $products;
    }
    private function allProduct(Request $request){
        if($request->allProduct == 1){
            $products = Product::where('is_active',1)->where('hide_on_website', 0)->with('deal')->latest()->paginate(999999);
        }
        return $products;
    }

    private function filterbyproduct(Request $request){
        if($request->filterbyproduct == 'newest'){
            $products = Product::where('is_active',1)->where('hide_on_website', 0)->with('deal')->latest()->paginate(20);
        }elseif($request->filterbyproduct == 'oldest'){
            $products = Product::where('is_active',1)->where('hide_on_website', 0)->with('deal')->oldest()->paginate(20);
        }elseif($request->filterbyproduct == 'lowtohigh'){
            $products = Product::where('is_active',1)->where('hide_on_website', 0)->with('deal')->orderBy('price', 'asc')->paginate(20);
        }elseif($request->filterbyproduct == 'hightolow'){
            $products = Product::where('is_active',1)->where('hide_on_website', 0)->with('deal')->orderBy('price', 'desc')->paginate(20);
        }else{
            $products = Product::where('is_active',1)->where('hide_on_website', 0)->with('deal')->paginate(20);
        }
        return $products;
    }


    public function deals_page(){
        
        $deals = DB::table('deals')->whereDate('expire', '>', Carbon::now())->get();
        $dealProductIds = [];
        if($deals){
            foreach($deals as $deal){
                $dealProductIds[] = $deal->product_id;
            }
        }
        $products = Product::where('is_active',1)->where('hide_on_website', 0)->with('deal')->whereIn('id',$dealProductIds)->latest()->paginate(20);
        foreach($products as $product){
            $product->image = explode(',',$product->image);
        }

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

        return Inertia::render('Product/Deal', [
            'products'      => $products,
            'categorys2' => $categorys2
        ]);
    }


    
}

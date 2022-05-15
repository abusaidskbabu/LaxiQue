<?php 
namespace App\Helpers;
use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;

class Helper{

    public static function getPrice($id, $price, $starting_date = null, $last_date = null){
        $data = DB::table('deals')->where('product_id', $id)->where('expire', '>=', \Carbon\Carbon::now())->first();
        if($data){
            $percentage = $data->percentage;
            if($percentage){
                $percentage_price = ($percentage / 100) * $price;
                return $price - $percentage_price;
            }elseif($data->price){
                return $data->price;
            }else{
                return null;
            }
        }else{
            if($starting_date && $last_date){
                $data = DB::table('products')->where('id', $id)->where('starting_date', '<=', $starting_date)->where('last_date', '>=', $last_date)->first();
                if($data){
                    if($data->promotion_price){
                        return $data->promotion_price;
                    }else{
                        return $data->price;
                    }
                }else{
                    $data = DB::table('products')->where('id', $id)->first();
                    if($data){
                        return $data->price;
                    }else{
                        return null;
                    }
                }
            }else{
                $data = DB::table('products')->where('id', $id)->first();
                if($data){
                    return $data->price;
                }else{
                    return null;
                }
            }
        }
    }
	
	

	
	public static function sendSms($msisdn, $messageBody){
	   
	   if(substr($msisdn, 0, 3) == "+88") $msisdn = '' . substr($msisdn, 3);
	   if(substr($msisdn, 0, 2) == "88") $msisdn = '' . substr($msisdn, 2);
	   
		$curl = curl_init();
		$username = 'virtual';
		$password = 'Vmsl1212@';
		$from = 'TechHut';
		$to = '88'.$msisdn;
		$messageBody = urlencode($messageBody);

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.mobireach.com.bd/SendTextMessage?Username='.$username.'&Password='.$password.'&From='.$from.'&To='.$to.'&Message='.$messageBody,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_SSL_VERIFYPEER => false,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		));

		curl_exec($curl);
		curl_close($curl);
    } 
	
	
	public static function get_varianPrice($product_id, $is_variant){
		if($is_variant == 1){
			$variant_price = DB::table('product_variants')->where('product_id', $product_id)->sum('additional_price');
			if($variant_price && $variant_price > 0){
				return $variant_price;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
		
	}
	
	public static function getVarinat($variant_id){
		return DB::table('product_variants')->where('variant_id', $variant_id)->first();
	}
	
	public static function getFreeShipping($product_id){
		$product = DB::table('products')->where('id', $product_id)->first();
		if($product){
			if($product->free_shipping == 1){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}


public static function get_product_name($order_id){
    $order_details = DB::table('order_details')->where('order_id', $order_id)->first();
    if($order_details){
        $product_id = $order_details->product_id;
        $data = DB::table('products')->where('id', $product_id)->first();
        if($data){
            return $data->name;
        }else{
            return ' - ';
        }
        
    }else{
        return ' - ';
    }
}


}
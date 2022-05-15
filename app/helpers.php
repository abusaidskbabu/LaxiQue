<?php

use Illuminate\Support\Facades\Config;



function cdn($asset ){



    //Check if we added cdn's to the config file

    if( !Config::get('app.cdn') )

        return asset( $asset );



    //Get file name & cdn's

    $cdns = Config::get('app.cdn');

    $assetName = basename( $asset );

    //remove any query string for matching

    $assetName = explode("?", $assetName);

    $assetName = $assetName[0];



    //Find the correct cdn to use

    foreach( $cdns as $cdn => $types ) {

        if( preg_match('/^.*\.(' . $types . ')$/i', $assetName) )

            return cdnPath($cdn, $asset);

    }



    //If we couldnt match a cdn, use the last in the list.

    end($cdns);

    return cdnPath( key( $cdns ) , $asset);



}



function cdnPath($cdn, $asset) {

    return  "//" . rtrim($cdn, "/") . "/" . ltrim( $asset, "/");

}

function categoryImage($image)
{
    $parentUrl = env('PARENT_URL');

    return $parentUrl . '/images/category/' . $image;
}

function productImage($image)
{

    $parentUrl = env('PARENT_URL');

    if (!$image) {
        $image = 'product-demo.jpg';
    }else{
        $imageArray = explode(',',$image);
        if(count($imageArray) > 0){
            if(!$image){
                if(count($imageArray) > 1){
                    $image = $imageArray[1];
                }
            }else{
                $image = $imageArray[0];
            }

        }else{
            $image = 'product-demo.jpg';
        }
    }
    if(!$image){  
        $image = 'product-demo.jpg';
    }

    return $parentUrl . '/images/product/' . $image;
}

function logo($image)
{
    $parentUrl = env('PARENT_URL');

    return $parentUrl . '/logo/' . $image;
}

function brandImage($image){
    $parentUrl = env('PARENT_URL');


    if (!$image) {
        $image = 'product-demo.jpg';
    }else{
        $imageArray = explode(',',$image);
        if(count($imageArray) > 0){
            if(!$image){
                if(count($imageArray) > 1){
                    $image = $imageArray[1];
                }
            }else{
                $image = $imageArray[0];
            }

        }else{
            $image = 'product-demo.jpg';
        }
    }
    if(!$image){  
        $image = 'product-demo.jpg';
    }


    return $parentUrl . '/images/brand/' . $image;
}





function sliderImage($image){
    $parentUrl = env('PARENT_URL');
    return $parentUrl . '/images/slider/' . $image;
}

function get_slug_id($id){
    $result = DB::table('products')->where('id', $id)->first();
    return $result->slug;
}

function get_deal_price($id, $price){
    $data = DB::table('deals')->where('product_id', $id)->first();
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
        return null;
    }
}




?>

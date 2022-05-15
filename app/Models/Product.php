<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name', 'code','type','brand_id','category_id','price','qty','promotion','promotion_price','starting_date','last_date','image','file','is_variant','featured','product_details','created_at','slug'
    ];


    public function deal()
    {
        return $this->hasOne(Deal::class)->where('expire', '>', now());
    }


}

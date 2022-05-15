<?php

namespace App\Models;

use Actuallymab\LaravelComment\Contracts\Commentable;
use Actuallymab\LaravelComment\HasComments;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model implements Commentable
{
    use HasComments;
    public function canBeRated(): bool{
        return true; // default false
    }
}

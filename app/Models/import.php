<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class import extends Model
{
    //
    protected $table = "products";
    protected $fillable = array('feed_id',
                                'id',
                                'name',
                                'description',
                                'url',
                                'image',
                                'price',
                                'retail_price',
                                'brand',
                                'condition',
                                'availability'
                                );
}
//https://www.youtube.com/watch?v=QqNMAIHCqx8

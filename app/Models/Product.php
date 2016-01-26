<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    //
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'autoid';
    protected $fillable = array('feed_id',
                                'id',
                                'name',
                                'description',
                                'url',
                                'image',
                                'price',
                                'retail_price',
                                'category',
                                'google_category',
                                'brand',
                                'condition',
                                'availability'
                                );
}
//https://www.youtube.com/watch?v=QqNMAIHCqx8

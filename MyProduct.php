<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyProduct extends Model
{
    protected $fillable = ['name', 'category_id', 'price', 'user_id'];

    public function category() {
        return $this->belongsTo('App\Category');
    }
}

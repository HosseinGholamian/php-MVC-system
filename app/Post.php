<?php

namespace App;

use System\Database\ORM\Model;

class Post extends Model
{

    protected $table = "posts";
    protected $fillable = ['title', 'body', 'cat_id'];
    protected $casts = [];

    public function category(){
        return $this->belongsTo('\App\Category', 'cat_id', 'id');
    }


}
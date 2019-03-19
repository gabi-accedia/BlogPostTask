<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'is_headline'
    ];
}
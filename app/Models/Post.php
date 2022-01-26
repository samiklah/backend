<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Post extends Model
{
  // Table Name
    protected $table = 'post';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    protected $fillable = [
      'uri', 'user_id', 'country', 'language', 'type', 'score', 'thumbnail', 'total'
  ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function view()
    {
        return $this->hasMany('App\Models\View');
    }
    
}

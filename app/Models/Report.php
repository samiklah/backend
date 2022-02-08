<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Report extends Model
{
  // Table Name
    protected $table = 'report';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    protected $fillable = [
      'reporter', 'report', 'post'
  ];

    
}

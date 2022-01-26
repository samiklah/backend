<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 16 Nov 2018 05:10:50 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Auth;
/**
 * Class Follow
 * 
 * @property int $f_id
 * @property int $user_one
 * @property int $user_two
 *
 * @package App\Models
 */
class View extends Eloquent
{
	protected $primaryKey = 'id';
	public $timestamps = true;
	protected $table = 'view';

	protected $casts = [
		'user_id' => 'int',
		'post_id' => 'int'
	];

	protected $fillable = [
        'user_id', 'post_id', 'score'
    ];

	public function post(){
        return $this->belongsTo('App\Models\Post');
    }

	
}

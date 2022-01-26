<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 16 Nov 2018 05:10:50 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Follow
 * 
 * @property int $id
 * @property int $user_one
 * @property int $user_two
 *
 * @package App\Models
 */
class Follow extends Eloquent
{
	protected $primaryKey = 'id';
	public $timestamps = true;
	protected $table = 'follow';

	protected $casts = [
		'user_id' => 'int',
		'user_two' => 'int'
	];

	protected $fillable = [
		'user_id',
		'user_two'
	];

	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

	public function follower()
	{   
		return $this->hasMany('App\Models\Follow','user_two','user_id');
	}
	//Relationships
	//People who this user follows
	public function following()
	{   
		return $this->hasMany('App\Models\Follow','user_id','user_two');
	}

	
}

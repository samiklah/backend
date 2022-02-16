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
class Block extends Eloquent
{
	protected $primaryKey = 'id';
	public $timestamps = true;
	protected $table = 'block';

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

	public function blocked()
	{   
		return $this->hasMany('App\Models\Block','user_two','user_id');
	}
	//Relationships
	//People who this user follows
	public function blicking()
	{   
		return $this->hasMany('App\Models\Block','user_id','user_two');
	}

	
}

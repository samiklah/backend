<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\View;
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password',
    ];

    protected $table = 'users';

    public $primaryKay = 'id';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts(){
        return $this->hasMany('App\Models\Post');
    }
    public function balance(){
        return $this->hasOne('App\Balance', 'user_id');
    }
    public function placement(){
        return $this->hasOne('App\Placement', 'user_id');
    }
    public function published_posts(){
        return $this->posts();
    }
    public function recommends(){
        return $this->belongsToMany('App\Article', 'recommends', 'user_id', 'article_id');
    }

    public function isAuthUserFollowing(){
        $following = $this->follower()->where('user_id',  auth('api')->user()->id)->get();
        if ($following->isEmpty()){
            return false;
        }
        return true;
    }

    public function following()
    {
      return $this->belongsToMany('App\Models\User', 'follow','user_id','user_two')->withTimestamps();
    }

    public function follower()
    {
       return $this->belongsToMany('App\Models\User', 'follow','user_two','user_id')->withTimestamps();
    }

    public function isFollowing(User $user)
    {
        return !! $this->following()->where('user_two', $user->id)->count();
    }

    public function isFollowedBy(User $user)
    {
        return !! $this->followers()->where('user_id', $user->id)->count();
    }

    public function getFollowedCountAttribute()
    {
        return count($this->follower);
    }

    public function getFollowingCountAttribute()
    {
        return count($this->following);
    }

    public function comments() 
    {
        return $this->hasMany('App\Comment','user_id','id');
    }

    public function category()
    {
        return $this->belongsToMany('App\Category', 'category_users', 'user_id', 'category_id');
    }


    //public function follows(){
      //  return $this->belongsToMany('App\Follow');
    //}
    
}

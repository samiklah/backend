<?php

namespace App\Http\Controllers\Api;

use App\Models\Follow;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use AltThree\Segment\SegmentServiceProvider as VendorSegmentServiceProvider;
use Segment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth = User::find(auth()->user()->id);
        $user_ids = $auth->following->pluck('id');

        $user_id_list='';

        foreach($user_ids as $user)
        {
            $user_id_list .=$user.',';
        }

        $data_order_id=rtrim($user_id_list,',');
        $articles = DB::select("select * from article WHERE user_id IN('$data_order_id')");

            return view('authers')->with('articles', $articles);
    }



    public function follow($user_id)
    {
        $user = User::find($user_id);

        $follow = Follow::where('user_id', auth('api')->user()->id)->where('user_two',$user_id)->get();

        
        
        if (isset($user) && auth('api')->user()->id != $user_id && count($follow) == 0) {
  
            auth('api')->user()->following()->attach($user_id);


            return response($user, 201);
        }else{
            return response("Can not follow", 401);
        }
        
        
    }
    public function unfollow($user_id)
    {
        $user = User::find($user_id);

        if (isset($user) && auth('api')->user()->id != $user_id) {
            
            auth('api')->user()->following()->detach($user_id);
            return response($user, 201);
        }else{
            return response("Can not unfollow", 401);
        }
    }

    public function followFuzz($auth_id,$user_id){
        $user = User::find($user_id);
        $auth = User::find($auth_id);

        if (isset($user) && $user->push_token != null) {
            $auth->following()->attach($user_id);

        $response = Http::post("https://exp.host/--/api/v2/push/send",[
            "to" => [$user->push_token] ,
            "title"=>'New Follower!',
            "body"=> $auth->name." Is Following You",
            "sound"=> "default"
        ])->json();
        Log::info($response);
        }else{
            return response("Can not follow", 401);
        }

    }

    public function suggestedUsers()
    {

        //$users = User::orderBy('id', 'desc')->with('articles')->has('articles')->get();
        //$users = User::orderBy('id', 'desc')->with('articles')->whereHas('articles')->get();
        $users = User::orderBy('id', 'desc')->get();
        return response($users, 201);
    }

    public function suggestedUsersApi()
    {
        //$users = User::orderBy('id', 'desc')->with('posts')->has('posts')->get();
        $users = User::whereNotIn('id', DB::table('follow')->select('user_two')->where('user_id', auth('api')->user()->id))->whereNotIn('id', DB::table('users')->select('id')->where('id', auth('api')->user()->id))->orderBy('id', 'desc')->has('posts')->take(10)->get();
        //$users =  User::all();
        return response($users, 201);
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function showFolloers($id)
    {
        $user = User::find($id);
        $followers = $user->follower;
        return view('pages.followers')->with('followers', $followers);
    }
    public function showFolloing($id)
    {
        $user = User::find($id);
        $following = $user->following;
        return view('pages.following')->with('following', $following);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function edit(Follow $follow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Follow $follow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function destroy(Follow $follow)
    {
        //
    }
}
//ewr


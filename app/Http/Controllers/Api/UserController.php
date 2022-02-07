<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use App\Models\Follow;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $isfollowing = $user->isAuthUserFollowing();
        $posts = Post::where('user_id', $id)->withCount('view')->get();
        $folowers = $user->follower()->get();
        $folowing = $user->following()->get();

        $response = [
            'user' => $user,
            'posts' => $posts,
            'folowers' => $folowers,
            'folowing' => $folowing,
            'isFollowing' => $isfollowing
        ];

        return response($response  , 201);
    }

    public function getFollowing($id)
    {
        return response(User::find($id)->following()->get() , 201);
    }

    public function getFollowers($id)
    {
        return response(User::find($id)->follower()->get() , 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    public function profile($id)
    {
        $user = User::find($id);
        $isfollowing = $user->isAuthUserFollowing();

        $response = [
            'user' => $user,
            'isFollowing' => $isfollowing
        ];

        return response($response  , 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (auth('api')->user()) {
            // update user
            $updateuser = User::find(auth('api')->user()->id);
            /*
            $oldimg = $updateuser->img;
            if ($oldimg != null) {
                Storage::disk('s3')->delete('userImg/'.$oldimg); 
            }
            */
            //$updateuser->name = $request->input('name');
            //$path = Storage::disk('s3')->put('userImg/', $request->file('img'), 'public');
            //$updateuser->img = basename($path);
            /*
            if ($request->file('img')) {
                $path = Storage::disk('s3')->put('userImg/', $request->file('img'), 'public');
            }else{
                $path = null;
            }
            */
            
            $updateuser->img = $request->input('img');
            $updateuser->save();

            
        return response($updateuser , 201);
        }else{
            return response("erorr" , 401);
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateToken(Request $request)
    {
        if (auth('api')->user()) {
            $updateuser = User::find(auth('api')->user()->id);
            $updateuser->push_token = $request->input('push_token');
            
            $updateuser->save();

        return response($updateuser , 201);
        }else{
            return response("erorr" , 401);
        }
        
    }

public function search($username)
{
   $users = User::where('username', 'like', "%{$username}%")->take(3)->get();
   return response($users , 201);
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}

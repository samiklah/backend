<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\View;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        $users_ids = auth('api')->user()->following->pluck('id');
        $users_id_list=[];

        foreach($users_ids as $user)
        {
            array_push($users_id_list, $user);
        }

        $posts = DB::table('posts')->whereNotIn('id',  $users_id_list )->orderBy('score','desc')->get();

       // $posts = DB::table('posts')->orderBy('score','desc')->get();
       */

        $posts = Post::with("user")->whereNotIn('id', DB::table('view')->select('post_id')->where('user_id', auth('api')->user()->id))->orderBy('score','desc')->withCount('view')->take(10)->get();
            
        return response($posts, 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(array $data)
    {
        return Post::create([
            'uri' => $data['uri'],
            'user_id' => $data['user_id'],
            'country' => $data['country'],
            'language' => $data['language'],
            'type' => $data['type'],
            'thumbnail' => $data['thumbnail']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'uri' => 'required',
            'user_id' => 'required',
            'country' => 'required',
            'language' => 'required',
            'type' => 'required',
            'thumbnail' => 'image',
            'device_name' => 'required',
        ]);

       // make beter image validation in the feuter
        

        if(auth('api')->user()->id == $request->input('user_id')){

            if ($request->file('thumbnail')) {
                $path = Storage::disk('s3')->put('posts/', $request->file('thumbnail'), 'public');
            }else{
                $path = null;
            }
            $post = new Post;

            $post->uri = $request->input('uri');
            $post->user_id = $request->input('user_id');
            $post->country = $request->input('country');
            $post->language = $request->input('language');
            $post->type = $request->input('type');
            $post->thumbnail = basename($path);

            $post->save();

            //$post = $this->create($request->all());

            return response($post, 201);
        }else{
            return response("not authorized", 401);
        }
            
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request, $post_id)
    {
        $updatePost = Post::find($post_id);
        if (isset($updatePost)) {
            $view = new View();
            $view->user_id = auth('api')->user()->id;
            $view->score = $request->input('score');
            $view->post_id = $post_id;
            $view->save();   

            $postViews = View::where('post_id', '==', $post_id)->count();

            $total = $updatePost->total+$request->input('score');
            $updatePost->total = $total;
            if ($postViews == 0) {
                $updatePost->score = $total;
            }else{
                $updatePost->score = $total/$postViews;
            }
            

            $updatePost->save(); 

            return response($view, 201);
        }else{
            return response('post not found', 401);
        }
        
        
    }

    public function showFollowedUsersPosts()
    {
            $users_ids = auth('api')->user()->following->pluck('id');
            $users_id_list=[];

            foreach($users_ids as $user)
            {
                array_push($users_id_list, $user);
            }

            $posts = DB::table('posts')->whereIn('user_id',  $users_id_list )->orderBy('id','desc')->withCount('view')->take(10)->get();
            
            return response($posts, 201);

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if(auth('api')->user()->id == $post->user_id || auth('api')->user()->level == 2){
            //Storage::disk('s3')->delete('posts/'.$post->uri); 
            $post->delete();
            return response("deleted", 201);
        }else{
            return response("not authorized", 401);
        }
        
    }
}

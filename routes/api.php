<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
 
Route::group(['middleware' => 'auth:sanctum'], function(){
    //All secure URL's
    Route::get('/suggested', 'Api\FollowController@suggestedUsersApi');
});

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'username' => 'required',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('username', $request->username)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'username' => ['The provided credentials are incorrect.'],
        ]);
    }
    if($user->level == 0){
        throw ValidationException::withMessages([
            'username' => ['Your account is blocked.'],
        ]);
    }
    if ($request->push_token) {
        $user->push_token = $request->push_token;
    }
    $following = $user->following()->get();

    $token = $user->createToken($request->device_name)->plainTextToken;

    $response = [
        'user' => $user,
        'following' => $following,
        'token' => $token
    ];

    return response($response, 201);

});

Route::post('/sanctum/register', function (Request $request) {
    
    $request->validate([
        'name' => 'required|max:255',
        'username' => 'required|string|alpha_dash|max:255|unique:users|regex:/^[a-z0-9]+(?:[ _-][a-z0-9]+)*$/',
        'password' => 'required',
        'device_name' => 'required',
    ]);
    strtolower($request->username);
        
    $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'device_name' => $request->device_name,
                'password' => Hash::make($request->password),
            ]);
    
    
    $token = $user->createToken($request->device_name)->plainTextToken;

    $response = [
        'user' => $user,
        'token' => $token
    ];

    return response($response, 201);

});

// problem in register method
//Route::post('/sanctum/register', 'Api\RegisterController@register');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('/user/update', 'Api\UserController@update');
Route::middleware('auth:sanctum')->post('/report/{post_id}', 'Api\PostController@report');
Route::middleware('auth:sanctum')->post('/user/updatetoken', 'Api\UserController@updateToken');
Route::middleware('auth:sanctum')->get('/user/following/{id}', 'Api\UserController@getFollowing');
Route::middleware('auth:sanctum')->get('/user/followers/{id}', 'Api\UserController@getFollowers');
Route::middleware('auth:sanctum')->get('/search/{username}', 'Api\UserController@search');
Route::middleware('auth:sanctum')->post('/view/{post_id}', 'Api\PostController@view');
Route::middleware('auth:sanctum')->post('/add/post', 'Api\PostController@store');
Route::middleware('auth:sanctum')->post('/follow/{user_id}', 'Api\FollowController@follow');
Route::middleware('auth:sanctum')->post('/unfollow/{user_id}', 'Api\FollowController@unfollow');
Route::middleware('auth:sanctum')->get('/show/user/{id}', 'Api\UserController@show');
Route::middleware('auth:sanctum')->get('/posts', 'Api\PostController@index');
Route::middleware('auth:sanctum')->delete('/post/delete/{id}', 'Api\PostController@destroy');
Route::middleware('auth:sanctum')->get('/followed/posts', 'Api\PostController@showFollowedUsersPosts');


Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    
    $updateuser = User::find(auth('api')->user()->id);
    $updateuser->push_token = null;

    $request->user()->tokens()->delete();
    return response('Loggedout', 200);
});



//Route::post('/register','Api\RegisterController@register');

//Route::get('/suggested', 'Api\FollowController@suggestedUsersApi');
//Route::post('/image_upload', 'ArticlesController@uploadImage');

Route::get('follow/{auth}/{user_id}',  [
    'as' => 'followw',
    'uses' => 'Api\FollowController@followFuzz'
]);

Route::get('profile/follow/{auth}/{user_id}',  [
    'as' => 'followApi',
    'uses' => 'Api\followController2@followApi'
]);

Route::get('profile/unfollow/{auth}/{user_id}',  [
    'as' => 'unFollowApi',
    'uses' => 'Api\followController2@unFollowApi'
]);



<?php
/**
 * Created by PhpStorm.
 * User: QuangPM
 * Date: 3/21/2018
 * Time: 9:27 AM
 */
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Mockery\Exception;
use Validator;

class UserController extends Controller
{

    /**
     * login api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login() {

        $auth = Auth::attempt(['email' => request('email'), 'password' => request('password')]);

        if ($auth) {
            $user = Auth::user();
            $success['token'] = $user->createToken('Oauth_demo')->accessToken;

            return response()->json(['sucess' =>$success], 200);
        } else {

            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return response()->json(['success'=>$success], $this->successStatus);
    }

    /**
     * detail user api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details(){
        try {
            $user = Auth::user();
            return response()->json(['success' => $user], 200);
        } catch (Exception $e) {
            return response()->json(['error'=> $e->getMessage()], 401);
        }

    }
}
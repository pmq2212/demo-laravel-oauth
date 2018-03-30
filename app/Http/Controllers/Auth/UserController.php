<?php
/**
 * Created by PhpStorm.
 * User: QuangPM
 * Date: 3/21/2018
 * Time: 9:27 AM
 */
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use JWTAuth;
use JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Hash;

class UserController extends Controller
{

    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    /**
     * JWT register
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request){

        $user = $this->user->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password'))
        ]);

        return response()->json([
            'status'=> 200,
            'message'=> 'User created successfully',
            'data'=>$user
        ]);
    }

    /**
     * JWT Login
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $credentials = $request->only('email', 'password');
        $token = null;
        $tokenOld = null;
        try {
            if (!$tokenOld = JWTAuth::attempt($credentials)) {
                return response()->json(['invalid_email_or_password'], 422);
            }

            // Create token
//            $customClaims = [
//                'email' => $request->email,
//                'password' =>  $request->password
//            ];
//            $payload = JWTFactory::make($customClaims);
//            $token = JWTAuth::encode($payload)->get();

//            JWTAuth::setToken($token);
//            $token = JWTAuth::getToken()->get();

        } catch (JWTException $e) {
            return response()->json(['failed_to_create_token'], 500);
        }
        return response()->json([$tokenOld, $token]);
    }

    /**
     * JWT detail user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function details(Request $request){
        $user = JWTAuth::toUser($request->bearerToken());
//        JWTAuth::parseToken();
//        $token = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
//        $token = JWTAuth::getPayload($request->bearerToken())->toArray();
        return response()->json(['result' => $user, 'result2' => $token]);
    }
}
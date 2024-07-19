<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Str;
use App\Models\Verify;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function send(Request $request)
    {
        $email = $request->email; $data = array();
        $email = trim($email);
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        $count = User::where('email', $email)->count();
        if ($count>0) {
            return [
                'success' => false,
                'message' => 'このメールはすでに登録されています！'
            ];
        }

        $code = generateCode(4);
        $code = "1111";
        
        // $res = sendEmail($code, $email);
        
        // if (!$res) {
        //     $data = array("status"=> 0);
        //     return [
        //         'success' => false,
        //         'message' => 'もう一度メールアドレスを入力してください！'
        //     ];
        // }

        Verify::where('to', $email)->update(array('status'=>1));
        $data = array("to"=>$email, 'code'=>$code);
        Verify::create($data);

        return [
            'success' => true
        ];
    }

    public function verify(Request $request) {
        $code = $request->code;
        $email = $request->email;
        $count = Verify::where('to', $email)->where('code', $code)->where('status', 0)->count();
        if ($count) {
            Verify::where('to', $email)->where('code', $code)->where('status', 0)->update(array('status'=>2));
            return [
                'success' => true
            ];
        } else {
            return [
                'success' => false,
                'message' => 'もう一度メール認証をお試しください。'
            ];
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => "ユーザー",
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registration success',
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $credentials = $request->only('email', 'password');
        
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 200);
        }

        return $this->respondWithToken($token);

        // $hashed_password = Hash::make($request->password);
        // $password = User::where('email', $request->email)->first();
        // $verify = Hash::check($request->password, $password->password);

        // if($verify){

        //     $credentials = $request->only('email', 'password');
        //     $token = JWTAuth::attempt($credentials);
        //     $user = $password;

        //     return response()->json([
        //         'success' => 1,
        //         'message' => "ログイン成功!",
        //         'token' => $token,
        //         'user' => $user
        //     ]);
        // } else {
        //     return response()->json([
        //         'success' => 0,
        //         'message' => "入力データが正しくありません。",
        //         'token' => ""
        //     ]);
        // }

    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out',
        ], 200);
    }

    public function user()
    {
        return response()->json(auth('api')->user());
    }

    public function getUser(Request $request){
        return User::find($request->id);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'token' => $token
        ]);
    }
}

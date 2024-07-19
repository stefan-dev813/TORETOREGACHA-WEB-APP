<?php

namespace App\Http\Controllers\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\RegisterRequest;


use Str;

use App\Models\User;
use App\Models\Verify;
use App\Models\Invitation;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{
    public function create() {
        if(Auth::check()) {
            if (auth()->user()->getType() == 'admin') {
                return redirect()->route('admin');
            }else{
                return redirect()->route('user');
            }
        } 
        $hide_cat_bar = 1;
        return inertia('Auth/RegisterPhone', compact('hide_cat_bar'));
    }

    public function send(Request $request) {
        $phone = $request->phone; $data = array();
        $phone = trim($phone);
        
        if (!isPhoneNumber($phone)) {
            return redirect()->back()->with('message', '11桁の電話番号を入力してください！')->with('title', '入力エラー')->with('message_id', Str::random(9))->with('type', 'dialog')->with('data', $data);
        }
        $user = User::where('phone', $phone)->first();
        if ($user->email_verified_at) {
            return redirect()->back()->with('message', 'すでに登録された電話番号です！')->with('title', 'エラー')->with('message_id', Str::random(9))->with('type', 'dialog')->with('data', $data);
        }

        $code = generateCode(4);
        // $code = "1111";
        
        $res = sendCode($code, "+81". $phone);
        if (!$res) {
            $data = array("status"=> 0);
            return redirect()->back()->with('message', '電話番号を再度入力してください！')->with('title', '入力エラー')->with('message_id', Str::random(9))->with('type', 'dialog')->with('data', $data);
        }

        Verify::where('to', $phone)->update(array('status'=>1));
        $data = array("to"=>$phone, 'code'=>$code);
        Verify::create($data);

        $data = array("status"=> 1);
        return redirect()->back()->with('data', $data);
    }

    public function verify(Request $request) {
        $code = $request->code;
        $phone = $request->phone;
        $verify = Verify::where('to', $phone)->where('status', 0)->first();
        if ($verify && $verify->code == $code) {
            $verify->status = 2;
            $verify->save();
            $user = auth()->user();
            $user->update([
                'phone' => $phone,
                'email_verified_at' => $verify->updated_at,
                'point' => $user->point + ($user->email_verified_at ? 0 : 1000),
            ]);
            $data = [
                "status"=> 1,
                "user"=>$user
            ];
            return redirect()->back()->with('message', '電話番号が正常に認証されました。')->with('title', '電話認証成功')->with('message_id', Str::random(9))->with('type', 'dialog')->with('data', $data);
        } else {
            $verify?->update(['status', 1]);
            $data = array();
            return redirect()->back()->with('message', '再度SMS認証をお願い致します！')->with('title', 'エラー')->with('message_id', Str::random(9))->with('type', 'dialog')->with('data', $data);
        }
    }

    public function send_email(Request $request) {
        $email = $request->email; $data = array();
        $email = trim($email);
        
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users'
        ]);
        $count = User::where('email', $email)->count();
        if ($count>0) {
            return redirect()->back()->with('message', 'このメールはすでに登録されています！')->with('title', 'エラー')->with('message_id', Str::random(9))->with('type', 'dialog')->with('data', $data);
        }

        $code = generateCode(4);
        $code = "1111";
        
        // $res = sendEmail($code, $email);
        
        // if (!$res) {
        //     $data = array("status"=> 0);
        //     return redirect()->back()->with('message', 'もう一度メールアドレスを入力してください！')->with('title', '入力エラー')->with('message_id', Str::random(9))->with('type', 'dialog')->with('data', $data);
        // }

        Verify::where('to', $email)->update(array('status'=>1));
        $data = array("to"=>$email, 'code'=>$code);
        Verify::create($data);

        $data = array("status"=> 1);
        return redirect()->back()->with('data', $data);
    } 

    public function verify_email(Request $request) {
        $code = $request->code;
        $email = $request->email;
        $count = Verify::where('to', $email)->where('code', $code)->where('status', 0)->count();
        if ($count) {
            Verify::where('to', $email)->where('code', $code)->where('status', 0)->update(array('status'=>2));
            $data = array("status"=> 1);
            return redirect()->back()->with('data', $data);
        } else {
            $data = array();
            return redirect()->back()->with('message', '再度SMS認証をお願い致します！')->with('title', 'エラー')->with('message_id', Str::random(9))->with('type', 'dialog')->with('data', $data);
        }
    }

    public function register(Request $request) { 
        $request->validate([
            'password' => 'required|min:6|max:20',
        ]);

        $phone = $request->phone;
        $phone = trim($phone); $data = array();
        
        if (!isPhoneNumber($phone)) {
            return redirect()->back()->with('message', '11桁の電話番号を入力してください！')->with('title', '入力エラー')->with('message_id', Str::random(9))->with('type', 'dialog')->with('data', $data);
        }
        $count = User::where('phone', $phone)->count();
        if ($count>0) {
            return redirect()->back()->with('message', 'すでに登録された電話番号です！')->with('title', 'エラー')->with('message_id', Str::random(9))->with('type', 'dialog')->with('data', $data);
        }

        $friend = null;
        if (isset($request->invite_code)) {
            $friend = User::where('invite_code', $request->invite_code)->first();
            if ($friend == null) {
                return redirect()->back()->with('message', '有効な招待コードを入力してください。')->with('title', 'エラー')->with('message_id', Str::random(9))->with('type', 'dialog');
            }
        }
        
        $user = User::create([
            'name' => "ユーザー",
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        if ($friend) {
            Invitation::create([
                'user_id' => $user->id,
                'inviter' => $friend->id
            ]);
        }
        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}

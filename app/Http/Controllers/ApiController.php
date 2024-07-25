<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Gacha;
use App\Models\Point;
use App\Models\Favorite;

use App\Models\Product;
use App\Models\Product_log;
use App\Models\Profile;
use App\Models\Gacha_lost_product;

use App\Models\Category;
use App\Models\Gacha_record;
use App\Models\Payment;
use App\Models\User;
use App\Models\Option;
use App\Models\Coupon;
use App\Models\Coupon_record;

use App\Http\Resources\ProductListResource;
use App\Http\Resources\FavoriteListResource;
use App\Http\Resources\PointList;
use App\Http\Resources\GachaListResource;

use Str;

use \Exception;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use Stripe\Stripe;
use Stripe\PaymentIntent;

use App\Notifications\PushNotification;

class ApiController extends Controller
{

    public function categories() {
        $categories = Category::get();
        return [
            "success" => true,
            "categories" => $categories
        ];
    }
    // Gacha code

    public function gachas($cat_id = null) {
        if ($cat_id == null) {
            $cat_id = Category::first()->id;
        }
        $gachas = Gacha::where('category_id', $cat_id)->where('status', 1)->get();
        $gachas = GachaListResource::collection($gachas);
        return [
            "success" => true,
            "cat_id" => $cat_id,
            "gachas" => $gachas
        ];
    }

    public function gacha_detail($id) {
        $gachas = Gacha::where('id', $id)->where('status', 1)->get();
        if (count($gachas)) {
            return [
                "success" => true,
                "gacha" => GachaListResource::collection($gachas)[0]
            ];
        }
        return [
            "success" => false,
            "message" => "ガチャカードは存在しません！"
        ];
    }

    public function gacha_start(Request $request) {
        $id = $request->id;
        $number = $request->number;
        $gacha = Gacha::find($id);
        $user = auth('api')->user();
        
        $result = (object)[
            "success" => false,
        ];

        $userLock = Cache::lock('startGacha'.$user->id, 60);
        if (!$userLock->get()) {
            return $result;
        }

        try {

            if (!$gacha || $gacha->status == 0 || $gacha->count_card == $gacha->count) {
                $result->message = "ガチャカードは存在しません！";
                return $result;
            }
            
            $totalSpin = Gacha_record::where('user_id', $user->id)->where('gacha_id', $id)->where('status', 1)->sum('type');
            $remainingSpin = $gacha->spin_limit - $totalSpin;
            if ($remainingSpin < 0) $remainingSpin = 0;
    
            $count_rest = $gacha->count_card - $gacha->count;
            if ($number > $count_rest) $number = $count_rest;
            if ($number > $remainingSpin) {
                $result->message = 'このガチャは'.$gacha->spin_limit.'回までガチャできます。 すでに回したガチャ数は'.$totalSpin.'回です。';
                return $result;
            }
    
            $this->check_current_status($gacha);
            $status = $gacha->gacha_limit_status;
    
            if ($status == 1) {
                if ($number > 1) {
                    $result -> message = '1日1回以上ガチャできません。';
                    return $result;
                }
                $last = Gacha_record::where('user_id', $user->id)->where('gacha_id', $id)->where('status', 1)->latest()->first();
                if ($last) {
                    $now = $this->get_period_day(date('Y-m-d H:i:s'));
                    $record = $this->get_period_day($last->updated_at);
                    if ($now == $record) {
                        $result -> message = '1日1回以上ガチャできません。';
                        return $result;
                    }
                }
            }     
            
            $gacha_point = $gacha->point * $number;
            $user_point = $user->point;
            if ($user_point< $gacha_point) {
                $result->redirect_url = 'points';
                return $result;
            }
    
            $lock = Cache::lock('startGacha', 60);
            try {
                $lock->block(10);
                
                $data = [
                    'user_id' => $user->id,
                    'gacha_id' => $gacha->id,
                    'type' => $number
                ];
                $gacha_record = Gacha_record::create($data);
                
                $token = $gacha_record->id;
                $res = $this->reward($user, $gacha, $number, $token);
                
                $lock?->release();
                if ($res == 0) {
                    $gacha_record->update(['status'=>1]);
                    
                    $dp = $number + $user->dp;
                    $point = $user->point;
                    $point = $point - $gacha->point * $number;
                    $user->update(['dp'=>$dp, 'point'=>$point]);

                    $hide_cat_bar = 1;
                    $result -> success = true;
                    $result -> token = $token;
                    return $result;
                }
                if ($res==1) $result -> message = "サーバーが混み合っております。少し時間をおいて再度お試しください。";
                else if ($res==2) $result -> message = "ガチャ回数を超えました！";
                else if ($res==3) $result -> message = "ガチャ時間を超えました！";
                else if ($res==4) $result -> message = "ユーザーポイントが足りません。";

                return $result;
            } catch (LockTimeoutException $e) {
                $result -> message = "ガチャ時間を超えました！";
                return $result;
            }
        } finally {
            $userLock?->release();
        }
    }

    public function reward($user, $gacha, $number, $token) {
        $user = User::find($user->id);
        $gacha = Gacha::find($gacha->id);
        $ableCount = $gacha->ableCount();  // Check Gacha Product   #3
        if ($ableCount==0) return 1;

        $point = $user->point - $gacha->point * $number;  // Check User Point   #4
        if ($point<0) return 4;

        $count_rest = $gacha->count_card - $gacha->count;
        $award_products = $gacha->getAward($number, $count_rest); 
        if ($award_products) {} else {
            return 1;  // Check Gacha Product   #3
        }

        $max_point = 0;
        
        // from
        foreach($award_products as $key) {
            $product_item = Product::find($key);
            if ($max_point<$product_item->point) {
                $max_point = $product_item->point;
            }
            if ($product_item->marks>0) {
                $product_item->decrement('marks');

                $data = [
                    'product_id' => $product_item->id,
                    'point' => $product_item->point,
                    'rare' => $product_item->rare,
                    'image' => $product_item->image,
                    'name' => $product_item->name,
                    'gacha_record_id' => $token,
                    'user_id' => $user->id,
                    'status' => 1
                ];

                Product_log::create($data);

                if ($product_item->is_lost_product == 1) {
                    Gacha_lost_product::where('gacha_id', $gacha->id)
                        ->where('point', $product_item->point)
                        ->where('count','>',0)
                        ->first()?->decrement('count');
                }
            }
        
        }

        $gacha->update(['count'=> $gacha->count + $number ]);

        return $data = [
            'result' => 0,
            'max_point' => $max_point
        ];

        // foreach($award_products as $key) {
        //     $product_item = Product::find($key);
        //     if ($product_item->is_last==0) {
        //         if ($product_item->marks>0) {
        //             if ($product_item->is_lost_product==0) {
        //                 $data = [
        //                     'marks' => ($product_item->marks-1),
        //                 ];
        //                 Product::where('id', $key)->update($data);

        //                 $data = [
        //                     'name' => $product_item->name,
        //                     'point' => $product_item->point,
        //                     'rare' => $product_item->rare,
        //                     'image' => $product_item->image,
        //                     'marks' => 1,
        //                     'is_last' => $product_item->is_last,
        //                     'lost_type' => $product_item->lost_type,
        //                     'is_lost_product' => $product_item->is_lost_product,
        //                     'gacha_id' => $gacha->id,
        //                     'category_id' => $product_item->category_id,
        //                     'gacha_record_id' => $token, 
        //                     'user_id' => $user->id,
        //                     'status' => 1
        //                 ];
        //                 Product::create($data);
        //             } else {
        //                 if ($product_item->is_lost_product==1) {
        //                     $values = Gacha_lost_product::where('gacha_id', $gacha->id)->where('point', $product_item->point)->where('count','>',0)->get();
        //                     if (count($values)) {
        //                         $data = [
        //                             'count' => $values[0]->count - 1,
        //                         ];
        //                         $values[0]->update($data);

        //                         $data = [
        //                             'marks' => ($product_item->marks-1),
        //                         ];
        //                         Product::where('id', $key)->update($data);

        //                         $data = [
        //                             'name' => $product_item->name,
        //                             'point' => $product_item->point,
        //                             'rare' => $product_item->rare,
        //                             'marks' => $product_item->id,
        //                             'lost_type' => $product_item->lost_type,
        //                             'category_id' => $product_item->category_id,
        //                             'is_lost_product' => 1,
        //                             'image' => $product_item->image,

        //                             'gacha_id' => $gacha->id,
        //                             'user_id' => $user->id,
        //                             'gacha_record_id' => $token, 
        //                             'status' => 1
        //                         ];
        //                         Product::create($data);
        //                     }
        //                 }
        //             }
        //         }
        //     } else {
        //         $data = [
        //             'user_id' => $user->id,
        //             'gacha_record_id' => $token,
        //             'status' => 1
        //         ];
        //         Product::where('id', $key)->update($data);
        //     }
        // }
        // $gacha->update(['count'=> $gacha->count + $number ]);
        
        // return $max_point;
        // // return $result = [
        // //     'result' => 0,
        // //     'max_point' => $max_point
        // // ];
    }

    public function get_period_day($current) {
        $current_day = date('Y-m-d 20:00:00', strtotime($current));
        if ($current < $current_day) $current = date('Y-m-d', strtotime($current.' -1 days'));
        else $current = date('Y-m-d', strtotime($current));
        return $current;
    }

    public function check_current_status($gacha) {
        $now = $this->get_period_day(date('Y-m-d H:i:s'));
        if ($gacha->starting_day != $now) {
            $gacha->update(['starting_day' => $now, 'gacha_limit_status' => $gacha->gacha_limit_on_setting]);
        }
    }

    public function gacha_result($token) {
        $user = auth('api')->user();
        $products = Product_log::where('gacha_record_id', $token)->where('user_id', $user->id)->orderBy('point', 'DESC')->get();
        $max_point = 0;
        foreach ($products as $product) {
            $max_point = max($max_point, $product->point);
        }

        $products = ProductListResource::collection($products);

        $video = getVideo($max_point);
        return [
            'token' => $token,
            'video' => '/videos/'.$video,
            'products' => $products
        ];
    }

    public function result_exchange(Request $request) {
        DB::beginTransaction();
        $token = $request->token; 
        $checks = $request->checks;
        $user = auth('api')->user();
        $logs = Product_log::where('gacha_record_id', $token)->where('status', 1)->get();
  
        $point = $user->point;
        foreach($logs as $log) {
            $key = "id" . $log->id;
            if (isset($checks[$key]) && $checks[$key]) {
                $log->status = 2;
                $log->save();
                if ($product = Product::find($log->product_id)) {
                    if ($product->is_lost_product == 1)
                        $product->increment('marks');
                }
                $point = $point + $log->point;
            }
        }
        $user->update(['point'=>$point]);
        $user = auth('api')->user();
        DB::commit();

        return [
            'success' => true,
            'user' => $user
        ];
    }

    public function gacha_end($token) {
        $point = 0; $number_products = 0;
        if ($token) {
            $user = auth('api')->user();
            $products = Product_log::where('gacha_record_id', $token)->where('user_id', $user->id)->where('status', 2)->get();
            
            foreach($products as $product) {
                if ($product->status==2) {
                    $point = $point + $product->point;
                    $number_products = $number_products + 1;
                }
            }
            $gacha_record = Gacha_record::find($token);
            if ($gacha_record) {
                $gachas = Gacha::where('id', $gacha_record->gacha_id)->get();
                $gachas = GachaListResource::collection($gachas);
                return [
                    "success" => true,
                    "point" => $point,
                    "number_products" => $number_products,
                    "gachas" => $gachas
                ];
            }
        }        
        return [
            "success" => false,
            "message" => 'そのようなガチャ履歴は存在しません！'
        ];
    }

    // Gacha Code End

    public function points() {
        $points = Point::orderBy('amount','ASC')->get();
        $points = PointList::collection($points);
        return [
            "success" => true,
            "points" => $points
        ];
    }

    public function purchase_register(Request $request) {
        $id = $request->id;
        $pay_type = $request->pay_type;
        $hide_cat_bar = 1;

        $point = Point::find($id);
        if ($point == null) {
            return [
                'success' => false,
            ];
        }
        $amount = $point->amount;

        $user = auth('api')->user();
           
        $apiPath = "/v1/payments";
        $method = 'POST';

        if ($pay_type == 'Card') {
            $requestParams = array(
                "pay_type" => "Card",
                "job_code" => "CAPTURE",
                "amount" => strval($amount),
                "tds_type" => "0",
                "tds2_type" => "3"
            );        
            if (getOption('is3DSecure')=="1") {
                $requestParams['tds_type'] = "2";  //   3DS2.0を利用
                $requestParams['td_tenant_name'] = "Toretore Gacha Station";  //   3Dセキュア表示店舗名
                $requestParams['tds2_type'] = "3";  //   3DS2.0の認証なしでオーソリを実施し、決済処理を行う。
                // $requestParams['tds2_type'] = "2";  //   エラーを返し、決済処理を行わない。
            }
        }
        else if ($pay_type == 'Paypay') {
            $requestParams = array(
                "pay_type" => "Paypay",
                "job_code" => "CAPTURE",
                "amount" => strval($amount)
            );
        }

        $res = (new PaymentController)->do_request($apiPath, $method, $requestParams);
        if ($res['httpcode']!='200') {
            return [
                'success' => false,
                'message' => (new PaymentController)->getErrorText($res)
            ];
        }
        $json_data = json_decode($res['response']);
        $order_id = $json_data->id;
        $access_id = $json_data->access_id;

        Payment::Create([
            'pay_type'=>$pay_type,
            'user_id'=> $user->id,
            'point_id'=> $point->id,
            'access_id'=>$access_id,
            'order_id'=>$order_id,
        ]);

        return [
            'access_id'=>$access_id,
            'order_id'=>$order_id
        ];
    }

    public function purchase_process(Request $request) {
        $id = $request->id;
        $pay_type = $request->pay_type;
        $user = auth('api')->user();
        $transaction = $request->all();
        $res = [];
        $res['success'] = false;

        if ($pay_type == 'Card') {
            $is3DSecure = getOption('is3DSecure');
            if ($is3DSecure == "1") {
                $transaction['tds2_ret_url'] = route('tds2_ret_url');  
            }
            
            $res = array();
            $res['status'] = '0';
            
            $apiPath = "/v1/payments/".$transaction['order_id'];
            $method = 'PUT';
            $requestParams = $transaction;
            if ($is3DSecure=="1") {
                $ans = (new PaymentController)->do_request($apiPath, $method, $requestParams);
            }
            else {
                $ans = (new PaymentController)->do_request($apiPath, $method, [
                    'pay_type' => 'Card',
                    'access_id' => $transaction['access_id'],
                    'method' => $transaction['method'],
                    'token' => $transaction['token'],
                    'pay_times' => $transaction['pay_times'],
                    'holder_name' => $transaction['holder_name'],
                    'expire' => $transaction['expire']
                ]);
            }
            
            if ($ans['httpcode']!='200') {
                $res['message'] = "決済実行エラー！\n";
                $res['message'] .= (new PaymentController)->getErrorText($ans) ;
                return $res;
            }
            $json_data = json_decode($ans['response']);
            
            $order_id = $json_data->id;
            $access_id = $json_data->access_id;
            if ($json_data->status=="CAPTURED") {
                $payments = Payment::where('order_id', $order_id)->where('access_id', $access_id)->where('status', 0)->get();
                if(count($payments)) {
                    $payment = $payments[0];
                    $user = User::find($payment->user_id);
                    $point = Point::find($payment->point_id);
                    $sum = ((int)$point->point) + ((int)$user->point);
                    $user->update(['point'=>$sum]);
                    $payment->update(['status'=>1]);
                    $res['status'] = $json_data->status;  // CAPTURED
                }
            }
    
            if ($json_data->status=="AUTHENTICATED") {
                $payments = Payment::where('order_id', $order_id)->where('access_id', $access_id)->where('status', 0)->get();
                if(count($payments)) {
                    $payment = $payments[0];
                    $payment->update(['status'=>2]);
                }
    
                $res['order_id'] = $order_id;  
                $res['access_id'] = $access_id;
                $res['job_code'] = $json_data->job_code;  // CAPTURE
                $res['acs_url'] = $json_data->acs_url; 
                $res['status'] = $json_data->status; // AUTHENTICATED
            }

        }

        return json_encode($res);
    }

    // public function favorite() {
    //     $user = auth()->user();
    //     $products = Favorite::where('user_id', $user->id)->orderBy('id', 'ASC')->get();
    //     $products = FavoriteListResource::collection($products);  
    //     $hide_cat_bar = 1;
    //     // return $products;
    //     $hide_back_btn = 1;
    //     return inertia('User/Favorite', compact('products', 'hide_cat_bar', 'hide_back_btn'));
    // }

    // public function favorite_add(Request $request) {
    //     $res = ['status'=>0];
    //     $id = $request->id;
    //     $value = $request->value;
    //     if ($id) {
    //         $user = auth()->user();
    //         if ($value) {
    //             $products = Favorite::where('user_id', $user->id)->where('product_id', $id)->get();
    //             if (!count($products)) {
    //                 Favorite::create(['user_id'=>$user->id, 'product_id'=>$id]);
    //             }
    //         } else {
    //             Favorite::where('user_id', $user->id)->where('product_id', $id)->delete();
    //         }
    //         $res['status'] = 1;
    //     }
    //     return redirect()->back()->with('message', '保存しました！')->with('title', 'お気に入り')->with('message_id', Str::random(9))->with('type', 'dialog');
    // }

    public function profile() {
        $user = auth('api')->user();

        if ($user->invite_code == null) {
            $user->invite_code = Str::random(15);
            $user->save();
        }

        $profile = Profile::where('user_id', $user->id)->first();
        if ($profile) {
            return [
                'success' => true,
                'profile' => $profile
            ];
        }
        return [
            'success' => false,
        ];
    }

    public function profile_post(Request $request) {
        try {
            $validated = $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'first_name_gana' => 'required',
                'last_name_gana' => 'required',
                'postal_code' => 'required',
                'prefecture' => 'required',
                'address' => 'required',
                'phone' => 'required|numeric|digits:11',
            ]);
        } catch (error) {
            return response()->json([
                'success' => false,
                'errors' => error->errors(),
            ], 422);
        }
        
        $user = auth('api')->user();

        $result = [
            'success' => true
        ];

        $profile = Profile::where('user_id', $user->id)->first();
        if ($profile) {
            Profile::where('user_id', $user->id)->update($validated);
            $result['profile'] = Profile::where('user_id', $user->id)->first();
            $result['message'] = '個人情報が正常に更新されました。';
        } else {
            $validated['user_id'] = $user->id;
            Profile::create($validated);
            $result['message'] = '個人情報の登録が完了しました。';
        }
        return response()->json($result);
    }

    public function updateProfile(Request $request){
        try {
            $validated = $request->validate([
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required'
            ]);
        } catch (error) {
            return response()->json([
                'success' => false,
                'errors' => error->errors(),
            ], 422);
        }

        $user = auth('api')->user();
        User::where('id', $user->id)->update($validated);

        $result = [
            'success' => true,
            'message' => '個人情報が正常に更新されました。'
        ];

        return $result;
    }

    public function updateProfilePassword(Request $request){
        
        $user = auth('api')->user();
        $password = $user->getAttributes()['password'];
        if (Hash::check($request->current_password, $password)) {
            auth('api')->user()->update(['password' => Hash::make($request->password)]);
            return $result = [
                'success' => true,
                'message' => "パスワードの変更に成功しました。"
            ];
        } else {
            return $result = [
                'success' => false,
                'message' => "パスワードの変更に失敗しました。"
            ];
        }
    }

    public function products($status) {
        $user = auth('api')->user();
        $products = Product::where('user_id', $user->id)->where('status', $status)->orderBy('id', 'ASC')->get();
        $products = ProductListResource::collection($products); 

        $profiles = Profile::where('user_id', $user->id)->get();
        $profile = [];
        if (count($profiles)) {
            $profile = $profiles[0];
        }

        return [
            'success' => true,
            'products' => $products
        ];
    }

    public function product_point_exchange(Request $request) {
        // $checks = $request->checks;
        // $user = auth('api')->user();
        // $products = Product::where('user_id', $user->id)->where('status', 1)->get();
  
        // $point = $user->point;
        // foreach($products as $product) {
        //     $key = "id" . $product->id;
        //     if (isset($checks[$key]) && $checks[$key]) {
        //         $point = $point + $product->point;
        //         $product->status = 2;
        //         $product->save();
        //         Product::find($product->marks)?->increment('marks', 1);
        //     }
        // }
        // $user->update(['point'=>$point]);

        // return [
        //     'success' => true,
        //     'point' => $point
        // ];

        $checks = $request->checks;
        $user = auth('api')->user();
        $points = $request->points;

        $updated_points = $user->point + $points;

        $point = User::where('id', $user->id)->first('point');

        Product::whereIn('id', $checks)->where('user_id', $user->id)->where('status', 1)->update(['status' => 2]);
        User::find($user->id)->update(['point' => $point->point + $points]);

        $user = auth('api')->user();
        $products = Product::where('user_id', $user->id)->where('status', 1)->get();

        return response()->json([ 'points' => $updated_points, 'products' => $products ]);
    }

    public function product_delivery_post(Request $request) {
        $user = auth('api')->user();
        $checks = $request->checks;
        $points = $request->points;
        $products = Product::where('user_id', $user->id)->where('status', 1)->get();
        // $point = 0;
        // foreach($products as $product) {
        //     $key = "id" . $product->id;
        //     if (isset($checks[$key]) && $checks[$key]) $point += $product->point;
        // }
        // if ($point < 1000) {
        //     return [
        //         'success' => false,
        //         'message' => '発送は１０００ポイント以上からお願いします。'
        //     ];
        // }
        // foreach($products as $product) {
        //     $key = "id" . $product->id;
        //     if (isset($checks[$key]) && $checks[$key]) {
        //         $product->status = 3;
        //         $product->save(); 
        //     }
        // }

        $result = Product::whereIn('id', $checks)->where('user_id', $user->id)->where('status', 1)->update(['status' => 3]);
        if($result){
            return [
                'success' => true
            ];
        } else {
            return [
                'success' => false
            ];
        }
        
    }

    // public function dp_detail($id) {
    //     $user = auth()->user();
    //     $products = Product::where('id', $id)->where('status', 0)->where('is_lost_product', 2)->get();
    //     if (!count($products)) {
    //         return redirect()->route('main.dp'); 
    //     }
    //     $product = $products[0];
    //     $favorite = Favorite::where('user_id', $user->id)->where('product_id', $product->id)->count();

    //     $products = ProductListResource::collection($products); 
    //     $productStatusTxt = getProductStatusTxt();
        
    //     $profiles = Profile::where('user_id', $user->id)->get();
    //     $profile = [];
    //     if (count($profiles)) {
    //         $profile = $profiles[0];
    //     }
        
    //     $hide_cat_bar = 1;
    //     return inertia('User/Dp/Detail', compact('products', 'favorite', 'hide_cat_bar', 'productStatusTxt', 'profile'));
    // }

    // public function dp_detail_post(Request $request) {
    //     $id = $request->id;
    //     if (!$id) {
    //         return redirect()->route('main.dp');
    //     }
    //     $products = Product::where('id', $id)->where('status', 0)->where('is_lost_product', 2)->get();
    //     if (!count($products)) {
    //         return redirect()->route('main.dp');
    //     }

    //     $user = auth()->user();
    //     if ($user->dp<$products[0]->dp) {
    //         return redirect()->back()->with('message', 'DPが足りてないです！')->with('title', 'TP交換所 – 詳細')->with('message_id', Str::random(9))->with('type', 'dialog');
    //     }

    //     // $product = Product::where('id', $id)->where('status', 0)->where('is_lost_product', 2)->update(['status'=>1, 'user_id'=>$user->id]);
    //     $product = Product::find($id);
    //     $data = [
    //         'name' => $product->name,
    //         'point' => $product->point,
    //         'dp' => $product->dp,
    //         'image' => $product->image,
    //         'category_id' => $product->category_id,
    //         'rare' => $product->rare,
    //         'product_type' => $product->product_type,
    //         'status_product' => $product->status_product,
    //         'is_lost_product' => 2,
    //         'status' => 3,
    //         'user_id'=>$user->id
    //     ];
    //     Product::create($data);
            
    //     $dp = $user->dp - $product->dp;
    //     $user->update(['dp'=>$dp]);
    //     return redirect()->route('user.dp.detail.success');
    // }

    // public function dp_detail_success(Request $request) {
    //     $hide_cat_bar = 1;$hide_back_btn = 1;
    //     return inertia('User/Dp/Success', compact('hide_cat_bar', 'hide_back_btn'));
    // }

    public function coupons() {
        $user = auth('api')->user();
        $hide_cat_bar = 1;
        $coupons = DB::table('coupon_records')->leftJoin('coupons', 'coupons.id', '=', 'coupon_records.coupon_id')
            ->select('coupons.title', 'coupons.point', 'coupon_records.updated_at')
            ->where('coupon_records.user_id', $user->id)
            ->orderBy('coupon_records.updated_at', 'desc')->get();
        foreach($coupons as $coupon) {
            $coupon->acquired_time = date('Y年m月d日 H時i分', strtotime($coupon->updated_at));
        }
        return [
            'success' => true,
            'coupons' => $coupons
        ];
    }

    public function coupon_post(Request $request) {
        $user = auth('api')->user();
        // $request->validate([
        //     'code' => 'required'
        // ]);
        $coupon = Coupon::where('code', $request->coupon)->first();
        if ($coupon) {
            if ($coupon->expiration >= date('Y-m-d H:i:s')) {
                return [
                    'success' => false,
                    'message' => '有効期間を超えました。'
                ];
            }
            $record = Coupon_record::where(['coupon_id' => $coupon->id, 'user_id' => $user->id])->first();
            if ($record) {
                return [
                    'success' => false,
                    'message' => 'すでにこのコードを利用しました。'
                ];
            }
            $records = Coupon_record::where(['coupon_id' => $coupon->id])->count();
            if ($records == $coupon->user_limit) {
                return [
                    'success' => false,
                    'message' => '利用可能な人数を超えました。'
                ];
            }
            $coupon_record = Coupon_record::create([
                'coupon_id' => $coupon->id,
                'user_id' => $user->id
            ]);
            $user->update(['point' => $user->point + $coupon->point]);
            $coupon_record->acquired_time = date('Y年m月d日 H時i分', strtotime($coupon_record->updated_at));

            $coupon_record->title = $coupon->title;
            $coupon_record->point = $coupon->point;

            return [
                'success' => true,
                'message' => '取得に成功しました。',
                'coupon' => $coupon_record
            ];
        }
        return [
            'success' => false,
            'message' => '有効なコードを入力してください。'
        ];
    }

    public function startPost(Request $request) {
        $id = $request->id;
        $number = $request->number;
        $gacha = Gacha::find($id);
        $user = auth('api')->user();
        $userLock = Cache::lock('startGacha'.$user->id, 60);

        $result = (object)[
            "success" => false,
        ];

        if (!$userLock->get()) {
            return $result;
        }

        try {

            if (!$gacha || $gacha->count_card == $gacha->count) {
                return $result; 
            }
            
            $totalSpin = Gacha_record::where('user_id', $user->id)->where('gacha_id', $id)->where('status', 1)->sum('type');
            $remainingSpin = $gacha->spin_limit - $totalSpin;
            if ($remainingSpin < 0) $remainingSpin = 0;
            
            $count_rest = $gacha->count_card - $gacha->count;
            if ($number > $count_rest) $number = $count_rest;
            if ($number > $remainingSpin) {
                return response()->json([
                    'message' => 'このガチャは'.$gacha->spin_limit.'回までガチャできます。 すでに回したガチャ数は'.$totalSpin.'回です。',
                    'message_id' => Str::random(9),
                    'title' => 'ガチャ回数超過!'
                ]);
            }
    
            $this->check_current_status($gacha);
            $status = $gacha->gacha_limit_status;
            if ($status == 1) {
                if ($number > 1) {
                    $message = '1日1回以上ガチャできません。';
                    return redirect()->back()->with('message', $message)->with('title', '1日1回ガチャ制限')->with('message_id', Str::random(9))->with('type', 'dialog');
                }
                $last = Gacha_record::where('user_id', $user->id)->where('gacha_id', $id)->where('status', 1)->latest()->first();
                if ($last) {
                    $now = $this->get_period_day(date('Y-m-d H:i:s'));
                    $record = $this->get_period_day($last->updated_at);
                    if ($now == $record) {
                        $message = '1日1回以上ガチャできません。';
                        return response()->json([
                            'message' => $message,
                            'title' => '1日1回ガチャ制限',
                            'message_id' => Str::random(9)
                        ]);
                    }
                }
            }     
            if ($number > $remainingSpin) {
                return response()->json([
                    'message' => 'このガチャは'.$gacha->spin_limit.'回までガチャできます。<br>すでに回したガチャ数は'.$totalSpin.'回です。',
                    'title' => 'ガチャ回数超過!',
                    'message_id' => Str::random(9)
                ]);
            }
            
            $gacha_point = $gacha->point * $number;
            $user_point = $user->point;
            if ($user_point < $gacha_point) {
                return response()->json([
                    'url' => 'points'
                ]);
            }
    
            $lock = Cache::lock('startGacha', 60);
            try {
                $lock->block(10);
                
                $data = [
                    'user_id' => $user->id,
                    'gacha_id' => $gacha->id,
                    'type' => $number
                ];

                $gacha_record = Gacha_record::create($data);

                $token = $gacha_record->id; 
                
                $result = $this->reward($user, $gacha, $number, $token); 

                $lock?->release();

                if (isset($result['max_point'])) {
                    $max_point = $result['max_point'];
                    $gacha_record->update(['status'=>1]);
                    
                    $dp = $number + $user->dp;
                    $point = $user->point;
                    $point = $point - $gacha->point * $number;
                    $user->update(['dp'=>$dp, 'point'=>$point]);

                    $hide_cat_bar = 1;
                    $video = getVideo($max_point);

                    return response()->json([
                        'video' => $video,
                        'token' => $token,
                        'hide_cat_bar' => $hide_cat_bar
                    ]);
                    // return inertia('User/Video', compact('hide_cat_bar', 'video', 'token'));
                }
                if ($result==1) {
                    $text = "サーバーが混み合っております。少し時間をおいて再度お試しください。";
                    $hide_cat_bar = 1;
                    return inertia('NoProduct', compact('text', 'hide_cat_bar'));
                }
        
                if ($result==2) {
                    $text = "ガチャ回数を超えました！";
                    $hide_cat_bar = 1;
                    return inertia('NoProduct', compact('text', 'hide_cat_bar'));
                }
        
                if ($result==3) {
                    $text = "ガチャ時間を超えました！";
                    $hide_cat_bar = 1;
                    return inertia('NoProduct', compact('text', 'hide_cat_bar'));
                }
        
                if ($result==4) {
                    $text = "ユーザーポイントが足りません。";
                    $hide_cat_bar = 1;
                    return inertia('NoProduct', compact('text', 'hide_cat_bar'));
                }
            } catch (LockTimeoutException $e) {
                $text = "ガチャ時間を超えました！";
                $hide_cat_bar = 1;
                return inertia('NoProduct', compact('text', 'hide_cat_bar'));
            }
        } finally {
            $userLock?->release();
        }
    }

    public $config = [
        'testOrLive' => 'test',  // live &  test
        'secret_key' => '',
        'webhook_secret' => '',
    ];

    private function set_config() {
        $testOrLive = getOption('testOrLive');
        if ($testOrLive!="live") {
            $testOrLive = 'test';
        }
        $this->config['testOrLive'] = $testOrLive;
        if ($this->config['testOrLive'] =="live") {
            $this->config['secret_key'] = env('STRIPE_SECRET_KEY');
            $this->config['webhook_secret'] = env('STRIPE_WEBHOOK_SECRET');
        } else {
            $this->config['secret_key'] = env('STRIPE_SECRET_KEY_TEST');
            $this->config['webhook_secret'] = env('STRIPE_WEBHOOK_SECRET_TEST');
        }
    }

    public function toPurchase($id) {
        $this->set_config();
        $point = Point::find($id); 
        // return $this->config['secret_key'];
        $stripe = new \Stripe\StripeClient(
            $this->config['secret_key']
        );

        $url = 'https://modern-gifts-shake.loca.lt/' . '/point';
        $checkout = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            // 'success_url' => url('/point/success'),
            // 'cancel_url' => url('/point'),
            'success_url' => $url . '/success',
            'cancel_url' => $url,
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'jpy',
                        'unit_amount' => $point->amount,
                        'product_data' => [
                            'name' => $point->title,
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
            'locale' => 'ja',
            'mode' => 'payment',
        ]);
        if ($checkout['id']) {
            Payment::Create([
                'user_id'=> auth('api')->user()->id,
                'point_id'=> $point->id,
                'order_id'=>$checkout['id'],
                'payment_intent'=>"",
            ]);
            $hide_cat_bar = 1;
            return response()->json([ 'checkout' => $checkout, 'cat_bar' => $hide_cat_bar ]);
            return inertia("User/Payment/Index", compact('checkout', 'hide_cat_bar'));
        }

        $text = "決済登録に失敗しました！";
        return inertia("NpProduct", compact('text'));
    }

    public function createPaymentIntent(Request $request){
         // Fetch the Stripe secret key from the environment variables
         $stripeSecret = env('STRIPE_SECRET_KEY_TEST');

         if (!$stripeSecret) {
             return response()->json(['error' => 'Stripe secret key is not set'], 500);
         }

         $user = auth('api')->user();
         $point = Point::find($request->id);
 
         Stripe::setApiKey($stripeSecret);
 
         $amount = $request->input('amount');
 
         try {
             $paymentIntent = PaymentIntent::create([
                 'amount' => $point->amount,
                 'currency' => 'jpy',
             ]);

            //  Payment::Create([
            //     'user_id'=> auth()->user()->id,
            //     'point_id'=> $point->id,
            //     // 'order_id'=>$checkout['id'],
            //     'payment_intent'=>"",
            // ]);
 
             return response()->json([
                 'clientSecret' => $paymentIntent->client_secret,
                 'point' => $point->amount
             ]);
         } catch (\Exception $e) {
             return response()->json([
                 'error' => $e->getMessage(),
             ], 500);
         }
    }


    public function purchaseSuccess(){
        return response()->json([
            'url' => 'point'
        ]);
    }

    public function purchaseCancel(){
        return response()->json([
            'url' => 'point'
        ]);
    }

    public function sendNotification(Request $request){

        // return $request;
        $user = auth('api')->user();
        $user->notify(new PushNotification($request->title, $request->content));

        return $user->notify(new PushNotification($request->title, $request->content));
    }
}

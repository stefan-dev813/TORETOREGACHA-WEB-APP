<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Gacha;
use App\Models\Point;
use App\Models\Favorite;

use App\Models\Product;
use App\Models\Profile;
use App\Models\Gacha_lost_product;

use App\Models\Gacha_record;
use App\Models\Payment;
use App\Models\User;
use App\Models\Option;
use App\Models\Coupon;
use App\Models\Coupon_record;
use App\Models\Invitation;

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

class UserController extends Controller
{
    public function index() {
        return inertia('User/Index');
    }

    public function dpexchange() {
        return inertia('User/Dpexchange');
    }

    // Gacha code

    public function gacha($id) {
        $gachas = Gacha::where('id', $id)->where('status', 1)->get();
        if (!count($gachas)) {
            $text = "ガチャカードは存在しません！";
            return inertia('NoProduct', compact('text'));
        }
        $gacha_log = [];
        if (auth()->user() && auth()->user()->type == 1) {
            $gacha_log = Product::leftJoin('users', function($join) { $join->on('users.id', '=', 'products.user_id'); })
            ->leftJoin('gacha_records', function($join) { $join->on('gacha_records.id', '=', 'products.gacha_record_id'); });
        
            if (auth()->user() && auth()->user()->type == 1) $gacha_log = $gacha_log->select('products.name', 'products.point', 'products.image', 'products.rare', 'users.email', 'products.status', 'products.created_at', 'products.updated_at', 'gacha_records.created_at as gacha_time');
            else $gacha_log = $gacha_log->select('products.name', 'products.image', 'products.rare');
            
            $gacha_log = $gacha_log->where('products.gacha_id', $id)
            ->where('products.status', '>', 0)
            ->orderBy('products.is_last')
            ->orderBy('gacha_records.created_at')->get();
        }
        foreach ($gacha_log as $log) {
            if (auth()->user() && auth()->user()->type == 1) {
                $log->updated_at_time = $log->updated_at->format('Y-m-d H:i:s');
                $log->status = getProductStatus($log->status);
            }
            $log->image = getProductImageUrl($log->image);
        }
        $gachas = GachaListResource::collection($gachas);
        $hide_cat_bar = 1;
        $is_admin = auth()->user() && auth()->user()->type == 1;

        return inertia('User/Gacha', compact('hide_cat_bar', 'gachas', 'gacha_log', 'is_admin'));
    }

    public function gacha_ionic($id) {
        $gachas = Gacha::where('id', $id)->where('status', 1)->get();
        if (!count($gachas)) {
            $text = "ガチャカードは存在しません！";
            return inertia('NoProduct', compact('text'));
        }
        $gacha_log = [];
        if (auth()->user() && auth()->user()->type == 1) {
            $gacha_log = Product::leftJoin('users', function($join) { $join->on('users.id', '=', 'products.user_id'); })
            ->leftJoin('gacha_records', function($join) { $join->on('gacha_records.id', '=', 'products.gacha_record_id'); });
        
            if (auth()->user() && auth()->user()->type == 1) $gacha_log = $gacha_log->select('products.name', 'products.point', 'products.image', 'products.rare', 'users.email', 'products.status', 'products.created_at', 'products.updated_at', 'gacha_records.created_at as gacha_time');
            else $gacha_log = $gacha_log->select('products.name', 'products.image', 'products.rare');
            
            $gacha_log = $gacha_log->where('products.gacha_id', $id)
            ->where('products.status', '>', 0)
            ->orderBy('products.is_last')
            ->orderBy('gacha_records.created_at')->get();
        }
        foreach ($gacha_log as $log) {
            if (auth()->user() && auth()->user()->type == 1) {
                $log->updated_at_time = $log->updated_at->format('Y-m-d H:i:s');
                $log->status = getProductStatus($log->status);
            }
            $log->image = getProductImageUrl($log->image);
        }
        $gachas = GachaListResource::collection($gachas);
        $hide_cat_bar = 1;
        $is_admin = auth()->user() && auth()->user()->type == 1;

        return response()->json([
            'hide_cat_bar' => $hide_cat_bar, 
            'gachas' => $gachas, 
            'gacha_log' => $gacha_log, 
            'is_admin' => $is_admin
        ]);
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
        
        foreach($award_products as $key) {
            $product_item = Product::find($key);
            if ($max_point<$product_item->point) {
                $max_point = $product_item->point;
            }
            if ($product_item->is_last==0) {
                if ($product_item->marks>0) {
                    if ($product_item->is_lost_product==0) {
                        $data = [
                            'marks' => ($product_item->marks-1),
                        ];
                        Product::where('id', $key)->update($data);

                        $data = [
                            'name' => $product_item->name,
                            'point' => $product_item->point,
                            'rare' => $product_item->rare,
                            'image' => $product_item->image,
                            'marks' => $product_item->id,
                            'is_last' => $product_item->is_last,
                            'lost_type' => $product_item->lost_type,
                            'is_lost_product' => $product_item->is_lost_product,
                            'gacha_id' => $gacha->id,
                            'category_id' => $product_item->category_id,
                            'gacha_record_id' => $token, 
                            'user_id' => auth()->user()->id,
                            'status' => 1
                        ];
                        Product::create($data);
                    } else {
                        if ($product_item->is_lost_product==1) {
                            $values = Gacha_lost_product::where('gacha_id', $gacha->id)->where('point', $product_item->point)->where('count','>',0)->get();
                            if (count($values)) {
                                $data = [
                                    'count' => $values[0]->count - 1,
                                ];
                                $values[0]->update($data);

                                $data = [
                                    'marks' => ($product_item->marks-1),
                                ];
                                Product::where('id', $key)->update($data);

                                $data = [
                                    'name' => $product_item->name,
                                    'point' => $product_item->point,
                                    'rare' => $product_item->rare,
                                    'marks' => $product_item->id,
                                    'lost_type' => $product_item->lost_type,
                                    'category_id' => $product_item->category_id,
                                    'is_lost_product' => 1,
                                    'image' => $product_item->image,

                                    'gacha_id' => $gacha->id,
                                    'user_id' => auth()->user()->id,
                                    'gacha_record_id' => $token, 
                                    'status' => 1
                                ];
                                Product::create($data);
                            }
                        }
                    }
                }
            } else {
                $data = [
                    'user_id' => auth()->user()->id,
                    'gacha_record_id' => $token,
                    'status' => 1
                ];
                Product::where('id', $key)->update($data);
            }
        }
        $gacha->update(['count'=> $gacha->count + $number ]);
        
        return (object)[
            'result' => 0,
            'max_point' => $max_point
        ];
    }

    public function startPost(Request $request) {
        $id = $request->id;
        $number = $request->number;
        $gacha = Gacha::find($id);
        $user = auth()->user();
        
        $userLock = Cache::lock('startGacha'.$user->id, 60);
        if (!$userLock->get()) {
            return redirect()->route('main'); 
        }

        try {

            if (!$gacha || $gacha->count_card == $gacha->count) {
                return redirect()->route('main'); 
            }
            
            $totalSpin = Gacha_record::where('user_id', $user->id)->where('gacha_id', $id)->where('status', 1)->sum('type');
            $remainingSpin = $gacha->spin_limit - $totalSpin;
            if ($remainingSpin < 0) $remainingSpin = 0;
    
            $count_rest = $gacha->count_card - $gacha->count;
            if ($number > $count_rest) $number = $count_rest;
            if ($number > $remainingSpin) {
                return redirect()->back()->with('message', 'このガチャは'.$gacha->spin_limit.'回までガチャできます。 すでに回したガチャ数は'.$totalSpin.'回です。')
                ->with('title', 'ガチャ回数超過!')->with('message_id', Str::random(9))->with('type', 'dialog');
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
                        return redirect()->back()->with('message', $message)->with('title', '1日1回ガチャ制限')->with('message_id', Str::random(9))->with('type', 'dialog');
                    }
                }
            }     
            if ($number > $remainingSpin) {
                return redirect()->back()->with('message', 'このガチャは'.$gacha->spin_limit.'回までガチャできます。<br>すでに回したガチャ数は'.$totalSpin.'回です。')->with('title', 'ガチャ回数超過!')->with('message_id', Str::random(9))->with('type', 'dialog');
            }
            
            $gacha_point = $gacha->point * $number;
            $user_point = $user->point;
            if ($user_point< $gacha_point) {
                return redirect()->route('user.point');
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
                if (isset($result->max_point)) {
                    $max_point = $result->max_point;
                    $gacha_record->update(['status'=>1]);
                    
                    $dp = $number + $user->dp;
                    $point = $user->point;
                    $point = $point - $gacha->point * $number;
                    $user->update(['dp'=>$dp, 'point'=>$point]);

                    $hide_cat_bar = 1;
                    $video = getVideo($max_point);
                    return inertia('User/Video', compact('hide_cat_bar', 'video', 'token'));
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

    public function result($token) {
        $user = auth()->user();
        $products = Product::where('gacha_record_id', $token)->where('user_id', $user->id)->where('status', 1)->orderBy('is_last', 'ASC')->get();
        $products = ProductListResource::collection($products);
        $hide_cat_bar = 1;
        $hide_back_btn = 1;
        $show_result_bg = 1;
        return inertia('User/Result', compact('products', 'hide_cat_bar', 'hide_back_btn', 'show_result_bg', 'token'));
    }

    public function result_exchange(Request $request) {
        $token = $request->token;
        $checks = $request->checks;
        $user = auth()->user();
        $products = Product::where('gacha_record_id', $token)->where('user_id', $user->id)->where('status', 1)->get();
  
        $point = $user->point;
        foreach($products as $product) {
            $key = "id" . $product->id;
            if ($checks[$key]) {
                $point = $point + $product->point;
                $product->status = 2;
                $product->save();
                Product::where('id', $product->marks)->where('is_lost_product', 1)->first()?->increment('marks', 1);
            }
        }
        $user->update(['point'=>$point]);

        return redirect()->route('user.gacha.end', ['token'=>$token]);
    }

    public function gacha_end(Request $request) {
        $token = $request->token;
        $point = 0; $number_products = 0;
        if ($token) {
            $user = auth()->user();
            $products = Product::where('gacha_record_id', $token)->where('user_id', $user->id)->where('status', 2)->get();
            
            foreach($products as $product) {
                if ($product->status==2) {
                    $point = $point + $product->point;
                    $number_products = $number_products + 1;
                }
            }
            $gacha_record = Gacha_record::find($token);
            if ($gacha_record) {
                $gachas = Gacha::where('id', $gacha_record->gacha_id)->get();
                if (!count($gachas)) {
                    return redirect()->route('main');
                }
                $gachas = GachaListResource::collection($gachas);
                $hide_cat_bar = 1;
                $hide_back_btn = 1;
                return inertia('User/GachaEnd', compact('point', 'number_products', 'gachas', 'hide_cat_bar', 'hide_back_btn'));
            } else {
                return redirect()->route('main');
            }
        }        
        return redirect()->route('main');
    }

    // Gacha Code End

    public function point(Request $request) {
        $cat_id = getCategories()[0]->id;;
        if ($request->cat_id) {
            $cat_id = $request->cat_id;
        }
        $points = Point::orderBy('id','ASC')->get();
        $points = PointList::collection($points);
        $hide_cat_bar = 1;
        return inertia('User/Point/Index', compact('points', 'hide_cat_bar')); 
    }

    public function purchase_success() {
        $hide_cat_bar = 1;
        $hide_back_btn = 1;
        return inertia('User/Point/Success', compact('hide_cat_bar', 'hide_back_btn'));
    }

    public function favorite() {
        $user = auth()->user();
        $products = Favorite::where('user_id', $user->id)->orderBy('id', 'ASC')->get();
        $products = FavoriteListResource::collection($products);  
        $hide_cat_bar = 1;
        // return $products;
        $hide_back_btn = 1;
        return inertia('User/Favorite', compact('products', 'hide_cat_bar', 'hide_back_btn'));
    }

    public function favorite_add(Request $request) {
        $res = ['status'=>0];
        $id = $request->id;
        $value = $request->value;
        if ($id) {
            $user = auth()->user();
            if ($value) {
                $products = Favorite::where('user_id', $user->id)->where('product_id', $id)->get();
                if (!count($products)) {
                    Favorite::create(['user_id'=>$user->id, 'product_id'=>$id]);
                }
            } else {
                Favorite::where('user_id', $user->id)->where('product_id', $id)->delete();
            }
            $res['status'] = 1;
        }
        return redirect()->back()->with('message', '保存しました！')->with('title', 'お気に入り')->with('message_id', Str::random(9))->with('type', 'dialog');
    }

    public function address() {
        $hide_cat_bar = 1;
        $user = auth()->user();
        $profiles = Profile::where('user_id', $user->id)->get();
        return inertia('User/Address', compact('hide_cat_bar', 'profiles'));
    }

    public function address_post(Request $request) {
        $validated = $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'first_name_gana'=>'required',
            'last_name_gana'=>'required',
            'postal_code'=>'required',
            'prefecture'=>'required',
            'address'=>'required',
            'phone' => 'required|numeric|digits:11',
        ]);
        
        $user = auth()->user();

        $profiles = Profile::where('user_id', $user->id)->get();
        if (count($profiles)>0) {
            $profiles[0]->update($validated);
        } else {
            $validated['user_id'] = $user->id;
            Profile::create($validated);
        }
        return redirect()->back()->with('message', '保存しました！')->with('title', '個人情報登録')->with('message_id', Str::random(9))->with('type', 'dialog');
    }

    public function products() {
        $user = auth()->user();
        $products = Product::where('user_id', $user->id)->where('status', 1)->orderBy('id', 'ASC')->get();
        $products = ProductListResource::collection($products); 

        $user = auth()->user();
        $profiles = Profile::where('user_id', $user->id)->get();
        $profile = [];
        if (count($profiles)) {
            $profile = $profiles[0];
        }

        $hide_cat_bar = 1;
         
        return inertia('User/Product/Index', compact('products', 'hide_cat_bar', 'profile'));
    }

    public function product_point_exchange(Request $request) {
        $checks = $request->checks;
        $user = auth()->user();
        $products = Product::where('user_id', $user->id)->where('status', 1)->get();
  
        $point = $user->point;
        foreach($products as $product) {
            $key = "id" . $product->id;
            if ($checks[$key]) {
                $point = $point + $product->point;
                $product->status = 2;
                $product->save();
                Product::where('id', $product->marks)->where('is_lost_product', 1)->first()?->increment('marks', 1);
            }
        }
        $user->update(['point'=>$point]);

        return redirect()->back()->with('message', '変換しました！')->with('title', 'ポイント変換')->with('message_id', Str::random(9))->with('data', ['user' => $user])->with('type', 'dialog');
    }

    public function product_delivery_post(Request $request) {
        $user = auth()->user();
        $checks = $request->checks;
        $products = Product::where('user_id', $user->id)->where('status', 1)->get();
        $point = 0;
        foreach($products as $product) {
            $key = "id" . $product->id;
            if ($checks[$key]) $point += $product->point;
        }
        if ($point < 1000) {
            return redirect()->back()->with('message', '発送は１０００ポイント以上からお願いします。')->with('title', '発送エラー')->with('message_id', Str::random(9))->with('data', ['delivery_result' => 'fail'])->with('type', 'dialog');
        }
        foreach($products as $product) {
            $key = "id" . $product->id;
            if ($checks[$key]) {
                $product->status = 3;
                $product->save(); 
            }
        }
        return redirect()->back();
    }

    public function delivery_wait(Request $request) {
        $user = auth()->user();
        $products = Product::where('user_id', $user->id)->where('status', 3)->orderBy('id', 'ASC')->get();
        $products = ProductListResource::collection($products); 
        $hide_cat_bar = 1;
        return inertia('User/Product/Wait', compact('products', 'hide_cat_bar'));
    }


    public function delivered(Request $request) {
        $user = auth()->user();
        $products = Product::where('user_id', $user->id)->where('status', 4)->orderBy('updated_at', 'DESC')->get();
        $products = ProductListResource::collection($products); 
        $hide_cat_bar = 1;
        return inertia('User/Product/Delivered', compact('products', 'hide_cat_bar'));
    }


    public function dp_detail($id) {
        $user = auth()->user();
        $products = Product::where('id', $id)->where('status', 0)->where('is_lost_product', 2)->get();
        if (!count($products)) {
            return redirect()->route('main.dp'); 
        }
        $product = $products[0];
        $favorite = Favorite::where('user_id', $user->id)->where('product_id', $product->id)->count();

        $products = ProductListResource::collection($products); 
        $productStatusTxt = getProductStatusTxt();
        
        $profiles = Profile::where('user_id', $user->id)->get();
        $profile = [];
        if (count($profiles)) {
            $profile = $profiles[0];
        }
        
        $hide_cat_bar = 1;
        return inertia('User/Dp/Detail', compact('products', 'favorite', 'hide_cat_bar', 'productStatusTxt', 'profile'));
    }

    public function dp_detail_post(Request $request) {
        $id = $request->id;
        if (!$id) {
            return redirect()->route('main.dp');
        }
        $products = Product::where('id', $id)->where('status', 0)->where('is_lost_product', 2)->get();
        if (!count($products)) {
            return redirect()->route('main.dp');
        }

        $user = auth()->user();
        if ($user->dp<$products[0]->dp) {
            return redirect()->back()->with('message', 'DPが足りてないです！')->with('title', 'TP交換所 – 詳細')->with('message_id', Str::random(9))->with('type', 'dialog');
        }

        // $product = Product::where('id', $id)->where('status', 0)->where('is_lost_product', 2)->update(['status'=>1, 'user_id'=>$user->id]);
        $product = Product::find($id);
        $data = [
            'name' => $product->name,
            'point' => $product->point,
            'dp' => $product->dp,
            'image' => $product->image,
            'category_id' => $product->category_id,
            'rare' => $product->rare,
            'product_type' => $product->product_type,
            'status_product' => $product->status_product,
            'is_lost_product' => 2,
            'status' => 3,
            'user_id'=>$user->id
        ];
        Product::create($data);
            
        $dp = $user->dp - $product->dp;
        $user->update(['dp'=>$dp]);
        return redirect()->route('user.dp.detail.success');
    }

    public function dp_detail_success(Request $request) {
        $hide_cat_bar = 1;$hide_back_btn = 1;
        return inertia('User/Dp/Success', compact('hide_cat_bar', 'hide_back_btn'));
    }

    public function coupon() {
        $user = auth()->user();
        $hide_cat_bar = 1;
        $coupons = DB::table('coupon_records')->leftJoin('coupons', 'coupons.id', '=', 'coupon_records.coupon_id')
            ->select('coupons.title', 'coupons.point', 'coupon_records.updated_at')
            ->where('coupon_records.user_id', $user->id)
            ->orderBy('coupon_records.updated_at', 'desc')->get();
        foreach($coupons as $coupon) {
            $coupon->acquired_time = date('Y年n月j日 H:i', strtotime($coupon->updated_at));
        }
        return inertia('User/Coupon', compact('hide_cat_bar', 'coupons'));
    }

    public function coupon_post(Request $request) {
        $user = auth()->user();
        $request->validate([
            'code' => 'required'
        ]);
        $coupon = Coupon::where('code', $request->code)->first();
        if ($coupon) {
            if ($coupon->expiration <= date('Y-m-d H:i:s')) {
                return redirect()->back()->with('message', '有効期間を超えました。')->with('title', '取得エラー')->with('message_id', Str::random(9))->with('type', 'dialog');
            }
            $record = Coupon_record::where(['coupon_id' => $coupon->id, 'user_id' => $user->id])->first();
            if ($record) {
                return redirect()->back()->with('message', 'すでにこのコードを利用しました。')->with('title', '取得エラー')->with('message_id', Str::random(9))->with('type', 'dialog');
            }
            $records = Coupon_record::where(['coupon_id' => $coupon->id])->count();
            if ($records == $coupon->user_limit) {
                return redirect()->back()->with('message', '利用可能な人数を超えました。')->with('title', '取得エラー')->with('message_id', Str::random(9))->with('type', 'dialog');
            }
            Coupon_record::create([
                'coupon_id' => $coupon->id,
                'user_id' => $user->id
            ]);
            $user->update(['point' => $user->point + $coupon->point]);
            $coupon->acquired_time = date('Y年m月d日 H時i分', strtotime($coupon->updated_at));
            return redirect()->back()->with('message', '取得に成功しました。')->with('title', '取得成功')->with('message_id', Str::random(9))->with('type', 'dialog')->with('data', ['coupon' => $coupon, 'user' => $user]);
        }
        else {
            return redirect()->back()->with('message', '有効なコードを入力してください。')->with('title', '取得エラー')->with('message_id', Str::random(9))->with('type', 'dialog');
        }
    }

    public function confirm_invitation(Request $request) {
        $invitation = Invitation::find($request->id);
        $user = auth()->user();
        
        if ($invitation?->inviter == $user->id && $invitation?->status == 0) {
            $user->update([
                'point' => $user->point + 1000,
            ]);
            $invitation->update(['status' => 1]);
            return redirect()->back()->with('message', '友達招待で1,000ポイント貰いました。')->with('title', '取得成功')->with('message_id', Str::random(9))->with('type', 'dialog')->with('data', ['user' => $user]);
        }
    }
}

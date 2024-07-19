<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\DeliveryProductResource;

use Str;
use Carbon\Carbon;
use Mail;


class DeliveryController extends Controller
{
    public function admin (Request $request) {
        $products = Product::select(
            'products.id as id',
            'products.name as name',
            'products.point as point',
            'products.image as image',
            'products.updated_at as updated_at',
            'products.status as status',
            'products.rare as rare',
            'products.user_id as user_id',
            'profiles.address as address',
        )->leftJoin('profiles', function($join) { $join->on('products.user_id', '=', 'profiles.user_id'); })
        ->where('products.status', 3)
        ->orderBy('updated_at', 'ASC')->get();
        $products = DeliveryProductResource::collection($products);
        $hide_cat_bar = 1;
        return inertia('Admin/Delivery/Index', compact('hide_cat_bar', 'products')) ; 
    }

    public function getProductData(Request $request) {
        $id = $request->id;
        $product = Product::find($id);
        $res = ['status' =>1 ];
        if($product) {
            $user = $product->user;
            $profile = $product->profile;
            $res['user'] = $user;
            $res['profile'] = $profile;
        } else {
            $res = ['status' =>0 ];
        }
        return $res;
    }

    public function deliver_post(Request $request) {
        $id = $request->id;
        $product = Product::find($id);
        $product->status = 4; // into delivered status
        $product->save();
        return redirect()->back()->with('message', '発送済みにしました！')->with('title', '発送')->with('message_id', Str::random(9))->with('type', 'dialog');
    }


    public function completed (Request $request) {
        $products = Product::select(
            'products.id as id',
            'products.name as name',
            'products.point as point',
            'products.image as image',
            'products.updated_at as updated_at',
            'products.status as status',
            'products.rare as rare',
            'products.user_id as user_id',
            'profiles.address as address',
        )->leftJoin('profiles', function($join) { $join->on('products.user_id', '=', 'profiles.user_id'); })
        ->where('products.status', 4)
        ->orderBy('updated_at', 'ASC')->get();
        $products = DeliveryProductResource::collection($products);
        $hide_cat_bar = 1;
        
        return inertia('Admin/Delivery/Completed', compact('hide_cat_bar', 'products')) ; 
    }

    public function unDeliver_post(Request $request) {
        $id = $request->id;
        $product = Product::find($id);
        $product->status = 3;   // into waiting status
        $product->save();
        return redirect()->back()->with('message', '未発送にしました！')->with('title', '発送')->with('message_id', Str::random(9))->with('type', 'dialog');
    }


    public function csv_delivery(Request $request) {
        $hide_cat_bar = 1;
        return inertia('Admin/Delivery/CSV', compact('hide_cat_bar')) ; 
    }

    public function csv_delivery_post(Request $request) {
        $rules = [
            'email' => 'required|email',
        ];
        $validatored = $request->validate($rules);

        $email = $validatored['email'];
        
        $products = Product::select(
            'products.updated_at as updated_at',
            'products.user_id as user_id',
            'products.name as name',
            'products.rare as rare',
            'products.point as point',
            'products.image as image',
            
            'profiles.first_name as first_name',
            'profiles.last_name as last_name',
            'profiles.first_name_gana as first_name_gana',
            'profiles.last_name_gana as last_name_gana',
            'profiles.postal_code as postal_code',
            'profiles.prefecture as prefecture',
            'profiles.address as address',
            'profiles.phone as phone',
        )->leftJoin('profiles', function($join) { $join->on('products.user_id', '=', 'profiles.user_id'); })
        ->where('products.status', 3)
        ->orderBy('updated_at', 'ASC')->get();

        $columnNames = [
            '更新日時',
            'ユーザーID',
            '商品名',
            'レアリティ',
            '商品画像URL',
            'PT',
            '名前',
            '名前(カナ)',
            '郵便番号',
            '住所',
            '電話番号',
        ];
        
        $outputs = '';
        foreach ($columnNames as $item) {
            $outputs .= $item . ',';
        }
        $outputs = rtrim($outputs, ',') . "\n";

        
        foreach ($products as $item) {
            $update_at = $item->updated_at->format('Y年m月d日 H時i分');
            $arrInfo = [
                $update_at,
                $item->user_id,
                $item->name,
                $item->rare,
                'https://toretoreoripa.com' . getProductImageUrl($item->image),
                $item->point,
                $item->first_name . ' '. $item->last_name,
                $item->first_name_gana . ' '. $item->last_name_gana,
                
                "〒" . $item->postal_code,
                $item->prefecture .' '.$item->address,
                " ". $item->phone,
            ];
            foreach ($arrInfo as $item) {
                $outputs .= $item . ',';
            }
            $outputs = rtrim($outputs, ',') . "\n";
        }
        $txt2 = pack('C*',0xEF,0xBB,0xBF). $outputs;
        $fileName = date('Y_m_d') .'_'. uniqid() . '.csv';
        $save_path = 'delivery_csv/' . $fileName;
        file_put_contents($save_path, $txt2);

        
        $info = array(
            'name' => "とれとれガチャステーション"
        );
        Mail::send('delivery_list', $info, function ($message) use ($save_path, $email)
        {
            $message->to($email)
                ->subject('発送依頼一覧');
            $message->attach(getcwd(). "/" . $save_path);
            $message->from(env('MAIL_FROM_ADDRESS'), 'とれとれガチャステーション');
        });

        return redirect()->back()->with('message', '送信しました！')->with('title', '発送依頼一覧')->with('message_id', Str::random(9))->with('type', 'dialog');
    }

}

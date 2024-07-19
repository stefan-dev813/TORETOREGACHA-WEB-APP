<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Gacha;
use App\Models\Product;
use App\Models\Gacha_lost_product;

use App\Http\Resources\GachaListResource;
use App\Http\Resources\ProductListResource;
use App\Http\Controllers\User\UserController;

use Str;

class GachaController extends Controller
{
    public function index(Request $request) {
        $cat_id = getCategories()[0]->id;;
        if ($request->cat_id) {
            $cat_id = $request->cat_id;
        }
        $GachaObj = Gacha::where('category_id', $cat_id);
        if (auth()->user()->getType()=="staff") {
            $GachaObj->where('status', 0);
        }
        $gachas = $GachaObj->orderBy('order_level', 'DESC')->orderBy('id', 'DESC')->get();

        $gachas = GachaListResource::collection($gachas);
        return inertia('Admin/Gacha/Index', compact('gachas'));
    }

    public function store(Request $request) {
        $validatored = $request->validate([
            'point' => 'required',
            'count_card' => 'required|numeric',
            'spin_limit' => 'required|numeric',
            'lost_product_type' => '',
            'thumbnail' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:8192',
            'category_id' => 'required',
        ]);

        $image = saveImage('images/gacha', $request->file('image'), false);
        $thumbnail = saveImage('images/gacha/thumbnail', $request->file('thumbnail'), false);
        $data = [
            'point' => $request->point,
            'count_card' => $request->count_card,
            'lost_product_type' => $request->lost_product_type,
            'thumbnail' => $thumbnail,
            'image' => $image,
            'category_id' => $request->category_id,
            'spin_limit' => $request->spin_limit,
        ];
        $gacha = Gacha::create($data);
        return redirect(combineRoute(route('admin.gacha.edit', $gacha->id), $request->category_id) ); 
    }

    public function create() {
        return inertia('Admin/Gacha/Create');
    }

    public function edit($id) {
        $gacha = Gacha::find($id);
        if (auth()->user()->getType()=="staff" && $gacha->status!=0) {
            $text = "権限がありません！";
            return inertia('NoProduct', compact('text'));
        }

        $gacha->image = getGachaImageUrl($gacha->image);
        $gacha->thumbnail = getGachaThumbnailUrl($gacha->thumbnail);
        $product_last = $gacha->getProductLast();

        foreach($product_last as $last) {
            $last->status = getProductStatus($last->status);
        }

        $products = $gacha->getProducts();
        $products = ProductListResource::collection($products);
        $productsLostSetting = $gacha->productsLostSetting;
        return inertia('Admin/Gacha/Edit', compact('gacha', 'product_last', 'products', 'productsLostSetting'));
    }

    public function update(Request $request) {
        $rules = [
            'point' => 'required',
            'count_card' => 'required|numeric',
            'count' => 'required|numeric',
            'lost_product_type' => '',
            'thumbnail' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:8192',
            'category_id' => 'required',
            'spin_limit' => 'required|numeric',
        ];
        
        if (!$request->thumbnail) {
            $rules['thumbnail'] = '';
        } 
        if (!$request->image) {
            $rules['image'] = ''; 
        }
        $validatored = $request->validate($rules);

        $data = [
            'point' => $request->point,
            'count_card' => $request->count_card,
            'count' => $request->count,
            'lost_product_type' => $request->lost_product_type,
            'category_id' => $request->category_id,
            'spin_limit' => $request->spin_limit,
        ];

        if ($request->thumbnail) {
            $thumbnail = saveImage('images/gacha/thumbnail', $request->file('thumbnail'), false);
            $data['thumbnail'] = $thumbnail;
        }
        if ($request->image) {
            $image = saveImage('images/gacha', $request->file('image'), false);
            $data['image'] = $image;
        }
        $gacha = Gacha::find($request->id);

        if (auth()->user()->getType()=="staff" && $gacha->status!=0) {
            $text = "権限がありません！";
            return inertia('NoProduct', compact('text'));
        }


        $gacha->update($data);

        // lost products
        Gacha_lost_product::where('gacha_id', $gacha->id)->delete();
        if($request->lostProducts) {
            foreach($request->lostProducts as $item) {
                if ($item['key']) {
                    $point = 0;
                    if ($item['point']) { $point = $item['point']; }
                    $count = 0;
                    if ($item['count']) { $count = $item['count']; };
                    $data = ['gacha_id'=>$gacha->id, 'point'=>$point, 'count'=>$count];
                    Gacha_lost_product::create($data);
                }
            }
        }
        // lost products end

        return redirect()->back()->with('message', '保存しました！')->with('title', 'ガチャ 編集')->with('message_id', Str::random(9))->with('type', 'dialog');;
    }

    public function sorting(Request $request) {
        $cat_id = getCategories()[0]->id;;
        if ($request->cat_id) {
            $cat_id = $request->cat_id;
        }
        $GachaObj = Gacha::where('category_id', $cat_id);
        if (auth()->user()->getType()=="staff") {
            $GachaObj->where('status', 0);
        }
        $gachas = $GachaObj->orderBy('order_level', 'DESC')->orderBy('id', 'DESC')->get();

        $gachas = GachaListResource::collection($gachas);
        return inertia('Admin/Gacha/Sorting', compact('gachas'));
    }

    public function sorting_store(Request $request) {
        $data = $request->all();
        $order_level = 1;
        $data['gachas'] = array_reverse($data['gachas']);
        foreach($data['gachas'] as $key=>$item) {
            Gacha::where('id', $item['id'])->update([
                'order_level'=>$order_level
            ]);
            $order_level += 1;
        }
        return redirect()->back()->with('message', '保存しました！')->with('title', 'ガチャ編集')->with('message_id', Str::random(9))->with('type', 'dialog');
    }

    public function product_last_create(Request $request) {
        $rules = [
            'last_name' => 'required',
            'last_point' => 'required|numeric',
            'last_rare' => 'required',
            'last_image' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'gacha_id' => 'required',
            'is_last' => 'required|numeric|gt:0'
        ];
        if ($request->is_update==1) {
            if(!$request->last_image){
                $rules['last_image'] = '';
            }
        }

        $validatored = $request->validate($rules); 
        
            
        $data = [
            'name' => $request->last_name,
            'point' => $request->last_point,
            'rare' => $request->last_rare,
            'gacha_id' => $request->gacha_id,
            'is_last' => $request->is_last
        ];
        if($request->last_image){
            $image = saveImage('images/products', $request->file('last_image'), false);
            $data['image'] = $image;
        }

        if ($request->is_update==1) { 
            $product = Product::where('id', $request->last_id)->where('status', 0)->first();
            if ($product) {
                $result = $product->update($data);
            }
            else {
                return redirect()->back()->with('message', '失敗しました！')->with('title', 'ガチャ 編集')->with('message_id', Str::random(9))->with('type', 'dialog');        
            }
        } else {
            Product::create($data);
        }
        return redirect()->back()->with('message', '保存しました！')->with('title', 'ガチャ 編集')->with('message_id', Str::random(9))->with('type', 'dialog');
    }

    public function product_last_destroy($id) {
        Product::where("id", $id)->delete();
        return redirect()->back()->with('message', '削除しました！')->with('title', '編集')->with('message_id', Str::random(9))->with('type', 'dialog');
    }

    public function product_create(Request $request) {
        $rules = [
            'last_name' => 'required',
            'last_point' => 'required|numeric',
            'last_rare' => 'required',
            'last_image' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'gacha_id' => 'required', 
        ];
        if ($request->is_update==1) {
            if(!$request->last_image){
                $rules['last_image'] = '';
            }
        }

        $validatored = $request->validate($rules);
        
            
        $data = [
            'name' => $request->last_name,
            'point' => $request->last_point,
            'rare' => $request->last_rare,
            'gacha_id' => $request->gacha_id,
            'marks' => $request->last_marks,
            'is_last' => 0,
        ];
        if($request->last_image){
            $image = saveImage('images/products', $request->file('last_image'), false);
            $data['image'] = $image;
        }

        
        if ($request->is_update==1) {
            $product = Product::where('id', $request->last_id)->where('status', 0);
            $result = $product->update($data);
            if (!$result) {
                return redirect()->back()->with('message', '失敗しました！')->with('title', 'ガチャ 編集')->with('message_id', Str::random(9))->with('type', 'dialog');        
            }
        } else {
            Product::create($data);
        }
        return redirect()->back()->with('message', '保存しました！')->with('title', 'ガチャ 編集')->with('message_id', Str::random(9))->with('type', 'dialog');
    }

    public function to_public (Request $request) {
        $gacha_id = $request->gacha_id;
        if (!$gacha_id) {
            return redirect()->back();
        }

        $gacha = Gacha::find($gacha_id);
        if ( !$gacha ) {
            return redirect()->back();
        }
       

        $gacha->status = $request->to_status;
        $gacha->save();

        $string = "非公開にしました！";
        if ($request->to_status) {
            $string = "公開にしました！";
        }
        return redirect()->back()->with('message', $string)->with('title', 'ガチャ 編集')->with('message_id', Str::random(9))->with('type', 'dialog');
    }

    public function gacha_limit (Request $request) {
        $gacha_id = $request->gacha_id;
        if (!$gacha_id) {
            return redirect()->back();
        }

        $gacha = Gacha::find($gacha_id);
        if ( !$gacha ) {
            return redirect()->back();
        }
        (new UserController)->check_current_status($gacha);

        $gacha->gacha_limit_on_setting = $request->to_status;
        $gacha->save();

        $string = "1日1回制限設定をキャンセルしました。";
        if ($request->to_status) {
            $string = "1日1回制限設定を完了しました。";
        }
        return redirect()->back()->with('message', $string)->with('message_id', Str::random(9))->with('type', 'dialog');
    }
    
    public function destroy($id) {
        Product::where('gacha_id', $id)->where('status', 0)->where('is_lost_product', 0)->delete();
        Gacha::where('id', $id)->delete();
        // Favorite::where('product_id', $id)->delete();
        // Product::where("id", $id)->where('status', 0)->where('is_lost_product', 2)->delete();
        return redirect()->back()->with('message', '削除しました！')->with('title', 'ガチャ')->with('message_id', Str::random(9))->with('type', 'dialog');
    }
}

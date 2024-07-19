<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Gacha_lost_product;
use App\Http\Resources\ProductListResource;
use Str;

class LostProductController extends Controller
{
    public function index(Request $request) {
        $cat_id = getCategories()[0]->id;;
        if ($request->cat_id) {
            $cat_id = $request->cat_id;
        }
        $products_status = [];
        if (isset($request->id)) {
            $products = [Product::find($request->id)];
        }
        else {
            $products = Product::where('is_lost_product', 1)->where('status', 0)->where('category_id', $cat_id)->orderBy('point', 'DESC')->orderBy('id', 'ASC')->get();

            $points = Gacha_lost_product::where('count', '>', 0)->orderBy('point')->select('point')->get();
            $point = 0;
            foreach($points as $point1) {
                if ($point1->point!=$point) {
                    $point = $point1->point;
                    $gacha_products_count = Gacha_lost_product::leftJoin('gachas', function($join) { $join->on('gachas.id', '=', 'gacha_lost_products.gacha_id'); })
                    ->where('gacha_lost_products.point', $point)
                    ->where('gachas.category_id', $cat_id)
                    ->sum('gacha_lost_products.count');
                    $products_lost_count = Product::where('point', $point)->where('status', 0)->where('is_lost_product', 1)->where('category_id', $cat_id)->where('marks', '>', 0)->sum('marks');
                    $arr['point'] = $point;
                    $arr['gacha_products_count'] = $gacha_products_count;
                    $arr['products_lost_count'] = $products_lost_count;
                    array_push( $products_status , $arr);
                }  
            }
        }

        $products = ProductListResource::collection($products);
        // return $products_status;
      

        return inertia('Admin/LostProduct/Index', compact('products', 'products_status'));
    }
    
    public function create(Request $request) {
        $rules = [
            'last_name' => 'required',
            'last_point' => 'required|numeric',
            'last_rare' => 'required',
            'last_marks' => 'required|numeric',
            'last_image' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
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
            'marks' => $request->last_marks,
            'lost_type' => $request->last_lost_type,
            'category_id' => $request->category_id,
            'is_lost_product' => 1,
        ];
        
        if($request->last_image){
            $image = saveImage('images/products', $request->file('last_image'), false);
            $data['image'] = $image;
        }
        
        if ($request->is_update==1) {
            $product = Product::find($request->last_id);
            $product->update($data);
        } else {
            Product::create($data);
        }

        return redirect()->back()->with('message', '保存しました！')->with('title', 'カード 編集')->with('message_id', Str::random(9))->with('type', 'dialog');;
    }
}

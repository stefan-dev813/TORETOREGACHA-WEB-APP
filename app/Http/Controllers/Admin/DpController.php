<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Favorite;
use App\Http\Resources\ProductListResource;
use Str;

class DpController extends Controller
{
    public function index(Request $request) {
        $cat_id = getCategories()[0]->id;;
        if ($request->cat_id) {
            $cat_id = $request->cat_id;
        }
        $products = Product::where('is_lost_product', 2)->where('category_id', $cat_id)->where('status', 0)->orderBy('id', 'ASC')->get();
        $products = ProductListResource::collection($products);
        // return $products; 
        return inertia("Admin/Dp/Index", compact('products'));
    }

    public function create(Request $request) {
        $productStatusTxt = getProductStatusTxt();
        return inertia("Admin/Dp/Create", compact('productStatusTxt'));
    }


    public function store(Request $request) {
        $rules = [
            'name' => 'required',
            'dp' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'status_product' => 'required',
        ];
        $validatored = $request->validate($rules);

        $image = saveImage('images/products', $request->file('image'), false);
        
        $data = [
            'name' => $request->name,
            'dp' => $request->dp,
            'image' => $image,
            'category_id' => $request->category_id,
            'rare' => $request->rare,
            'product_type' => $request->product_type,
            'status_product' => $request->status_product,
            'is_lost_product' => 2,
        ];
        Product::create($data);
        return redirect(combineRoute(route('admin.dp'), $request->category_id))->with('message', '追加しました！')->with('title', '交換アイテム')->with('message_id', Str::random(9))->with('type', 'dialog');
    }

    public function edit($id) {
        $products = Product::where('is_lost_product', 2)->where('id', $id)->where('status', 0)->get();
        if (count($products)) {
            $product = $products[0];
            $product->image = getProductImageUrl($product->image);
            $productStatusTxt = getProductStatusTxt();
            return inertia('Admin/Dp/Edit', compact('product', 'productStatusTxt'));
        } 
        return redirect()->route('admin.dp');
    }

    public function update(Request $request) {
        $rules = [
            'id' => 'required',
            'name' => 'required',
            'dp' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'status_product' => 'required',
        ];
        if (!$request->image) {
            $rules['image'] = '';
        }
        $validatored = $request->validate($rules);

        
        
        $data = [
            'name' => $request->name,
            'dp' => $request->dp,
            'category_id' => $request->category_id,
            'rare' => $request->rare,
            'product_type' => $request->product_type,
            'status_product' => $request->status_product,
            'is_lost_product' => 2,
        ];
        if ($request->image) {
            $image = saveImage('images/products', $request->file('image'), false);
            $data['image'] = $image;
        }

        $product = Product::find($request->id);
        $product->update($data);

        return redirect(combineRoute(route('admin.dp'), $request->category_id))->with('message', '保存しました！')->with('title', '交換アイテム')->with('message_id', Str::random(9))->with('type', 'dialog');
    }

    public function destroy($id = null) {
        Favorite::where('product_id', $id)->delete();
        Product::where("id", $id)->where('status', 0)->where('is_lost_product', 2)->delete();
        return redirect()->back()->with('message', '削除しました！')->with('title', '交換アイテム')->with('message_id', Str::random(9))->with('type', 'dialog');
    }
}

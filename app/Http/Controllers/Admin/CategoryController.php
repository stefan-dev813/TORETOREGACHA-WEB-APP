<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Http\Resources\CategoryListResource;
use App\Http\Requests\CategoryCreateRequest;
use Str;

class CategoryController extends Controller
{
    public function category() {
        $hide_cat_bar = 1;
        $categories = Category::orderBy('order', 'asc')->get();
        $categories = CategoryListResource::collection($categories);
        return inertia('Admin/Category/Index', compact('categories', 'hide_cat_bar'));
    }

    public function category_create() {
        return inertia('Admin/Category/Create');
    }

    public function category_store(CategoryCreateRequest $request) {
        Category::create($request->validated());
        return redirect()->back()->with('message', '追加しました！')->with('title', 'カテゴリー編集')->with('message_id', Str::random(9))->with('type', 'dialog');
    }

    public function category_edit($category_id) {
        $category = Category::where('id', $category_id)->get();
        return inertia('Admin/Category/Edit', compact('category'));
    }

    public function category_update($category_id, CategoryUpdateRequest $request) {
        $category = Category::find($category_id);
        $category->update($request->validated());
        return redirect()->back()->with('message', '保存しました！')->with('title', 'カテゴリー編集')->with('message_id', Str::random(9))->with('type', 'dialog');
    }

    public function category_destroy($category_id = null) {
        Category::where("id", $category_id)->delete();
        return redirect()->back()->with('message', '削除しました！')->with('title', 'カテゴリー編集')->with('message_id', Str::random(9))->with('type', 'dialog');
    }

    public function sorting_store(Request $request) {
        $data = $request->categories;
        $order_level = 1;
        foreach($data["data"] as $item) {
            Category::where('id', $item['id'])->update([
                'order'=>$order_level,
                'title'=>$item['title']
            ]);
            $order_level += 1;
        }
        return redirect()->back()->with('message', '保存しました！')->with('title', 'カテゴリー編集')->with('message_id', Str::random(9))->with('type', 'dialog');
    }
}

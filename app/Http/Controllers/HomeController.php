<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Models\Gacha;
use App\Http\Resources\GachaListResource;
use App\Http\Resources\ProductListResource;

use App\Models\Product;
use Str;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    public function index(Request $request) {
        $cat_id = getCategories()[0]->id;;
        if ($request->cat_id) {
            $cat_id = $request->cat_id;
        }
        $gachas = Gacha::where('category_id', $cat_id)->where('status', 1)->orderBy('order_level', 'DESC')->orderBy('id', 'DESC')->get();
        $gachas = GachaListResource::collection($gachas);
        $hide_back_btn = 1;
        $branch_is_gacha = 1;
        $show_notification = 0;
        $show_home_bg = 1;
        $show_banner = DB::table('banner')->first();

        return inertia('Home', compact('gachas', 'hide_back_btn', 'branch_is_gacha', 'show_notification', 'show_home_bg', 'show_banner')); 
    }

    public function dp(Request $request) {
        $cat_id = getCategories()[0]->id;;
        if ($request->cat_id) {
            $cat_id = $request->cat_id;
        }
        $products = Product::where('is_lost_product', 2)->where('category_id', $cat_id)->where('status',0)->orderBy('id', 'ASC')->get();
        $products = ProductListResource::collection($products);

        $hide_back_btn = 1; 
        $branch_is_gacha = 2;
        return inertia('HomeDp', compact('products', 'hide_back_btn', 'branch_is_gacha')); 
    }

    public function dashboard() {
        if(Auth::check()) {
            if (auth()->user()->getType() == 'admin') {
                // return redirect()->route('admin');
                return redirect()->route('admin.gacha');
            }else{
                return redirect()->route('main');
            }
        } 
        return redirect()->route('main');
    }

    public function how_to_use() {
        $hide_cat_bar = 1;
        return inertia('Normal/HowToUse', compact('hide_cat_bar'));
    }

    public function privacy_police() {
        $hide_cat_bar = 1;
        return inertia('Normal/PrivacyPolice', compact('hide_cat_bar'));
    }

    public function terms_conditions() {
        $hide_cat_bar = 1;
        return inertia('Normal/TermsConditions', compact('hide_cat_bar'));
    }

    public function contact_us() {
        $hide_cat_bar = 1;
        return inertia('Normal/ContactUs', compact('hide_cat_bar'));
    }

    public function notation_commercial() {
        $hide_cat_bar = 1;
        return inertia('Normal/NotationCommercial', compact('hide_cat_bar'));
    }

    public function status_estimate() {
        $hide_cat_bar = 1;
        return inertia('Normal/StatusEstimate', compact('hide_cat_bar'));
    }

    public function maintenance() {
        $maintenance = getOption('maintenance');
        if ($maintenance!="1") {
            return redirect()->route('main');
        }
        return inertia('maintenance');
    }
}

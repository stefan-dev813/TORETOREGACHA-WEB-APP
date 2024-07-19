<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Coupon_record;

use Illuminate\Http\Request;
use Str;
use DB;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = DB::table('coupons')
        ->leftJoin(DB::raw('(select coupon_id, count(id) as count from coupon_records GROUP BY coupon_id) A'),
            function($join) {
                $join->on('coupons.id', '=', 'A.coupon_id');
            },
            DB::raw('A')
        )
        ->select('coupons.*', 'A.count')
        ->get();
    

        // $coupons = Coupon::get();
        $hide_cat_bar = 1;
        return inertia('Admin/Coupon/Index', compact('coupons', 'hide_cat_bar'));
    }

    public function create_card_number($length = 10)
    {
        $string = 'K-'.Str::random($length);
        return $string;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $coupon = [
            'id' => '',
            'title' => '',
            'code' => $this->create_card_number(),
            'point' => '',
            'expiration' => date('Y-m-d H:i'),
            'user_limit' => 1
        ];
        $hide_cat_bar = 1;
        return inertia('Admin/Coupon/New', compact('hide_cat_bar', 'coupon'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'title' => 'required',
            'point' => 'required|integer|gt:0',
            'code' => 'required',
            'expiration' => 'required',
            'user_limit' => 'required',
        ]);

        $title = 'ポイント配布編集';
        $coupon = Coupon::updateOrCreate(
            [
                'id' => $id
            ],
            [
                'title' => $request->title,
                'point' => $request->point,
                'code' => $request->code,
                'expiration' => date('Y-m-d H:i', strtotime($request->expiration)),
                'user_limit' => $request->user_limit
            ]
        );
        if ($id == '') {
            $coupon = [
                'title' => '',
                'code' => $this->create_card_number(),
                'point' => '',
                'expiration' => date('Y-m-d H:i'),
                'user_limit' => 1
            ];
            $title = 'ポイント配布追加';
        }
        return redirect()->back()->with('message', '保存しました！')->with('title', $title)->with('message_id', Str::random(9))->with('type', 'dialog')->with('data', $coupon);
    }

    public function edit($id)
    {
        $coupon = Coupon::where(['id' => $id])->first();
        $editing = true;
        return inertia('Admin/Coupon/New', compact('coupon', 'editing'));
    }

    public function delete($id)
    {
        $coupon = Coupon::where('id', $id)->delete();
        Coupon_record::where('coupon_id', $id)->delete();
        return redirect()->back()->with('message', '削除しました！')->with('title', 'ポイント配布')->with('message_id', Str::random(9))->with('type', 'dialog');
    }
}

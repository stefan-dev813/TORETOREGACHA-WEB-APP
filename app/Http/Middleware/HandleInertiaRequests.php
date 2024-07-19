<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed[]
     */
    public function share(Request $request)
    {
        $cats = getCategories();
        $cat_id = $cats[0]->id;
        if ($request->cat_id) {
            $cat_id = $request->cat_id;
        }
        $cat_route_appendix = "?cat_id=". $cat_id;

        return array_merge(parent::share($request), [
            'flash' => [
                'message' => session('message'),
                'title' => session('title'),
                'type' => session('type'),
                'data' => session('data'),
                'message_id' => session('message_id')
            ],
            'category_share' => [
                'cat_id' => $cat_id,
                'categories' => getCategories(), 
                'cat_route_appendix' => $cat_route_appendix,
            ],
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ]);
    }
}

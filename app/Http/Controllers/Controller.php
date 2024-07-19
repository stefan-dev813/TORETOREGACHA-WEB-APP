<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct() {
        
        $maintenance = getOption('maintenance');
        if ($maintenance=='1') {
            $current_route = Route::currentRouteName();
            if (str_contains($current_route, 'login') || str_contains($current_route, 'logout') || str_contains($current_route, 'admin') || str_contains($current_route, 'dashboard') || str_contains($current_route, 'test') || str_contains($current_route, 'tds2_ret_url')) { 
                
            } else {
                if ($current_route!="maintenance") {
                    $url = route('maintenance');
                    header("Location: $url");
                    die();
                    exit();
                }
            }
        }
    }
}

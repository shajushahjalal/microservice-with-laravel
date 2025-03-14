<?php

use App\Services\ConsulService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'ok'], 200);
});

Route::get("user_list", function(Request $request){
    $service = (new ConsulService())->getService("AuthService");
    if (!$service) {
        return response()->json(['error' => 'AuthService not available'], 503);
    }
    $method = strtolower($request->method());
    $response = Http::{$method}("{$service}/api/user/list");
    
    return response($response->json());
});
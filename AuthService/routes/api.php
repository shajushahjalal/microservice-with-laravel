<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'ok'], 200);
});

Route::get("user/list", function(){
    return response([
        "name" => "User",
        "email" => "user@yopmail.com"
    ],200);
});
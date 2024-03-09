<?php

Route::middleware('api')->group(function () {
    Route::post('post-content', function () {
        return 'Hello, World!';
    });
});

Route::middleware('web')->group(function () {
    Route::get('post-content', function () {
        return 'Hello, World!';
    });
});

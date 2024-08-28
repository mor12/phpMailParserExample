<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailParserController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/parse-email', [MailParserController::class, 'parseEmail']);

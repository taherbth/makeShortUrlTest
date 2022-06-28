<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::post('makeShortlink', 'ShortLinkApiController@makeShortLink');


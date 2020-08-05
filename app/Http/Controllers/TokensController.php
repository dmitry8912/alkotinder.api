<?php

namespace App\Http\Controllers;

use App\Domain\Implementations\TokenTmpStorage;
use Illuminate\Http\Request;

class TokensController extends Controller
{
    public function get(string $state){
        return response([
            'token' => TokenTmpStorage::getToken($state)
        ],200);
    }
}

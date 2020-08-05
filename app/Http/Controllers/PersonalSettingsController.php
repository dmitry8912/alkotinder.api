<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domain\Implementations\UsersRepository;
use Illuminate\Support\Facades\Storage;

class PersonalSettingsController extends Controller
{
    public function getSettings(){
        return UsersRepository::current()->with('settings')->first();
    }

    public function setSettings(Request $request){
        $user = UsersRepository::current();
        if($request->has('nickname')){
            $user->name = $request->nickname;
        }
        $settings = UsersRepository::personalSettings();
        $settings->weight = $request->weight;
        $settings->sex = $request->sex;
        $settings->dailyTarget = $request->dailyTarget;
        $user->save();
        $settings->save();
        return response([
            'jRPC_Code' => 'OK'
        ],200);
    }

    public function getAvatar() {
        return response([
            'avatar' => UsersRepository::current()->avatar
        ],200);
    }

    public function setAvatar(Request $request) {
        $user = UsersRepository::current();
        if($user->avatar !== null){
            Storage::delete('public/images/'.$user->avatar);
        }
        $path = $request->file('avatar-photo')->store('public/images');
        $user->avatar = basename($path);
        $user->save();
        return response(['path' => basename($path)],200);
    }
}

<?php


namespace App\Domain\Implementations;

use App\Domain\Interfaces;
use App\User;
use App\Models\PersonalSettings;
use Illuminate\Support\Facades\Auth;
class UsersRepository implements Interfaces\IUsersRepository
{
    public static function getById(int $id){
        return User::find($id);
    }

    public static function getByExtId(string $id){
        return User::where([
            ['external_id',$id]
        ])->first();
    }

    public static function create(string $email, string $name, string $provider, string $externalId){
        $users = User::where([
            ['email',$email]
        ])->orWhere([
            ['external_id',$externalId]
        ]);
        if($users->count() > 0){
            return $users->first();
        }
        $user = new User();
        $user->email = $email;
        $user->provider = $provider;
        $user->external_id = $externalId;
        $user->name = $name;
        $user->password = \Hash::make(
            base64_encode(
                $externalId.sha1(
                    uniqid()
                )
            )
        );
        $user->save();
        return $user;
    }

    public static function current()
    {
        return Auth::user();
    }

    public static function personalSettings()
    {
        $settings = null;
        if(PersonalSettings::where('user_id',self::current()->id)->count() == 0){
            $settings = new PersonalSettings();
            $settings->dailyTarget = 0;
            $settings->user_id = self::current()->id;
        } else {
            $settings = PersonalSettings::where('user_id',self::current()->id)->first();
        }
        return $settings;
    }
}

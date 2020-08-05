<?php


namespace App\Domain\Implementations;

use App\Models\ExternalAuthTokens;
class TokenTmpStorage
{
    public static function setState(string $deviceUid, string $state){
        if(ExternalAuthTokens::where('device_uid',$deviceUid)->count() > 0){
            ExternalAuthTokens::where('device_uid',$deviceUid)->delete();
        }
        $token = new ExternalAuthTokens();
        $token->device_uid = $deviceUid;
        $token->state = sha1($state);
        $token->save();
    }

    public static function setToken(string $state, string $plainToken){
        $state = self::getState($state);
        $state->plainToken = $plainToken;
        $state->save();
    }

    public static function getToken(string $state){
        $state = self::getState($state);
        $token = $state->plainToken;
        ExternalAuthTokens::destroy($state->id);
        return $token;
    }

    public static function getState(string $state){
        $query = ExternalAuthTokens::where('state',sha1($state));
        if($query->count() == 0){
            throw new \Exception('NO_STATE_EXISTS');
        }
        return $query->first();
    }
}

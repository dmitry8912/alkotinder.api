<?php

namespace App\Http\Controllers;

use App\Domain\Implementations\UsersRepository;
use App\Models\UsersProgressDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function getToday() {
        return UsersProgressDetails::where([
            ['user_id', '=',UsersRepository::current()->id],
            ['created_at', '>=', Carbon::today()->toDateString()]
        ])->get();
    }

    public function add(Request $request) {
        $userProgress = new UsersProgressDetails();
        $userProgress->client_uid = $request->uid;
        $userProgress->user_id = UsersRepository::current()->id;
        $userProgress->drink_id = $request->drinkName['id'];
        $userProgress->actual_drink_name = $request->drinkName['label'];
        $userProgress->actual_strong = $request->drinkName['strong'];
        $userProgress->actual_total = $request->total;
        $userProgress->save();
        return response([],200);
    }

    public function remove(string $uid) {
        UsersProgressDetails::where('client_uid',$uid)->delete();
        return response([],200);
    }
}

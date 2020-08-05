<?php

namespace App\Http\Controllers;

use App\Domain\Implementations\UsersRepository;
use App\Models\Drinks;
use Illuminate\Http\Request;
use App\Events\DrinkAdded;

class DrinkController extends Controller
{
    public function all() {
        return Drinks::all()->toArray();
    }

    public function add(Request $request){
        $drinkNameLower = strtolower($request->label);
        $drinks = Drinks::where('label',"%${drinkNameLower}%")->count();
        if($drinks > 0) {
            return response([
                'error' => 'ALREADY_EXISTS'
            ], 400);
        }
        $drink = new Drinks();
        $drink->label = $request->label;
        $drink->strong = intval($request->strong);
        $drink->user_id = UsersRepository::current()->id;
        $drink->save();
        event(new DrinkAdded($drink->toArray()));
        return response($drink, 200);
    }
}

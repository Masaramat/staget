<?php

namespace App\Http\Controllers;

use App\Models\LocalGovernment;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function GetLga(Request $request){

        $data['lgas'] = LocalGovernment::where("state_id",$request->state_id)
                    ->get();
    
        return response()->json($data);

    }

    // public function GetState(Request $request){
    //     $data['states'] = State::all();
    //     return response()->json($data);
    // }
}

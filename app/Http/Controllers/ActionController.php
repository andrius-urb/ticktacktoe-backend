<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActionController extends Controller
{
    public function get()
    {
        $actions = Action::get();
        return response()->json($actions, 200);
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'player'    =>  'required|boolean',
            'row'       =>  'required|integer',
            'col'       =>  'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json('Validation failed', 500);
        }

        $action = new Action;
        $action->player = $request->player;
        $action->row    = $request->row;
        $action->column = $request->col;
        $action->save();
        return response()->json($action, 200);
    }

    public function delete(Request $request)
    {
        Action::truncate();
        return response()->json([], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActionController extends Controller
{
    public function get()
    {
        $actions = Action::get(['id', 'player', 'row', 'column']);
        return response()->json($actions, 200);
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'player'    =>  'required|string',
            'row'       =>  'required|integer',
            'col'       =>  'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'     =>  'Validation failed',
                'errorType' =>  'VALIDATION_FAIL'
            ], 500);
        }

        /**
         * Checking last move and if last move done by same player, return error.
         */
        $lastAction = Action::orderBy('id', 'desc')->first();
        if ($lastAction !== null) {
            if ($lastAction->player == $request->player) {
                return response()->json([
                    'error'     =>  'Two player moves in a row.',
                    'errorType' =>  'TWO_MOVES_IN_A_ROW'
                ], 500);
            }
        }

        if ($this->checkWin(true)) {
            return response()->json([
                'error'     =>  'Game ended.',
                'errorType' =>  'GAME_ENDED'
            ], 500);
        }

        /**
         * Checking if field is used
         */
        $isUsed = Action::where('row', $request->row)->where('column', $request->col)->first();
        if ($isUsed !== null) {
            return response()->json([
                'error'     =>  'Field is used.',
                'errorType' =>  'FIELD_USED'
            ], 500);
        }



        $action = new Action;
        $action->player = $request->player;
        $action->row    = $request->row;
        $action->column = $request->col;
        $action->save();
        return $this->checkWin();
    }

    /**
     * Function for win check.
     * If $justReturn = true, then just returns true if there is winner or cross-win, and false if there is no winner.
     */
    public function checkWin($justReturn = false)
    {
        $actions = Action::get();
        $gameBoard = array(
            [' ', ' ', ' '],
            [' ', ' ', ' '],
            [' ', ' ', ' ']
        );

        foreach ($actions as $action) {
            $gameBoard[$action->row][$action->column] = $action->player;
        }


        $isX            =   'X';  // Player X in gameBoard array is assigned to "X"
        $isO            =   'O';   // Player O in gameBoard array is assigned to "O"
        $checkPlayer    =   $isX;   // Tells us, which player we are checking for win
        $result         =   true;   // Win result, after each loop must be "true"

        // Checking diagonally 0,0  1,1  2,2  game board fields
        $res = array();
        for ($a = 0; $a < 2; $a++) {
            // This loop first time checking player X, and second time player O
            $result = true; // Resetting $result to true
            if ($a == 1) {
                // loop goes 2nd time, so we are setting $checkPlayer to $isO(true), to check for player O
                $checkPlayer = $isO;
            }
            for ($b = 0; $b < 3; $b++) {
                /**
                 * Because variable $result = true, on win check must be:
                 * $result = true && true, to get result that there is any winner(because true && true is TRUE)
                 * if there is other condition, for example:
                 * $result = true && false, there is no winner(because true && false is FALSE)
                 */
                $result = $result && $gameBoard[$b][$b] === $checkPlayer;
            }

            if ($result) {
                /**
                 * $result = true, that means that there is a winner.
                 * Winner would be that one, which was checked last time by loop and defined by variable $checkPlayer.
                 * Returning winner.
                 */
                if ($justReturn)
                    return true;

                return response()->json([
                    'winner'    =>  $checkPlayer,
                ], 200);
            }
        }


        // Checking diagonally 2,0  1,1  0,2  game board fields
        $checkPlayer    =   $isX;   // Resetting player that we are checking to player X.
        for ($a = 0; $a < 2; $a++) {
            // This loop first time checking player X, and second time player O
            $result = true; // Resetting $result to true
            if ($a == 1) {
                // loop goes 2nd time, so we are setting $checkPlayer to $isO(true), to check for player O
                $checkPlayer = $isO;
            }
            for ($b = 0; $b < 3; $b++) {
                /**
                 * Because variable $result = true, on win check must be:
                 * $result = true && true, to get result that there is any winner(because true && true is TRUE)
                 * if there is other condition, for example:
                 * $result = true && false, there is no winner(because true && false is FALSE)
                 */
                $result = $result && $gameBoard[2 - $b][$b] === $checkPlayer;
            }

            if ($result) {
                /**
                 * $result = true, that means that there is a winner.
                 * Winner would be that one, which was checked last time by loop and defined by variable $checkPlayer.
                 * Returning winner.
                 */
                if ($justReturn)
                    return true;

                return response()->json([
                    'winner'    =>  $checkPlayer,
                ], 200);
            }
        }


        $checkPlayer    =   $isX;   // Resetting player that we are checking to player X.
        for ($a = 0; $a < 2; $a++) {
            if ($a == 1) $checkPlayer = $isO;
            for ($b = 0; $b < 3; $b++) {
                $result = true;
                for ($c = 0; $c < 3; $c++) {
                    // Checking for rows
                    $result = $result && $gameBoard[$b][$c] === $checkPlayer;
                }
                if ($result) {
                    if ($justReturn)
                        return true;

                    return response()->json([
                        'winner'    =>  $checkPlayer,
                    ], 200);
                }
                $result = true;
                for ($c = 0; $c < 3; $c++) {
                    // Checking for columns
                    $result = $result && $gameBoard[$c][$b] === $checkPlayer;
                }
                if ($result) {
                    if ($justReturn)
                        return true;

                    return response()->json([
                        'winner'    =>  $checkPlayer,
                    ], 200);
                }
            }
        }


        /**
         * None conditinion was succes, that means there is no winner yet, so now checking if there is any free game board fields.
         */
        $foundEmpty = 0;
        foreach ($gameBoard as $row => $col) {
            foreach ($col as $player) {
                if ($player === ' ')
                    $foundEmpty++;
            }
        }
        if ($foundEmpty == 0) {
            if ($justReturn)
                return true;

            return response()->json([
                'winner'    =>  ' ',
            ], 200);
        }

        if ($justReturn)
            return false;

        return response()->json([
            'noWinner'  =>  true
        ], 200);
    }

    public function delete(Request $request)
    {
        Action::truncate();
        return response()->json([], 200);
    }
}

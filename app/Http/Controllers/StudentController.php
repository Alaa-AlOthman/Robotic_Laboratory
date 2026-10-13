<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Piece;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class StudentController extends Controller
{

    public function borrowed(Request $request)
    {
        $team = Team::findOrFail($request->team_id);

        if (!$team) {
            return response()->json([]);
        }



        $borrowedItems = DB::table('borrow_request_piece')
            ->join('borrow_request', 'borrow_request_piece.borrow_request_id', '=', 'borrow_request.id')
            ->join('pieces', 'borrow_request_piece.piece_id', '=', 'pieces.id')
            ->where('borrow_request.team_id', $team->id)
            ->whereNot('borrow_request.request_state', 'IRFS')
            ->where('borrow_request_piece.delivered_pieces_num', '>', 0)
            ->select(
                'pieces.piece_name',
                DB::raw('SUM(borrow_request_piece.delivered_pieces_num) as quantity'),
                'borrow_request.created_at as borrow_date'
            )
            ->groupBy('pieces.piece_name', 'borrow_request.created_at')
            ->get();


        return response()->json($borrowedItems);
    }


    public function pieces()
    {
        $pieces = Piece::all(['id', 'piece_name', 'piece_type']);
        return response()->json($pieces);
    }
}

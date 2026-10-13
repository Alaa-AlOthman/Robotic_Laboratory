<?php

namespace App\Http\Controllers;

use App\Models\CompoundPieces;
use App\Models\Compounds;
use App\Models\Piece;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompoundPiecesController extends Controller
{
    // public function getPiecesCompound(Request $request)
    // {
    //     // $request->validate([
    //     //     'diwan_num' => 'required',
    //     // ]);
    //     if ($request->diwan_num && !empty($request->diwan_num)) {
    //         $compound = Compounds::where('diwan_num', $request->diwan_num)->first();
    //     }
    //     if ($request->compound_name && !empty($request->compound_name)) {
    //         $compound = Compounds::with('borrowRequest')
    //             ->where('compound_name', $request->compound_name)
    //             ->first();
    //     }
    //     //  Log::debug( 'compoud : ' . $compound);
    //     if ($compound) {
    //         $pieces = app(CompoundController::class)->getPieces($compound->id);
    //         return response()->json(['pieces' => $pieces, 'compound' => $compound], 200);
    //     }
    // }

    // public function addPieceToCompound(Request $request)
    // {
    //     //  ['compound_id','piece_id','pieces_num'];
    //     $request->validate([
    //         'piece_id' => 'required|exists:pieces,id',
    //         'compound_id' => 'required|exists:compounds,id',
    //         'pieces_num' => 'required|integer|min:1',
    //     ]);
    //     $compound = Compounds::where('id', $request->compound_id)->first();
    //     $piece = Piece::where(['id' => $request->piece_id])->first();
    //     if ($piece) {
    //         if ($compound) {
    //             $compoundPieces = CompoundPieces::create([
    //                 'compound_id' => $request->compound_id,
    //                 'piece_id' => $request->piece_id,
    //                 'pieces_num' => $request->pieces_num
    //             ]);

    //             if ($piece->available_num >= $request->pieces_num) {
    //                 $piece->available_num -= $request->pieces_num;
    //             } else {
    //                 $piece->available_num = 0;
    //             }
    //             $piece->save();
    //         }
    //         return response()->json($compoundPieces, 200);
    //     }
    // }

    // public function editePieceInCompound(Request $request)
    // {
    //     $request->validate([
    //         'piece_id' => 'required|exists:pieces,id',
    //         'compound_id' => 'required|exists:compounds,id',
    //         'pieces_num' => 'required|integer|min:1',
    //     ]);
    //     $compoundPieces = CompoundPieces::where(['piece_id' => $request->piece_id, 'compound_id' => $request->compound_id])
    //         ->first();

    //     if ($compoundPieces) {
    //         $oldnum = $compoundPieces->pieces_num;

    //         $compoundPieces->pieces_num = $request->pieces_num;
    //         $compoundPieces->save();

    //         $piece = Piece::findOrFail($request->piece_id);
    //         if ($piece) {
    //             $newnum = $request->pieces_num ?? 0;
    //             if ($newnum > $oldnum) {
    //                 $diff = $newnum - $oldnum;
    //                 $piece->available_num = max($piece->available_num - $diff, 0);
    //             } elseif ($newnum < $oldnum) {
    //                 $diff = $oldnum - $newnum;
    //                 $piece->available_num += $diff;
    //             }
    //             $piece->save();
    //             return response()->json($compoundPieces, 200);
    //         }
    //     }
    // }
    public function getPiecesCompound(Request $request)
{
    if ($request->diwan_num && !empty($request->diwan_num)) {
        $compound = Compounds::where('diwan_num', $request->diwan_num)->first();
    }
    if ($request->compound_name && !empty($request->compound_name)) {
        $compound = Compounds::with('borrowRequest')
            ->where('compound_name', $request->compound_name)
            ->first();
    }

    if ($compound) {
        $pieces = app(CompoundController::class)->getPieces($compound->id);
        return response()->json(['message' => 'تم جلب معلومات القطع والمحضر بنجاح', 'pieces' => $pieces, 'compound' => $compound], 200);
    } else {
        return response()->json(['error' => 'لم يتم العثور على محضر مطابق للمعلومات المدخلة'], 404);
    }
}

public function addPieceToCompound(Request $request)
{
    $request->validate([
        'piece_id' => 'required|exists:pieces,id',
        'compound_id' => 'required|exists:compounds,id',
        'pieces_num' => 'required|integer|min:1',
    ]);
    $compound = Compounds::where('id', $request->compound_id)->first();
    $piece = Piece::where(['id' => $request->piece_id])->first();
    if ($piece) {
        if ($compound) {
            $compoundPieces = CompoundPieces::create([
                'compound_id' => $request->compound_id,
                'piece_id' => $request->piece_id,
                'pieces_num' => $request->pieces_num
            ]);

            if ($piece->available_num >= $request->pieces_num) {
                $piece->available_num -= $request->pieces_num;
            } else {
                $piece->available_num = 0;
            }
            $piece->save();

            return response()->json(['message' => 'تمت إضافة القطعة إلى المحضر بنجاح', 'data' => $compoundPieces], 200);
        } else {
            return response()->json(['error' => 'المحضر غير موجود'], 404);
        }
    } else {
        return response()->json(['error' => 'القطعة غير موجودة'], 404);
    }
}

public function editePieceInCompound(Request $request)
{
    $request->validate([
        'piece_id' => 'required|exists:pieces,id',
        'compound_id' => 'required|exists:compounds,id',
        'pieces_num' => 'required|integer|min:1',
    ]);
    $compoundPieces = CompoundPieces::where(['piece_id' => $request->piece_id, 'compound_id' => $request->compound_id])->first();

    if ($compoundPieces) {
        $oldnum = $compoundPieces->pieces_num;

        $compoundPieces->pieces_num = $request->pieces_num;
        $compoundPieces->save();

        $piece = Piece::findOrFail($request->piece_id);
        if ($piece) {
            $newnum = $request->pieces_num ?? 0;
            if ($newnum > $oldnum) {
                $diff = $newnum - $oldnum;
                $piece->available_num = max($piece->available_num - $diff, 0);
            } elseif ($newnum < $oldnum) {
                $diff = $oldnum - $newnum;
                $piece->available_num += $diff;
            }
            $piece->save();

            return response()->json(['message' => 'تم تعديل عدد القطع في المحضر بنجاح', 'data' => $compoundPieces], 200);
        } else {
            return response()->json(['error' => 'فشل في العثور على القطعة المحددة'], 404);
        }
    } else {
        return response()->json(['error' => 'العنصر غير موجود ضمن المحضر المطلوب'], 404);
    }
}


    public function deletePieceFromCompound(Request $request)
    {
        $request->validate([
            'piece_id' => 'required|exists:pieces,id',
            'compound_id' => 'required|exists:compounds,id',
        ]);

        $compoundPieces = CompoundPieces::where(['piece_id' => $request->piece_id, 'compound_id' => $request->compound_id])
            ->first();
        if ($compoundPieces) {
            $piece = Piece::findOrFail($request->piece_id);
            $piece->available_num += $compoundPieces->pieces_num;
            $piece->save();

            $compoundPieces->delete();
            return response()->json($compoundPieces, 200);
        }
    }
}

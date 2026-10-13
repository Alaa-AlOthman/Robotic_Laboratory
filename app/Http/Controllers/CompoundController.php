<?php

namespace App\Http\Controllers;

use App\Models\BorrowRequest;
use App\Models\borrowRequestPiece;
use App\Models\CompoundPieces;
use App\Models\Compounds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompoundController extends Controller
{
    public function getAllCompounds()
    {
        $compouds = Compounds::with('borrowRequest')->get();
        return response()->json($compouds, 200);
    }

    // ['diwan_num', 'diwan_date', 'compound_name', 'borrow_id'];
    // public function createNewCompound(Request $request)
    // {
    //     $request->validate([
    //         'diwan_num' => 'required',
    //         'diwan_date' => 'required|date',
    //         'compound_name' => 'required|string',
    //         'borrow_id' => 'required|exists:borrow_request,id',
    //     ]);

    //     $borrow = BorrowRequest::with('BorrowRequestPieces.piece')->findOrFail($request->borrow_id);
    //     Log::debug('borrow : ' . $borrow);
    //     if ($borrow) {
    //         if ($borrow->request_state == 'IRFS') {
    //             // $pieces= borrowRequestPiece::with('piece')
    //             // ->where('borrow_request_id', $request->borrow_id)
    //             // ->get();

    //             $compoud = Compounds::create([
    //                 'diwan_num' => $request->diwan_num,
    //                 'diwan_date' => $request->diwan_date,
    //                 'compound_name' => $request->compound_name,
    //                 'borrow_id' => $request->borrow_id
    //             ]);
    //             //  Log::debug( 'compoud : ' . $compoud);
    //             // $newCompound=Compounds::findOrFail($compoud->id);
    //             // Log::debug( 'Id : ' . $newCompound->id);
    //             if ($compoud) {
    //                 foreach ($borrow->BorrowRequestPieces as $borrowReq) {
    //                     $piece = $borrowReq->piece;
    //                     if ($piece) {
    //                         CompoundPieces::create([
    //                             'compound_id' => $compoud->id,
    //                             'piece_id' => $piece->id,
    //                             'pieces_num' => $borrowReq->delivered_pieces_num
    //                         ]);
    //                     }
    //                 }
    //             }
    //             $compoudPieces = $this->getPieces($compoud->id);
    //             return response()->json($compoudPieces, 200);
    //         }
    //     }
    // }

   public function createNewCompound(Request $request)
{
    $request->validate([
        'diwan_num' => 'required',
        'diwan_date' => 'required|date',
        'compound_name' => 'required|string',
        'borrow_id' => 'required|exists:borrow_request,id',
    ]);

    // التحقق من رقم الديوان
    if (Compounds::where('diwan_num', $request->diwan_num)->exists()) {
        return response()->json(['message' => 'رقم الديوان مستخدم بالفعل'], 400);
    }

    // التحقق من اسم المحضر
    if (Compounds::where('compound_name', $request->compound_name)->exists()) {
        return response()->json(['message' => 'اسم المحضر مستخدم بالفعل'], 400);
    }

    $borrow = BorrowRequest::with('BorrowRequestPieces.piece')->find($request->borrow_id);
    Log::debug('borrow : ' . $borrow);

    if (!$borrow) {
        return response()->json(['message' => 'الطلب غير موجود'], 404);
    }

    if ($borrow->request_state != 'IRFS') {
        return response()->json(['message' => 'الحالة غير مناسبة لإنشاء محضر تركيب'], 400);
    }

    $compoud = Compounds::create([
        'diwan_num' => $request->diwan_num,
        'diwan_date' => $request->diwan_date,
        'compound_name' => $request->compound_name,
        'borrow_id' => $request->borrow_id
    ]);

    if (!$compoud) {
        return response()->json(['message' => 'فشل إنشاء المحضر'], 500);
    }

    foreach ($borrow->BorrowRequestPieces as $borrowReq) {
        $piece = $borrowReq->piece;
        $piece->available_num-=$borrowReq->delivered_pieces_num;
        $piece->save();
        if ($piece) {
            CompoundPieces::create([
                'compound_id' => $compoud->id,
                'piece_id' => $piece->id,
                'pieces_num' => $borrowReq->delivered_pieces_num
            ]);
        }
    }

    $compoudPieces = $this->getPieces($compoud->id);
    return response()->json([
        'message' => 'تم إنشاء المحضر بنجاح',
        'data' => $compoudPieces
    ], 200);
}


    public function getPieces($compoudId)
    {
        $compoudPieces = CompoundPieces::with('piece')
            ->where('compound_id', $compoudId)
            ->get();
        $pieces=[];    
        $compoudPieces->map(function ($compound) use (&$pieces) {
            $piece = $compound->piece;
            $compound->piece->image_url = "http://localhost/Robotic/BACKEND/project/storage/app/public/$piece->image";
            //  array_push($pieces, $compound->piece);
            $compound->piece->pieces_num=$compound->pieces_num;
            $pieces[]= $compound->piece;
        //   Log::debug( 'array : ' ,  $pieces);

            return $compound;
        });

        return $pieces;
    }

    // public function editeCompound(Request $request)
    // {
    //     $request->validate([
    //         'compound_id' => 'required',
    //         'diwan_num' => 'required',
    //         'diwan_date' => 'required|date',
    //         'compound_name' => 'required|string',
    //         // 'borrow_id' => 'required|exists:borrow_request,id',
    //     ]);

    //     $compoud = Compounds::findOrFail($request->compound_id);
    //     if ($compoud) {
    //         $compoud->update([
    //             'diwan_num' => $request->diwan_num,
    //             'diwan_date' => $request->diwan_date,
    //             'compound_name' => $request->compound_name,
    //             // 'borrow_id' => $request->borrow_id
    //         ]);
    //         return response()->json($compoud, 200);
    //     }
    // }
public function editeCompound(Request $request)
{
    $request->validate([
        'compound_id' => 'required',
        'diwan_num' => 'required',
        'diwan_date' => 'required|date',
        'compound_name' => 'required|string',
    ]);

    $compoud = Compounds::find($request->compound_id);

    if (!$compoud) {
        return response()->json(['message' => 'المحضر غير موجود'], 404);
    }

    $success = $compoud->update([
        'diwan_num' => $request->diwan_num,
        'diwan_date' => $request->diwan_date,
        'compound_name' => $request->compound_name,
    ]);

    if ($success) {
        return response()->json([
            'message' => 'تم تعديل المحضر بنجاح',
            'data' => $compoud
        ], 200);
    } else {
        return response()->json(['message' => 'فشل تعديل المحضر'], 500);
    }
}

    public function deleteComound(Request $request)
    {
        $request->validate([
            'compound_id' => 'required',
        ]);
        $compoud = Compounds::findOrFail($request->compound_id);
        if ($compoud) {
            $compoud->delete();
        }
    }
}

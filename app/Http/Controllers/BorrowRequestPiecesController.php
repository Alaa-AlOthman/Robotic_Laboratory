<?php

namespace App\Http\Controllers;

use App\BorrowRequestPiecesImport;
use App\Models\BorrowRequest;
use App\Models\borrowRequestPiece;
use App\Models\BrokenPieces;
use App\Models\Piece;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Facades\Excel;

class BorrowRequestPiecesController extends Controller
{
    // ['requested_pieces_num','delivered_pieces_num','piece_deliver_state','piece_deliver_note','piece_recieve_state'];
    // public function addPieceToBorrowRequest(Request $request)
    // {
    //     $request->validate([
    //         'borrow_request_id' => 'required|exists:borrow_request,id',
    //         'piece_id' => 'required|exists:pieces,id',
    //         'requested_pieces_num' => 'required|integer|min:1',
    //         'delivered_pieces_num' => 'nullable|integer|min:0',
    //         'piece_deliver_state' => 'required|in:DA,PD,ND',
    //         'piece_deliver_note' => 'nullable|string',
    //     ]);

    //     $borrowRequest = BorrowRequest::findOrFail($request->borrow_request_id);
    //     $borrowRequest->pieces()->attach($request->piece_id, [
    //         'requested_pieces_num' => $request->requested_pieces_num,
    //         'delivered_pieces_num' => $request->delivered_pieces_num,
    //         'piece_deliver_state' => $request->piece_deliver_state,
    //         'piece_recieve_state' => $request->piece_recieve_state,
    //         'piece_deliver_note' => $request->piece_deliver_note,
    //         'piece_recieve_state' => $request->piece_recieve_state,
    //     ]);
    //     $piece = Piece::findOrFail($request->piece_id);
    //     if ($piece->available_num >= $request->delivered_pieces_num) {
    //         $piece->available_num -= $request->delivered_pieces_num;
    //     } else {
    //         $piece->available_num = 0;
    //     }
    //     $piece->save();
    //     app(BorrowRequestController::class)->updateBorrowRequestDeliverState($request->borrow_request_id);

    //     return response()->json($borrowRequest, 200);
    // }
      public function addPieceToBorrowRequest(Request $request)
{
  $validated = $request->validate([
    'borrow_request_id' => 'required|exists:borrow_request,id',
    'piece_id' => 'required|exists:pieces,id',
    'requested_pieces_num' => 'required|integer|min:1',
    'delivered_pieces_num' => 'nullable|integer|min:0',
    'piece_deliver_state' => 'required|in:DA,PD,ND',
    'piece_deliver_note' => 'nullable|string',
    'piece_recieve_state' => 'nullable|string'
  ], [
    'borrow_request_id.required' => '❌ رقم طلب الاستعارة مطلوب',
    'borrow_request_id.exists' => '❌ طلب الاستعارة غير موجود',
    'piece_id.required' => '❌ رقم القطعة مطلوب',
    'piece_id.exists' => '❌ القطعة غير موجودة',
    'requested_pieces_num.required' => '❌ عدد القطع المطلوبة مطلوب',
    'requested_pieces_num.integer' => '❌ عدد القطع يجب أن يكون رقمًا صحيحًا',
    'requested_pieces_num.min' => '❌ يجب طلب قطعة واحدة على الأقل',
    'delivered_pieces_num.integer' => '❌ عدد القطع المُسلّمة يجب أن يكون رقمًا صحيحًا',
    'delivered_pieces_num.min' => '❌ عدد القطع المُسلّمة لا يمكن أن يكون سالبًا',
    'piece_deliver_state.required' => '❌ حالة التسليم مطلوبة',
    'piece_deliver_state.in' => '❌ حالة التسليم غير صحيحة',
  ]);

  try {
    $borrowRequest = BorrowRequest::findOrFail($validated['borrow_request_id']);
    $borrowRequest->pieces()->attach($validated['piece_id'], [
      'requested_pieces_num' => $validated['requested_pieces_num'],
      'delivered_pieces_num' => $request->delivered_pieces_num ?? 0,
      'piece_deliver_state' => $validated['piece_deliver_state'],
      'piece_recieve_state' => $request->piece_recieve_state,
      'piece_deliver_note' => $request->piece_deliver_note
    ]);

    $piece = Piece::findOrFail($request->piece_id);
        if ($piece->available_num >= $request->delivered_pieces_num) {
            $piece->available_num -= $request->delivered_pieces_num;
        } else {
            $piece->available_num = 0;
        }
    $piece->save();

    app(BorrowRequestController::class)->updateBorrowRequestDeliverState($validated['borrow_request_id']);

    return response()->json([
      'message' => '✅ تم إضافة القطعة إلى طلب الاستعارة بنجاح',
      'request' => $borrowRequest
    ], 201);
  } catch (\Exception $e) {
    return response()->json([
      'error' => '❌ حدث خطأ أثناء إضافة القطعة ' 
    ], 500);
  }
}
public function editePieceInBorrowRequest(Request $request)
{
  $validated = $request->validate([
    'borrow_request_id' => 'required|exists:borrow_request,id',
    'piece_id' => 'required|exists:pieces,id',
    'requested_pieces_num' => 'required|integer|min:1',
    'delivered_pieces_num' => 'nullable|integer|min:0',
    'piece_deliver_state' => 'required|in:DA,PD,ND',
    'piece_deliver_note' => 'nullable|string',
    'piece_recieve_state' => 'nullable|string'
  ], [
    'borrow_request_id.required' => '❌ رقم طلب الاستعارة مطلوب',
    'borrow_request_id.exists' => '❌ طلب الاستعارة غير موجود',
    'piece_id.required' => '❌ رقم القطعة مطلوب',
    'piece_id.exists' => '❌ القطعة غير موجودة',
    'requested_pieces_num.required' => '❌ عدد القطع المطلوبة مطلوب',
    'requested_pieces_num.integer' => '❌ عدد القطع يجب أن يكون رقمًا صحيحًا',
    'requested_pieces_num.min' => '❌ يجب طلب قطعة واحدة على الأقل',
    'delivered_pieces_num.integer' => '❌ عدد القطع المُسلّمة يجب أن يكون رقمًا صحيحًا',
    'delivered_pieces_num.min' => '❌ عدد القطع المُسلّمة لا يمكن أن يكون سالبًا',
    'piece_deliver_state.required' => '❌ حالة التسليم مطلوبة',
    'piece_deliver_state.in' => '❌ حالة التسليم غير صحيحة',
  ]);

  try {
    $borrowRequest = borrowRequestPiece::where('borrow_request_id', $validated['borrow_request_id'])
      ->where('piece_id', $validated['piece_id'])
      ->first();

    if (!$borrowRequest) {
      return response()->json(['error' => '❌ لا يوجد سجل لهذه القطعة في الطلب المحدد'], 404);
    }

   $oldDelivered = $borrowRequest->delivered_pieces_num;
        $borrowRequest->update([
            'requested_pieces_num' => $request->requested_pieces_num,
            'delivered_pieces_num' => $request->delivered_pieces_num,
            'piece_deliver_state' => $request->piece_deliver_state,
            'piece_recieve_state' => $request->piece_recieve_state,
            'piece_deliver_note' => $request->piece_deliver_note,
        ]);

        $piece = Piece::findOrFail($request->piece_id);
        $newDelivered = $request->delivered_pieces_num ?? 0;
        if ($newDelivered > $oldDelivered) {
            $diff = $newDelivered - $oldDelivered;
            $piece->available_num = max($piece->available_num - $diff, 0);
        } elseif ($newDelivered < $oldDelivered) {
            $diff = $oldDelivered - $newDelivered;
            $piece->available_num += $diff;
        }

    $piece->save();
    app(BorrowRequestController::class)->updateBorrowRequestDeliverState($validated['borrow_request_id']);

    return response()->json([
      'message' => '✅ تم تعديل بيانات القطعة في طلب الاستعارة بنجاح',
      'request_piece' => $borrowRequest
    ], 200);
  } catch (\Exception $e) {
    return response()->json([
      'error' => '❌ حدث خطأ أثناء تعديل البيانات ' 
    ], 500);
  }
}

    // public function editePieceInBorrowRequest(Request $request)
    // {
    //     $request->validate([
    //         'borrow_request_id' => 'required|exists:borrow_request,id',
    //         'piece_id' => 'required|exists:pieces,id',
    //         'requested_pieces_num' => 'required|integer|min:1',
    //         'delivered_pieces_num' => 'nullable|integer|min:0',
    //         'piece_deliver_state' => 'required|in:DA,PD,ND',
    //         'piece_deliver_note' => 'nullable|string',
    //     ]);
    //     $borrowRequest = borrowRequestPiece::where('borrow_request_id', $request->borrow_request_id)
    //         ->where('piece_id', $request->piece_id)
    //         ->first();
    //     $oldDelivered = $borrowRequest->delivered_pieces_num;
    //     $borrowRequest->update([
    //         'requested_pieces_num' => $request->requested_pieces_num,
    //         'delivered_pieces_num' => $request->delivered_pieces_num,
    //         'piece_deliver_state' => $request->piece_deliver_state,
    //         'piece_recieve_state' => $request->piece_recieve_state,
    //         'piece_deliver_note' => $request->piece_deliver_note,
    //     ]);

    //     $piece = Piece::findOrFail($request->piece_id);
    //     $newDelivered = $request->delivered_pieces_num ?? 0;
    //     if ($newDelivered > $oldDelivered) {
    //         $diff = $newDelivered - $oldDelivered;
    //         $piece->available_num = max($piece->available_num - $diff, 0);
    //     } elseif ($newDelivered < $oldDelivered) {
    //         $diff = $oldDelivered - $newDelivered;
    //         $piece->available_num += $diff;
    //     }

    //     $piece->save();
    //     app(BorrowRequestController::class)->updateBorrowRequestDeliverState($request->borrow_request_id);
    //     return response()->json($borrowRequest, 200);
    // }

    public function getPiecesByBorrowRequest(Request $request)
    {
        // Log::debug( 'Id : ' . $request->id);
        if ($request->id && !empty($request->id)) {
            $borrowRequest = $this->getPiecesBorrow($request->id);
            return response()->json($borrowRequest, 200);
        }
    }

    public function getPiecesByBorrowRequestWithoutPigination(Request $request)
    {
        if ($request->id && !empty($request->id)) {
            $borrowRequest = borrowRequestPiece::with('piece')
                ->where('borrow_request_id', $request->id)
                ->get();

            return response()->json($borrowRequest, 200);
        }
    }

    // public function getBorrowRequestByDiawanNum(Request $request)
    // {
    //     //  Log::debug( 'Id : ' . $request->diwan_num);
    //     if ($request->diwan_num && !empty($request->diwan_num)) {
    //         $borrow = BorrowRequest::where('diwan_num', $request->diwan_num)->first();
    //         //    $borrowRequest = $this->getPiecesBorrow($borrow->id);
    //         return response()->json(['borrow' => $borrow], 200);
    //         //    return response()->json($borrowRequest, 200);
    //     }
    // }

    public function getBorrowRequestByDiawanNum(Request $request)
{
    
    if (!$request->diwan_num || empty($request->diwan_num)) {
        return response()->json([
            'error' => 'رقم الديوان غير موجود أو فارغ'
        ], 400); 
    }

 
    $borrow = BorrowRequest::where('diwan_num', $request->diwan_num)->first();

    if (!$borrow) {
        return response()->json([
            'error' => 'لم يتم العثور على طلب استعارة بهذا الرقم'
        ], 404); 
    }

    return response()->json(['borrow' => $borrow], 200);
}

    public function getPiecesBorrow($borrowRequestId)
    {
        $borrowRequest = borrowRequestPiece::with('piece')
            ->where('borrow_request_id', $borrowRequestId)
            ->paginate(3)
            ->through(function ($borrow) {
                $piece = $borrow->piece;
                $borrow->piece->image_url = "http://localhost/Robotic/BACKEND/project/storage/app/public/$piece->image";
                return $borrow;
            });
        return $borrowRequest;
    }

    public function deleteAllPiecesFromBorrowRequets($borrowRequestId)
    {
        $pieces = borrowRequestPiece::where('borrow_request_id', $borrowRequestId)->pluck('piece_id');
        foreach ($pieces as $piece) {
            $this->removePieceFromBorrow($borrowRequestId, $piece);
        }
        return response()->json($pieces, 200);
    }

    public function deletePieceFromBorrowRequest(Request $request)
    {
        $borrowRequest = $this->removePieceFromBorrow($request->borrow_request_id, $request->piece_id);
        if ($borrowRequest)
            return response()->json($borrowRequest, 200);
    }

    public function removePieceFromBorrow($borrowRequestId, $pieceId)
    {
        $borrowRequest = borrowRequestPiece::where('borrow_request_id', $borrowRequestId)
            ->where('piece_id', $pieceId)
            ->first();
        if (!$borrowRequest) {
            return false;
        }
        $piece = Piece::findOrFail($pieceId);
        if ($piece) {
            $piece->available_num += $borrowRequest->delivered_pieces_num;
            $piece->save();
        }
        $borrowRequest->delete();
        return true;
    }

    // public function recievePieceFromStudent(Request $request)
    // {
    //     // [RecievedAll,PartialRecieve,NoReceived,Broken]
    //     // $table->enum('piece_recieve_state',['RA','PR','NR','BR']);
    //     //  $table->integer('piece_recieve_num')->default(0);
    //     $request->validate([
    //         'borrow_request_id' => 'required|exists:borrow_request,id',
    //         'piece_id' => 'required|exists:pieces,id',
    //         'piece_recieve_state' => 'required|in:RA,PR,NR,BR',
    //         'piece_recieve_num' => 'nullable|integer',
    //     ]);
    //     $borrowRequest = borrowRequestPiece::where('borrow_request_id', $request->borrow_request_id)
    //         ->where('piece_id', $request->piece_id)
    //         ->first();
    //     if ($borrowRequest) {
    //         $borrowRequest->update([
    //             'piece_recieve_state' => $request->piece_recieve_state,
    //             'piece_recieve_num' => $request->piece_recieve_num
    //         ]);
    //         return response()->json($borrowRequest, 200);
    //     }
    // }
// public function recievePieceFromStudent(Request $request)
// {
//     try {
//         $request->validate([
//             'borrow_request_id' => 'required|exists:borrow_request,id',
//             'piece_id' => 'required|exists:pieces,id',
//             'piece_recieve_state' => 'required|in:RA,PR,NR,BR',
//             'piece_recieve_num' => 'nullable|integer',
//         ]);

//         $borrowRequest = borrowRequestPiece::where('borrow_request_id', $request->borrow_request_id)
//             ->where('piece_id', $request->piece_id)
//             ->first();

//         if ($borrowRequest) {
//             $borrowRequest->update([
//                 'piece_recieve_state' => $request->piece_recieve_state,
//                 'piece_recieve_num' => $request->piece_recieve_num
//             ]);
//             $piece = Piece::findOrFail($request->piece_id);
//         if ($piece) {
//             $piece->available_num += $request->piece_recieve_num;
//             $piece->save();
//         }
//             return response()->json([
//                 'success' => true,
//                 'message' => 'تم تحديث حالة استلام القطعة بنجاح.',
//                 'data' => $borrowRequest
//             ], 200);
//         } else {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'لم يتم العثور على العلاقة بين الطلب والقطعة.'
//             ], 404);
//         }
//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'حدث خطأ أثناء تحديث حالة استلام القطعة.',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }
public function recievePieceFromStudent(Request $request)
{
    try {
        // ✅ التحقق من البيانات
        $request->validate([
            'borrow_request_id' => 'required|exists:borrow_request,id',
            'piece_id' => 'required|exists:pieces,id',
            'piece_recieve_state' => 'required|in:RA,PR,NR,BR',
            'piece_recieve_num' => 'nullable|integer|min:0',
        ]);

        // ✅ البحث عن العلاقة بين الطلب والقطعة
        $borrowRequest = borrowRequestPiece::where('borrow_request_id', $request->borrow_request_id)
            ->where('piece_id', $request->piece_id)
            ->first();

        if (!$borrowRequest) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على العلاقة بين الطلب والقطعة'
            ], 404);
        }

        // ✅ تحديث حالة الاستلام
        $borrowRequest->update([
            'piece_recieve_state' => $request->piece_recieve_state,
            'piece_recieve_num' => $request->piece_recieve_num
        ]);

        // ✅ تحديث حالة القطعة
        $piece = Piece::findOrFail($request->piece_id);
        $recievedNum = $request->piece_recieve_num ?? 0;

        if ($request->piece_recieve_state === 'BR') {
            // القطعة معطلة
            $brokenPiece = BrokenPieces::firstOrNew(['piece_id' => $request->piece_id]);
            $brokenPiece->broken_piece_num = ($brokenPiece->broken_piece_num ?? 0) + $recievedNum;
            $brokenPiece->isFixed = false;
            $brokenPiece->save();

            $piece->available_num = max(0, $piece->available_num - $recievedNum);
        } else {
            // القطعة سليمة
            $piece->available_num += $recievedNum;
        }

        $piece->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة استلام القطعة بنجاح',
            'data' => $borrowRequest
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء تحديث حالة استلام القطعة',
            'error' => $e->getMessage()
        ], 500);
    }
}


    // public function recieveBorrowRequest(Request $request)
    // {
    //     // request_receive_note,request_receive_date
    //     $request->validate([
    //         'borrow_request_id' => 'required|exists:borrow_request,id',
    //     ]);
    //     $borrowRequest = borrowRequestPiece::with('piece')
    //         ->where('borrow_request_id', $request->borrow_request_id)
    //         ->get();
    //     if ($borrowRequest) {
    //         foreach ($borrowRequest as $borrowPiece) {
    //             $piece = Piece::findOrFail($borrowPiece->piece_id);
    //             if ($piece) {
    //                 $piece->available_num += $borrowPiece->piece_recieve_num;
    //                 $piece->save();
    //             }
    //         }
    //         $borrow = BorrowRequest::findOrFail($request->borrow_request_id);
    //         $borrow->update([
    //             'request_receive_note' => $request->request_receive_note,
    //             'request_receive_date' => $request->request_receive_date,
    //             'request_state' => 'IRFS'
    //         ]);
    //         // app(BorrowRequestController::class)->updateBorrowRequestReciveState($request->borrow_request_id);
    //         return response()->json($borrow, 200);
    //     }
    // }
public function recieveBorrowRequest(Request $request)
{
    try {
        $request->validate([
            'borrow_request_id' => 'required|exists:borrow_request,id',
        ]);

        $borrowRequest = borrowRequestPiece::with('piece')
            ->where('borrow_request_id', $request->borrow_request_id)
            ->get();

        if ($borrowRequest->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يوجد قطع مرتبطة بهذا الطلب'
            ], 404);
        }

        // foreach ($borrowRequest as $borrowPiece) {
        //     $piece = Piece::findOrFail($borrowPiece->piece_id);
        //     $piece->available_num += $borrowPiece->piece_recieve_num;
        //     $piece->save();
        // }

        $borrow = BorrowRequest::findOrFail($request->borrow_request_id);
        $borrow->update([
            'request_receive_note' => $request->request_receive_note,
            'request_receive_date' => $request->request_receive_date,
            'request_state' => 'IRFS'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة استلام الطلب بنجاح',
            'data' => $borrow
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء تحديث الطلب بعد الاستلام.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function importPiecesFromExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
            'borrow_request_id' => 'required|exists:borrow_request,id',
        ]);

        try {
            Excel::import(new BorrowRequestPiecesImport($request->borrow_request_id), $request->file('file'));
            return response()->json(['message' => 'تم الاستيراد بنجاح ✅']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'حدث خطأ أثناء الاستيراد ❌', 'error' => $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BorrowRequest;
use App\Models\borrowRequestPiece;
use App\Models\BrokenPieces;
use App\Models\Compounds;
use App\Models\LostPieces;
use App\Models\Piece;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Compound;
use Termwind\Components\Raw;

class BorrowRequestController extends Controller
{
    // ['diwan_num','diwan_date','request_goal','team_id','request_type','request_state','request_deliver_date','request_deliver_note','request_receive_date','request_receive_note']

    // public function createNewBorrowRequest(Request $request)
    // {
    //     $request->validate([
    //         'diwan_num' => 'required|string',
    //         'diwan_date' => 'required|date',
    //         'team_id' => 'required',
    //         'request_type' => 'required',
    //         'request_state' => 'required'
    //     ]);
    //     $borrow = BorrowRequest::create([
    //         'diwan_num' => $request->diwan_num,
    //         'diwan_date' => $request->diwan_date,
    //         'team_id' => $request->team_id,
    //         'request_type' => $request->request_type,
    //         'request_state' => $request->request_state,
    //         'request_goal' => $request->request_goal,
    //     ]);
    //     return response()->json($borrow, 200);
    // }
public function createNewBorrowRequest(Request $request)
{
  $validated = $request->validate([
    'diwan_num' => 'required|string',
    'diwan_date' => 'required|date',
    'team_id' => 'required|exists:teams,id',
    'request_type' => 'required|string',
    'request_state' => 'required|string'
  ], [
    'diwan_num.required' => '❌ رقم الديوان مطلوب',
    'diwan_date.required' => '❌ تاريخ الديوان مطلوب',
    'diwan_date.date' => '❌ صيغة التاريخ غير صحيحة',
    'team_id.required' => '❌ رقم الفريق مطلوب',
    'team_id.exists' => '❌ الفريق غير موجود',
    'request_type.required' => '❌ نوع الطلب مطلوب',
    'request_state.required' => '❌ حالة الطلب مطلوبة'
  ]);

  try {
    $borrow = BorrowRequest::create([
      'diwan_num' => $validated['diwan_num'],
      'diwan_date' => $validated['diwan_date'],
      'team_id' => $validated['team_id'],
      'request_type' => $validated['request_type'],
      'request_state' => $validated['request_state'],
      'request_goal' => $request->request_goal, // اختياري
    ]);

    return response()->json([
      'message' => '✅ تم إنشاء طلب الاستعارة بنجاح',
      'borrow_request' => $borrow
    ], 201);

  } catch (\Exception $e) {
    return response()->json([
      'error' => '❌ حدث خطأ أثناء إنشاء طلب الاستعارة ' 
    ], 500);
  }
}

    //   'request_deliver_date' => $request->request_deliver_date,
    // 'request_deliver_note' => $request->request_deliver_note

    // public function AddToBorrowRequest(Request $request)
    // {
    //     $borrowRequest = BorrowRequest::findOrFail($request->id);
    //     if ($borrowRequest) {
    //         $borrowRequest->request_deliver_date = $request->request_deliver_date;
    //         $borrowRequest->request_deliver_note = $request->request_deliver_note;
    //         $borrowRequest->save();
    //         return response()->json($borrowRequest, 200);
    //     }
    // }
public function AddToBorrowRequest(Request $request)
{
    try {
        $borrowRequest = BorrowRequest::findOrFail($request->id);
        if ($borrowRequest) {
            $borrowRequest->request_deliver_date = $request->request_deliver_date;
            $borrowRequest->request_deliver_note = $request->request_deliver_note;
            $borrowRequest->save();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث معلومات الطلب بنجاح',
                'data' => $borrowRequest
            ], 200);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء تحديث الطلب',
            'error' => $e->getMessage()
        ], 500);
    }
}

    // public function updateBorrowRequest(Request $request)
    // {
    //     $borrowRequest = BorrowRequest::findOrFail($request->id);
    //     if ($request->request_goal) {
    //         $newRequestGoal = $request->request_goal;
    //     }
    //     if ($request->diwan_date) {
    //         $newDiwanDate = $request->diwan_date;
    //     }
    //     if ($request->diwan_num) {
    //         $newDiwanNum = $request->diwan_num;
    //     }
    //     if ($request->team_id) {
    //         $newTeamId = $request->team_id;
    //     }
    //     $borrowRequest->request_goal = $newRequestGoal;
    //     $borrowRequest->diwan_date = $newDiwanDate;
    //     $borrowRequest->diwan_num = $newDiwanNum;
    //     $borrowRequest->team_id = $newTeamId;
    //     $borrowRequest->update();
    //     return response()->json($borrowRequest, 200);
    // }
    public function updateBorrowRequest(Request $request)
{
    try {
        $borrowRequest = BorrowRequest::findOrFail($request->id);

        if ($request->request_goal) {
            $newRequestGoal = $request->request_goal;
        }
        if ($request->diwan_date) {
            $newDiwanDate = $request->diwan_date;
        }
        if ($request->diwan_num) {
            $newDiwanNum = $request->diwan_num;
        }
        if ($request->team_id) {
            $newTeamId = $request->team_id;
        }

        $borrowRequest->request_goal = $newRequestGoal ?? $borrowRequest->request_goal;
        $borrowRequest->diwan_date = $newDiwanDate ?? $borrowRequest->diwan_date;
        $borrowRequest->diwan_num = $newDiwanNum ?? $borrowRequest->diwan_num;
        $borrowRequest->team_id = $newTeamId ?? $borrowRequest->team_id;

        $borrowRequest->update();

        return response()->json([
            'success' => true,
            'message' => 'تم تعديل الطلب بنجاح',
            'data' => $borrowRequest
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء تعديل الطلب',
            'error' => $e->getMessage()
        ], 500);
    }
}


    // public function deleteBorrowRequest(Request $request)
    // {
    //     // Log::debug( 'Id : ' . $request->id);
    //     //  if($request->id && !empty($request->borrow_request_id)){
    //     $borrow = BorrowRequest::findOrFail($request->borrow_request_id);
    //     $pieces = borrowRequestPiece::where('borrow_request_id', $request->borrow_request_id)->get();
    //     foreach ($pieces as $item) {
    //         $piece = Piece::findOrFail($item->piece_id);
    //         if ($piece) {
    //             $piece->available_num += $item->delivered_pieces_num;
    //             $piece->save();
    //         }
    //         $item->delete();
    //     }
    //     $borrow->delete();
    //     return response()->json($borrow, 200);
    // }

    public function deleteBorrowRequest(Request $request)
{
    $borrow = BorrowRequest::findOrFail($request->borrow_request_id);
    $diwanNum = $borrow->diwan_num;

    // تحقق من وجود رقم الديوان ضمن القطع الناقصة
    $existsInLost = LostPieces::whereJsonContains('borrow_diwan_num', $diwanNum)->exists();

    // تحقق من وجوده ضمن القطع العاطلة
    $existsInBroken = BrokenPieces::whereJsonContains('borrow_diwan_num', $diwanNum)->exists();

    // تحقق من وجوده ضمن محاضر التركيب
    $existsInCompounds = Compounds::where('borrow_id', $request->borrow_request_id)->exists();

    if ($existsInLost || $existsInBroken || $existsInCompounds) {
        $message = 'لا يمكن حذف الطلب لأنه مرتبط بـ ';
        $sources = [];
        if ($existsInLost) $sources[] = 'القطع الناقصة';
        if ($existsInBroken) $sources[] = 'القطع العاطلة';
        if ($existsInCompounds) $sources[] = 'محضر تركيب';
        $message .= implode(' و ', $sources);

        return response()->json(['error' => $message], 400);
    }

    // إذا ما في ارتباط، نكمل الحذف
    $pieces = borrowRequestPiece::where('borrow_request_id', $request->borrow_request_id)->get();
    foreach ($pieces as $item) {
        $piece = Piece::findOrFail($item->piece_id);
        if ($piece) {
            $piece->available_num += $item->delivered_pieces_num;
            $piece->save();
        }
        $item->delete();
    }

    $borrow->delete();
    return response()->json(['success' => 'تم حذف الطلب بنجاح'], 200);
}

    public function getAllBorrowRequests(Request $request)
    {
        $BorrowRequests = BorrowRequest::with('team');
        // print_r($_POST);
        // dd($request->all());
        if ($request->has('team_id') && !empty($request->team_id)) {
            // Log::debug('team Id : ' . $request->team_id);
            $BorrowRequests->where('team_id', $request->team_id);
        }
        if ($request->has('request_goal') && !empty($request->request_goal)) {
            // Log::debug('request_goal : ' . $request->request_goal);

            $BorrowRequests->where('request_goal', $request->request_goal);
        }

        if ($request->has('diwan_date') && !empty($request->diwan_date)) {
            // Log::debug('diwan_date : ' . $request->diwan_date);
            $BorrowRequests->where('diwan_date', $request->diwan_date);
        }
        if ($request->has('request_state') && !empty($request->request_state)) {
            // Log::debug('request_state : ' . $request->request_state);
            $BorrowRequests->where('request_state', $request->request_state);
        }
        $borrows = $BorrowRequests->paginate(8);

        return response()->json($borrows, 200);
    }
     public function getAllBorrowRequestsWithoutPaginate(){
         $BorrowRequests = BorrowRequest::all();
             
        return response()->json($BorrowRequests, 200);
     }

    public function getRecordsForSelect()
    {
        $borrows = BorrowRequest::with('team')->get();
        $team = [];
        foreach ($borrows as $borrow) {
            $goal[] = $borrow->request_goal;

            $team[] = $borrow->team;
        }
        $goal = array_unique($goal);
        $team = array_unique($team);

        return response()->json(['goal' => $goal, 'team' => $team], 200);
    }

    // [ItemRecievedFromStudent,ItemDeliverdeToStudent,PartialDelivery,NoDelivered]
    // $table->enum('request_state',['IRFS','IDTS','PD','ND']);
    public function updateBorrowRequestDeliverState($borrowRequestId)
    {
        $pieces = borrowRequestPiece::where('borrow_request_id', $borrowRequestId)->get();
        if ($pieces->isEmpty()) {
            return;
        }
        $allDelivered = true;
        $anyDelivered = false;

        foreach ($pieces as $piece) {
            if ($piece->delivered_pieces_num >= $piece->requested_pieces_num) {
                $anyDelivered = true;
            } else {
                $allDelivered = false;
                if ($piece->delivered_pieces_num > 0) {
                    $anyDelivered = true;
                }
            }
        }
        $borrowRequest = BorrowRequest::findOrFail($borrowRequestId);
        if ($allDelivered) {
            $borrowRequest->request_state = 'IDTS';
        } elseif ($anyDelivered) {
            $borrowRequest->request_state = 'PD';
        } else {
            $borrowRequest->request_state = 'ND';
        }
        $borrowRequest->save();
    }

    // public function updateBorrowRequestReciveState($borrowRequestId)
    // {
    //     $pieces = borrowRequestPiece::where('borrow_request_id', $borrowRequestId)->get();
    //     if ($pieces->isEmpty()) {
    //         return;
    //     }
    //     $allRecived = true;
    //     $anyRecived = false;
    //     foreach ($pieces as $piece) {
    //         if ($piece->delivered_pieces_num <= $piece->piece_recieve_num) {
    //             $anyRecived = true;
    //         } else {
    //             $allRecived = false;
    //             if ($piece->piece_recieve_num > 0) {
    //                 $anyRecived = true;
    //             }
    //         }
    //     }
    //     $borrowRequest = BorrowRequest::findOrFail($borrowRequestId);
    //     if ($allRecived) {
    //         $borrowRequest->request_state = 'IRFS';
    //     }
    //     //  elseif ($anyRecived) {
    //     //     $borrowRequest->request_state = 'PD';
    //     // } else {
    //     //     $borrowRequest->request_state = 'ND';
    //     // }
    //     $borrowRequest->save();
    // }
}

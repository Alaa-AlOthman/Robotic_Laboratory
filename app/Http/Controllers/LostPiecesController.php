<?php

namespace App\Http\Controllers;

use App\Models\LostPieces;
use App\Models\Piece;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LostPiecesController extends Controller
{
    public function getAllLostPieces()
    {
        $lostPieces = LostPieces::with('piece')->paginate(5);

        $lostPieces->through(function ($piece) {
            $img = $piece->piece->image;
            $piece->piece->image_url = "http://localhost/Robotic/BACKEND/project/storage/app/public/$img";
            return $piece;
        });

        return response()->json($lostPieces, 200);
    }
     public function searchLostPieces(Request $request)
    {
        $query = LostPieces::with('piece');
        if ($request->has('piece_name') && !empty($request->piece_name)) {
            $query->whereHas('piece', function ($q) use ($request) {
                $q->whereRaw('LOWER(piece_name) LIKE ?', ['%' . strtolower($request->piece_name) . '%']);
            });
        }

        if ($request->has('piece_type') && !empty($request->piece_type)) {
            $query->whereHas('piece', function ($q) use ($request) {
                $q->where('piece_type', $request->piece_type);
            });
        }

        $pieces = $query->paginate(5);

        $pieces->getCollection()->transform(function ($piece) {
            $img = $piece->piece->image;
            $piece->piece->image_url = "http://localhost/Robotic/BACKEND/project/storage/app/public/$img";
            return $piece;
        });
        return response()->json($pieces, 200);
    }


    public function createLostPiece(Request $request)
    {
        // ['requested_piece_num','lost_piece_note','borrow_diwan_num'];
        // ['piece_name','Base_num','available_num','description','image','code','piece_type'];
        $request->validate([
            'piece_name' => 'required|string',
            'available_num' => 'required|integer',
            'piece_type' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg,svg|max:2048',
            'requested_piece_num' => 'required|integer'
        ]);
        $file = $request->file('image');
        $imgname = $file->getClientOriginalName();
        $path = $file->storeAs('images', $imgname, 'public');
        //    $data=file_get_contents($file->getRealPath());
        $piece = Piece::create([
            'piece_name' => $request->piece_name,
            'Base_num' => $request->available_num,
            'piece_type' => $request->piece_type,
            'image' => $path,
            'available_num' => $request->available_num,
            'code' => $request->code
        ]);
        $borrowNum = [];
        $lostPiece = LostPieces::create([
            'piece_id' => $piece->id,
            'requested_piece_num' => $request->requested_piece_num,
            'lost_piece_note' => $request->lost_piece_note,
            'borrow_diwan_num' => $borrowNum
        ]);
        return response()->json($lostPiece, 200);
    }

    // public function addToLostPiece(Request $request)
    // {
    //     $request->validate([
    //         'piece_id' => 'required|exists:pieces,id',
    //         'requested_piece_num' => 'required|integer'
    //     ]);
    //     $piece = Piece::where(['id' => $request->piece_id])->first();
    //     if ($piece) {
    //         $lostPiece = LostPieces::firstOrNew(['piece_id' => $request->piece_id]);
    //         $lostPiece->requested_piece_num = ($lostPiece->requested_piece_num ?? 0) + $request->requested_piece_num;
    //         $borrowNum = is_array($lostPiece->borrow_diwan_num) ? $lostPiece->borrow_diwan_num : [];
    //         if (!in_array($request->borrow_diwan_num, $borrowNum)) {
    //             $borrowNum[] = $request->borrow_diwan_num;
    //         }

    //         $lostPiece->borrow_diwan_num = $borrowNum;
    //         $lostPiece->save();
    //         if ($piece->available_num >= $request->requested_piece_num) {
    //             $piece->available_num -= $request->requested_piece_num;
    //         } else {
    //             $piece->available_num = 0;
    //         }
    //         $piece->save();
    //         return response()->json($lostPiece, 200);
    //     }
    // }
    public function addToLostPiece(Request $request)
{
    $request->validate([
        'piece_id' => 'required|exists:pieces,id',
        'requested_piece_num' => 'required|integer'
    ]);

    $piece = Piece::where(['id' => $request->piece_id])->first();

    if ($piece) {
        $lostPiece = LostPieces::firstOrNew(['piece_id' => $request->piece_id]);
        $lostPiece->requested_piece_num = ($lostPiece->requested_piece_num ?? 0) + $request->requested_piece_num;

        $borrowNum = is_array($lostPiece->borrow_diwan_num) ? $lostPiece->borrow_diwan_num : [];
        if (!in_array($request->borrow_diwan_num, $borrowNum)) {
            $borrowNum[] = $request->borrow_diwan_num;
        }

        $lostPiece->borrow_diwan_num = $borrowNum;
        $lostPiece->save();

        if ($piece->available_num >= $request->requested_piece_num) {
            $piece->available_num -= $request->requested_piece_num;
        } else {
            $piece->available_num = 0;
        }

        $piece->save();

        return response()->json([
            'message' => 'تمت إضافة القطعة إلى جدول القطع الناقصة بنجاح',
            'data' => $lostPiece
        ], 200);
    }

    return response()->json([
        'error' => 'لم يتم العثور على القطعة المطلوبة'
    ], 404);
}


    public function removeLostPiece(Request $request)
    {
        $piece = Piece::where(['id' => $request->piece_id])->first();
        if ($piece) {
            if ($piece->Base_num <= 0) {
                if ($piece->image) {
                    Storage::disk('public')->delete($piece->image);
                }
                $piece->delete();
            }
            $lostPiece = LostPieces::where('piece_id', $request->piece_id)->first();
            if ($lostPiece) {
                $lostPiece->delete();
            }
            return response()->json(['message' => 'تم حذف القطعة'], 200);
        }
    }

    public function editLostPiece(Request $request)
    {
        $request->validate([
            'piece_id' => 'required|exists:pieces,id',
            'requested_piece_num' => 'required|integer'
        ]);
        // ['requested_piece_num','lost_piece_note','borrow_diwan_num'];
        $lostPiece = LostPieces::where(['piece_id' => $request->piece_id])->first();
        if ($lostPiece) {
            $oldnum = $lostPiece->requested_piece_num;
            $lostPiece->update([
                'requested_piece_num' => $request->requested_piece_num,
                'lost_piece_note' => $request->lost_piece_note,
                'borrow_diwan_num' => $request->borrow_diwan_num
            ]);
            $piece = Piece::findOrFail($request->piece_id);
            $newnum = $request->requested_piece_num ?? 0;
            if ($newnum > $oldnum) {
                $diff = $newnum - $oldnum;
                $piece->available_num = max($piece->available_num - $diff, 0);
            } elseif ($newnum < $oldnum) {
                $diff = $oldnum - $newnum;
                $piece->available_num += $diff;
            }
            $piece->save();
            return response()->json($lostPiece, 200);
        }
    }

    public function providePiece(Request $request)
    {
        // provided_num
        $request->validate([
            'piece_id' => 'required|exists:pieces,id',
            'provided_num' => 'required|integer'
        ]);
        $lostPiece = LostPieces::where('piece_id', $request->piece_id)->first();
        if ($lostPiece) {
            if ($request->provided_num == $lostPiece->requested_piece_num) {
                $lostPiece->delete();
            } elseif ($request->provided_num < $lostPiece->requested_piece_num) {
                $requestnum = $lostPiece->requested_piece_num - $request->provided_num;
                $lostPiece->requested_piece_num = $requestnum;
                $lostPiece->save();
            }else{
                  $lostPiece->delete();
            }
            $piece = Piece::where('id', $request->piece_id)->first();
            $piece->available_num += $request->provided_num;
            $piece->Base_num += $request->provided_num;
            $piece->save();
        }
        return response()->json(['message' => 'تم توفير القطعة'], 200);
    }
}

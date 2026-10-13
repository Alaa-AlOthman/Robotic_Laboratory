<?php

namespace App\Http\Controllers;

use App\Models\BrokenPieces;
use App\Models\Piece;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrokenPiecesController extends Controller
{
    public function getAllBrokenPieces()
    {
        $brokenPieces = BrokenPieces::with('piece')->paginate(5);

        $brokenPieces->through(function ($piece) {
            $img = $piece->piece->image;
            $piece->piece->image_url = "http://localhost/Robotic/BACKEND/project/storage/app/public/$img";
            return $piece;
        });

        return response()->json($brokenPieces, 200);
    }

    public function searchBrokenPieces(Request $request)
    {
        $query = BrokenPieces::with('piece');
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

    // ['piece_id','broken_piece_num','fixed_piece_num','broken_piece_note','borrow_diwan_num','isFixed']
    public function createBrokenPiece(Request $request)
    {
        $request->validate([
            'piece_id' => 'required|exists:pieces,id',
            'broken_piece_num' => 'required|integer'
        ]);
        $borrowNum = [];
        $brokenPiece = BrokenPieces::create([
            'piece_id' => $request->piece_id,
            'broken_piece_num' => $request->broken_piece_num,
            'broken_piece_note' => $request->broken_piece_note,
            'borrow_diwan_num' => $borrowNum,
            'isFixed' => false
        ]);
        $piece = Piece::where(['id' => $request->piece_id])->first();
        $piece->available_num -= $request->broken_piece_num;
        $piece->save();
        return response()->json($brokenPiece, 200);
    }

    // public function addToBrokenPieces(Request $request)
    // {
    //     $request->validate([
    //         'piece_id' => 'required|exists:pieces,id',
    //         'broken_piece_num' => 'required|integer'
    //     ]);
    //     $piece = Piece::where(['id' => $request->piece_id])->first();
    //     if ($piece) {
    //         $brokenPiece = BrokenPieces::firstOrNew(['piece_id' => $request->piece_id]);
    //         $brokenPiece->broken_piece_num = ($brokenPiece->broken_piece_num ?? 0) + $request->broken_piece_num;
    //         $borrowNum = is_array($brokenPiece->borrow_diwan_num) ? $brokenPiece->borrow_diwan_num : [];
    //         if (!in_array($request->borrow_diwan_num, $borrowNum)) {
    //             $borrowNum[] = $request->borrow_diwan_num;
    //         }

    //         $brokenPiece->borrow_diwan_num = $borrowNum;
    //         $brokenPiece->broken_piece_note .= $request->broken_piece_note;
    //         $brokenPiece->isFixed = false;
    //         $brokenPiece->save();
    //         if ($piece->available_num >= $request->broken_piece_num) {
    //             $piece->available_num -= $request->broken_piece_num;
    //         } else {
    //             $piece->available_num = 0;
    //         }
    //         $piece->save();
    //         return response()->json($brokenPiece, 200);
    //     }
    // }
//     public function addToBrokenPieces(Request $request)
// {
//     try {
//         $request->validate([
//             'piece_id' => 'required|exists:pieces,id',
//             'broken_piece_num' => 'required|integer'
//         ]);

//         $piece = Piece::where(['id' => $request->piece_id])->first();
//         if ($piece) {
//             $brokenPiece = BrokenPieces::firstOrNew(['piece_id' => $request->piece_id]);
//             $brokenPiece->broken_piece_num = ($brokenPiece->broken_piece_num ?? 0) + $request->broken_piece_num;

//             $borrowNum = is_array($brokenPiece->borrow_diwan_num) ? $brokenPiece->borrow_diwan_num : [];
//             if (!in_array($request->borrow_diwan_num, $borrowNum)) {
//                 $borrowNum[] = $request->borrow_diwan_num;
//             }

//             $brokenPiece->borrow_diwan_num = $borrowNum;
//             $brokenPiece->broken_piece_note .= $request->broken_piece_note;
//             $brokenPiece->isFixed = false;
//             $brokenPiece->save();

//             if ($piece->available_num >= $request->broken_piece_num) {
//                 $piece->available_num -= $request->broken_piece_num;
//             } else {
//                 $piece->available_num = 0;
//             }
//             $piece->save();

//             return response()->json([
//                 'status' => true,
//                 'message' => 'تمت إضافة القطعة المعطلة بنجاح',
//                 'data' => $brokenPiece
//             ], 200);
//         } else {
//             return response()->json([
//                 'status' => false,
//                 'message' => 'القطعة المطلوبة غير موجودة'
//             ], 404);
//         }
//     } catch (\Exception $e) {
//         return response()->json([
//             'status' => false,
//             'message' => 'حدث خطأ أثناء إضافة القطعة المعطلة',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }
public function addToBrokenPieces(Request $request)
{
    try {
        $request->validate([
            'piece_id' => 'required|exists:pieces,id',
            'broken_piece_num' => 'required|integer|min:1',
            'borrow_diwan_num' => 'nullable|string',
            'broken_piece_note' => 'nullable|string'
        ]);

        $piece = Piece::find($request->piece_id);
        if (!$piece) {
            return response()->json([
                'status' => false,
                'message' => 'القطعة المطلوبة غير موجودة'
            ], 404);
        }

        $brokenPiece = BrokenPieces::firstOrNew(['piece_id' => $request->piece_id]);
        $brokenPiece->broken_piece_num = ($brokenPiece->broken_piece_num ?? 0) + $request->broken_piece_num;

        // تحديث رقم الديوان
        $borrowNum = is_array($brokenPiece->borrow_diwan_num) ? $brokenPiece->borrow_diwan_num : [];
        if ($request->borrow_diwan_num && !in_array($request->borrow_diwan_num, $borrowNum)) {
            $borrowNum[] = $request->borrow_diwan_num;
        }
        $brokenPiece->borrow_diwan_num = $borrowNum;

        // تحديث الملاحظات
        if ($request->broken_piece_note) {
            $brokenPiece->broken_piece_note = trim($brokenPiece->broken_piece_note . "\n" . $request->broken_piece_note);
        }

        $brokenPiece->isFixed = false;
        $brokenPiece->save();

        // تحديث عدد القطع المتوفرة
        if ($piece->available_num >= $request->broken_piece_num) {
            $piece->available_num -= $request->broken_piece_num;
        } else {
            return response()->json([
                'status' => false,
                'message' => 'عدد القطع المعطلة أكبر من المتوفر، لا يمكن التحديث.'
            ], 400);
        }

        $piece->save();

        return response()->json([
            'status' => true,
            'message' => 'تمت إضافة القطعة المعطلة بنجاح',
            'data' => $brokenPiece
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'حدث خطأ أثناء إضافة القطعة المعطلة.',
            'error' => $e->getMessage()
        ], 500);
    }
}


    public function removeBrokenPiece(Request $request)
    {
        $piece = Piece::where(['id' => $request->piece_id])->first();
        if ($piece) {
            if ($piece->Base_num <= 0) {
                if ($piece->image) {
                    Storage::disk('public')->delete($piece->image);
                }
                $piece->delete();
            }
            $brokenPiece = BrokenPieces::where('piece_id', $request->piece_id)->first();
            if ($brokenPiece) {
                $brokenPiece->delete();
            }
            return response()->json(['message' => 'تم حذف القطعة'], 200);
        }
    }

    public function editBrokenPiece(Request $request)
    {
        $request->validate([
            'piece_id' => 'required|exists:pieces,id',
            'broken_piece_num' => 'required|integer'
        ]);
        $brokenPiece = BrokenPieces::where('piece_id', $request->piece_id)->first();
        $oldnum = $brokenPiece->broken_piece_num;
        if ($brokenPiece) {
            $brokenPiece->update([
                'broken_piece_num' => $request->broken_piece_num,
                'broken_piece_note' => $request->broken_piece_note,
                'borrow_diwan_num' => $request->borrow_diwan_num,
                'isFixed' => false
            ]);
            $piece = Piece::findOrFail($request->piece_id);
            $newnum = $request->broken_piece_num ?? 0;
            if ($newnum > $oldnum) {
                $diff = $newnum - $oldnum;
                $piece->available_num = max($piece->available_num - $diff, 0);
            } elseif ($newnum < $oldnum) {
                $diff = $oldnum - $newnum;
                $piece->available_num += $diff;
            }

            $piece->save();
            return response()->json($brokenPiece, 200);
        }
    }

    public function repairBrokenPiece(Request $request)
    {
        $request->validate([
            'piece_id' => 'required|exists:pieces,id',
            'fixed_piece_num' => 'required|integer'
        ]);
        $brokenPiece = BrokenPieces::where('piece_id', $request->piece_id)->first();
        if ($brokenPiece) {
            $brokenPiece->fixed_piece_num = $request->fixed_piece_num;
            $brokenPiece->save();
            $brokenPiece->isFixed = $this->checkIsFixed($brokenPiece);
            if ($brokenPiece->isFixed) {
                $brokenPiece->delete();
            } else {
                $brokenPiece->broken_piece_num -= $brokenPiece->fixed_piece_num;
                $brokenPiece->save();
            }
        }
        return response()->json(['message' => 'تم توفير القطعة'], 200);
    }

    public function checkIsFixed($brokenPiece)
    {
        $piece = Piece::where('id', $brokenPiece->piece_id)->first();
        if ($brokenPiece->fixed_piece_num > 0) {
            if ($brokenPiece->broken_piece_num == $brokenPiece->fixed_piece_num) {
                $brokenPiece->isFixed = true;
            } elseif ($brokenPiece->broken_piece_num > $brokenPiece->fixed_piece_num) {
                $brokenPiece->isFixed = false;
            }
            $piece->available_num += $brokenPiece->fixed_piece_num;
            $piece->save();
        }
        return $brokenPiece->isFixed;
    }
}

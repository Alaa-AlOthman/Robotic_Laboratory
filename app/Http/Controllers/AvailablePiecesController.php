<?php

namespace App\Http\Controllers;

use App\Models\BrokenPieces;
use App\Models\LostPieces;
use App\Models\Piece;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AvailablePiecesController extends Controller
{
    public function getAllAvailablePiecesWithPaginate()
    {
        try {
            $pieces = Piece::paginate(5);
            $pieces->through(function ($piece) {
                $piece->image_url = "http://localhost/Robotic/BACKEND/project/storage/app/public/$piece->image";
                return $piece;
            });
            return response()->json($pieces, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'فشل تحميل البيانات'], 500);
        }
    }

    public function getAllAvailablePiecestoExport()
    {
        try {
            $pieces = Piece::select('id', 'piece_name', 'Base_num', 'available_num', 'description', 'piece_type', 'code')->get();
            return response()->json($pieces, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'فشل في التصدير'], 500);
        }
    }

    public function getAllAvailablePieces()
    {
        try {
            $pieces = Piece::all();
            $pieces->map(function ($piece) {
                $piece->image_url = "http://localhost/Robotic/BACKEND/project/storage/app/public/$piece->image";
                return $piece;
            });
            return response()->json($pieces, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ أثناء تحميل القطع'], 500);
        }
    }

    public function searchAvailablePieces(Request $request)
    {
        try {
            $query = Piece::query();

            if ($request->has('piece_name') && !empty($request->piece_name)) {
                $query->whereRaw('LOWER(piece_name) LIKE ?', ['%' . strtolower($request->piece_name) . '%']);
            }

            if ($request->has('piece_type') && !empty($request->piece_type)) {
                $query->where('piece_type', $request->piece_type);
            }

            $pieces = $query->paginate(5);

            $pieces->getCollection()->transform(function ($piece) {
                $piece->image_url = "http://localhost/Robotic/BACKEND/project/storage/app/public/$piece->image";
                return $piece;
            });
            return response()->json($pieces, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'فشل البحث، تحقق من مدخلاتك'], 500);
        }
    }

    public function addAvailablePiece(Request $request)
    {
        try {
            $validate = $request->validate([
                'piece_name' => 'required|string',
                'Base_num' => 'required|integer',
                'piece_type' => 'required',
                'image' => 'required|image|mimes:jpg,png,jpeg,svg,webp|max:2048'
            ]);
            $avnum = $request->Base_num;
            if ($request->available_num) {
                $avnum = $request->available_num;
            }
            $file = $request->file('image');
            $imgname = $file->getClientOriginalName();
            $path = $file->storeAs('images', $imgname, 'public');
            //    $data=file_get_contents($file->getRealPath());
            $piece = new Piece([
                'piece_name' => $request->piece_name,
                'Base_num' => $request->Base_num,
                'piece_type' => $request->piece_type,
                'image' => $path,
                'available_num' => $avnum,
                'code' => $request->code,
                'description'=>$request->description,
            ]);
            $piece->save();
            //    $this->getAllAvailablePieces();

            return response()->json($piece, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'البيانات المدخلة غير صالحة'], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'فشل في إضافة القطعة'], 500);
        }
    }

    public function EditeAvailablePiece(Request $request)
    {
        try {
            $validate = $request->validate([
                'piece_name' => 'required|string',
                'Base_num' => 'required|integer',
                'piece_type' => 'required',
            ]);

            $piece = Piece::query()->where(['id' => $request->id])->first();
            if (!$piece) {
                return response()->json(['error' => 'القطعة غير موجودة'], 404);
            }

            $newpname = $request->piece_name ?? $piece->piece_name;
            $newBasenum = $request->Base_num ?? $piece->Base_num;
            $newAvnum = $request->available_num ?? $newBasenum;
            $newptype = $request->piece_type ?? $piece->piece_type;

            $path = $piece->image;
            if ($request->file('image')) {
                Storage::disk('public')->delete($piece->image);
                $file = $request->file('image');
                $imgname = $file->getClientOriginalName();
                $path = $file->storeAs('images', $imgname, 'public');
            }

            $piece->piece_name = $newpname;
            $piece->Base_num = $newBasenum;
            $piece->piece_type = $newptype;
            $piece->image = $path;
            $piece->available_num = $newAvnum;
            $piece->description=$request->description;
            $piece->code = $request->input('code', 0);

            $piece->update();

            return response()->json($piece, 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'البيانات المدخلة غير صالحة'], 422);
        }
    }

    // public function EditeAvailablePiece(Request $request)
    // {
    //     $validate = $request->validate([
    //         'piece_name' => 'required|string',
    //         'Base_num' => 'required|integer',
    //         'piece_type' => 'required',
    //         // 'image'=>'required|image|mimes:jpg,png,jpeg,svg|max:2048'
    //     ]);
    //     $piece = Piece::query()->where(['id' => $request->id])->first();
    //     if ($piece) {
    //         if ($request->piece_name)
    //             $newpname = $request->piece_name;
    //         if ($request->Base_num) {
    //             $newBasenum = $request->Base_num;
    //             $newAvnum = $request->Base_num;
    //         }
    //         if ($request->piece_type)
    //             $newptype = $request->piece_type;
    //         if ($request->available_num)
    //             $newAvnum = $request->available_num;

    //         $path = $piece->image;
    //         if ($request->file('image')) {
    //             Storage::disk('public')->delete($piece->image);
    //             $file = $request->file('image');
    //             $imgname = $file->getClientOriginalName();
    //             $path = $file->storeAs('images', $imgname, 'public');
    //         }

    //         //    $data=file_get_contents($file->getRealPath());
    //         $newcode = $request->input('code', 0);
    //         $piece->piece_name = $newpname;
    //         $piece->Base_num = $newBasenum;
    //         $piece->piece_type = $newptype;
    //         $piece->image = $path;
    //         $piece->available_num = $newAvnum;
    //         $piece->code = $newcode;

    //         $piece->update();
    //         return response()->json($piece, 200);
    //     }
    // }

    public function deleteAvailablePiece(Request $request)
    {
        $piece = Piece::query()->where(['id' => $request->id])->first();
        if ($piece) {
            if ($piece->image) {
                Storage::disk('public')->delete($piece->image);
            }
        }
        $piece->delete();
        return response()->json($piece, 200);
    }

    public function getPieceDetails(Request $request)
    {
        $id = $request->piece_id;
        $piece = Piece::findOrFail($id);
        $piece->image_url = "http://localhost/Robotic/BACKEND/project/storage/app/public/{$piece->image}";

        // عدد المعطوب
        $broken = BrokenPieces::where('piece_id', $id)->first();
        $brokenCount = $broken?->broken_piece_num ?? 0;

        // عدد الناقص
        $lost = LostPieces::where('piece_id', $id)->first();
        $lostCount = $lost?->requested_piece_num ?? 0;

        // كل طلبات الاستعارة المرتبطة بالقطعة
        $borrowList = DB::table('borrow_request_piece')
            ->join('borrow_request', 'borrow_request.id', '=', 'borrow_request_piece.borrow_request_id')
            ->where('piece_id', $id)
            ->select(
                'borrow_request.diwan_num',
                'borrow_request.diwan_date',
                'borrow_request_piece.requested_pieces_num',
                'borrow_request_piece.delivered_pieces_num',
                'borrow_request_piece.piece_recieve_num'
            )
            ->get();

        // كل محاضر التركيب المرتبطة بالقطعة
        $compoundList = DB::table('compound_pieces')
            ->join('compounds', 'compound_pieces.compound_id', '=', 'compounds.id')
            ->where('piece_id', $id)
            ->select(
                'compounds.compound_name',
                'compounds.diwan_num',
                'compounds.diwan_date',
                'compound_pieces.pieces_num'
            )
            ->get();

        return response()->json([
            'piece' => $piece,
            'image_url' => $piece->image_url,
            'broken_count' => $brokenCount,
            'lost_count' => $lostCount,
            'borrow_list' => $borrowList,
            'compound_list' => $compoundList,
        ]);
    }
}

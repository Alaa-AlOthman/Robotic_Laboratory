<?php
namespace App;

use App\Models\BorrowRequestPiece;
use App\Models\Piece;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;

class BorrowRequestPiecesImport  implements ToCollection
{
    protected $borrowRequestId;

    public function __construct($borrowRequestId)
    {
        $this->borrowRequestId = $borrowRequestId;
    }

    public function collection(Collection $rows)
    {
        $headers = $rows->first(); // أول صف هو رؤوس الأعمدة
        $rows->shift(); // حذف الرؤوس

        // نحاول نعرف مواقع الأعمدة المطلوبة
        $pieceIdIndex = $headers->search('piece_id');
        $quantityIndex = $headers->search('quantity');

        if ($pieceIdIndex === false || $quantityIndex === false) {
            Log::error('Excel columns missing.');
            return;
        }

        foreach ($rows as $row) {
            $pieceId = $row[$pieceIdIndex];
            $quantity = $row[$quantityIndex];

            if (!$pieceId || !$quantity) continue;

            $piece = Piece::find($pieceId);
            if (!$piece) continue;

            BorrowRequestPiece::updateOrCreate(
                [
                    'borrow_request_id' => $this->borrowRequestId,
                    'piece_id' => $pieceId
                ],
                [
                    'requested_pieces_num' => $quantity,
                    'delivered_pieces_num' => 0,
                    'piece_deliver_state' => 'ND',
                    'piece_recieve_state' => 'NR',
                    'piece_deliver_note' => '',
                ]
            );
        }
    }
}


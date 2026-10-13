import axios from "axios";
const deletePieceFromBorrowRequest=async(borrowId,pieceId)=>{
    var success=false;
    try {
        const res = await axios.post('/deletePieceFromBorrowRequest', { borrow_request_id: borrowId, piece_id:pieceId});
        //   console.log(res)
       success=true;
    } catch (e) {
        console.log(e);
    }
    return success;
};
export default deletePieceFromBorrowRequest;
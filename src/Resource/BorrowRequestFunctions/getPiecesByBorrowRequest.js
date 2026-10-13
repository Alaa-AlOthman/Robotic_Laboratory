import axios from "axios"
import pieceDeliverStateFormat from "../FormatFunctions/pieceDeliverStateFormat";

const getPiecesByBorrowRequest=async(borrowId,page)=>{
    var borrowData=[];
    try {
        const res = await axios.post(`/getPiecesByBorrowRequest?page=${page}`,{id:borrowId});
        console.log(res);
         borrowData=res.data;
         console.log(borrowData)
         // borrowData=Object.entries(borrowData);
         // console.log(typeof(borrowData)+' '+borrowData.data)
        borrowData.data.forEach(borrow => {
           borrow['piece_deliver_state_ar']=pieceDeliverStateFormat( borrow['piece_deliver_state']);
        });
      
     }
     catch (e) {
        console.log(e);
     }
     
     return borrowData;
    }
    
  export default getPiecesByBorrowRequest
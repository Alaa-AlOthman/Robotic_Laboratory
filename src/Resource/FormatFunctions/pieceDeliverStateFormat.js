const pieceDeliverStateFormat=(deliverState)=>{
    var state="";
    switch (deliverState) {
      case 'DA': { state = 'استلم الكل ' }
        break;
      case 'PD': { state = 'تسليم جزئي' }
        break;
      case 'ND': { state = 'لم يستلم الطلاب' }
        break;
  }return state  
    }
  export default pieceDeliverStateFormat
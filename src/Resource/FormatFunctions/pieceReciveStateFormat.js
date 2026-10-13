const pieceReciveStateFormat=(reciveState)=>{
    var state="";
    switch (reciveState) {
      case 'RA': { state = 'تم استلام الكل ' }
        break;
      case 'PR': { state = 'تم استلام جزء' }
        break;
      case 'NR': { state = 'لم يتم الاستلام' }
        break;
      case 'BR':{state='القطعة معطلة'}
      break;
  }return state  
    }
  export default pieceReciveStateFormat
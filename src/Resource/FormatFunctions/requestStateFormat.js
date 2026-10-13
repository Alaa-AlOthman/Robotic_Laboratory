const requestStateFormat=(requestState)=>{
    var state="";
    switch (requestState) {
        case 'IRFS': { state = 'استلام من الطلاب' }
        break;
      case 'IDTS': { state = 'استلم الطلاب' }
        break;
      case 'PD': { state = 'تسليم جزئي' }
        break;
      case 'ND': { state = 'لم يستلم الطلاب' }
        break;
  }return state
    }
  export default requestStateFormat
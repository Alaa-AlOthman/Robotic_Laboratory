const borrowRequest={
    namespaced: true,
    state:{
        borrowReq:{},
        piecesInBorrow:{},
         pagination: {
                current_page: 1,
                last_page: 1,
                total: 0
            },
    },
    getters:{
        borrowReq(state){
            return state.borrowReq;
        },
        piecesInBorrow(state){
            return state.piecesInBorrow;
        },
        pagination(state){
        return state.pagination;
        }
    },
    mutations:{
        setBorrowRequest(state,borrow){
            state.borrowReq=borrow;
        },
        setPiecesInBorrow(state,pieces){
            state.piecesInBorrow=pieces;
        },
        setPagination(state,pagination){
            state.pagination=pagination;
        }
    },
    actions:{}


};
export default borrowRequest;
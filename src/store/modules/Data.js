const Data = {
    namespaced: true,
    state: {
        allAvailablePieces: {},
        allBrokenPieces: {},
        allLostPieces: {},
    },
    getters: {
        allAvailablePieces(state) {
            return state.allAvailablePieces;
        },
        allBrokenPieces(state) {
            return state.allBrokenPieces;
        },
        allLostPieces(state) {
            return state.allLostPieces;
        },
    },
    mutations: {
        setAllAvailablePieces(state, pieces) {
            state.allAvailablePieces = pieces;
        },
        setAllBrokenPieces(state, pieces) {
            state.allBrokenPieces = pieces;
        },
        setAllLostPieces(state, pieces) {
            state.allLostPieces = pieces;
        },
    },
    actions: {},
};
export default Data;

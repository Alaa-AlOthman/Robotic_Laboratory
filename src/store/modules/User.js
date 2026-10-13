const User = {
    namespaced: true,
    state: {
        username: '',
        role: '',
        team:{},
        Authenticated: false
    },
    getters: {
        username(state) {
            return state.username;
        },
        role(state) {
            return state.role;
        },
        Authenticated(state) {
            return state.Authenticated;
        },team(state){
            return state.team;
        }
    },
    mutations: {
        setUserName(state, usn) {
            state.username = usn;
        },
        setRole(state, r) {
            state.role = r;
        },
        setAuthenticated(state, auth) {
            state.Authenticated = auth;
        },setTeam(state,team){
            state.team=team;
        }

    }, actions: {}
};
export default User;
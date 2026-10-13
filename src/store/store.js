import { createStore } from "vuex";
import User from "./modules/User";
import borrowRequest from "./modules/borrowRequest";
import Data from "./modules/Data";

const store = createStore({
  state() {
    return {
      homeAnimation: false,
      homeAnimation2: false,
      successLogin:false,
     
    };
  },
  getters: {
    homeAnimation(state) {
      return state.homeAnimation;
    },
    homeAnimation2(state) {
      return state.homeAnimation2;
    },
    successLogin(state){
      return state.successLogin;
    }

  },
  mutations: {
    setHomeAnimation(state, ha) {
      state.homeAnimation = ha;
    },
    setHomeAnimation2(state, ha) {
      state.homeAnimation2 = ha;
    },
    setSuccessLogin(state,s){
      state.successLogin=s;
    }
  },
  actions: {},
  modules:{
    User:User,
    borrowRequest:borrowRequest,
    Data:Data
  }
 
});

export default store;

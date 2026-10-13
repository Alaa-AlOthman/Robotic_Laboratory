<template lang="">
  <div class="backdrop1 " >
    <div class="bg-secondary-bg px-0 mx-auto  flex space-y-3 rounded-lg flex-col items-center my-40  border-3 md:w-96 w-3/4">
     <div class="bg-secondary-button rounded-t-lg flex justify-start space-x-28  py-1 font-semibold text-center md:text-2xl text-xl  w-full">
        <div id="icon" class="flex flex-col items-center  justify-center pl-2">
              <img src="../assets/image/icon.png" class=" " alt="" />
          </div>
          <div class=" flex flex-col items-center text-center justify-center  " >
            <p class="text-center ">تسجيل  الدخول</p>
          </div>
       
          </div>
          <form> 
     <div class=" w-full  mx-auto px-4 pt-2">
        <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
        <input type="text" id="name" v-model="form.username"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9   text-lg md:w-3/5 w-1/2  px-4 ">
        <label for="name" class=" mx-5  md:text-2xl ">اسم المستخدم</label>
     </div></div>
     <div class=" w-full  mx-auto px-4 pb-0 mt-2 ">
        <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
        <input type="password" id="pass" v-model="form.password"  dir="auto" autocomplete  class=" bg-primary text-primary-bg rounded-lg  text-lg  h-9  md:w-3/5 w-1/2  px-4 ">
        <label for="pass" class=" mx-5  md:text-2xl ">كلمة السر</label>
     </div>
    </div>
     </form>
    <div class=" w-full  mx-auto px-4 pb-2 ">
        <div class="flex-col justify-center space-x-3 items-center  px-3 py-1 text-xl ">
        <div class="text-red-900 text-shadow-lg   text-center pb-1  text-2xl" v-if="msg"><p>{{ msg }}</p></div>
          <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="handle()" :disabled="!showB">دخول</button>
         

     </div>
    </div>
    </div>
   
  </div>
</template>
<script>
import { mapGetters } from 'vuex'
import { inject, watchEffect } from 'vue';
import { ref, reactive } from '@vue/reactivity';
import axios from "axios";
export default {

  data() {
    return {
      success: false
    }
  }, setup() {
    const form = reactive({
      username: '',
      password: ''
    });
    const showB = ref(false);
    const msg = ref('');
    watchEffect(() => {

      if (form.username == '' || form.password == '') {
        showB.value = false
      }
      else { showB.value = true }
    })
    return { form, showB, msg }
  }, methods: {
    async handle() {
      console.log(this.form.username);
      try {
        const res = await axios.post("/login", { name: this.form.username, password: this.form.password });
        localStorage.setItem('token', res.data.token)
        console.log(res);
        this.success = true;
        this.msg = '';
        this.$emit("success", this.success);
        this.$store.commit('setSuccessLogin', this.success);
        this.$store.commit('setHomeAnimation', this.success);
        try {
          const res = await axios.get('/user');
          console.log(res)
          var user = res.data.user;
          this.$store.commit('User/setUserName', user.name);
          this.$store.commit('User/setRole', user.role);
          if(user.role=='user'){
            console.log(user)
               this.$store.commit('User/setTeam', user.team);
              this.$router.push(`/StudentDashboard/${user.team.id}`);
          }


        } catch (error) {
          console.log(error)
        }
      } catch (e) {
        console.log(e);
        // if (e.data.error == "Invalid credentials") {
          this.msg = e.response.data.error;
        // }
      }
    }

  }, computed: {
    ...mapGetters(["successLogin"]),
  },
};
</script>
<style>
#icon {
  width: 40px;
  height: 30px;
}
</style>

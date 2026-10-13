<template lang="">
    <div class="backdrop1" >
      <div class="bg-secondary-bg px-0 mx-auto  flex space-y-3 rounded-lg flex-col items-center my-32  border-3 w-96">
 
          <div class="w-full flex justify-end mr-5 mt-2">
                <img src="@/assets/image/close.png" class="w-6 h-8 cursor-pointer" @click="close()" alt="" />
          </div>
        <div>
         
       <div class=" w-full  mx-auto px-4 pt-2">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <input type="number" id="diwan_num"  v-model="diwan_num" dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="diwan_num" class=" mx-5  text-2xl">رقم الديوان</label>
       </div></div>
       <div class=" w-full  mx-auto px-4 py-2">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <input type="date" id="diwan_date"  v-model="diwan_date" dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="diwan_date" class=" mx-5  text-2xl"> تاريخ الديوان</label>
       </div></div>
         <div class=" w-full  mx-auto px-4 py-2">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <select id="tname" v-model="team"  dir="auto"   class=" bg-primary text-primary-bg text-center rounded-lg  h-9  w-3/5  px-4 ">
                        <option :value="team" v-for="(team, index) in teams" :key="index">{{ team.team_name }}</option>
             </select>
          <label for="tname" class=" mx-5  text-2xl">اسم الفريق</label>
       </div></div>
         <div class=" w-full  mx-auto px-4 pb-1">
            <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <input type="text" id="request_goal" v-model="request_goal"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
            <label for="request_goal" class=" mx-5  text-2xl">الغرض</label>
         </div></div>
      </div>

   
      <div class=" w-full  mx-auto px-4 pb-2 " >
          <div class="flex justify-between space-x-3 items-center  px-3 py-1 text-xl ">
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="handle()" >تعديل</button>
       </div>
      </div>
      </div>
          <transition name="toast">
    <Toast v-if="shtoast" :msg="msg"></Toast>
  </transition>
    </div>
  </template>
<script>
import axios from 'axios';
import Toast from '@/components/Toast.vue';
export default {
  components:{Toast},
  props: ['borrow', 'teams'],
  data() {
    return {
      diwan_num: '',
      diwan_date: '',
      team: {},
      request_goal: ' ',
       msg: '',
         shtoast: false
    }
  }, methods: {

    close() {
      this.$emit('editOrder', false)
    }, async handle() {
      try {
        //['diwan_num','diwan_date','request_goal','team_id','request_type','request_state','request_deliver_date','request_deliver_note','request_receive_date','request_receive_note'] 
        const res = await axios.post("/updateBorrowRequest", { id: this.borrow.id, team_id: this.team.id, request_goal: this.request_goal, diwan_num: this.diwan_num, diwan_date: this.diwan_date });

        // this.borrowrequests=res.data;

        console.log(res);
   this.msg=res.data.message;
                 this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);

            } catch (e) {
                console.log(e)
                  this.msg = e.response.data.message;
                this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);
            }
    }
  }, mounted() {
    this.team = this.borrow.team;
    this.request_goal = this.borrow.request_goal;
    this.diwan_date = this.borrow.diwan_date;
    this.diwan_num = this.borrow.diwan_num;
  }


}
</script>
<style lang="">

</style>
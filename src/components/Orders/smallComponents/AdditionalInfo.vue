<template lang="">
    <div class="backdrop1" >
      <div class="bg-secondary-bg px-0 mx-auto  flex space-y-3 rounded-lg flex-col items-center my-32  border-3 w-96">
 
          <div class="w-full flex justify-end mr-5 mt-2">
                <img src="@/assets/image/close.png" class="w-6 h-8 cursor-pointer" @click="close()" alt="" />
          </div>
        <div v-if="counter==1">
         
       <div class=" w-full  mx-auto px-4 pt-2">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <select v-if="sec=='neworder'"  id="team_name" v-model="team"  dir="auto"   class=" bg-primary text-primary-bg text-center rounded-lg  h-9  w-3/5  px-4 ">
                        <option :value="team" v-for="(team, index) in teams" :key="index">{{ team.team_name }}</option>
             </select>
               <input type="text" v-else id="team_name" disabled  v-model="team.team_name" dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="team_name" class=" mx-5  text-2xl">اسم الفريق</label>
       </div></div>
       <div class=" w-full  mx-auto px-4 py-2">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <select v-if="sec=='neworder'" id="team_members" v-model="team"   dir="auto"   class=" bg-primary text-primary-bg text-center rounded-lg  h-9  w-3/5  px-4 ">
                        <option :value="team" v-for="(team, index) in teams" :key="index">{{ team.team_members }}</option>
            </select>
              <input v-else type="text" id="team_members" disabled  v-model="team.team_members" dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="team_members" class=" mx-5  text-2xl">أعضاء الفريق</label>
       </div></div>
       <div class=" w-full  mx-auto px-4 pb-2">
            <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <input type="text" id="team_leader" disabled  v-model="team.team_leader" dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
            <label for="team_leader" class=" mx-5  text-2xl">مدرب الفريق</label>
         </div></div>
      </div>
    
      <div class=" w-full  mx-auto px-4 pb-2 " >
          <div class="flex-col justify-between space-x-3 items-center  px-3 py-1 text-xl ">
           <div class="text-green-600 text-shadow-lg   text-center pb-1  text-2xl"  v-if="msg"><p>{{ msg }}</p></div>
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="handle()" :disabled="sec!='neworder'" >إضافة</button>
       </div>
      </div>
      </div>
     
    </div>
  </template>
<script>
import axios from 'axios';
import { mapGetters } from 'vuex';
export default {
  props:['sec'],
  data() {
    // ['team_name','team_members','teams_goal','team_leader']
    // ['diwan_num','diwan_date','request_goal','team_id','request_type','request_state','request_deliver_date','request_deliver_note','request_receive_date','request_receive_note'] 
    return {
      counter: 1,
      team_name: '',
      team_members: '',
      team_leader: '',
      teams:[],
      team:{},
      additionalData:{},
      msg:''
    }
  }, methods: {
    handle() { 
      this.additionalData.team=this.team;
      this.msg="تمت إضافة الفريق"
      // this.additionalData.request_deliver_date=this.request_deliver_date;
      // this.additionalData.request_deliver_note=this.request_deliver_note;
      // console.log(this.additionalData)
    },
    close() {
      this.$emit('additional', false,this.additionalData)
    }, async loadTeams() {
      try {
        const res = await axios.get("/getAllTeam");
        console.log(res);
        this.teams = res.data;
      }
      catch (e) {
        console.log(e);
      }
    },async getTeam(){
      try {
        const res=await axios.post('/getTeam',{team_id:this.borrowReq.team_id});
        console.log(res);
        this.team=res.data;
      } catch (error) {
        console.log(error);
      }
    }
  },mounted(){
    if(this.sec=='neworder'){
    this.loadTeams();
  }else{
     this.request_deliver_date=this.borrowReq.request_deliver_date;
     this.request_deliver_note=this.borrowReq.request_deliver_note;
     this.getTeam();
  }
  },computed:{
        ...mapGetters('borrowRequest',['borrowReq','piecesInBorrow','pagination'])
    }


}
</script>
<style lang="">

</style>
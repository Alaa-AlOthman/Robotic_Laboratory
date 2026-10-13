<template lang="">
    <div class="backdrop1" >
      <div class="bg-secondary-bg px-0 mx-auto  flex space-y-3 rounded-lg flex-col items-center my-32  border-3 w-96">
 
          <div class="w-full flex justify-end mr-5 mt-2">
                <img src="@/assets/image/close.png" class="w-6 h-8 cursor-pointer" @click="close()" alt="" />
          </div>
        <div>
         
       <div class=" w-full  mx-auto px-4 pt-2">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <input type="text" id="team_name"  v-model="team_name" dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="team_name" class=" mx-5  text-2xl">اسم الفريق</label>
       </div></div>
       <div class=" w-full  mx-auto px-4 py-2">
        <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <input type="text" id="team_leader"  v-model="team_leader" dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="team_leader" class=" mx-5  text-2xl">اسم المدرب</label>
       </div>
       </div>
       <div class=" w-full  mx-auto px-4 py-2">
            <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
                <textarea id="team_members" v-model="team_members"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 "></textarea>
            <label for="team_members" class=" mx-5  text-2xl">أعضاء الفريق</label>
         </div></div>
         <div class=" w-full  mx-auto px-4 pb-1">
            <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <input type="text" id="teams_goal" v-model="teams_goal"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
            <label for="teams_goal" class=" mx-5  text-2xl"> الغرض</label>
         </div></div>
      </div>
    
      <div class=" w-full  mx-auto px-4 pb-2 "  >
          <div class="flex justify-start space-x-3 items-center  px-3 py-1 text-xl ">
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="handle" >{{sec=='add'?'إضافة':'تعديل'}}</button>
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
 import Toast from '../Toast.vue';
export default {
   props: ['sec', 'team'],
   data() {
      return {
         team_name: '',
         team_leader: '',
         team_members: '',
         teams_goal: '',
         updated: false,
         msg: '',
         shtoast: false
      }
   }, methods: {

      // ['team_name','team_members','teams_goal','team_leader']
      async handle() {
         //add team

         if (this.sec == 'add') {
            try {
               const res = await axios.post("/addTeam", { team_name: this.team_name, team_leader: this.team_leader, team_members: this.team_members, teams_goal: this.teams_goal });
               console.log(res);
               this.updated = true;
               this.msg=res.data.message;
                 this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);
            } catch (e) {
               console.log(e);
                this.msg = e.response.data.message;
                this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);
            }
         }
         //edite team
         if (this.sec == 'edit') {
            try {
               const res = await axios.post("/editTeam", { id: this.team.id, team_name: this.team_name, team_leader: this.team_leader, team_members: this.team_members, teams_goal: this.teams_goal });
               console.log(res);
               this.updated = true;
               this.msg=res.data.message;
                 this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);
            } catch (e) {
               console.log(e);
                this.msg = e.response.data.error;
                this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);
            }
         }
      },
      close() {
         this.$emit('addTeam', false, this.sec, this.updated)
      }
   }, mounted() {
      if (this.team) {
         this.team_name = this.team.team_name;
         this.team_members = this.team.team_members;
         this.team_leader = this.team.team_leader;
         this.teams_goal = this.team.teams_goal;
      }
   },components:{Toast}


}
</script>
<style lang="">

</style>
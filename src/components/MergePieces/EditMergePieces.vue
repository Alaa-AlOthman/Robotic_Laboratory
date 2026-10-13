<template lang="">
    <div class="backdrop1" >
      <div class="bg-secondary-bg px-0 mx-auto  flex space-y-3 rounded-lg flex-col items-center my-32  border-3 w-96">
 
          <div class="w-full flex justify-end mr-5 mt-2">
                <img src="@/assets/image/close.png" class="w-6 h-8 cursor-pointer" @click="close()" alt="" />
          </div>
        <div >
         
       <div class=" w-full  mx-auto px-4 pt-2">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <input type="text" id="compound_name"  v-model="compound_name" dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="compound_name" class=" mx-5  text-2xl">اسم المحضر</label>
       </div></div>
     
       <div class=" w-full  mx-auto px-4 py-2 ">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <input type="number" id="diwan_num" v-model="diwan_num"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="diwan_num" class=" mx-5  text-2xl"> رقم الديوان</label>
       </div>
      </div>
      <div class=" w-full  mx-auto px-4 pb-1 ">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <input type="date" id="diwan_date"  v-model="diwan_date" dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
            <label for="diwan_date" class=" mx-5  text-2xl">التاريخ</label>
       </div>
      </div>
       </div>
    
      <div class=" w-full  mx-auto px-4 pb-2 " >
          <div class="flex justify-start space-x-3 items-center  px-3 py-1 text-xl ">
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="editeCompound" >تعديل</button>
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
  components:{Toast},
  // ['compound_id','diwan_num', 'diwan_date', 'compound_name']; 
  props: ['compound'],
  data() {
    return {
      compound_name: '',
      diwan_date: '',
      diwan_num: '',
      edited:false,
       msg: '',
         shtoast: false
    }
  }, methods: {
    async editeCompound() {
      try {
        const res = await axios.post('/editeCompound',
         {compound_id:this.compound.id,diwan_num:this.diwan_num,diwan_date:this.diwan_date,compound_name:this.compound_name});
         this.edited=true;
         console.log(res);
          this.msg = res.data.message;
        this.shtoast = true
        setTimeout(() => { this.shtoast = false }, 3000);

      } catch (e) {
        console.log(e)
        this.msg = e.response.data.message;
        this.shtoast = true
        setTimeout(() => { this.shtoast = false }, 3000);
      }
    },
    close() {
      this.$emit('editeMergePieces', false,this.edited)
    }
  },mounted(){
    this.diwan_date=this.compound.diwan_date;
    this.diwan_num=this.compound.diwan_num;
    this.compound_name=this.compound.compound_name;
  }


}
</script>
<style lang="">

</style>
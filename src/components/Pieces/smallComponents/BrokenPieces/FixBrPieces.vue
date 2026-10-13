<template lang="">
    <div class="backdrop1" >
      <div class="bg-secondary-bg px-0 mx-auto  flex space-y-3 rounded-lg flex-col items-center my-32  border-3 w-96">
 
          <div class="w-full flex justify-end mr-5 mt-2">
                <img src="@/assets/image/close.png" class="w-6 h-8 cursor-pointer" @click="close()" alt="" />
          </div>
          <div class=" w-full  mx-auto px-4 pb-2 ">
          <div class="flex flex-col    px-3 py-1 text-2xl space-y-2 ">
          <p class="text-right">: هل تم إصلاح القطعة</p>
          <p class="text-center">{{piece.piece.piece_name}}</p>
      
          <div class=" w-full  mx-auto  pt-2 ">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <input type="number" id="fixed_piece_num" v-model="fixed_piece_num"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="fixed_piece_num" class=" mx-5  text-2xl"> العدد</label>
       </div>
      </div> </div>
          </div>
      <div class=" w-full  mx-auto px-4 pb-2 ">
          <div class="flex-col justify-between space-x-3 items-center  px-3 py-1 text-xl ">
           <div class="text-green-600 text-shadow-lg   text-center pb-1  text-2xl" v-if="msg"><p>{{ msg }}</p></div>
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="repairBrokenPiece()" >إصلاح</button>
       </div>
      </div>
      </div>
     
    </div>
  </template>
<script>
import axios from 'axios';


export default {
  props:['piece'],
   data(){
    return{
      fixed_piece_num:'',
      msg:''
    }
   },methods:{
    // piece_id
    async repairBrokenPiece(){
      try {
        const res=await axios.post('/repairBrokenPiece',{piece_id:this.piece.piece_id,fixed_piece_num:this.fixed_piece_num});
        console.log(res)
        this.msg="تم إصلاح القطعة"
      } catch (error) {
        console.log(error);
         this.msg=""   
            }
    },
    close(){
      this.$emit('fixPieces',false)
    }
   },
   
  
}
</script>
<style lang="">
    
</style>
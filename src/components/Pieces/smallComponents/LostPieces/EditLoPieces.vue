<template lang="">
    <div class="backdrop1" >
      <div class="bg-secondary-bg px-0 mx-auto  flex space-y-3 rounded-lg flex-col items-center my-32  border-3 w-96">
 
          <div class="w-full flex justify-end mr-5 mt-2">
                <img src="@/assets/image/close.png" class="w-6 h-8 cursor-pointer" @click="close()" alt="" />
          </div>
        <div>
         
       <div class=" w-full  mx-auto px-4 pt-2">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <label id="piece_name"   dir="auto"  class=" bg-primary  text-primary-bg rounded-lg    w-3/5  px-4 ">{{piece_name}}</label>
          <label for="piece_name" class=" mx-5  text-2xl">اسم القطعة</label>
       </div></div>
       <div class=" w-full  mx-auto px-4 py-2">
       <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <input type="text" id="borrow_diwan_num" v-model="borrow_diwan_num"  dir="auto"  class=" bg-primary text-center text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="borrow_diwan_num" class=" mx-5  text-2xl">طلب الإستعارة</label>
       </div>
      </div>
       <div class=" w-full  mx-auto px-4 py-2 ">
           <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <input type="number" id="requested_piece_num" v-model="requested_piece_num"  dir="auto"  class=" bg-primary text-center text-primary-bg rounded-lg  h-9  w-3/5  px-4 "></input>
            <label for="requested_piece_num" class=" mx-5  text-2xl">العدد المطلوب</label>
         </div>
      </div>
       <div class=" w-full  mx-auto px-4 pb-1 ">  
            <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <textarea id="lost_piece_note" v-model="lost_piece_note"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 "></textarea>
            <label for="lost_piece_note" class=" mx-5  text-2xl">ملاحظات</label>
        </div>
      </div>
   </div>

     
      <div class=" w-full  mx-auto px-4 pb-2 ">
          <div class="flex justify-between space-x-3 items-center  px-3 py-1 text-xl ">
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="editLostPiece()" >تعديل</button>
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
import Toast from "@/components/Toast.vue";
  //['requested_piece_num','lost_piece_note','borrow_diwan_num'];
  // ['piece_name','Base_num','available_num','description','image','code','piece_type'];
 export default {
   props:['lostPiece'],
   data(){
    return{
      piece_name:'',
      borrow_diwan_num:'',
      requested_piece_num:'',
      lost_piece_note:'',
        msg: '',
         shtoast: false
    }
   },methods:{
   async editLostPiece(){
      // this.borrow_diwan_num.split(",")
      try {
        console.log(this.lostPiece.piece_id)
        const res= await axios.post('/editLostPiece',{piece_id:this.lostPiece.piece_id,requested_piece_num:this.requested_piece_num,
          lost_piece_note:this.lost_piece_note,borrow_diwan_num:this.borrow_diwan_num.split(",")
        });
        console.log(res)
       this.msg = "تم تعديل القطعة بنجاح";
            this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);




         } catch (e) {
            console.log(e);
            this.msg = ''

         }
    },
    close(){
      this.$emit('editPieces',false)
    }
   },mounted(){
      this.piece_name=this.lostPiece.piece.piece_name;
      this.borrow_diwan_num=this.lostPiece.borrow_diwan_num.toString(),
      console.log(this.borrow_diwan_num)
      console.log(this.borrow_diwan_num.split(","))
      this.requested_piece_num=this.lostPiece.requested_piece_num;
      this.lost_piece_note=this.lostPiece.lost_piece_note;
      console.log(this.lostPiece)
      
   }, components: { Toast }
   
  
 }
 </script>
 <style lang="">
    
 </style>
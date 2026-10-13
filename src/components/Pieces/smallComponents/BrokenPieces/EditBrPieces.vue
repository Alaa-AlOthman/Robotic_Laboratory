<template lang="">
    <div class="backdrop1" >
      <div class="bg-secondary-bg px-0 mx-auto  flex space-y-3 rounded-lg flex-col items-center my-32  border-3 w-96">
 
          <div class="w-full flex justify-end mr-5 mt-2">
                <img src="@/assets/image/close.png" class="w-6 h-8 cursor-pointer" @click="close()" alt="" />
          </div>
        <div v-if="counter==1">
         
       <div class=" w-full  mx-auto px-4 pt-2">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <input type="text" id="piece_name"   v-model="brokenPiece.piece.piece_name" dir="auto"  disabled  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="piece_name" class=" mx-5  text-2xl">اسم القطعة</label>
       </div></div>
       <div class=" w-full  mx-auto px-4 py-2">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <input type="text" id="piece_type"  v-model="brokenPiece.piece.piece_type" dir="auto"  disabled class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 "> 
           
          <label for="piece_type" class=" mx-5  text-2xl">نوع القطعة</label>
       </div></div>
       <div class=" w-full  mx-auto px-4 py-2">
            <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <input type="number" id="code" v-model="brokenPiece.piece.code"  dir="auto" disabled  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
            <label for="code" class=" mx-5  text-2xl">الكود</label>
         </div></div>
                  <div class=" w-full  mx-auto px-4 pb-1">
            <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <input type="number" id="available_num" v-model="brokenPiece.piece.available_num" disabled  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
            <label for="available_num" class=" mx-5  text-2xl">العدد المتوفر</label>
         </div></div></div>
      <div v-if="counter==2">
         <div class=" w-full  mx-auto px-4 pt-2 ">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <input type="number" id="borrow_diwan_num" v-model="borrow_diwan_num"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="borrow_diwan_num" class=" mx-5  text-2xl">طلب الإستعارة</label>
       </div>
      </div>
         <div class=" w-full  mx-auto px-4 py-2">
            <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <input type="number" id="broken_piece_num" v-model="broken_piece_num"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
            <label for="broken_piece_num" class=" mx-5  text-2xl">العدد</label>
         </div></div>
         <div class=" w-full  mx-auto px-4 pb-1  ">
            <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <textarea id="broken_piece_note" v-model="broken_piece_note"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 "></textarea>
            <label for="broken_piece_note" class=" mx-5  text-2xl">ملاحظات</label>
         </div>
        </div></div>
      <div class=" w-full  mx-auto px-4 pb-2 "  v-if="counter==1">
          <div class="flex justify-start space-x-3 items-center  px-3 py-1 text-xl ">
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="()=>{counter++}" >التالي</button>
       </div>
      </div>
      <div class=" w-full  mx-auto px-4 pb-2 " v-if="counter==2">
          <div class="flex justify-between space-x-3 items-center  px-3 py-1 text-xl ">
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="editBrokenPiece()" >تعديل</button>
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="()=>{counter--}" >السابق</button>
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
//  ['piece_id','broken_piece_num','fixed_piece_num','broken_piece_note','borrow_diwan_num','isFixed']
export default {
   props: ['brokenPiece'],
   data() {
      return {
         counter: 1,
         borrow_diwan_num: '',
         broken_piece_num: '',
         broken_piece_note: '',
         msg: '',
         shtoast: false
      }
   }, methods: {
      async editBrokenPiece() {
         try {
            const res = await axios.post('/editBrokenPiece',
               {
                  piece_id: this.brokenPiece.piece_id, broken_piece_num: this.broken_piece_num, broken_piece_note: this.broken_piece_note,
                  borrow_diwan_num: this.borrow_diwan_num.split(",")
               });
            console.log(res);
            this.msg = "تم تعديل القطعة بنجاح";
            this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);




         } catch (e) {
            console.log(e);
            this.msg = ''

         }
      },
      close() {
         this.$emit('editPieces', false)
      }
   }, mounted() {
      this.borrow_diwan_num = this.brokenPiece.borrow_diwan_num.toString(),
         this.broken_piece_num = this.brokenPiece.broken_piece_num;
      this.broken_piece_note = this.brokenPiece.broken_piece_note;
   }, components: { Toast }


}
</script>
<style lang="">

 </style>
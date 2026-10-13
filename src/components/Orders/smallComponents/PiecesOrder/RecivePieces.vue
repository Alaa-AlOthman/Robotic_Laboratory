<template lang="">
    <div class="backdrop1" >
      <div class="bg-secondary-bg px-0 mx-auto  flex space-y-3 rounded-lg flex-col items-center my-32  border-3 w-96">
 
          <div class="w-full flex justify-end mr-5 mt-2">
                <img src="@/assets/image/close.png" class="w-6 h-8 cursor-pointer" @click="close()" alt="" />
          </div>
        <div >
         <div v-if="counter==1">
       <div class=" w-full  mx-auto px-4 pt-2">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <input type="text" id="piece_name"  v-model="piece_name" dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="piece_name" class=" mx-5  text-2xl">اسم القطعة</label>
       </div></div>
         <div class=" w-full  mx-auto px-4 py-2">
            <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
                <input type="number" id="piece_recieve_num" v-model="piece_recieve_num"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
            <label for="piece_recieve_num" class=" mx-5  text-2xl">العدد</label>
         </div></div>
         <div class=" w-full  mx-auto px-4 pb-2 ">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <select id="piece_recieve_state"   dir="auto" v-model="piece_recieve_state"  class=" bg-primary text-primary-bg text-center rounded-lg  h-9  w-3/5  px-4 ">
                        <option value="NR">لم يسلّم</option>
                        <option value="PR">سلّم جزء</option>
                        <option value="RA">سلّم الكل</option>
                     </select>
          <label for="piece_recieve_state" class=" mx-5  text-2xl">الحالة</label>
       </div></div></div>
       <div v-if="counter==2">
       <div class=" w-full  mx-auto px-4 pb-0 ">
          <div class="flex flex-col items-end space-x-3  border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <div class="flex items-center gap-2 my-1">
                <label class="toggle">
                    <input type="checkbox" v-model="broken">
                    <span class="slider round"></span>
                </label>
                <label for="broken" class=" mr-1 ml-4 text-xl">يوجد قطع معطلة</label>
            </div>
            <div class=" w-full  mx-auto  py-2" >
            <div class="flex justify-between space-x-3 items-center   text-xl ">
                <input type="number" id="broken_piece_num" v-model="broken_piece_num"  dir="auto" :disabled="!broken" class="disabled:brightness-90 bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
            <label for="broken_piece_num" class=" mx-5  text-2xl">العدد</label>
         </div></div>
         <div class=" w-full  mx-auto  py-1" >
            <div class="flex justify-between space-x-3 items-center   text-xl ">
                <textarea  id="broken_piece_note" v-model="broken_piece_note"  dir="auto"  class="disabled:brightness-90 bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 " :disabled="!broken"/>
            <label for="broken_piece_note" class=" mx-5  text-2xl">ملاحظات</label>
         </div></div>
       </div></div>
      </div></div>
      <div class=" w-full  mx-auto px-4 pb-2 "  v-if="counter==1">
          <div class="flex justify-start space-x-3 items-center  px-3 py-1 text-xl ">
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="()=>{counter++}" >التالي</button>
       </div>
      </div>
      <div class=" w-full  mx-auto px-4 pb-2 " v-if="counter==2">
          <div class="flex justify-between space-x-3 items-center  px-3 py-1 text-xl ">
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="recievePieceFromStudent()">إستلام</button>

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
import { mapGetters } from 'vuex';
import Toast from '@/components/Toast.vue';

export default {
   components: { Toast },
  props: ['piece'],
  data() {
    return {
      counter: 1,
      piece_name: '',
      piece_recieve_num: '',
      piece_recieve_state: '',
      broken: false,
      broken_piece_num: '',
      broken_piece_note: '',
      msg: '',
         shtoast: false
    }
  }, methods: {
    async recievePieceFromStudent() {
      try {
        const res = await axios.post('/recievePieceFromStudent',
          { borrow_request_id: this.piece.borrow_request_id, piece_id: this.piece.piece_id, piece_recieve_num: this.piece_recieve_num, piece_recieve_state: this.piece_recieve_state });
        if (this.broken) {
          this.addToBrokenPieces();
        }
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
    }, async addToBrokenPieces() {
      // piece_id , broken_piece_num , borrow_diwan_num ,  broken_piece_note
      try {
        const res = await axios.post('/addToBrokenPieces',
          { piece_id: this.piece.piece_id, broken_piece_num: this.broken_piece_num, broken_piece_note: this.broken_piece_note, borrow_diwan_num: this.borrowReq.diwan_num });
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
    }
    ,
    close() {
      this.$emit('recivePiecse', false)
    }
  }, mounted() {
    this.piece_name = this.piece.piece.piece_name;
    this.piece_recieve_num = this.piece.piece_recieve_num;
    this.piece_recieve_state = this.piece.piece_recieve_state;
  }, watch: {
    piece_recieve_num(newVal) {
      if (newVal == 0) {
        this.piece_recieve_state = "NR";
      }
      else if (newVal >= this.piece.delivered_pieces_num) {
        this.piece_recieve_state = "RA";
      } else if (newVal < this.piece.delivered_pieces_num) {
        this.piece_recieve_state = "PR";
      }

    }
  }, computed: {
    ...mapGetters('borrowRequest', ['borrowReq', 'piecesInBorrow']),
  }



}
</script>
<style lang="">

</style>
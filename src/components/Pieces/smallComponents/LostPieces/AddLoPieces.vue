<template lang="">
    <div class="backdrop1" >
      <div class="bg-secondary-bg px-0 mx-auto  flex space-y-3 rounded-lg flex-col items-center my-32  border-3 w-96">
 
          <div class="w-full flex justify-end mr-5 mt-2">
                <img src="@/assets/image/close.png" class="w-6 h-8 cursor-pointer" @click="close()" alt="" />
          </div>
        <div v-if="counter==1">
         
       <div class=" w-full  mx-auto px-4 pt-2">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <input type="text" id="piece_name"  v-model="piece_name" dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="piece_name" class=" mx-5  text-2xl">اسم القطعة</label>
       </div></div>
       <div class=" w-full  mx-auto px-4 py-2">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <select id="piece_type"   dir="auto" v-model="piece_type"  class=" bg-primary text-primary-bg text-center rounded-lg  h-9  w-3/5  px-4 ">
                        <option value="spike">spike</option>
                        <option value="EV3">EV3</option>
                        <option value="consume">consumables</option>
                        <option value="electronic">electronics</option>
                        <option value="other">Other</option>
                     </select>
          <label for="name" class=" mx-5  text-2xl">نوع القطعة</label>
       </div></div>
       <div class=" w-full  mx-auto px-4 py-2 ">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <input type="number" id="code" v-model="code"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="code" class=" mx-5  text-2xl">كود</label>
       </div></div>
       <div class=" w-full  mx-auto px-4 pb-1 ">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <input type="file" id="image" accept="image/png, image/gif, image/jpeg" @change="selectImg"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg py-1 h-10  w-3/5  px-1 ">
          <label for="image" class=" mx-5  text-2xl">صورة</label>
       </div>
      </div></div>
      <div v-if="counter==2">
         <!-- <div class=" w-full  mx-auto px-4 pt-2 ">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <input type="number" id="ordercode" v-model="ordercode"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="ordercode" class=" mx-5  text-2xl">طلب الإستعارة</label>
       </div>
      </div> -->
         <div class=" w-full  mx-auto px-4 py-2">
            <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <input type="number" id="available_num" v-model="available_num"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
            <label for="available_num" class=" mx-5  text-2xl">العدد المتوفر</label>
         </div></div>
         <div class=" w-full  mx-auto px-4 pb-1  ">
            <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <input type="number" id="requested_piece_num" v-model="requested_piece_num"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 "></input>
            <label for="requested_piece_num" class=" mx-5  text-2xl">العدد المطلوب</label>
         </div>
        </div>
         <div class=" w-full  mx-auto px-4 pb-1 pt-2 ">
            <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <textarea id="lost_piece_note" v-model="lost_piece_note"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 "></textarea>
            <label for="lost_piece_note" class=" mx-5  text-2xl">ملاحظات</label>
         </div>
        </div>
      </div>
      <div class=" w-full  mx-auto px-4 pb-2 "  v-if="counter==1">
          <div class="flex justify-start space-x-3 items-center  px-3 py-1 text-xl ">
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="()=>{counter++}" >التالي</button>
       </div>
      </div>
      <div class=" w-full  mx-auto px-4 pb-2 " v-if="counter==2">
          <div class="flex justify-between space-x-3 items-center  px-3 py-1 text-xl ">
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="createLostPiece()" >إضافة</button>
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
export default {
   data() {
      return {
         // ['requested_piece_num','lost_piece_note','borrow_diwan_num'];
         // ['piece_name','Base_num','available_num','description','image','code','piece_type'];
         counter: 1,
         piece_name: '',
         piece_type: '',
         // ordercode:'',
         available_num: '',
         requested_piece_num: '',
         code: 0,
         image: '',
         lost_piece_note: '',
          msg: '',
         shtoast: false
      }
   }, methods: {
      selectImg() {
         this.image = event.target.files[0];
      },
      async createLostPiece() {
         const formData = new FormData();
         formData.append('piece_name', this.piece_name.toLowerCase());
         formData.append('lost_piece_note', this.lost_piece_note);
         formData.append('available_num', this.available_num);
         formData.append('requested_piece_num', this.requested_piece_num);
         formData.append('image', this.image);
         formData.append('code', this.code);
         formData.append('piece_type', this.piece_type);
         try {
            const res =await axios.post('/createLostPiece', formData, { headers: { 'Content-Type': 'multipart/form-data' } });
           console.log(res)
                 this.msg = "تم إدخال القطعة بنجاح";
            this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);




         } catch (e) {
            console.log(e);
            this.msg = ''

         }
      },
      close() {
         this.$emit('addPieces', false)
      }
   },   components: { Toast }


}
</script>
<style lang="">

 </style>
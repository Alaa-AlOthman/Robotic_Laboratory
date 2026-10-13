<template lang="">
    <div class="backdrop1" >
      <div class="bg-secondary-bg px-0 mx-auto  flex space-y-3 rounded-lg flex-col items-center my-32  border-3 w-96">
 
          <div class="w-full flex justify-end mr-5 mt-2">
                <img src="@/assets/image/close.png" class="w-6 h-8 cursor-pointer" @click="close()" alt="" />
          </div>
        <div v-if="counter==1">
         
       <div class=" w-full  mx-auto px-4 pt-2">
          <div class="flex justify-between relative space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <!-- <input type="text" id="piece_name"  v-model="piece_name" dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 "> -->
          <input type="text" id="piece_name"  v-model="searchQuery"   @input="filterItem" dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <div  class=" w-1/2  absolute left-0 top-12 right-0  rounded-lg text- text-2xl items-center border border-secondary-button  mx-auto max-h-40 overflow-y-auto my-0 z-10"  v-if="filteredItems.length>0">
          <ul class="list-none p-0 bg-primary text-primary-bg text-center rounded-lg ">
            <li class="  p-3 cursor-pointer border-b-secondary-button border-b" v-for="(item, index) in filteredItems" :key="index" @click="selectItem(item)">{{ item.piece_name }}</li>
          </ul>
         </div>
          <label for="piece_name" class=" mx-5  text-2xl">اسم القطعة</label>
       </div></div>
   
       <div class=" w-full  mx-auto px-4 py-2">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <input type="text" id="piece_type"  v-model="piece.piece_type" dir="auto"  disabled class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 "> 
           
          <label for="piece_type" class=" mx-5  text-2xl">نوع القطعة</label>
       </div></div>
       <div class=" w-full  mx-auto px-4 py-2">
            <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <input type="number" id="code" v-model="piece.code"  dir="auto" disabled  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
            <label for="code" class=" mx-5  text-2xl">الكود</label>
         </div></div>
         <div class=" w-full  mx-auto px-4 pb-1">
            <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <input type="number" id="available_num" v-model="piece.available_num" disabled  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
            <label for="available_num" class=" mx-5  text-2xl">العدد المتوفر</label>
         </div></div>
      </div>
      <div v-if="counter==2">
         <div class=" w-full  mx-auto px-4  pt-2 ">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <input type="number" id="requested_pieces_num" v-model="requested_pieces_num"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="requested_pieces_num" class=" mx-5  text-2xl"> العدد المطلوب</label>
       </div>
       
      </div>
      <div class=" w-full  mx-auto px-4  pt-2 ">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
          <input type="number" id="delivered_pieces_num" v-model="delivered_pieces_num"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 ">
          <label for="delivered_pieces_num" class=" mx-5  text-2xl"> العدد المسلم</label>
       </div>
      </div>
      <div class=" w-full  mx-auto px-4 py-2">
          <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <select id="piece_deliver_state"   dir="auto" v-model="piece_deliver_state"  class=" bg-primary text-primary-bg text-center rounded-lg  h-9  w-3/5  px-4 ">
                        <option value="ND">لم يستلم</option>
                        <option value="PD">استلم جزء</option>
                        <option value="DA">استلم الكل</option>
                     </select>
          <label for="piece_deliver_state" class=" mx-5  text-2xl">الحالة</label>
       </div></div>
        
         <div class=" w-full  mx-auto px-4 pb-1  ">
            <div class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl ">
            <textarea id="piece_deliver_note" v-model="piece_deliver_note"  dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 "></textarea>
            <label for="piece_deliver_note" class=" mx-5  text-2xl">ملاحظات</label>
         </div>
        </div></div>
      <div class=" w-full  mx-auto px-4 pb-2 "  v-if="counter==1">
          <div class="flex justify-start space-x-3 items-center  px-3 py-1 text-xl ">
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="()=>{counter++}" >التالي</button>
       </div>
      </div>
      <div class=" w-full  mx-auto px-4 pb-2 " v-if="counter==2">
          <div class="flex justify-between space-x-3 items-center  px-3 py-1 text-xl ">
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="addPieceToBorrowRequest()" >إضافة</button>
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="addToLostPiece()" >غير موجود</button>
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
   // props:['borrow'],
components:{Toast},
   data() {
      //'piece_name','Base_num','available_num','description','image','code','piece_type'
       //['borrow_request_id,piece_id,requested_pieces_num','delivered_pieces_num','piece_deliver_state','piece_deliver_note'];
      return {

         counter: 1,
         requested_pieces_num: '',
         delivered_pieces_num:'',
         available_num: '',
         piece_deliver_note: '',
         piece_deliver_state: '',
         pieces: [],
         searchQuery: '',
         filteredItems: [],
         selectedItem: null,
         piece:{},
         answer:false,
            msg: '',
         shtoast: false
     
      }
   }, methods: {
      filterItem() {
         if (this.searchQuery) {
       
            this.filteredItems = this.pieces.filter(item => item.piece_name.toUpperCase().includes(this.searchQuery.toUpperCase()));
         }
         else {
            this.filteredItems = [];
         }
      },selectItem(item){
         this.selectedItem=item;
         this.piece=item;
         this.searchQuery=item.piece_name;
         console.log(item);
         this.filteredItems=[];
      }
    //['borrow_request_id,piece_id,requested_pieces_num','delivered_pieces_num','piece_deliver_state','piece_deliver_note'];
   ,async addPieceToBorrowRequest() {
      try {
         const res = await axios.post("/addPieceToBorrowRequest",{borrow_request_id:this.borrowReq.id,piece_id:this.piece.id,requested_pieces_num:this.requested_pieces_num,
            delivered_pieces_num:this.delivered_pieces_num,piece_deliver_state:this.piece_deliver_state,piece_deliver_note:this.piece_deliver_note,piece_recieve_state:'NR'
         });
         console.log(res);
         this.answer=true
          this.msg=res.data.message;
                 this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);

            } catch (e) {
                console.log(e)
                  this.msg = e.response.data.message;
                this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);
            }
    },async addToLostPiece(){
         // ['requested_piece_num','lost_piece_note','borrow_diwan_num'];
         // ['piece_name','Base_num','available_num','description','image','code','piece_type'];
         if(this.piece.available_num < this.requested_pieces_num){
      try {
         let requestNum=this.requested_pieces_num-this.piece.available_num;
         const res= await axios.post('/addToLostPiece',{piece_id:this.piece.id,requested_piece_num:requestNum,borrow_diwan_num:this.borrowReq.diwan_num});
         console.log(res);
     this.msg=res.data.message;
                 this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);

            } catch (e) {
                console.log(e)
                  this.msg = e.response.data.message;
                this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);
            }}
    },
   close() {
      this.$emit('addPiecesOrder', false,this.answer)
   }, async loadAvailablePieces() {
      try {
         const res = await axios.get("/getAllAvailablePieces");
         console.log(res);
         const pieces = res.data;
         this.pieces=Object.values(pieces);
        
      }
      catch (e) {
         console.log(e);
      }
   },
}, mounted(){
   this.loadAvailablePieces();
}, watch:{
   delivered_pieces_num(newVal){
      if(newVal ==0){
       this.piece_deliver_state="ND";
      }
     else if(newVal>= this.requested_pieces_num){
         this.piece_deliver_state="DA";
      }else if(newVal < this.requested_pieces_num ){
          this.piece_deliver_state="PD";
      }

   }
},computed:{
        ...mapGetters('borrowRequest',['borrowReq','piecesInBorrow'])
    }
   
  
}
</script>
<style lang="">
/*ul {
   list-style-type:none;
   padding:0;
}
li{
   background:#f0f0f0;
   margin:5px 0;
   padding:10px;
   cursor:pointer;
}
li:hover{
   background:#e0e0e0;
}*/
</style>
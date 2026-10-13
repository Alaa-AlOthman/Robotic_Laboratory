<template lang="">
    <div class="backdrop1" >
      <div class="bg-secondary-bg px-0 mx-auto  flex space-y-3 rounded-lg flex-col items-center my-32  border-3 w-3/5">
 
          <div class="w-full flex justify-end mr-5 mt-2">
                <img src="@/assets/image/close.png" class="w-6 h-8 cursor-pointer" @click="close()" alt="" />
          </div>
          <div class="my-2 flex flex-col  w-full">
            <div class="text-xl  text-right mr-10 my-2">
              <p>تمت إضافة القطع التالية للطلب الذي رقمه {{borrowReq.diwan_num}}</p>
            </div>
            <div class=" mx-10  max-h-64 overflow-y-auto py-0 my-0">

<table class="w-full  px-1  text-primary-bg bg-primary text-xl table-auto">  
        <thead class="sticky top-0 z-10 shadow-sms">
        <tr  class="bg-primary-button text-primary text-xl py-1 ">
        <th >ملاحظات</th>
         <th >العدد المسلم</th>
        <th >العدد المطلوب</th>
        <th >نوع القطعة</th>
        <th >اسم القطعة</th>
        <th style="width:2%">رقم</th>
        </tr>
      </thead>
    <tbody v-for="(item, index) in dataBorrow" :key="index" >
        <tr class="bg-primary">
        <td>{{ item.piece_deliver_note }}</td>
        <td>{{ item.delivered_pieces_num  }}</td>
        <td>{{ item.requested_pieces_num }}</td>
         <td>{{ item.piece.piece_type }}</td>
         <td>{{ item.piece.piece_name }}</td>
          <td>{{ index+1 }}</td>
        </tr>
      </tbody>
</table>
</div>
   <div class="my-2 pr-8 mx-2 flex justify-end space-x-2">
        
          <div
            class="flex justify-end space-x-1 items-center rounded-lg px-1 py-1 text-xl"
          >
            <textarea
              id="request_deliver_note"
              v-model="request_deliver_note"
              dir="auto"
              class="bg-primary text-primary-bg rounded-lg h-9  px-1"
            ></textarea>
            <label for="request_deliver_note" class="text-xl">ملاحظات تسليم القطع</label>
          </div>
            <div
            class="justify-between space-x-1  items-center rounded-lg px-1 py-1 text-xl"
          >
            <input
              type="date"
              id="request_deliver_date"
              v-model="request_deliver_date"
              dir="auto"
              class="bg-primary text-primary-bg rounded-lg h-9  px-1"
            />
            <label for="request_deliver_date" class="text-xl">تاريخ تسليم القطع</label>
          </div>
        </div>
          </div>
  <div class=" w-full  mx-10  pb-2 "  >
          <div class="ml-4  py-1 text-xl ">
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="AddToBorrowRequest()" >إضافة</button>
       </div></div>
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
  components:{Toast},
  data() {
    return {
      dataBorrow: [],
      request_deliver_date:'',
      request_deliver_note:'',
      added:false,
       msg: '',
         shtoast: false
    };
  },
  methods: {
    async PiecesByBorrow() {
      try {
        const res = await axios.post('/getPiecesByBorrowRequestWithoutPigination', { id: this.borrowReq.id });
        console.log(res);
        this.dataBorrow = res.data;
      }
      catch (e) {
        console.log(e);
      }
    },
    async AddToBorrowRequest(){
      try {
        const res=await axios.post('/AddToBorrowRequest',
        {id:this.borrowReq.id,request_deliver_date:this.request_deliver_date,request_deliver_note:this.request_deliver_note});
        console.log(res);
        this.added=true

         this.msg=res.data.message;
                 this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);

            } catch (e) {
                console.log(e)
                  this.msg = e.response.data.message;
                this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);
            
      this.added=false;
      }
    },
    close() {
      this.$emit("addOrder", false,this.added);
    },}, computed: {
      ...mapGetters('borrowRequest', ['borrowReq', 'piecesInBorrow'])
    }, async mounted() {
    this.PiecesByBorrow();
    this.request_deliver_date = this.borrowReq.request_deliver_date;
    this.request_deliver_note = this.borrowReq.request_deliver_note ;
  },
  
};
</script>
<style lang="">
</style>
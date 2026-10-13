<template lang="">
  <div class="backdrop1">
    <div
      class="bg-secondary-bg px-0 mx-auto flex space-y-3 rounded-lg flex-col items-center my-32 border-3 w-3/5"
    >
      <div class="w-full flex justify-end mr-5 mt-2">
        <img
          src="@/assets/image/close.png"
          class="w-6 h-8 cursor-pointer"
          @click="close()"
          alt=""
        />
      </div>
      <div class="my-2 flex flex-col w-full">
        <div class="text-xl text-right mr-10 mb-3">
          <p>تم إستلام القطع التالية من الفريق</p>
        </div>
        <div class="mx-10 max-h-64 overflow-y-auto py-0 my-0">
          <table
            class="w-full py-1 px-1  text-primary-bg bg-primary text-center text-xl table-auto"
          >
          <thead class="sticky top-0 z-10 shadow-sm">
            <tr class="bg-primary-button text-primary text-xl py-1">
              <th>الحالة</th>
              <th>العدد</th>
              <th>نوع القطعة</th>
              <th>اسم القطعة</th>
              <th style="width:2%">رقم</th>
            </tr>
          </thead>
          <tbody v-for="(item, index) in dataBorrow" :key="index" >
            <tr class="bg-primary">
              <td>{{ item.piece_recieve_state_ar }}</td>
              <td>{{ item.piece_recieve_num }}</td>
              <td>{{ item.piece.piece_type }}</td>
              <td>{{ item.piece.piece_name }}</td>
              <td>{{ index+1 }}</td>
            </tr>
          </tbody>
          </table>
        </div>
        <div class="my-2  mx-2 flex justify-end space-x-4">
        
          <div
            class="flex justify-end space-x-1 items-center rounded-lg px-1 py-1 text-xl"
          >
            <textarea
              id="request_receive_note"
              v-model="request_receive_note"
              dir="auto"
              class="bg-primary text-primary-bg rounded-lg h-9  px-1"
            ></textarea>
            <label for="request_receive_note" class="text-xl">ملاحظات إستلام القطع</label>
          </div>
            <div
            class="justify-between space-x-1  items-center rounded-lg px-1 py-1 text-xl"
          >
            <input
              type="date"
              id="request_receive_date"
              v-model="request_receive_date"
              dir="auto"
              class="bg-primary text-primary-bg rounded-lg h-9 w-2/4 px-1"
            />
            <label for="request_receive_date" class="text-xl">تاريخ إستلام القطع</label>
          </div>
        </div>
        <div class=" w-full  mx-10  pb-2 "  >
          <div class="flex justify-start space-x-3 items-center   py-1 text-xl ">
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" :disabled="!showRecive" @click="recieveBorrowRequest" >إستلام</button>
       </div></div>
      </div>
    </div>
      <transition name="toast">
    <Toast v-if="shtoast" :msg="msg"></Toast>
  </transition>
  </div>
</template>
<script>
import pieceReciveStateFormat from '@/Resource/FormatFunctions/pieceReciveStateFormat';
import axios from 'axios';
import { mapGetters } from 'vuex';
import Toast from '@/components/Toast.vue';
export default {
  components: { Toast },
  // props: ['borrow'],
  data() {
    return {
      showRecive: false,
      request_receive_date: '',
      request_receive_note: '',
      dataBorrow: [],
      msg: '',
         shtoast: false
    };
  },
  methods: {
    async recieveBorrowRequest() {
      try {
        const res = await axios.post('/recieveBorrowRequest',
          { borrow_request_id: this.borrowReq.id, request_receive_date: this.request_receive_date, request_receive_note: this.request_receive_note });
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
      this.$emit("reciveOrderPieces", false);
    }, async PiecesByBorrow() {
      try {
        const res = await axios.post('/getPiecesByBorrowRequestWithoutPigination', { id: this.borrowReq.id });
        console.log(res);
        this.dataBorrow = res.data;
        this.dataBorrow.forEach(borrow => {
          borrow['piece_recieve_state_ar'] = pieceReciveStateFormat(borrow['piece_recieve_state']);
        });
        var allRecive = true;
        var anyRecive = false;
        this.dataBorrow.forEach(piece => {
          if (piece.piece_recieve_state == 'RA') {
            anyRecive = true;
          } else {
            allRecive = false;
          }
        });
        this.showRecive = allRecive;
        if (this.borrowReq.request_receive_date) {
          this.request_receive_date = this.borrowReq.request_receive_date;
        }
        this.request_receive_note = this.borrowReq.request_receive_note != (null || undefined) ? this.borrowReq.request_receive_note : '';
      } catch (error) {
        console.log(error);
      }
    }
  }, async mounted() {
    this.PiecesByBorrow();
    this.request_receive_date = this.borrow.request_receive_date;
    this.request_receive_note = this.borrow.request_receive_note;
  }, computed: {
    ...mapGetters('borrowRequest', ['borrowReq', 'piecesInBorrow'])
  }
};
</script>
<style lang=""></style>

<template lang="">
    <div>
        <div class="mx-auto overflow-auto">

     <table class="w-full py-1 px-1  text-primary-bg bg-primary text-xl text-center">  
        <thead>
        <tr  class="bg-primary-button text-primary text-xl py-1 ">
            <th style="width:10%" v-if="!showButton">الإجراء</th>
        <th style="width:10%">ملاحظات</th>
        <th style="width:8%">العدد المستلم</th>
        <th style="width:10%"> الحالة</th>
        <th style="width:8%">العدد المسلم</th>
        <th style="width:8%">العدد المطلوب</th>
        <th style="width:14%"> صورة</th>
        <th style="width:5%">الكود</th>
        <th style="width:10%">نوع القطعة</th>
        <th style="width:10%">اسم القطعة</th>
        <th style="width:2%">رقم</th>
        </tr>
    </thead>
    <tbody  v-for="(item, index) in piecesInBorrow" :key="index">
        <tr class="bg-primary">
        <td class=""  v-if="!showButton">
         <div class="flex justify-center space-x-4">
                 
                       <div class=" px-1  py-1   cursor-pointer " @click="recivePiecse(true,item)">
                      <img src="@/assets/image/receive.svg"
                        class="w-12 h-12  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                    </div>
            <!-- <button class=" bg-primary-button text-primary   rounded-lg text-center  mx-10 py-2 px-3 hover:bg-hover  "  @click="recivePiecse(true,item)">إستلام القطعة</button> -->
           
        </td>
        <td>{{ item.piece_deliver_note }}</td>
        <td>{{ item.piece_recieve_num }}</td>
        <td>{{ item.piece_deliver_state_ar }}</td>
        <td>{{ item.delivered_pieces_num }}</td>
        <td>{{ item.requested_pieces_num }}</td>
        <td><img :src="item.piece.image_url" v-if="item.piece.image" class="max-w-24 h-auto mx-auto"/></td>
        <td>{{ item.piece.code }}</td>
        <td>{{ item.piece.piece_type }}</td>
        <td>{{ item.piece.piece_name }}</td>
        <td>{{ index+1 }}</td>
        </tr>
    </tbody>
</table>
</div>
 <div class="items-center justify-center flex  my-1">
            <span>صفحة
                    {{ pagination.current_page }} من 
                    {{ pagination.last_page }}
                </span>
            <button class=" bg-primary-button text-primary   rounded-lg text-center  mx-1 py-2 px-3 my-1 hover:bg-hover  " v-if="pagination.current_page < pagination.last_page" @click="show(pagination.current_page+1)">التالي</button> 
                
                <button class=" bg-primary-button text-primary   rounded-lg text-center  mx-1 py-2 px-3 my-1 hover:bg-hover  " v-if="pagination.current_page > 1" @click="show(pagination.current_page-1)">السابق</button>
  </div>
<div class="flex justify-start w-full space-x-3 items-center  px-3 py-1 my-1 text-xl">
    <button class="bg-primary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="reciveOrderPieces(true)"> إستلام القطع</button>
               
 </div>
 <RecivePieces v-if="showRP" @recivePiecse="recivePiecse" :piece="piece"></RecivePieces>
 <ReciveOrder v-if="showRO" @reciveOrderPieces="reciveOrderPieces"  ></ReciveOrder>

    </div>
</template>
<script>
import RecivePieces from './smallComponents/PiecesOrder/RecivePieces.vue';
import ReciveOrder from './smallComponents/Orders/ReciveOrder.vue';
import getPiecesByBorrowRequest from '@/Resource/BorrowRequestFunctions/getPiecesByBorrowRequest';
import pieceReciveStateFormat from '@/Resource/FormatFunctions/pieceReciveStateFormat';
import axios from 'axios';
import { mapGetters } from 'vuex';
export default {
    props: ['showButton'],
    data() {
        return {
            showRP: false,
            showRO: false,
            answer: false,
            piece: {},
            borrowData: {},
            paginate: {
                current_page: 1,
                last_page: 1,
                total: 0
            },
           

        }
    }, methods: {
        recivePiecse(rp, item) {
            this.showRP = rp;
            this.piece = item;
            this.show();
        },
        reciveOrderPieces(ro) {
            this.showRO = ro;
        }, async show(page = 1) {
            const data = await getPiecesByBorrowRequest(this.borrowReq.id, page);
            this.borrowData = data.data;
            this.$store.commit('borrowRequest/setPiecesInBorrow',this.borrowData);
            this.paginate = {
                current_page: data.current_page,
                last_page: data.last_page,
                total: data.total
            }
            this.$store.commit('borrowRequest/setPagination',this.paginate);
            // console.log(typeof(this.borrowData)+' '+this.borrowData)
            this.borrowData.forEach(borrow => {
                borrow['piece_recieve_state_ar'] = pieceReciveStateFormat(borrow['piece_recieve_state']);
            });
        }
    }, components: {
        RecivePieces,
        ReciveOrder
    },computed:{
        ...mapGetters('borrowRequest',['borrowReq','piecesInBorrow','pagination'])
    }
}
</script>
<style lang="">

</style>
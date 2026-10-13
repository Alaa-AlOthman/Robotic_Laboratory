<template lang="">
    <div>
        <div class="mx-auto overflow-auto">

<table class="w-full py-1 px-1  text-primary-bg bg-primary text-xl text-center">  
        <thead>
        <tr  class="bg-primary-button text-primary text-xl py-1 ">
            <th style="width:8%" v-if="!showButton">الإجراء</th>
        <th style="width:10%">ملاحظات</th>
        <th style="width:10%"> الحالة</th>
        <th style="width:10%">العدد المسلم</th>
        <th style="width:10%">العدد المطلوب</th>
        <th style="width:14%"> صورة</th>
        <th style="width:10%">الكود</th>
        <th style="width:10%">نوع القطعة</th>
        <th style="width:10%">اسم القطعة</th>
        <th style="width:2%">رقم</th>
        </tr>
    </thead>
    <tbody  v-for="(item, index) in piecesInBorrow" :key="index">
    
        <tr class="bg-primary">
        <td class="" v-if="!showButton">
              <div class="flex justify-center space-x-4">
                 
                    <div class=" px-1     cursor-pointer " @click="deletePiecesOrder(true,false,item.piece)">
                      <img src="@/assets/image/delete.svg"
                        class="w-11 h-11  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                       <div class=" px-1  py-1   cursor-pointer " @click="editPiecesOrder(true,false,item)">
                      <img src="@/assets/image/edit.svg"
                        class="w-9 h-9  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                    </div>
            <!-- <button class=" bg-primary-button text-primary   rounded-lg text-center  mx-4 py-2 px-3 my-1 hover:bg-hover  " @click="deletePiecesOrder(true,false,item.piece)">حذف </button>
            <button class=" bg-primary-button text-primary  rounded-lg text-center  mx-4 py-2 px-3 my-1 hover:bg-hover  " @click="editPiecesOrder(true,false,item)" >تعديل</button> -->
        </td>
        <td>{{ item.piece_deliver_note }}</td>
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
  <div class="items-center justify-center flex my-1">
            <span>صفحة
                    {{ pagination.current_page }} من 
                    {{ pagination.last_page }}
                </span>
            <button class=" bg-primary-button text-primary   rounded-lg text-center  mx-1 py-2 px-3 my-1 hover:bg-hover  " v-if="pagination.current_page < pagination.last_page" @click="show(pagination.current_page+1)">التالي</button> 
                
                <button class=" bg-primary-button text-primary   rounded-lg text-center  mx-1 py-2 px-3 my-1 hover:bg-hover  " v-if="pagination.current_page > 1" @click="show(pagination.current_page-1)">السابق</button>
     </div>
 <div class="flex justify-start w-full space-x-3 items-center  px-3 py-1  text-xl">
    <button class="bg-primary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="addOrder(true)"> تعديل الطلب</button>
    <button class="bg-primary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="addPiecesOrder(true)"> إضافة قطعة</button>
               
 </div>
     <EditPiecesInOrder v-if="showedit" @editPiecesOrder="editPiecesOrder"  :pieceData="piece" ></EditPiecesInOrder>
     <AddPiecesToOrder v-if="showAdd" @addPiecesOrder="addPiecesOrder" sec="editorder" ></AddPiecesToOrder>
     <DeleteComp v-if="showDel" @deleteTh="deletePiecesOrder" text="القطعة" :data="piece"  ></DeleteComp>
     <AddOrder v-if="showAddOrder" @addOrder="addOrder"></AddOrder>
   
    </div>
</template>
<script>
import EditPiecesInOrder from './smallComponents/PiecesOrder/EditPiecesInOrder.vue';
import AddPiecesToOrder from './smallComponents/PiecesOrder/AddPiecesToOrder.vue';
import DeleteComp from '@/components/DeleteComp.vue';
import AddOrder from './smallComponents/Orders/AddOrder.vue';
import getPiecesByBorrowRequest from '@/Resource/BorrowRequestFunctions/getPiecesByBorrowRequest';
import axios from 'axios';
import deletePieceFromBorrowRequest from '@/Resource/BorrowRequestFunctions/deletePieceFromBorrowRequest';
import { mapGetters } from 'vuex';
export default {
    props: ['showButton'],
    data() {

        return {
            showedit: false,
            showAdd: false,
            showDel: false,
            showAddOrder: false,
            answer: false,
            piece: {},
            borrowData: {},
            paginate: {
                current_page: 1,
                last_page: 1,
                total: 0
            }
        }
    }, methods: {
        editPiecesOrder(edit, an, piece) {
            this.showedit = edit
            this.piece = piece
            if (an) {
                this.show();
            }
        },
        addPiecesOrder(add) {
            this.showAdd = add
             this.show();
        },
        deletePiecesOrder(del, an, piece) {
            this.showDel = del;
            this.answer = an;

            this.piece = piece;
            this.piece.name = this.piece.piece_name;

            if (this.answer) {
                // console.log(this.piece);
                this.deletePieceFromBorrowRequest(this.piece);
            }
        },
        addOrder(addO,added) {
            this.showAddOrder = addO;
            if(added)
            this.show();
        }, async show(page=1) {
            const data = await getPiecesByBorrowRequest(this.borrowReq.id,page);
            this.borrowData=data.data;
             this.$store.commit('borrowRequest/setPiecesInBorrow',this.borrowData);
            this.paginate = {
                    current_page: data.current_page,
                    last_page: data.last_page,
                    total: data.total
                }
                this.$store.commit('borrowRequest/setPagination',this.paginate);

        }, async deletePieceFromBorrowRequest() {
            
           var success= await deletePieceFromBorrowRequest(this.borrowReq.id,this.piece.id);
                if(success)
                this.show();
           
        }
    }, components: {
        EditPiecesInOrder,
        AddPiecesToOrder,
        DeleteComp,
        AddOrder
    }, updated() {
        // this.pieces=this.borrowData;
    },computed:{
         ...mapGetters('borrowRequest',['borrowReq','piecesInBorrow','pagination'])
    }
}
</script>
<style lang="">

</style>s
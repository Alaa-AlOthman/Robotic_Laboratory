<template lang="">
    <div>
    <div class="mx-auto overflow-auto">
        <table class="w-full py-1 px-1  text-primary-bg bg-primary text-xl">  
          <thead>
                <tr  class="bg-primary-button text-primary text-xl py-1 ">

                <th style="width:18%">الإجراء</th>
                <th style="width:10%">ملاحظات</th>
                <th style="width:10%">طلب الإستعارة</th>
                <th style="width:10%">العدد </th>
                <th style="width:10%">صورة</th>
                <th style="width:10%">الكود</th>
                <th style="width:10%">نوع القطعة</th>
                <th style="width:10%">اسم القطعة</th>
                <th style="width:2%">رقم</th>
                </tr>
      
            </thead>
            <tbody  v-for="(brokenPiece, index) in brokenPieces" :key="index" class="text-center content-center">
                <tr class="bg-primary">
                <td class="">
                      <div class="flex justify-center space-x-4">
                 
                    <div class=" px-1     cursor-pointer " @click="delPieces(true,false,brokenPiece)">
                      <img src="@/assets/image/delete.svg"
                        class="w-11 h-11  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                       <div class=" px-1     cursor-pointer " @click="fixPieces(true,brokenPiece)">
                      <img src="@/assets/image/repair.svg"
                        class="w-10 h-10  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                       <div class=" px-1  py-1   cursor-pointer " @click="editPieces(true,brokenPiece)">
                      <img src="@/assets/image/edit.svg"
                        class="w-9 h-9  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                    </div>
                    <!-- <button class=" bg-primary-button text-primary   rounded-lg text-center my-1  mx-1 py-2 px-3 hover:bg-hover  " @click="delPieces(true,false,brokenPiece)" >حذف </button>
                    <button class=" bg-primary-button text-primary  rounded-lg text-center  mx-1 py-2 px-3 hover:bg-hover  " @click="editPieces(true,brokenPiece)">تعديل</button>
                    <button class=" bg-primary-button text-primary  rounded-lg text-center my-1  mx-1 py-2 px-2 hover:bg-hover  " @click="fixPieces(true,brokenPiece)">إصلاح</button> -->
                </td>
                <td>{{ brokenPiece.broken_piece_note }}</td>
                <td>{{ brokenPiece.borrow_diwan_num.length > 0 ? brokenPiece.borrow_diwan_num.toString() : ''  }}</td>
                <td>{{ brokenPiece.broken_piece_num }}</td>
                <td><img :src="brokenPiece.piece.image_url" v-if="brokenPiece.piece.image" class="max-w-24 h-auto mx-auto"></td>
                <td>{{ brokenPiece.piece.code }}</td>
                <td>{{ brokenPiece.piece.piece_type }}</td>
                <td>{{ brokenPiece.piece.piece_name }}</td>
                <td>{{ index+1 }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <AddBrPieces v-if="showAdd" @addPieces="addPieces"></AddBrPieces>
    <EditBrPieces v-if="showedit" @editPieces="editPieces" :brokenPiece="brokenPiece"></EditBrPieces>
    <DeleteComp v-if="showdel" @deleteTh="delPieces" text="القطعة" :data="brokenPiece"  ></DeleteComp>
    <FixBrPieces v-if="showfix" @fixPieces="fixPieces" :piece="brokenPiece"></FixBrPieces>
     <div class="items-center justify-center flex mt-2">
            <span>صفحة
                    {{ pagination.current_page }} من 
                    {{ pagination.last_page }}
                </span>
            <button class=" bg-primary-button text-primary   rounded-lg text-center  mx-1 py-2 px-3 my-1 hover:bg-hover  " v-if="pagination.current_page < pagination.last_page" @click="getAllBrokenPieces(pagination.current_page+1)">التالي</button> 
                
                <button class=" bg-primary-button text-primary   rounded-lg text-center  mx-1 py-2 px-3 my-1 hover:bg-hover  " v-if="pagination.current_page > 1" @click="getAllBrokenPieces(pagination.current_page-1)">السابق</button>
            </div>
</div>
</template>
<script>
import AddBrPieces from './smallComponents/BrokenPieces/AddBrPieces.vue';
import EditBrPieces from './smallComponents/BrokenPieces/EditBrPieces.vue';
import DeleteComp from '@/components/DeleteComp.vue';
import FixBrPieces from './smallComponents/BrokenPieces/FixBrPieces.vue';
import axios from 'axios';
export default {
    // ['piece_id','broken_piece_num','fixed_piece_num','broken_piece_note','borrow_diwan_num','isFixed']
    props: ['piece_type', 'piece_name'],
    data() {
        return {
            showAdd: false,
            showdel: false,
            showedit: false,
            showfix: false,
            brokenPieces: [],
            brokenPiece: {},
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0
            },
            searchP: "",
            searchType: "",
        }
    }, methods: {
        addPieces(add) {
            this.showAdd = add;
            this.getAllBrokenPieces();
        },
        delPieces(del, an, brokenp) {
            this.showdel = del;
            this.answer = an;
            this.brokenPiece = brokenp;
            this.brokenPiece.name = this.brokenPiece.piece.piece_name;

            if (an) {
                this.removeBrokenPiece();
            }
        },
        editPieces(ed, brpiece) {
            this.showedit = ed;
            this.brokenPiece = brpiece;
            this.getAllBrokenPieces();
        },
        fixPieces(fix, brpiece) {
            this.showfix = fix;
            this.brokenPiece = brpiece;
            this.getAllBrokenPieces();
        },
        async search(page = 1) {
            // this.searchp = this.piece_type;
            this.searchType = "type";
            console.log(this.piece_type)
            try {
                const res = await axios.post(
                    `/searchBrokenPieces?page=${page}`, { piece_type: this.piece_type, piece_name: this.piece_name.toLowerCase() }
                );
                console.log(res);
                this.brokenPieces = res.data.data;
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    total: res.data.total,
                };
            } catch (e) {
                console.log(e);
            }
        },


        async getAllBrokenPieces(page = 1) {
            try {
                const res = await axios.get(`/getAllBrokenPieces?page=${page}`);
                console.log(res);
                this.brokenPieces = res.data.data;
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    total: res.data.total
                }
            } catch (error) {
                console.log(error);
            }
        },
        async removeBrokenPiece() {
            try {
                const res = await axios.post('/removeBrokenPiece', { piece_id: this.brokenPiece.piece_id });
                console.log(res);
                this.getAllBrokenPieces();
            } catch (error) {
                console.log(error);
            }
        }
    }, components: {
        AddBrPieces,
        EditBrPieces,
        DeleteComp,
        FixBrPieces
    }
}
</script>
<style></style>
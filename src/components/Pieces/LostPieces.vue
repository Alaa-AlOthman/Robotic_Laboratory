<template lang="">
    <div>
    <div class="mx-auto overflow-auto">
        <table class="w-full py-1 px-1  text-primary-bg bg-primary text-xl">  
          <thead>
                <tr  class="bg-primary-button text-primary text-xl py-1 ">

                <th style="width:18%">الإجراء</th>
                <th style="width:10%">ملاحظات</th>
                <th style="width:10%">طلب الإستعارة</th>
                <th style="width:10%">العدد المطلوب</th>
                <th style="width:10%">العدد المتوفر</th>
                <th style="width:10%">صورة</th>
                <th style="width:10%">الكود</th>
                <th style="width:10%">نوع القطعة</th>
                <th style="width:10%">اسم القطعة</th>
                <th >رقم</th>
                </tr>
            </thead>
            <tbody v-for="(LostPiece, index) in lostPieces" :key="index" class="text-center content-center">
            
                <tr class="bg-primary">
                <td class="">
                         <div class="flex justify-center space-x-4">
                 
                    <div class=" px-1     cursor-pointer " @click="delPieces(true,false,LostPiece)">
                      <img src="@/assets/image/delete.svg"
                        class="w-11 h-11  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                       <div class=" px-1     cursor-pointer " @click="providePiece(true,LostPiece)">
                      <img src="@/assets/image/provide.svg"
                        class="w-10 h-10  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                       <div class=" px-1  py-1   cursor-pointer " @click="editPieces(true,LostPiece)">
                      <img src="@/assets/image/edit.svg"
                        class="w-9 h-9  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                    </div>
                    <!-- <button class=" bg-primary-button text-primary   rounded-lg text-center  py-2 px-3 mx-1 my-1 hover:bg-hover  " @click="delPieces(true,false,LostPiece)">حذف </button>
                    <button class=" bg-primary-button text-primary  rounded-lg text-center   py-2 px-3 mx-1 hover:bg-hover  " @click="editPieces(true,LostPiece)">تعديل</button>
                    <button class=" bg-primary-button text-primary  rounded-lg text-center   py-2 px-3 mx-1 my-1 hover:bg-hover  " @click="providePiece(true,LostPiece)">توفير</button> -->
                </td>
                <td>{{ LostPiece.lost_piece_note }}</td>
                <td>{{ LostPiece.borrow_diwan_num.length > 0 ? LostPiece.borrow_diwan_num.toString() : '' }}</td>
                <td>{{ LostPiece.requested_piece_num }}</td>
                <td>{{ LostPiece.piece.available_num }}</td>
                <td><img :src="LostPiece.piece.image_url" v-if="LostPiece.piece.image" class="max-w-24 h-auto mx-auto"></td>
                <td>{{ LostPiece.piece.code }}</td>
                <td>{{ LostPiece.piece.piece_type }}</td>
                <td>{{ LostPiece.piece.piece_name }}</td>
                <td>{{ index+1 }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <DeleteComp v-if="showdel" @deleteTh="delPieces" text="القطعة" :data="lostPiece"  ></DeleteComp>
    <AddLoPieces v-if="showAdd" @addPieces="addPieces"></AddLoPieces>
    <EditLoPieces v-if="showedit" @editPieces="editPieces" :lostPiece="lostPiece"></EditLoPieces>
    <ProvideLoPieces v-if="showProvide" @providePiece="providePiece" :lostPiece="lostPiece"></ProvideLoPieces>
           <div class="items-center justify-center flex mt-2">
            <span>صفحة
                    {{ pagination.current_page }} من 
                    {{ pagination.last_page }}
                </span>
            <button class=" bg-primary-button text-primary   rounded-lg text-center  mx-1 py-2 px-3 my-1 hover:bg-hover  " v-if="pagination.current_page < pagination.last_page" @click="getAllLostPieces(pagination.current_page+1)">التالي</button> 
                
                <button class=" bg-primary-button text-primary   rounded-lg text-center  mx-1 py-2 px-3 my-1 hover:bg-hover  " v-if="pagination.current_page > 1" @click="getAllLostPieces(pagination.current_page-1)">السابق</button>
            </div>
    </div>
</template>
<script>
import DeleteComp from '@/components/DeleteComp.vue';
import AddLoPieces from '@/components/Pieces/smallComponents/LostPieces/AddLoPieces.vue';
import EditLoPieces from '@/components/Pieces/smallComponents/LostPieces/EditLoPieces.vue';
import ProvideLoPieces from '@/components/Pieces/smallComponents/LostPieces/ProvideLoPieces.vue';
import axios from 'axios';
export default {
    props: ['piece_type', 'lpiece_name'],
    data() {
        return {
            // ['requested_piece_num','lost_piece_note','borrow_diwan_num'];
            // ['piece_name','Base_num','available_num','description','image','code','piece_type'];
            showAdd: false,
            showdel: false,
            showedit: false,
            showProvide: false,
            answer: false,
            piece_name: '',
            lostPieces: [],
            lostPiece: {},
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0
            },
            searchP: "",
            searchType: "",
        }
    }, methods: {
        async getAllLostPieces(page = 1) {
            try {
                const res = await axios.get(`/getAllLostPieces?page=${page}`);
                console.log(res);
                this.lostPieces = res.data.data;
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    total: res.data.total
                }
            } catch (error) {
                console.log(error);
            }
        },
        addPieces(add) {
            this.showAdd = add;
            this.getAllLostPieces();

        },
        delPieces(del, an, lostp) {
            this.showdel = del;
            this.answer = an;
            this.lostPiece = lostp;
            this.lostPiece.name = this.lostPiece.piece.piece_name;

            if (an) {
                this.removeLostPiece();

            }
        },
        editPieces(ed, lostP) {
            this.showedit = ed;
            this.lostPiece = lostP;
            this.getAllLostPieces();
        },
        providePiece(pr, lp) {
            this.showProvide = pr;
            this.lostPiece = lp;
            this.getAllLostPieces();
        }, async removeLostPiece() {
            try {
                const res = await axios.post('/removeLostPiece', { piece_id: this.lostPiece.piece_id });
                console.log(res);
            } catch (error) {
                console.log(error);
            }
            this.getAllLostPieces();
        }, async search(page = 1) {
            // this.searchp = this.piece_type;
            this.searchType = "type";
            console.log(this.piece_type)
            try {
                const res = await axios.post(
                    `/searchLostPieces?page=${page}`, { piece_type: this.piece_type, piece_name: this.lpiece_name.toLowerCase() }
                );
                console.log(res);
                this.lostPieces = res.data.data;
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    total: res.data.total,
                };
            } catch (e) {
                console.log(e);
            }
        },
    }, components: { DeleteComp, AddLoPieces, EditLoPieces, ProvideLoPieces }

}
</script>
<style></style>
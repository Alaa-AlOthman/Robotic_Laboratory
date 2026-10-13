<template lang="">
  <div>
    <div class="mx-auto overflow-auto">
      <table class="w-full py-1 px-1 text-primary-bg bg-primary text-xl">
        <thead>
          <tr class="bg-primary-button text-primary text-xl py-1">
            <th style="width: 13%">الإجراء</th>
            <th style="width: 13%">وصف</th>
            <th style="width: 13%">العدد المتوفر</th>
            <th style="width: 13%">العدد الإجمالي</th>
            <th style="width: 13%">صورة</th>
            <th style="width: 13%">الكود</th>
            <th style="width: 13%">نوع القطعة</th>
            <th style="width: 13%">اسم القطعة</th>
            <th>رقم</th>
          </tr>
        </thead>
        <tbody
          v-for="(piece, index) in pieces"
          :key="index"
          class="text-center content-center"
        >
          <tr class="bg-primary">
            <td class="">
             <div class="flex justify-center space-x-4">
                 
                    <div class=" px-1     cursor-pointer " @click="delPieces(true, false, piece)">
                      <img src="@/assets/image/delete.svg"
                        class="w-11 h-11  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                       <div class=" px-1  py-1   cursor-pointer " @click="editPieces(true, piece)">
                      <img src="@/assets/image/edit.svg"
                        class="w-9 h-9  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                    </div>
              <!-- <button
                class="bg-primary-button text-primary rounded-lg text-center mx-1 py-2 px-3 my-1 hover:bg-hover"
                @click="delPieces(true, false, piece)"
              >
                حذف
              </button>
              
              <button
                class="bg-primary-button text-primary rounded-lg text-center mx-1 py-2 px-3 hover:bg-hover"
                @click="editPieces(true, piece)"
              >
                تعديل
              </button> -->
            </td>
            <td>{{ piece.description }}</td>
            <td>{{ piece.available_num }}</td>
            <td>{{ piece.Base_num }}</td>
            <td>
              <img
                :src="piece.image_url"
                v-if="piece.image"
                class="max-w-24 h-auto mx-auto"
              />
            </td>
            <td>{{ piece.code }}</td>
            <td>{{ piece.piece_type }}</td>
            <td>
            
             <div class="flex items-center justify-end text-xl  space-x-1">
                      <div>
                 {{ piece.piece_name }}
                    </div>
                       <div class="    cursor-pointer ">
                       <router-link :to="`/PieceDetails/${piece.id}`">
                      <img src="@/assets/image/list_details.svg" 
                        class="w-7 h-7 max-h-7 min-h-7 max-w-7 min-w-7 transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                        </router-link>
                        </div></div>
            </td>
            <td>{{ index + 1 }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <AddAvPieces v-if="showAdd" @addPieces="addPieces"></AddAvPieces>
    <EditAvPieces
      v-if="showedit"
      @editPieces="editPieces"
      :piece="piece"
    ></EditAvPieces>
    <DeleteComp
      v-if="showdel"
      @deleteTh="delPieces"
      text="القطعة"
      :data="piece"
    ></DeleteComp>
    <div class="items-center justify-center flex">
      <span
        >صفحة {{ pagination.current_page }} من
        {{ pagination.last_page }}
      </span>
      <button
        class="bg-primary-button text-primary rounded-lg text-center mx-1 py-2 px-3 my-1 hover:bg-hover"
        v-if="pagination.current_page < pagination.last_page"
        @click="loadAvailablePieces(pagination.current_page + 1)"
      >
        التالي
      </button>

      <button
        class="bg-primary-button text-primary rounded-lg text-center mx-1 py-2 px-3 my-1 hover:bg-hover"
        v-if="pagination.current_page > 1"
        @click="loadAvailablePieces(pagination.current_page - 1)"
      >
        السابق
      </button>
    </div>
  </div>
</template>
<script>
import AddAvPieces from "@/components/Pieces/smallComponents/AvailablePieces/AddAvPieces.vue";
import EditAvPieces from "@/components/Pieces/smallComponents/AvailablePieces/EditAvPieces.vue";
import DeleteComp from "@/components/DeleteComp.vue";
import axios from "axios";
export default {
  components: {
    AddAvPieces,
    EditAvPieces,
    DeleteComp,
  },
  props:['piece_type','piece_name'],
  data() {
    //  'piece_name','Base_num','available_num','description','image','code','piece_type'
    return {
      showAdd: false,
      showedit: false,
      showdel: false,
      answer: false,
      piece_id: 0,
      pieces: [],
      piece: {},
      allAvP:[],
      pagination: {
        current_page: 1,
        last_page: 1,
        total: 0,
      },
      searchP: "",
      searchType: "",
    };
  },
  methods: {
    addPieces(a) {
      this.showAdd = a;
      this.loadAvailablePieces();
    },
    editPieces(e, piece) {
      this.showedit = e;
      this.piece = piece;
      this.loadAvailablePieces();
    },
    delPieces(d, an, piece) {
      this.showdel = d;
      this.answer = an;
      this.piece = piece;
      this.piece.name = this.piece.piece_name;
      //    console.log(this.answer);
      //    console.log(this.piece);
      if (this.answer) {
        // console.log(this.piece);
        this.deleteAvailablePiece(this.piece);
      }
    },
    async search(page = 1) {
        // this.searchp = this.piece_type;
        this.searchType = "type";
      console.log(this.piece_type)
       try {
        const res = await axios.post(
          `/searchAvailablePieces?page=${page}`,{piece_type:this.piece_type,piece_name:this.piece_name.toLowerCase()}
        );
        console.log(res);
        this.pieces = res.data.data;
        this.pagination = {
          current_page: res.data.current_page,
          last_page: res.data.last_page,
          total: res.data.total,
        };
      } catch (e) {
        console.log(e);
      }
    },
    async loadAvailablePieces(page = 1) {
      try {
        const res = await axios.get(
          `/getAllAvailablePiecesWithPaginate?page=${page}`
        );
        console.log(res);
        this.pieces = res.data.data;
        this.pagination = {
          current_page: res.data.current_page,
          last_page: res.data.last_page,
          total: res.data.total,
        };
      } catch (e) {
        console.log(e);
      }
    },
    async deleteAvailablePiece(piece) {
      try {
        const res = await axios.post("/deleteAvailablePiece", { id: piece.id });
        console.log(res);
        this.loadAvailablePieces();
      } catch (e) {
        console.log(e);
      }
    }, async getAllAvailablePiecestoExport() {
      try {
         const res = await axios.get("/getAllAvailablePiecestoExport");
         console.log(res);
         return res.data;
        
      }
      catch (e) {
         console.log(e);
      }
   },
  },
  mounted() {
    // this.loadallAvailablePieces();
  },
  computed: {
    // findPiece() {
    //   if ((this.searchType === "name")) {
    //     return this.allAvP.filter((ps) => {
    //        ps.piece_name?.toLowerCase().includes(this.searchP.toLowerCase());
    //     });
    //   } else if ((this.searchType === "type")) {
    //     return this.allAvP.filter((ps) => {
    //        ps.piece_type?.toLowerCase().includes(this.searchP.toLowerCase());
    //     });
    //   }else{
    //     return this.pieces;
    //   }
    // },
//   },watch:{
//      findPiece() {
//       if ((this.searchType = "name")) {
//         return this.pieces.filter((ps) => {
//           return ps.piece_name.match(this.searchP);
//         });
//       } else if ((this.searchType = "type")) {
//         return this.pieces.filter((ps) => {
//           return ps.piece_type.match(this.searchP);
//         });
//       }
//     },
  }
};
</script>
<style></style>

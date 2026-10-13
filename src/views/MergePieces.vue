<template lang="">
  <div>
    <div>
      <Navbar selectN="merge" thirdR="محاضر التركيب" thirdREn="merge"></Navbar>
    </div>
    <div class="pt-5 pb-2 px-5 w-full">
      <div
        class="border border-primary-button rounded-lg space-y-1 items-center p-1"
      >
        <div class="flex justify-between w-full">
          <div class="flex items-center justify-start space-x-2 px-3 py-1">
            <button
              class="bg-primary-button rounded-lg px-6 py-1 hover:bg-hover2 text-xl"
              @click="createNewCompound()" :disabled="compound_name==='' || diwan_num==='' || Object.keys(borrow).length==0 || diwan_date===''"
            >
              جديد
            </button>
            <button
              class="bg-primary-button rounded-lg px-6 py-1 hover:bg-hover2 text-xl"
              @click="getPiecesCompound()" :disabled="compound_name==='' && diwan_num===''"
            >
              بحث
            </button>
          </div>
          <div
            class="flex justify-end space-x-2 items-center px-1 py-1 text-xl"
          >
            <select
              id="borrow"
              dir="auto"
              v-model="borrow"
              class="bg-primary text-primary-bg rounded-lg h-9 px-4"
            >
              <option
                :value="borrow"
                v-for="(borrow, index) in allBorrow"
                :key="index"
              >
                {{ borrow.diwan_num }}
              </option>
            </select>
            <label for="borrow" class="text-xl"> طلب الإستعارة</label>
          </div>
          <div
            class="flex space-x-2 items-center justify-end px-1 py-1 text-xl"
          >
            <input
              type="date"
              id="diwan_date"
              dir="auto"
              v-model="diwan_date"
              class="bg-primary text-primary-bg rounded-lg h-9 px-4"
            />
            <label for="diwan_date" class="text-xl">تاريخ الديوان</label>
          </div>
          <div
            class="flex space-x-2 items-center justify-end px-1 py-1 text-xl"
          >
            <input
              type="number"
              id="diwan_num"
              dir="auto"
              v-model="diwan_num"
              class="bg-primary text-primary-bg rounded-lg w-1/2 h-9 px-4"
            />
            <label for="diwan_num" class="text-xl">رقم الديوان</label>
          </div>
          <div
            class="flex space-x-2 items-center justify-end px-1 py-1 text-xl"
          >
            <input
              type="text"
              id="compound_name"
              dir="auto"
              v-model="compound_name"
              class="bg-primary text-primary-bg rounded-lg w-1/2 h-9 px-4"
            />
            <label for="compound_name" class="text-xl">اسم المحضر</label>
          </div>
        </div>
      </div>
    </div>
    <div class="pt-3 pb-2 px-5 w-full">
      <div class="mx-auto overflow-auto">
        <table class="w-full py-1 px-1 text-primary-bg bg-primary text-xl">
          <thead>
            <tr class="bg-primary-button text-primary text-xl py-1">
              <th style="width: 18%">الإجراء</th>
              <th style="width: 5%">العدد</th>
              <th style="width: 20%">صورة</th>
              <th style="width: 20%">نوعها</th>
              <th style="width: 20%">اسم القطعة</th>
              <th style="width: 2%">الرقم</th>
            </tr>
          </thead>
          <tbody
            v-for="(piece, index) in pieces"
            :key="index"
            class="text-center content-center"
          >
            <tr class="bg-primary">
              <td>
                  <div class="flex justify-center space-x-4">
                 
                    <div class=" px-1     cursor-pointer " @click="delPieces(true, false, piece)">
                      <img src="@/assets/image/delete.svg"
                        class="w-11 h-11  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                       <div class=" px-1  py-1   cursor-pointer " @click="addPieces(true, 'edite', false, piece)">
                      <img src="@/assets/image/edit.svg"
                        class="w-9 h-9  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                    </div>
                <!-- <button
                  class="bg-primary-button text-primary rounded-lg text-center mx-2 py-2 px-3 my-1 hover:bg-hover"
                  @click="delPieces(true, false, piece)"
                >
                  حذف
                </button>
                <button
                  class="bg-primary-button text-primary rounded-lg text-center mx-2 py-2 px-3 my-1 hover:bg-hover"
                  @click="addPieces(true, 'edite', false, piece)"
                >
                  تعديل
                </button> -->
              </td>
              <td>{{ piece.pieces_num }}</td>
              <td>
                <img
                  :src="piece.image_url"
                  v-if="piece.image"
                  class="max-w-24 h-auto mx-auto"
                />
              </td>
              <td>{{ piece.piece_type }}</td>
              <td>{{ piece.piece_name }}</td>
              <td>{{ index + 1 }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="flex justify-between w-full px-2">
    <div class="flex justify-center space-x-3 items-center px-3 py-1 text-xl">
      <button
        class="bg-primary-button rounded-lg px-6 py-1 hover:bg-hover2"
        @click="addPieces(true, 'add')"
      >
        إضافة قطعة
      </button>
    </div>
  </div>
  <div
    class="absolute right-3 bottom-3 rounded-full hover:-rotate-12 hover:bg-hover2 z-10 py-10 px-4 font-semibold text-center bg-primary-button text-primary text-xl"
  >
    <router-link :to="{ name: 'allmerge' }">عرض الكل</router-link>
  </div>
  <div id="mySidenav">
    <div
      id="second"
      class="bg-third w-40 text-center py-1 text-2xl px-3 rounded-r-xl hover:bg-hover cursor-pointer"
    >
      <router-link :to="{ name: 'teams' }">الفرق</router-link>
    </div>
    <div
      id="first"
      class="bg-first w-40 text-center py-1 text-2xl px-3 rounded-r-xl hover:bg-hover cursor-pointer"
    >
      <router-link :to="{ name: 'orders' }">طلبات الإستعارة</router-link>
    </div>
  </div>
  <AddPTM
    v-if="showAdd"
    @addPieces="addPieces"
    :sec="sec"
    :compound="compound"
    :selpiece="piece"
  ></AddPTM>
  <DeleteComp
    v-if="showDel"
    @deleteTh="delPieces"
    text="القطعة"
    :data="piece"
  ></DeleteComp>
    <transition name="toast">
    <Toast v-if="shtoast" :msg="msg"></Toast>
  </transition>
</template>
<script>
import Navbar from "@/components/Navbar.vue";
import AddPTM from "@/components/MergePieces/Pieces/AddPTM.vue";
import DeleteComp from "@/components/DeleteComp.vue";
import Toast from "@/components/Toast.vue";
import axios from "axios";
export default {
  // ['diwan_num', 'diwan_date', 'compound_name', 'borrow_id'];
  data() {
    return {
      diwan_num: "",
      diwan_date: "",
      compound_name: "",
      borrow: {},
      allBorrow: [],
      pname: "hh",
      sec: "",
      pieces: {},
      piece: {},
      showAdd: false,
      showDel: false,
      answer: false,
      compound: {},
      msg: '',
         shtoast: false
    };
  },
  methods: {
    addPieces(add, sec, answer, piece) {
      this.showAdd = add;
      this.sec = sec;
      if (sec == "edite") {
        this.piece = piece;
      }
      if (answer) {
        this.getPiecesCompound();
      }
    },
    delPieces(del, an, compPiece) {
      this.showDel = del;
      this.answer = an;

      this.piece = compPiece;
      this.piece.name = this.piece.piece_name;

      if (an) {
        this.deletePieceFromCompound();
      }
    },
    async deletePieceFromCompound() {
      try {
        const res = await axios.post("/deletePieceFromCompound", {
          compound_id: this.compound.id,
          piece_id: this.piece.id,
        });
        console.log(res);
          this.getPiecesCompound();
      } catch (error) {
        console.log(error);
      }
    },
    async getAllBorrowRequests() {
      try {
        const res = await axios.get("/getAllBorrowRequestsWithoutPaginate");
        this.allBorrow = res.data;
        console.log(res);
      } catch (error) {
        console.log(error);
      }
    },
    // ['diwan_num', 'diwan_date', 'compound_name', 'borrow_id'];
    async createNewCompound() {
      try {
        const res = await axios.post("/createNewCompound", {
          diwan_num: this.diwan_num,
          diwan_date: this.diwan_date,
          compound_name: this.compound_name,
          borrow_id: this.borrow.id,
        });
        console.log(res);
        this.pieces = res.data.data;
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
    async getPiecesCompound() {
      try {
        const res = await axios.post("/getPiecesCompound", {
          diwan_num: this.diwan_num,
          compound_name: this.compound_name,
        });
        console.log(res);
        this.pieces = res.data.pieces;
        this.compound = res.data.compound;
        this.compound_name = this.compound.compound_name;
        this.diwan_date = this.compound.diwan_date;
        this.diwan_num = this.compound.diwan_num;
        this.borrow = this.compound.borrow_request;
         this.msg = res.data.message;
        this.shtoast = true
        setTimeout(() => { this.shtoast = false }, 3000);

      } catch (e) {
        console.log(e)
        this.msg = e.response.data.error;
        this.shtoast = true
        setTimeout(() => { this.shtoast = false }, 3000);
      }
    },
  },
  mounted() {
    this.getAllBorrowRequests();
  },
  components: {
    Navbar,
    AddPTM,
    DeleteComp,
    Toast
  },
};
</script>
<style lang=""></style>

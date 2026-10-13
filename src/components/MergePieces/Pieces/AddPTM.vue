<template lang="">
  <div class="backdrop1">
    <div
      class="bg-secondary-bg px-0 mx-auto flex space-y-3 rounded-lg flex-col items-center my-32 border-3 w-96"
    >
      <div class="w-full flex justify-end mr-5 mt-2">
        <img
          src="@/assets/image/close.png"
          class="w-6 h-8 cursor-pointer"
          @click="close()"
          alt=""
        />
      </div>
      <div>
        <div class="w-full mx-auto px-4 pt-2">
          <div
            class="flex justify-between relative space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl"
          >
            <!-- <input type="text" id="piece_name"  v-model="piece_name" dir="auto"  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 "> -->
            <input
              type="text"
              id="piece_name"
              v-model="searchQuery"
              @input="filterItem"
              :disabled="sec=='edite'"
              dir="auto"
              class="bg-primary text-primary-bg rounded-lg h-9 w-3/5 px-4"
            />
            <div
              class="w-1/2 absolute left-0 top-12 right-0 rounded-lg text- text-2xl items-center border border-secondary-button mx-auto max-h-40 overflow-y-auto my-0 z-10"
              v-if="filteredItems.length > 0"
            >
              <ul
                class="list-none p-0 bg-primary text-primary-bg text-center rounded-lg"
              >
                <li
                  class="p-3 cursor-pointer border-b-secondary-button border-b"
                  v-for="(item, index) in filteredItems"
                  :key="index"
                  @click="selectItem(item)"
                >
                  {{ item.piece_name }}
                </li>
              </ul>
            </div>
            <label for="piece_name" class="mx-5 text-2xl">اسم القطعة</label>
          </div>
        </div>

        <div class="w-full mx-auto px-4 py-2">
          <div
            class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl"
          >
            <input
              type="text"
              id="piece_type"
              v-model="piece.piece_type"
              dir="auto"
              disabled
              class="bg-primary text-primary-bg rounded-lg h-9 w-3/5 px-4"
            />

            <label for="piece_type" class="mx-5 text-2xl">نوع القطعة</label>
          </div>
        </div>
        <div class="w-full mx-auto px-4 pb-1">
          <div
            class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 text-xl"
          >
            <input
              type="number"
              id="pieces_num"
              v-model="pieces_num"
              dir="auto"
              class="bg-primary text-primary-bg rounded-lg h-9 w-3/5 px-4"
            />
            <label for="pieces_num" class="mx-5 text-2xl"> العدد </label>
          </div>
        </div>
      </div>

      <div class="w-full mx-auto px-4 pb-2">
        <div
          class="flex justify-start space-x-3 items-center px-3 py-1 text-xl"
        >
          <button
            class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2"
            @click="addPieceToCompound()"
          >
            {{ sec == "add" ? "إضافة" : "تعديل" }}
          </button>
        </div>
      </div>
    </div>
     <transition name="toast">
    <Toast v-if="shtoast" :msg="msg"></Toast>
  </transition>
  </div>
</template>
<script>
import axios from "axios";
import Toast from "@/components/Toast.vue";
export default {
  components: { Toast },
  props: ["sec", "compound", "selpiece"],
  data() {
    return {
      pieces_num: "",
      pieces: [],
      searchQuery: "",
      filteredItems: [],
      selectedItem: null,
      piece: {},
      answer: false,
        msg: '',
         shtoast: false
    };
  },
  methods: {
    filterItem() {
      if (this.searchQuery) {
        this.filteredItems = this.pieces.filter((item) =>
          item.piece_name.toUpperCase().includes(this.searchQuery.toUpperCase())
        );
      } else {
        this.filteredItems = [];
      }
    },
    selectItem(item) {
      this.selectedItem = item;
      this.piece = item;
      this.searchQuery = item.piece_name;
      console.log(item);
      this.filteredItems = [];
    },
    // ['compound_id','piece_id','pieces_num'];
    async addPieceToCompound() {
      if (this.sec == "add") {
        try {
          const res = await axios.post("/addPieceToCompound", {
            compound_id: this.compound.id,
            piece_id: this.piece.id,
            pieces_num: this.pieces_num,
          });
          console.log(res);
          this.answer = true;
          this.msg = res.data.message;
          this.shtoast = true
          setTimeout(() => { this.shtoast = false }, 3000);

        } catch (e) {
          console.log(e)
          this.msg = e.response.data.message;
          this.shtoast = true
          setTimeout(() => { this.shtoast = false }, 3000);

          this.answer = false;
        }
      } else if (this.sec == "edite") {
        try {
          const res = await axios.post("/editePieceInCompound", {
            compound_id: this.compound.id,
            piece_id: this.piece.id,
            pieces_num: this.pieces_num,
          });
          console.log(res);
          this.answer = true;
          this.msg = res.data.message;
          this.shtoast = true
          setTimeout(() => { this.shtoast = false }, 3000);

        } catch (e) {
          console.log(e)
          this.msg = e.response.data.message;
          this.shtoast = true
          setTimeout(() => { this.shtoast = false }, 3000);

          this.answer = false;
        }
      }
    },
    close() {
      this.$emit("addPieces", false, this.sec, this.answer);
    },
    async loadAvailablePieces() {
      try {
        const res = await axios.get("/getAllAvailablePieces");
        console.log(res);
        const pieces = res.data;
        this.pieces = Object.values(pieces);
      } catch (e) {
        console.log(e);
      }
    },
  },
  mounted() {
    this.loadAvailablePieces();
    if (this.sec == "edite") {
      this.piece = this.selpiece;
      this.pieces_num = this.selpiece.pieces_num;
      this.selectItem(this.piece);
    }
  },
};
</script>
<style lang=""></style>

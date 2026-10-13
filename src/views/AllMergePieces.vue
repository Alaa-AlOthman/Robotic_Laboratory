<template lang="">
  <div>
    <div class="flex justify-end">
      <div
        class="bg-gradient-to-b rounded-full px-4 py-3 inline-block mt-2 mr-5 cursor-pointer hover:rotate-6"
        @click="back"
      >
        <img src="../assets/image/Arrow.png" class="w-10 h-5" alt="" />
      </div>
    </div>

    <div class="pt-3 mt-8 pb-2 px-5 w-full">
      <div class="mx-auto overflow-auto">
        <table class="w-full py-1 px-1 text-primary-bg bg-primary text-xl">
          <thead>
            <tr class="bg-primary-button text-primary text-xl py-1">
              <th style="width: 20%">الإجراء</th>
              <th style="width: 18%">طلب الإستعارة</th>
              <th style="width: 20%">التاريخ</th>
              <th style="width: 20%">رقم الديوان</th>
              <th style="width: 20%">اسم المحضر</th>
              <th>رقم</th>
            </tr>
          </thead>
          <tbody v-for="(megre, index) in allMerge" :key="index" class="text-center content-center">
            <tr class="bg-primary">
              <td class="">
                  <div class="flex justify-center space-x-4">
                 
                    <div class=" px-1     cursor-pointer " @click="deleteMergePieces(true,false,megre)">
                      <img src="@/assets/image/delete.svg"
                        class="w-11 h-11  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                       <div class=" px-1  py-1   cursor-pointer " @click="editeMergePieces(true,false,megre)">
                      <img src="@/assets/image/edit.svg"
                        class="w-9 h-9  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                    </div>
                <!-- <button
                  class="bg-primary-button text-primary rounded-lg text-center mx-4 py-2 px-3 my-1 hover:bg-hover"
                  @click="deleteMergePieces(true,false,megre)"
                >
                  حذف
                </button>
                <button
                  class="bg-primary-button text-primary rounded-lg text-center mx-4 py-2 px-3 my-1 hover:bg-hover"
                  @click="editeMergePieces(true,false,megre)"
                >
                  تعديل
                </button> -->
              </td>

              <td>{{ megre.borrow_request.diwan_num }}</td>
              <td>{{ megre.diwan_date }}</td>
              <td>{{ megre.diwan_num }}</td>
              <td>{{ megre.compound_name }}</td>
              <td>{{ index + 1 }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <DeleteComp
      v-if="showDel"
      @deleteTh="deleteMergePieces"
      text="المحضر"
      :data="compound"
    ></DeleteComp>
    <EditMergePieces
      v-if="showedit"
      @editeMergePieces="editeMergePieces"
      :compound="compound"
    ></EditMergePieces>
  </div>
</template>
<script>
import DeleteComp from "@/components/DeleteComp.vue";
import EditMergePieces from "../components/MergePieces/EditMergePieces.vue";
import axios from "axios";
export default {
  components: { DeleteComp, EditMergePieces },
  data() {
    // ['diwan_num', 'diwan_date', 'compound_name', 'borrow_id'];
    return {
      showDel: false,
      showedit: false,
      answer: false,
      allMerge: [],
      compound:{}
    };
  },
  methods: {
    back() {
      this.$router.go(-1);
    },
    deleteMergePieces(del, an,megre) {
      this.showDel = del;
      this.answer = an;
      this.compound=megre;
      this.compound.name=this.compound.compound_name;
      if(an){
        this.deleteComound();
      }
    },
    editeMergePieces(ed,all,merge) {
      this.showedit = ed;
      this.compound=merge;
      if(all){
        this.getAllCompounds();
      }
    },async deleteComound(){
     try {
      const res=await axios.post('/deleteComound',{compound_id:this.compound.id});
      console.log(res);
      this.getAllCompounds();
     } catch (error) {
      console.log(error);
     }
    },
    async getAllCompounds() {
      try {
        const res = await axios.get("/getAllCompounds");
        this.allMerge = res.data;
        console.log(res);
      } catch (error) {
        console.log(error);
      }
    },
  },
  mounted() {
    this.getAllCompounds();
  },
};
</script>
<style lang=""></style>

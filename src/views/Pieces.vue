<template lang="">
    <div>
       <div>
        <Navbar selectN="pieces"></Navbar>
       </div>
       <div class=" pt-5 pb-2 px-5  w-full">
        <div class="border border-primary-button rounded-lg flex flex-col space-y-1 items-center p-1">
            <div class="flex justify-between w-full">
                <div class="flex justify-center space-x-3 items-center  px-1 py-1 text-xl ">
                      <!-- <button class="bg-primary-button rounded-lg px-6 py-1 hover:bg-hover2">إستيراد</button> -->
                                    <ExportButton  v-if="sec=='avilableP' && Object.keys(allAvailableData).length >0" :data="allAvailableData" file-name="القطع_المتوفرة.xlsx" /> 

                      <button class="bg-primary-button rounded-lg px-2 w-3/4 py-1 hover:bg-hover2" @click="getAllAvPieces">تحميل القطع</button>
                </div> 
                <div class="flex justify-between space-x-4 items-center  px-3 py-1 text-xl ">
                     <input type="text" id="piece_name"   dir="auto" v-model="piece_name"  class=" bg-primary text-primary-bg rounded-lg  h-9  px-4 ">
                     <label for="piece_name" class="  text-2xl">اسم القطعة</label>
                </div>           
            </div>
            <div class="flex justify-between w-full">
                <div class="flex justify-center space-x-3 items-center  px-3 py-1 text-xl ">
                      <button class="bg-primary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="search()">بحث عن قطعة</button>
                      <button class="bg-primary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="addPieces()">إضافة قطعة</button>
                </div> 
                <div class="flex justify-between space-x-3 items-center  px-3 py-1 text-xl ">
                     <select id="piece_type"   dir="auto" v-model="piece_type"  class=" bg-primary text-primary-bg text-center rounded-lg  h-9  px-4 ">
                        <option value="spike">spike</option>
                        <option value="EV3">EV3</option>
                        <option value="consume">consumables</option>
                        <option value="electronic">electronics</option>
                        <option value="other">Other</option>
                        <option value="">all</option>
                     </select>
                     <label for="piece_type" class="  text-2xl">نوع القطعة</label>
                </div>           
            </div>
        </div>
       </div>
       <div class=" py-3 px-5 w-full">
           <div class="bg-second w-3/4 mx-auto h-8 rounded-lg flex justify-between items-center p-0 text-2xl">
           <button class="cursor-pointer w-full rounded-l-lg " @click="selectSec('lostP')" :class="{'bg-hover' : sec=='lostP'}">القطع الناقصة</button>
           <button class="cursor-pointer w-full " @click="selectSec('brokenP')" :class="{'bg-hover' : sec=='brokenP'}">القطع المعطوبة</button>
           <button class="cursor-pointer w-full rounded-r-lg" @click="selectSec('avilableP')" :class="{'bg-hover' : sec=='avilableP'}">القطع المتوفرة</button>
           </div>
       </div>
       
       <div  class=" py-3 px-5 w-full">
            <AvailablePieces v-if="sec=='avilableP'" ref="avilableP" :piece_type="piece_type" :piece_name="piece_name"></AvailablePieces>
            <BrokenPieces v-if="sec=='brokenP'" ref="brokenP" :piece_type="piece_type" :piece_name="piece_name"></BrokenPieces>
            <LostPieces v-if="sec=='lostP'" ref="lostP" :piece_type="piece_type" :lpiece_name="piece_name"></LostPieces>

       </div>
      <div id="mySidenav">
        <div id="third" class=" bg-third w-40 text-center  py-1 text-2xl px-3 rounded-r-xl hover:bg-hover cursor-pointer" ><router-link :to="{name:'teams'}">الفرق</router-link></div>
        <div id="second" class=" bg-second w-40  text-center  py-1 text-2xl px-3 rounded-r-xl hover:bg-hover cursor-pointer "><router-link :to="{name:'merge'}">محاضر التركيب</router-link></div>
        <div id="first" class=" bg-first w-40  text-center   py-1 text-2xl px-3 rounded-r-xl  hover:bg-hover cursor-pointer"> <router-link :to="{name:'orders'}">طلبات الإستعارة</router-link></div>
      </div>
    </div>
   
</template>
<script>
import Navbar from "@/components/Navbar.vue";
import AvailablePieces from "@/components/Pieces/AvailablePieces.vue";
import BrokenPieces from "@/components/Pieces/BrokenPieces.vue";
import LostPieces from "@/components/Pieces/LostPieces.vue";
import ExportButton from "@/components/ExportButton.vue";
import axios from "axios";

export default {
  data() {
    return {
      piece_name:'',
      piece_type:'',
      sec:'avilableP',
      allAvailableData:[]

    }
  },methods: {
    selectSec(sel){
      this.sec=sel;
    },
    addPieces(){
      if(this.sec=='avilableP'){
        this.$refs.avilableP.addPieces(true);
      }
      if(this.sec=='brokenP'){
        this.$refs.brokenP.addPieces(true);
      }
      if(this.sec=='lostP'){
        this.$refs.lostP.addPieces(true);
      }
    },
    search(){
       if(this.sec=='avilableP'){
        this.$refs.avilableP.search();
      }
      if(this.sec=='brokenP'){
        this.$refs.brokenP.search();
      }
      if(this.sec=='lostP'){
        this.$refs.lostP.search();
      }
    },async getAllAvPieces(){
      if(this.sec==='avilableP'){
        try {
          const res=await  this.$refs.avilableP.getAllAvailablePiecestoExport();
            this.allAvailableData=res;
        } catch (error) {
          console.log(error)
        }
        
      }
    }
  },
  components: {
    Navbar,
    AvailablePieces,
    BrokenPieces,
    LostPieces,
    ExportButton
 
  },mounted(){
    if(this.sec=='avilableP'){
        this.$refs.avilableP.loadAvailablePieces();
      }
    //   else if(this.sec=='avilableP'){
    //     this.$refs.avilableP.getAllLostPieces();
    //   }
  },watch:{
    sec(newVal){
      this.$nextTick(()=>{
       if(newVal=='avilableP'){
        this.$refs.avilableP?.loadAvailablePieces();
      }
      else if(newVal=='lostP'){
         this.$refs.lostP?.getAllLostPieces();
      }else if(newVal=='brokenP'){
         this.$refs.brokenP?.getAllBrokenPieces();
      }
   }); }
  }
};
</script>
<style lang="">
</style>
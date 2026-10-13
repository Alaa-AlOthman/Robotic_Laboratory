<template lang="">
    <div>
       <div>
        <Navbar selectN="orders" thirdR="طلبات الإستعارة" thirdREn="orders"></Navbar>
       </div>
       <div class=" pt-5 pb-2 px-5  w-full">
        <div class="border border-primary-button rounded-lg flex flex-col space-y-1 items-center p-1">
            <div class="flex justify-between w-full">
                <div class="flex justify-start space-x-2 items-center  px-1 py-1 text-xl ">
                    <input type="text" id="request_goal"   dir="auto" v-model="request_goal"  class=" bg-primary text-primary-bg rounded-lg  h-9 w-1/2 px-2 ">
                    <label for="request_goal" class="  text-2xl">الغرض</label>
                </div> 
                <div class="flex justify-between space-x-2 items-center  px-1 py-1 text-xl ">
                     <select id="request_type"   dir="auto" v-model="request_type"  class=" bg-primary text-primary-bg text-center   rounded-lg  h-9  px-2 ">
                        <option value="external">خارجية</option>
                        <option value="enternal">داخلية</option>
                     </select>
                     <label for="request_type" class="  text-2xl">نوع الإستعارة</label>
                </div> 
                <div class="flex justify-between space-x-2 items-center  px-1 py-1 text-xl ">
                     <input type="text" id="diwan_num"   dir="auto" v-model="diwan_num"  class=" bg-primary text-primary-bg rounded-lg  h-9 w-28  px-2 ">
                     <label for="diwan_num" class="  text-2xl">رقم الديوان</label>
                </div>           
            </div>
            
            <div class="flex justify-between w-full">
                <div class="flex justify-between space-x-2 items-center  px-1 py-1 text-xl ">
                           <input type="file"ref="excelFile"accept=".xlsx, .xls"@change="uploadExcel"class="hidden"  v-if="sec=='neworder' &&  canImport" />
<button class="bg-primary-button rounded-lg px-6 py-1 hover:bg-hover2" v-if="sec=='neworder' && canImport"  @click="$refs.excelFile.click()"
>
  استيراد قطع  
</button>
                      <button class="bg-primary-button rounded-lg px-6 py-1 hover:bg-hover2" v-if="sec=='neworder'" @click="createBorrowOrder()" :disabled="!canSubmit">إنشاء طلب جديد </button>
           

                      <button class="bg-primary-button rounded-lg px-6 py-1 hover:bg-hover2" v-if="sec=='editorder' || sec=='oldorder'" @click="searchBorrowOrder()">  بحث عن طلب </button>
                      <button class="bg-primary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="additional(true)">معلومات إضافية</button>

                </div> 
                <div class="flex justify-between space-x-2 items-center  px-1  py-1 text-xl ">
                     <select id="request_state"   dir="auto" v-model="request_state"  class=" bg-primary text-primary-bg text-center  rounded-lg  h-9  px-2 ">
                        <option value="IRFS">استلام من الطلاب</option>
                        <option value="IDTS">استلم الطلاب</option>
                        <option value="PD">تسليم جزئي</option>
                        <option value="ND">لم يستلم الطلاب</option>
                     </select>
                     <label for="request_state" class="  text-2xl">حالة الإستعارة</label>
                </div> 
                <div  class="flex justify-between space-x-2 items-center  px-1 py-1 text-xl ">
                    <input type="date" id="diwan_date"   dir="auto" v-model="diwan_date"  class=" bg-primary text-primary-bg rounded-lg  h-9  px-2 ">
                    <label for="diwan_date" class="  text-2xl">تاريخ الديوان</label>
                </div>           
            </div>
        </div>
       </div>
       <div class=" py-3 px-5 w-full">
           <div class="bg-second w-3/4 mx-auto h-8 rounded-lg flex justify-between items-center p-0 text-2xl">
           <button class="cursor-pointer w-full rounded-l-lg " @click="selectSec('oldorder')" :class="{'bg-hover' : sec=='oldorder'}">طلب قديم</button>
           <button class="cursor-pointer w-full " @click="selectSec('editorder')" :class="{'bg-hover' : sec=='editorder'}">تعديل طلب</button>
           <button class="cursor-pointer w-full rounded-r-lg" @click="selectSec('neworder')" :class="{'bg-hover' : sec=='neworder'}">طلب جديد</button>
           </div>
           <div  class=" py-3 px-5 w-full"></div>
      <OldOrder v-if="sec=='oldorder'" ref="oldOrder"  :pieces="pieces" :showButton="showButton" ></OldOrder>
      <NewOrder v-if="sec=='neworder'" ref="newOrder" :showButton="showButton" ></NewOrder>
      <EditOrder v-if="sec=='editorder'" ref="editOrder" :pieces="pieces"   :showButton="showButton"></EditOrder>
    </div>
    <AdditionalInfo @additional="additional" v-if="showAdd"  :sec="sec"></AdditionalInfo>
       </div>
        <div id="mySidenav">
        <div id="second" class=" bg-third w-40 text-center  py-1 text-2xl px-3 rounded-r-xl hover:bg-hover cursor-pointer" ><router-link :to="{name:'teams'}">الفرق</router-link></div>
        <div id="first" class=" bg-second w-40  text-center  py-1 text-2xl px-3 rounded-r-xl hover:bg-hover cursor-pointer "><router-link :to="{name:'merge'}">محاضر التركيب</router-link></div>
      </div>
      <div class="fixed right-3 bottom-3 rounded-full hover:-rotate-12 hover:bg-hover2 z-10 py-10  px-4 font-semibold text-center bg-primary-button  text-primary text-xl">
  <router-link :to="{name:'allorder'}" >عرض الكل</router-link>
     <transition name="toast">
    <Toast v-if="shtoast" :msg="msg"></Toast>
  </transition>
 </div>
</template>
<script>
import Navbar from '@/components/Navbar.vue';
import OldOrder from '@/components/Orders/OldOrder.vue';
import EditOrder from '@/components/Orders/EditOrder.vue';
import NewOrder from '@/components/Orders/NewOrder.vue'
import AdditionalInfo from '@/components/Orders/smallComponents/AdditionalInfo.vue'
import axios from 'axios';
import Toast from '@/components/Toast.vue';
import { mapGetters } from 'vuex';
import { nextTick } from 'vue';
export default {
    data() {
        // ['diwan_num','diwan_date','request_goal','team_id','request_type','request_state','request_deliver_date','request_deliver_note','request_receive_date','request_receive_note'] 
        return {
            diwan_num: '',
            request_type: '',
            request_goal: '',
            diwan_date: '',
            request_state: '',
            sec: 'neworder',
            showAdd: false,
            team: {},
            borrow: {},
            pieces: {},
            team_id: '',
            showButton: false ,
            msg: '',
         shtoast: false

        }
    }, methods: {
        selectSec(s) {
            this.sec = s;
        },
        additional(add, additionalData = null) {
            this.showAdd = add;
            if (additionalData != null) {
                console.log(additionalData)
                this.team = additionalData.team;
                // this.request_deliver_date = additionalData.request_deliver_date;
                // this.request_deliver_note = additionalData.request_deliver_note;
            }
        },
        async createBorrowOrder() {
            try {
                //['diwan_num','diwan_date','request_goal','team_id','request_type','request_state','request_deliver_date','request_deliver_note','request_receive_date','request_receive_note'] 
                const res = await axios.post("/createNewBorrowRequest", { diwan_num: this.diwan_num, diwan_date: this.diwan_date, request_goal: this.request_goal, team_id: this.team.id, request_type: this.request_type, request_state: this.request_state });
                console.log(res);
                this.borrow = res.data.borrow_request;
                this.$store.commit('borrowRequest/setBorrowRequest', this.borrow)
                this.$store.commit('borrowRequest/setPiecesInBorrow', '')
                // this.$refs.newOrder.show();
                 this.msg=res.data.message;
                 this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);

            } catch (e) {
                console.log(e)
                  this.msg = e.response.data.error;
                this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);
            }
        }, async searchBorrowOrder() {
            try{
            console.log(this.diwan_num)
            const res = await axios.post("/getBorrowRequestByDiawanNum", { diwan_num: this.diwan_num });
            console.log(res);
            this.borrow = res.data.borrow;

            this.$store.commit('borrowRequest/setBorrowRequest', this.borrow)
            //  this.$store.commit('borrowRequest/setPiecesInBorrow',this.pieces)
            if (this.sec == 'editorder') {
                await this.$refs.editOrder.show();
            }
            if (this.sec == 'oldorder') {
                await this.$refs.oldOrder.show();
            }

            this.request_type = this.borrowReq.request_type;
            this.request_goal = this.borrowReq.request_goal;
            this.diwan_date = this.borrowReq.diwan_date;
            this.request_state = this.borrowReq.request_state;
            // await nextTick();
             

            } catch (e) {
                console.log(e)
                  this.msg = e.response.data.error;
                this.shtoast = true
            setTimeout(() => { this.shtoast = false }, 3000);}

        }, async uploadExcel(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append("file", file);
            formData.append("borrow_request_id", this.borrowReq.id); // مهم!
            console.log(this.borrowReq.id)
            try {
                const res = await axios.post("/importPiecesFromExcel", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                });
                console.log(res.data);
                alert("تم استيراد القطع بنجاح ✅");
            //       if (this.sec == 'editorder') {
            //     await this.$refs.editOrder.show();
            // }
             if (this.sec == 'neworder') {
                await this.$refs.newOrder.show(); // تحديث الجدول
            }
                
            } catch (e) {
                console.error(e);
                alert("حدث خطأ أثناء الاستيراد ❌");
            }
        }

    },
    components: {
        Navbar,
        OldOrder,
        EditOrder,
        NewOrder,
        AdditionalInfo,
        Toast
    }, computed: {
        ...mapGetters('borrowRequest', ['borrowReq', 'piecesInBorrow']),

        canSubmit(){
         return this.diwan_num!==''&& this.diwan_date!==''&& this.request_state!==''&&this.request_goal!==''&&Object.keys(this.team).length>0&&this.request_type!==''
        },
        canImport(){
            return Object.keys(this.borrow).length>0
        }
    }
    , watch: {
        request_state(newVal) {
            if (newVal == 'IRFS') {
                this.showButton = true;
            } else {
                this.showButton = false;
            }
        }
    }
}
</script>
<style lang="">

</style>
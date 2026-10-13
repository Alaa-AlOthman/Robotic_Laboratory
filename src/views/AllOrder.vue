<template lang="">
    <div>
        <div class="flex justify-end">
        <div class="bg-gradient-to-b rounded-full px-4 py-3 inline-block mt-2 mr-5 cursor-pointer  hover:rotate-6" @click="back">
            <img src="../assets/image/Arrow.png" class="w-10 h-5" alt="">
        </div></div>
        <div class=" pt-3 pb-2 px-5  w-full">
            <div class="border border-primary-button rounded-lg flex flex-col space-y-1 items-center p-1">
                <div class="flex justify-between w-full">
                
                 <div class="flex justify-start space-x-4 items-center  px-3  py-1 text-xl ">
                     <select id="request_state"   dir="auto" v-model="request_state"  class=" bg-primary text-primary-bg text-center  rounded-lg  h-9  px-4 ">
                        <option value="IRFS">استلام من الطلاب</option>
                        <option value="IDTS">استلم الطلاب</option>
                        <option value="PD">تسليم جزئي</option>
                        <option value="ND">لم يستلم الطلاب</option>
                        <option value="">الكل</option>
                     </select>
                     <label for="request_state" class="  text-2xl">حالة الإستعارة</label>
                </div> 
                <div class="flex justify-center space-x-3 items-center  px-3 py-1 text-xl ">
                    <select id="request_goal"   dir="auto" v-model="request_goal"  class=" bg-primary text-primary-bg text-center   rounded-lg  h-9  px-4 ">
                        <option :value="goal" v-for="(goal, index) in seldata.goal" :key="index">{{ goal }}</option> 
                        <option value=''> الكل</option>
                     </select>
                    
                    <label for="request_goal" class="  text-2xl">الغرض</label>
                </div> 
               
                <div class="flex justify-between space-x-4 items-center  px-3 py-1 text-xl ">
                    <select id="tname" v-model="team"  dir="auto"   class=" bg-primary text-primary-bg rounded-lg  h-9  px-4 ">
                        <option :value="team" v-for="(team, index) in seldata.team" :key="index">{{ team.team_name }}</option>
                        <option value=''>جميع الفرق</option>
                    </select>
                   <label for="tname" class="  text-2xl">اسم الفريق</label>
                     
                </div>           
            </div>
            
            <div class="flex justify-between w-full">
                <div class="flex justify-between space-x-3 items-center  px-3 py-1 text-xl ">
                      <button class="bg-primary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="loadBorrowOrder" >عرض</button>
                </div> 
           
                <div  class="flex justify-between space-x-4 items-center  px-3 py-1 text-xl ">
                    <input type="date" id="diwan_date"   dir="auto" v-model="diwan_date"  class=" bg-primary text-primary-bg rounded-lg  h-9  px-4 ">
                    <label for="diwan_date" class="  text-2xl">تاريخ الديوان</label>
                </div>           
            </div>
            </div>
        </div>
        <div class="pt-3 pb-2 px-5  w-full">
        <div class="mx-auto overflow-auto">

<table class="w-full py-1 px-1  text-primary-bg bg-primary text-xl">  
        <thead>
        <tr  class="bg-primary-button text-primary text-xl py-1 ">
        <th style="width:16%">الإجراء</th>
        <th style="width:16%">حالة الإستعارة</th>
        <th style="width:16%">الغرض</th>
        <th style="width:16%">اسم الفريق</th>
        <th style="width:16%">التاريخ</th>
        <th style="width:16%" >رقم الديوان</th>
        <th></th>
        </tr></thead>
<tbody v-for="(borrow, index) in borrowrequests" :key="index">
    
        <tr class="bg-primary text-center ">
        <td class="">
                 <div class="flex justify-center space-x-4">
                 
                    <div class=" px-1     cursor-pointer " @click="deleteOrder(true,false,borrow)">
                      <img src="@/assets/image/delete.svg"
                        class="w-11 h-11  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                       <div class=" px-1  py-1   cursor-pointer " @click="editOrder(true,borrow)">
                      <img src="@/assets/image/edit.svg"
                        class="w-9 h-9  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                    </div>

            <!-- <button class=" bg-primary-button text-primary   rounded-lg text-center  mx-4 py-2 px-3 my-1 hover:bg-hover  " @click="deleteOrder(true,false,borrow)">حذف </button>
            <button class=" bg-primary-button text-primary  rounded-lg text-center  mx-4 py-2 px-3 my-1 hover:bg-hover  " @click="editOrder(true,borrow)" >تعديل</button> -->
           
        </td>
        
        <td>{{ borrow.request_state_ar }}</td>
        <td>{{ borrow.request_goal }}</td>
        <td>{{ borrow.team.team_name }}</td>
        <td>{{ borrow.diwan_date }}</td>
        <td>{{ borrow.diwan_num }}</td>
        <td>{{ index+1 }}</td>
        </tr>
    </tbody>
  
</table>
</div>
</div>
<DeleteComp v-if="showDel" @deleteTh="deleteOrder" text="الطلب" :data="borrow"  ></DeleteComp>
<EditOrder v-if="showedit" @editOrder="editOrder" :borrow="borrow" :teams="seldata.team"></EditOrder>
<div class="items-center justify-center flex">
            <span>صفحة
                    {{ pagination.current_page }} من 
                    {{ pagination.last_page }}
                </span>
            <button class=" bg-primary-button text-primary   rounded-lg text-center  mx-1 py-2 px-3 my-1 hover:bg-hover  " v-if="pagination.current_page < pagination.last_page" @click="loadBorrowOrder(pagination.current_page+1)">التالي</button> 
                
                <button class=" bg-primary-button text-primary   rounded-lg text-center  mx-1 py-2 px-3 my-1 hover:bg-hover  " v-if="pagination.current_page > 1" @click="loadBorrowOrder(pagination.current_page-1)">السابق</button>
            </div>
             <transition name="toast">
    <Toast v-if="shtoast" :msg="msg"></Toast>
  </transition>
    </div>
</template>
<script>
import DeleteComp from '@/components/DeleteComp.vue';
import EditOrder from '../components/Orders/smallComponents/Orders/EditOrder.vue';
import axios from 'axios';
import requestStateFormat from '@/Resource/FormatFunctions/requestStateFormat';
import Toast from '@/components/Toast.vue';
export default {
    components: { DeleteComp, EditOrder,Toast },
    data() {
        // ['diwan_num','diwan_date','request_goal','team_id','request_type','request_state','request_deliver_date','request_deliver_note','request_receive_date','request_receive_note'] 
        return {
            request_state: '',
            request_goal: '',
            diwan_date: '',
            team: {},
            showDel: false,
            showedit: false,
            answer: false,
            diwan_num: '',
            borrowrequests: [],
            borrow: {},
            seldata: {},
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0
            }, msg: '',
            shtoast: false
        }
    }, methods: {
        back() {
            this.$router.go(-1)
        },
        deleteOrder(del, an, borrow) {
            this.showDel = del;
            this.answer = an;
            this.borrow = borrow;
            this.borrow.name = this.borrow.diwan_num;

            if (this.answer) {
                this.deleteBorrowRequest(this.borrow);
            }
        },
        editOrder(ed, borrow) {
            this.showedit = ed;
            this.borrow = borrow;
            this.loadBorrowOrder();
        },
        async loadBorrowOrder(page = 1) {
            try {
                //['diwan_num','diwan_date','request_goal','team_id','request_type','request_state','request_deliver_date','request_deliver_note','request_receive_date','request_receive_note'] 
                const res = await axios.post(`/getAllBorrowRequests?page=${page}`, { team_id: this.team.id, request_goal: this.request_goal, request_state: this.request_state, diwan_date: this.diwan_date });

                this.borrowrequests = res.data.data;
                this.borrowrequests.forEach(borrow => {
                    borrow['request_state_ar'] = requestStateFormat(borrow['request_state']);
                });
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    total: res.data.total
                }
                console.log(res);

            } catch (e) {
                console.log(e)
            }
        },
        async load() {
            try {
                //['diwan_num','diwan_date','request_goal','team_id','request_type','request_state','request_deliver_date','request_deliver_note','request_receive_date','request_receive_note'] 
                const res = await axios.get("/getRecordsForSelect");
                // this.borrowrequests=res.data;
                this.seldata = res.data;
                console.log(res);

            } catch (e) {
                console.log(e)
            }
        }, async deleteBorrowRequest(borrow) {

            try {
                const res = await axios.post("/deleteBorrowRequest", { borrow_request_id: borrow.id });
                console.log(res);
                this.loadBorrowOrder();
                this.msg = res.data.message;
                this.shtoast = true
                setTimeout(() => { this.shtoast = false }, 3000);

            }
            catch (e) {
                console.log(e);
                this.msg = e.response.data.error;
                this.shtoast = true
                setTimeout(() => { this.shtoast = false }, 3000);
            }
        }

    }, mounted() {
        this.load();
    }
}
</script>
<style lang="">

</style>
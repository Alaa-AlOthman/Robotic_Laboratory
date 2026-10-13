<template lang="">
    <div>
       <div>
        <Navbar selectN="teams" thirdR="الفرق" thirdREn="teams"></Navbar>
        <div id="mySidenav">
        <div id="second" class=" bg-second w-40  text-center  py-1 text-2xl px-3 rounded-r-xl hover:bg-hover cursor-pointer "><router-link :to="{name:'merge'}">محاضر التركيب</router-link></div>
        <div id="first" class=" bg-first w-40  text-center   py-1 text-2xl px-3 rounded-r-xl  hover:bg-hover cursor-pointer"> <router-link :to="{name:'orders'}">طلبات الإستعارة</router-link></div>
      </div>
      <div class="mx-10 pt-5 ">
          <div class=" px-1    cursor-pointer " @click="addTeam(true,'add',false)">
            <img src="../assets/image/addgroup.svg"
              class="w-20 h-20  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
              alt="" />
          </div>
        <!-- <button class="bg-primary-button rounded-lg text-xl px-6 py-1 hover:bg-hover2" @click="addTeam(true,'add',false)">إضافة فريق</button> -->
      </div>
      <div class="mx-auto py-1 px-5 overflow-auto">
            <table class="w-full py-1 px-1  text-primary-bg bg-primary text-xl">  
                <thead>
                    <tr  class="bg-primary-button text-primary text-xl py-1 ">
                    <th style="width:20%">الإجراء</th>
                    <th style="width:20%"> الغرض</th>
                    <th style="width:20%"> أعضاء الفريق</th>
                    <th style="width:20%"> اسم المدرب</th>
                    <th style="width:20%">اسم الفريق</th>
                    <th >رقم</th>
                    </tr>
          
                </thead>
                <tbody v-for="(team, index) in teams" :key="index">
                    <tr class="bg-primary text-center text-xl font-semibold">
                    <td class=" ">
                        <div class="flex justify-center space-x-4">
                 
                    <div class=" px-1     cursor-pointer " @click="delTeam(true,false,team)">
                      <img src="@/assets/image/deletegroup.svg"
                        class="w-14 h-14  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                       <div class=" px-1  py-2 pt-3   cursor-pointer " @click="addTeam(true,'edit',false,team)">
                      <img src="@/assets/image/edit.svg"
                        class="w-9 h-9  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                    </div>
                        <!-- <button class=" bg-primary-button text-primary   rounded-lg text-center  mx-2 py-2 px-3 hover:bg-hover  " @click="delTeam(true,false,team)">حذف </button>
                        <button class=" bg-primary-button text-primary  rounded-lg text-center  mx-2 py-2 px-3 hover:bg-hover  " @click="addTeam(true,'edit',false,team)">تعديل</button> -->
                    </td>
                 
                    <td>{{ team.teams_goal }}</td>
                    <td>{{ team.team_members }}</td>
                    <td>{{ team.team_leader }}</td>
                    <td>
                      <div class="flex justify-center items-center text-2xl font-bold space-x-4">
                      <div>
                    {{ team.team_name }}
                    </div>
                       <div class=" px-1  py-2 pt-3   cursor-pointer " @click="()=>{  $store.commit('User/setTeam', team);}">
                       <router-link :to="`/StudentDashboard/${team.id}`">
                      <img src="@/assets/image/list_details.svg"
                        class="w-7 h-7  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                        </router-link>
                        </div></div>
                    </td>
                    <td>{{ index+1 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
       </div>
       <AddTeam v-if="showAdd" @addTeam="addTeam" :sec="sec" :team="team"></AddTeam>
       <DeleteComp v-if="showDel" @deleteTh="delTeam" text="الفريق" :data="team"  ></DeleteComp>
       <div class="items-center justify-center flex">
            <span>صفحة
                    {{ pagination.current_page }} من 
                    {{ pagination.last_page }}
                </span>
            <button class=" bg-primary-button text-primary   rounded-lg text-center  mx-1 py-2 px-3 my-1 hover:bg-hover  " v-if="pagination.current_page < pagination.last_page" @click="loadTeams(pagination.current_page+1)">التالي</button> 
                
                <button class=" bg-primary-button text-primary   rounded-lg text-center  mx-1 py-2 px-3 my-1 hover:bg-hover  " v-if="pagination.current_page > 1" @click="loadTeams(pagination.current_page-1)">السابق</button>
            </div>
            <!-- <transition name="toast">
    <Toast v-if="shtoast" :msg="msg"></Toast>
  </transition> -->
    </div>
</template>
<script>
// ['team_name','team_members','teams_goal','team_leader']
import Navbar from '@/components/Navbar.vue';
import AddTeam from '../components/Teams/AddTeam.vue';
import DeleteComp from '@/components/DeleteComp.vue';
import Toast from '@/components/Toast.vue';
import axios from 'axios';
export default {
    components: {
        Navbar,
        AddTeam,
        DeleteComp,
        Toast
    }, data() {
        return {
            showAdd: false,
            showDel: false,
            sec: '',
            answer: false,
            team_name: '',
            teams: [],
            team: {},
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0
            },
             msg: '',
         shtoast: false
        }
    }, methods: {
        addTeam(add, sec, updated, team) {
            this.showAdd = add;
            this.sec = sec;
            this.team = team;
            console.log(updated);
            if (updated)
                this.loadTeams();
        },
        delTeam(del, an, team) {
            this.showDel = del;
            this.answer = an;
            this.team = team;
            this.team.name = this.team.team_name;

            if (this.answer) {
                this.deleteTeam(this.team);
            }
        },
        async loadTeams(page = 1) {
            try {
                const res = await axios.get(`/getAllTeamWithPaginate?page=${page}`);
                console.log(res);
                this.teams = res.data.data;
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    total: res.data.total
                }
            }
            catch (e) {
                console.log(e);
            }
        },
        async deleteTeam(team) {
            try {
                const res = await axios.post("/deleteTeam", { id: team.id });
                console.log(res);
                this.loadTeams();
            }
            catch (e) {
                console.log(e);
            }
        }
    }, mounted() {
        this.loadTeams();
    }
}
</script>
<style lang="">

</style>
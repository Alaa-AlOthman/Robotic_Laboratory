<template>
  <div class="">
    <div class="flex justify-end">
      <div class="bg-gradient-to-b rounded-full px-4 py-3 inline-block mt-2 mr-5 cursor-pointer hover:rotate-6"
        @click="back">
        <img src="../assets/image/Arrow.png" class="w-10 h-5" alt="" />
      </div>
    </div>
    <img src="../assets/image/robot7.png" class=" h-48 w-48 only-desktop absolute transition-all duration-[3000ms] ease-in-out"
      :class="{ 'arrived': hasArrived }" alt="" :style="initialStyle" @load="startAnimation">
    <!-- <img src="../assets/image/robot6.png" class=" h-48 w-48 absolute  animate-pulse"
     alt="" style="right: 3%; top: 27%;" > -->
    <div class="p-2 md:w-3/4 w-full mx-auto">
      <!-- قسم تغيير كلمة المرور -->
      <div class="border border-secondary-button rounded-lg md:p-4 p-2 mb-6 md:w-1/2 mx-auto">
        <h2 class="md:text-2xl text-xl  mb-4 text-center">تعديل كلمة المرور</h2>
        <div class="space-y-4">
          <form action="">
            <div class="flex justify-between items-center border border-secondary-button rounded-lg px-3 py-1 mb-2 md:text-xl">
              <input type="password" v-model="oldPassword" autocomplete
                class="bg-primary text-primary-bg rounded-lg h-9 w-1/2 px-4">
              <label class="md:mx-5">كلمة المرور الحالية</label>
            </div>
            <div class="flex justify-between items-center border border-secondary-button rounded-lg px-3 py-1 md:text-xl">
              <input type="password" v-model="newPassword" autocomplete
                class="bg-primary text-primary-bg rounded-lg h-9 w-1/2 px-4">
              <label class="md:mx-5">كلمة المرور الجديدة</label>
            </div>
          </form>
          <div class="text-center">
            <button @click="updatePassword"
              class="bg-secondary-button md:text-xl text-lg rounded-lg px-6 py-1 hover:bg-hover2">تحديث</button>
          </div>
          <div class="text-red-600 text-shadow-lg   text-center pb-1  md:text-xl" v-if="errorPassword">
            <p>{{ errorPassword }}</p>
          </div>
        </div>
      </div>

      <!-- قسم المستخدمين -->
      <div class="border border-secondary-button rounded-lg px-4 pb-2 ">
        <div class="flex justify-between items-center mb-4">
          <div class=" px-1   pt-3  cursor-pointer " @click="openAddUser">
            <img src="../assets/image/addperson.svg"
              class="md:w-12 md:h-12 w-8 h-8  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
              alt="" />
          </div>
          <h2 class="md:text-2xl text-lg mt-5">إدارة المستخدمين</h2>
        </div>
        <div class="  max-h-72 overflow-auto py-0 my-0">
          <table class="w-full min-w-[350px]  px-1  text-primary-bg bg-primary md:text-xl table-auto">
            <thead class="bg-primary text-primary-bg sticky top-0 z-10 shadow-sm">
              <tr class="bg-primary-button text-primary md:text-xl py-1  text-center">
                <th class="py-2 md:px-4 ">الإجراء</th>
                <th class="py-2 md:px-4 ">الفريق</th>
                <th class="py-2 md:px-4  md:w-[30%]" style="">الاسم</th>
                <th class="py-2 md:px-4  md:w-[10%]" >#</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(user, index) in users" :key="user.id" class="border-t border-gray-200">
                <td class="py-2 md:px-4 ">
                  <div class="flex justify-center md:space-x-14">
                    <div class=" px-1     cursor-pointer " @click="editUser(user)">
                      <img src="../assets/image/edit.svg"
                        class="md:w-10 md:h-10 w-5 h-5 max-w-full  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>
                    <div class=" px-1     cursor-pointer " @click="delUser(true, false, user)">
                      <img src="../assets/image/deleteperson.svg"
                        class="md:w-10 md:h-10 w-5 h-5 max-w-full transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
                        alt="" />
                    </div>

                  </div>
                </td>
                <td class="py-0 px-4 text-center">{{ user.team?.team_name || '-' }}</td>
                <td class="py-0 px-4 text-center">{{ user.name }}</td>
                <td class="py-0 px-4 text-center">{{ index + 1 }}</td>

              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- نافذة منبثقة لإضافة/تعديل مستخدم -->
      <div v-if="showUserModal"
        class="fixed top-0 left-0 w-full h-full backdrop1 flex justify-center items-center z-40">
        <div class="bg-secondary-bg p-6 rounded-lg md:w-96 w-3/4 border-2 border-primary-button relative">
          <img src="@/assets/image/close.png" class="w-6 h-6 absolute top-3 right-3 cursor-pointer"
            @click="closeUserModal" />
          <h2 class="md:text-xl font-bold mb-4 text-center">{{ editingUser ? 'تعديل المستخدم' : 'إضافة مستخدم' }}</h2>

          <div class="space-y-3">
            <form action="">
              <div class=" w-full  mx-auto px-4 pt-2  text-right">
                <div
                  class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 md:text-xl ">
                  <input type="text" v-model="userForm.name" dir="auto"
                    class="bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4">
                  <label for="name" class=" md:mx-5  md:text-xl text-sm">اسم المستخدم</label>
                </div>
              </div>
              <div class=" w-full  mx-auto px-4 pb-0 mt-2 ">
                <div
                  class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 md:text-xl ">
                  <input type="password" v-model="userForm.password" dir="auto" autocomplete
                    class="bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4">
                  <!-- <input type="password" id="pass" v-model="form.password"  dir="auto" autocomplete  class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 "> -->
                  <label for="pass" class=" md:mx-5  md:text-xl">كلمة السر</label>
                </div>
              </div>
              <div class=" w-full  mx-auto px-4 pt-2" v-if="!editingUser" >
                <div 
                  class="flex justify-between space-x-3 items-center border border-secondary-button rounded-lg px-3 py-1 md:text-xl ">
                  <select id="team_name" v-model="team" dir="auto"
                    class="bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4">
                    <option :value="null">-لا ينتمي إلى فريق-</option>
                    <option :value="team" v-for="(team, index) in teams" :key="index">{{ team.team_name }}</option>
                  </select>
                  <!-- <input type="text" v-else id="team_name" disabled  dir="auto"
                    class=" bg-primary text-primary-bg rounded-lg  h-9  w-3/5  px-4 "> -->
                  <label for="team_name" class=" md:mx-5  md:text-2xl text-right text-sm">اسم الفريق</label>
                </div>
              </div>


              <!-- <input type="email" v-model="userForm.email" class="w-full bg-primary text-primary-bg rounded-lg px-3 py-2" placeholder="البريد الإلكتروني"> -->
            </form>
          </div>

          <div class="mt-4 text-center ">
            <button @click="saveUser" class="bg-primary-button px-5 text-xl py-1 rounded-lg hover:bg-hover2">{{
              editingUser ? 'حفظ التعديلات' : 'إضافة' }}</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Toast -->
    <div class="">
    <transition name="toast">
    <!-- -if="shtoast" -->
      <Toast v-if="shtoast"  :msg="msg" class="z-50" />
    </transition>
    <DeleteComp v-if="showdel" @deleteTh="delUser" text="" :data="user"></DeleteComp>
</div>
  </div>
</template>

<script>
import Toast from '@/components/Toast.vue';
import DeleteComp from '@/components/DeleteComp.vue';
import axios from 'axios';
export default {
  components: { Toast, DeleteComp },
  data() {
    return {
      oldPassword: '',
      newPassword: '',
      users: [],
      showUserModal: false,
      editingUser: false,
      userForm: {
        id: null,
        name: '',
        password: '',

      },
      shtoast: false,
      showdel: false,
      user: {},
      msg: '',
      hasArrived: false,
      errorPassword: '',
      teams: [],
      team: {team_name:''},
    };
  },
  methods: {
    async updatePassword() {
      try {
        const res = await axios.post('/changePassword', { old_password: this.oldPassword, new_password: this.newPassword });
        console.log(res);
        this.errorPassword = "";
        this.msg = res.data.message;
        this.shtoast = true
        setTimeout(() => { this.shtoast = false }, 3000);

      } catch (error) {
        console.log(error);
        this.msg = '';
        if (error.response.data.error)
          this.errorPassword = error.response.data.error
        if (error.response.data.errors) {
          this.errorPassword = error.response.data.errors.new_password.toString();
        }
      }
    }, async getUsers() {
      try {
        const res = await axios.get('/getUsers');
        console.log(res)
        this.users = res.data
      } catch (error) {
        console.log(error)
      }
    },
    openAddUser() {
      this.resetUserForm();
      this.editingUser = false;
      this.showUserModal = true;
    },
    editUser(user) {
      this.user = user;
      this.team = user.team;
      this.userForm = { ...user, password: '' };

      this.editingUser = true;
      this.showUserModal = true;
    },
    async deleteUser(id) {
      try {
        const res = await axios.post('/deleteUser', { id: this.user.id });
        console.log(res);
        this.msg = res.data.message;
        this.getUsers();
        this.shtoast = true
        setTimeout(() => { this.shtoast = false }, 3000);
      } catch (error) {
        console.log(error);
        this.msg = ''
      }
    },
    async saveUser() {
      if (!this.editingUser) {
        try {
          const teamID = this.team ? this.team.id : null;
          console.log(teamID)
          const res = await axios.post('/createUser', { name: this.userForm.name, password: this.userForm.password, team_id: teamID });
          console.log(res);
          this.msg = res.data.message;
          this.getUsers();
          this.shtoast = true
          setTimeout(() => { this.shtoast = false }, 3000);
        } catch (error) {
          console.log(error);
          this.msg = ''
        }
        // this.resetUserForm();
      } else {
        try {
          const teamID = this.team ? this.team.id : null;
          console.log(teamID)
          const res = await axios.post('/editUser', { id: this.userForm.id, name: this.userForm.name, password: this.userForm.password, team_id: teamID });
          console.log(res);
          this.msg = res.data.message;
          this.getUsers();
          this.shtoast = true
          setTimeout(() => { this.shtoast = false }, 3000);
        } catch (error) {
          console.log(error);
          this.msg = ''
        }
      }
    },
    closeUserModal() {
      this.showUserModal = false;
    },
    resetUserForm() {
      this.userForm = {
        id: null,
        name: '',
        password: ''
      };
    },
    delUser(del, an, user) {
      this.showdel = del;
      this.user = user;
      // this.user.name = this.user.name;

      if (an) {
        this.deleteUser();
      }
    },
    back() {
      this.$router.go(-1);
    }, startAnimation() {
      setTimeout(() => {
        this.hasArrived = true;
      }, 300);
    }, async loadTeams() {
      try {
        const res = await axios.get("/getAllTeam");
        console.log(res);
        this.teams = res.data;
      }
      catch (e) {
        console.log(e);
      }
    }
  },
  mounted() {
    this.getUsers();
    this.loadTeams();
  }, computed: {
    initialStyle() {
      return {
        top: this.hasArrived ? '3%' : '33%',
        left: this.hasArrived ? '25%' : '0'
      };
    }
  }
};
</script>

<style scoped>
.backdrop1 {
  background-color: rgba(0, 0, 0, 0.4);
}
</style>

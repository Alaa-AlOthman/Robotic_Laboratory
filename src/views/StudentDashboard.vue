<template>
  <div class="flex flex-col h-screen bg-primary-bg">
    <header class="bg-primary-bg shadow p-4 text-2xl font-semibold">
      <div class="flex justify-between items-center">
        <div class="md:text-5xl text-3xl text-primary shadow-black shadow-2xl p-3 ml-2 rounded-2xl border border-primary-button">
          <h2 class="drop-shadow-neon animate-flicker">{{ team.team_name }}</h2>
        </div>
        <div class="bg-gradient-to-b rounded-full px-4 py-3 inline-block mt-2 mr-5 cursor-pointer hover:rotate-6"
          @click="back" v-if="role == 'admin'">
          <img src="../assets/image/Arrow.png" class="w-10 h-5" alt="" />
        </div>
        <div v-else class=" px-1   md:pt-3 pt-1  cursor-pointer " @click="logout">
          <img src="@/assets/image/logout.svg"
            class="w-12 h-12  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"
            alt="" />
        </div>

      </div>

    </header>
    <div class="flex flex-1 ">


      <main class="flex-1 flex flex-col p-4 overflow-y-auto">

        <div
          class="flex-1 flex flex-col bg-primary-button md:text-2xl font-TimesNewRoman  rounded shadow-black shadow-2xl  overflow-hidden">
          <div class="flex justify-end  ">
            <button v-for="(tab, index) in tabs" :key="tab.id" @click="activeTab = tab.id" :class="{
              'py-3 px-6   focus:outline-none transition-colors duration-200': true,
              'border-2  border-third text-primary-button bg-primary': activeTab === tab.id,
              'text-primary hover:text-first  hover:bg-primary': activeTab !== tab.id
            }">
              {{ tab.name }}
            </button>
          </div>

          <div class="flex-1 p-6 bg-primary-bg  w-full ">
            <div v-if="activeTab === 'Borrow'">
              <BorrowedPieces :borrowed="borrowedPieces" />
              <div class="text-center my-2 ">
                <ExportButton :data="borrowedPieces" file-name="القطع_المستعارة.xlsx" />
              </div>

            </div class=" p-1 bg-primary-bg  w-full ">
            <div v-else-if="activeTab === 'pieces'">
              <RequestPreparation :pieces="allPieces" @updateRequest="updateRequest" />
              <!-- زر تصدير الطلب (لاحقًا زر الطباعة) -->
              <div class="text-center my-2">
              <ExportButton :data="requestedPieces" file-name="طلب_استعارة.xlsx" /> 
              </div>

            </div>

          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import BorrowedPieces from '@/components/studentInterface/BorrowedPieces.vue';
import RequestPreparation from '@/components/studentInterface/RequestPreparation.vue';
import ExportButton from '@/components/ExportButton.vue';
import axios from 'axios';
import { mapGetters } from 'vuex';

export default {
  props: ['team_id'],
  components: { BorrowedPieces, RequestPreparation, ExportButton },
  data() {
    return {

      borrowedPieces: [],
      allPieces: [],
      requestedPieces: [],
      activeTab: 'Borrow', // التبويب النشط الافتراضي
      tabs: [
        { id: 'Borrow', name: 'القطع المستعارة' },
        { id: 'pieces', name: ' إعداد طلب استعارة' },

      ],

    };
  },
  methods: {
    back() {
      this.$router.go(-1);
    }, async logout() {

      try {
        const res = await axios.get("/logout");
        console.log(res);
        this.success = false;
        this.$emit("success", this.success);
        this.$store.commit('setSuccessLogin', this.success);
        this.$store.commit('setHomeAnimation', this.success);
        localStorage.removeItem('token')

        this.$router.push('/')
      } catch (e) {
        console.log(e);
        this.success = true;
      }
    },
    async fetchData() {
      const res1 = await axios.post('/student/borrowed', { team_id: this.team_id }); // القطع المستعارة
      this.borrowedPieces = res1.data;
      console.log(res1)

      const res2 = await axios.get('/pieces'); // كل القطع المتوفرة للاستعارة
      this.allPieces = res2.data;
      console.log(res2)
    },
    updateRequest(updated) {
      this.requestedPieces = updated;
    }
  },
  mounted() {
    this.fetchData();

  }, computed: {
    ...mapGetters('User', ['username', 'role', 'team'])
  },
};
</script>

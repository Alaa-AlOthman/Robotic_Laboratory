<template lang="">
    <div class="w-full h-16 flex justify-end  text-2xl   ">
        <div v-if="thirdREn" class=" px-5 hover:bg-hover2 " :class="{'bg-gradient-to-b':selectN== thirdREn}"><router-link :to="{name:thirdREn}"><p class="mt-5">{{thirdR}}</p></router-link></div>
        <div class=" px-5 hover:bg-hover2 only-desktop" :class="{'bg-gradient-to-b':selectN=='pieces'}"><router-link :to="{name:'pieces'}"><p class="mt-5">القطع</p></router-link></div>
        <div class=" px-5 hover:bg-hover2 only-desktop" :class="{'bg-gradient-to-b':selectN=='home'}"><router-link :to="{name:'home'}"><p class="mt-5 ">الرئيسية</p></router-link></div>
         <div class=" px-1   pt-2  cursor-pointer " ><router-link :to="{name:'AdminProfile'}">
        <img src="../assets/image/person.svg" class="w-14 h-14  transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)] " alt="" />
        </router-link></div>
        <div class=" px-1   pt-3  cursor-pointer " @click="logout" >
        <img src="../assets/image/logout.svg" class=" w-12 h-12 transition-all duration-300 hover:scale-105 hover:brightness-150 hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]" alt="" />
        </div>
        
        <RouterView />
        <div class="absolute left-1  ">
            <img src="../assets/image/logo.png" class="w-20 h-20  brightness-200 animate-pulse" alt="">
        </div>
    </div>
</template>
<script>
import axios from "axios";
import { mapGetters } from "vuex";

export default {
    props: ['selectN', 'thirdR', 'thirdREn'],
    data() {
        return {
            success: true
        }
    }, methods: {
        async logout() {

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
        }
    }, computed: {
      ...mapGetters('User', ['username', 'role'])
    },

}

</script>
<style lang="">

</style>
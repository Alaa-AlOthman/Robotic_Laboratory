<template>
 <div class="bg-primary-bg">
  <Login v-if="!successLogin" ></Login>  
 <div>
    <Navbar selectN="home"></Navbar>
     <RouterView />
 </div>
 <div>
   <div class="flex flex-col absolute bottom-0 md:text-3xl text-2xl left-0 w-3/4"> 
    
           <img src="../assets/image/robot2.png"   class=" md:h-44 md:w-44 h-28 w-28 only-desktop animate-bounce "  alt="">
        <div class="box bg-third rounded-tr-3xl text-center md:h-20 h-10 only-desktop  py-16  hover:bg-hover cursor-pointer md:w-[200px]  w-[100px]" ><router-link :to="{name:'teams'}">الفرق</router-link></div>
        <div class="box bg-second  rounded-tr-3xl text-center md:h-20 h-10 only-desktop  py-16  hover:bg-hover cursor-pointer md:w-[300px] w-[200px]" ><router-link :to="{name:'merge'}">محاضر التركيب</router-link></div>
        <div class="box bg-first  rounded-tr-3xl text-center md:h-20 h-10 only-desktop  py-16   hover:bg-hover cursor-pointer md:w-[404px] w-[304px]" > <router-link :to="{name:'orders'}">طلبات الإستعارة</router-link></div>
      </div>
    <div class="" > 
        <!-- <transition name="scale" appear @beforeEnter="beforeEnter" @enter="enter" @after-enter="afterEnter"> -->
        <!-- <img src="../assets/image/home.png"  v-if="homeAnimation" class=" h-3/4 absolute bottom-0 right-0" alt=""> -->
        <!-- </transition> -->
        <!-- <img src="../assets/image/home.png"  v-if="homeAnimation2" class=" h-3/4 absolute bottom-0 right-0" alt=""> -->
     
        <img src="../assets/image/home.png"   class=" md:h-3/4 h-1/2 absolute bottom-0 right-0" alt="">
        <img src="../assets/image/iconRotate.png" class="md:w-10 md:h-10 h-10 w-10 absolute md:bottom-1/2 md:right-[430px] right-[220px] bottom-[46%]  animate-spin" alt="" style="">
        <img src="../assets/image/iconRotate.png" class="md:w-24 md:h-24 h-16 w-16 absolute md:bottom-1/2 md:right-[340px] bottom-[46%]  right-[265px] animate-spin" alt="" style="">
        <img src="../assets/image/iconRotate.png" class="md:w-28 md:h-28 h-20 w-20 absolute md:right-[415px] md:bottom-[57%] bottom-[35%] right-[220px] animate-spin" alt="" style="">
       

    </div>
 </div>
 </div>

</template>
<script>
import Navbar from '@/components/Navbar.vue';
import Login from '@/components/Login.vue';
import { mapGetters } from 'vuex'
import gsap from 'gsap'
export default{
  
    data() {
        return {
            success:false
        }
    },methods: {
      
        beforeEnter(el) {
            el.style.opacity = 0
        },
        enter(el, done) {
            gsap.to(el, {
                x: 0,
                opacity: 1,
                duration: 3,
                onComplete: done,
                ease: 'in'
            })

        },
        afterEnter(el, done) {
            this.$store.commit('setHomeAnimation', false)
            gsap.to(el, {
                opacity: 0,
                duration: 0.1,
                onComplete: done,
            })
            setTimeout(() => {
                this.$store.commit('setHomeAnimation2', true)
            }, 1000);

        }
    },computed:{
        ...mapGetters([ "homeAnimation", "homeAnimation2","successLogin"]),
    },
    components:{
        Navbar,
        Login
    }
}
</script>
<style>
.box:hover{
 transform: scaleX(1.1);
}

.wheel {
  animation: spinw 2s linear infinite;
}
@keyframes spinw {
  0% { transform: rotateZ(-30deg); }
  25%{transform: rotateZ(0deg);}
  50%{transform: rotateZ(30deg);}
  75%{transform: rotateZ(0deg);}
  100% { transform: rotateZ(-30deg); }
}
</style>

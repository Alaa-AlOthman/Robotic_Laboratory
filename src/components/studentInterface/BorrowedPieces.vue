<template>
  <div>
    <h2 class="md:text-2xl text-xl animate-flicker text-center text-primary tracking-widest font-arial  mb-2">القطع المستعارة حاليًا</h2>
    <div class="  max-h-96 overflow-y-auto py-0 my-0 md:w-3/4 mx-auto text-center">
      <table class="w-full  px-1  text-primary-bg bg-primary md:text-xl table-auto rounded-xl">
        <thead class="bg-primary text-primary-bg sticky top-0 z-10 shadow-sm">
          <tr class="bg-primary-button text-primary md:text-xl py-1  text-center">

            <th>تاريخ الاستعارة</th>
            <th style="width: 20%;">العدد</th>
            <th style="width:40% ;">اسم القطعة</th>
            <th style="width: 10%;">#</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in borrowed" :key="index" class="border-t border-gray-200">


            <td>{{ item.borrow_date }}</td>
            <td>{{ item.quantity }}</td>
            <td>{{ item.piece_name }}</td>
            <td>{{ index+1 }}</td>
          </tr>
        </tbody>
      </table>
    </div>
        <div class="overflow-hidden absolute only-desktop bottom-14 left-4">
         <transition name="robot-fade">
                     <img v-if="show" src="@/assets/image/robot4.png"   class=" h-48 w-48  " 
                   
                       alt=""></img>
       <!--   :class="['transition-all duration-1000 ease-out',show?'scale-100 opacity-100' : 'scale-0 opacity-0',]" -->
         </transition>
      </div>
  </div>
</template>

<script>
export default {
  props: ['borrowed'],
  data() {
    return {
      show:false
    }
  },
  mounted() {
      setTimeout(() => {
      this.show=true
    }, 200);
  },
};
</script>
<style scoped>
.robot-fade-enter-from{
  transform: scale(0.2) translateZ(-200px) ;
  opacity: 0;
}
.robot-fade-enter-active{
  transition: all 5s ease-out;
  transform-style: preserve-3d;
}
.robot-fade-enter-to{
   transform: scale(1) translateZ(0) ;
  opacity: 1;
}

</style>

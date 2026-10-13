<script setup>
import { RouterLink, RouterView } from 'vue-router'

</script>

<template>
  <div>
 
  <RouterView /></div>
</template>

<style >
#mySidenav div {
  position: absolute; 
  left: -135px;
  transition: 0.3s; 
  z-index: 0; 
}
#mySidenav div:hover {
  left: 0;
}

#second{
  bottom: 70px;
}

#third{
  bottom: 120px;
}
#first{
  bottom: 20px;
}
/*toast animation */ 
.toast-enter-active{
   /* transition: all 0.3s ease; */
   animation: wobble 0.5s ease;
  }
  
    .toast-leave-to{
      opacity: 0;
      transform: translateY(-60px);
    }
    .toast-leave-active{
      transition: all 0.3s ease;
    }
    @keyframes wobble{
      0%{  opacity: 0; transform: translateY(-60px);}
      50%{opacity: 1;  transform: translateY(0px);} 
      60%{transform: translateX(8px);}   
      70%{transform: translateX(-8px);}   
      80%{transform: translateX(4px);}   
      90%{transform: translateX(-4px);}   
      100%{transform: translateX(0px);}   
   }
   /* */
   @media (max-width:768px) {
    .only-desktop{
      display: none;
    }
    
   }
</style>

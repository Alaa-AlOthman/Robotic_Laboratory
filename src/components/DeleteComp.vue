<template lang="">
    <div class="backdrop1 z-10 " >
      <div class="bg-secondary-bg px-0 mx-auto  flex space-y-3 rounded-lg flex-col items-center md:my-32 my-44 text-right border-3 w-3/4 md:w-96">
 
          <div class="w-full flex justify-end mr-5 mt-2">
                <img src="@/assets/image/close.png" class="w-6 h-8 cursor-pointer" @click="close()" alt="" />
          </div>
          <div class=" w-full  mx-auto px-4 pb-2 ">
          <div class="flex flex-col justify-normal items-center  px-3 py-1 md:text-2xl ">
          <p>: هل أنت متأكد من أنّك تريد حذف {{text}}</p>
          <p>{{data.name}}</p>
       </div>
          </div>
      <div class=" w-full  mx-auto px-4 pb-2 ">
          <div class="flex justify-between space-x-3 items-center  px-3 py-1 text-xl ">
            <button class="bg-secondary-button rounded-lg px-6 py-1 hover:bg-hover2" @click="handle(true)" >حذف</button>
       </div>
      </div>
      </div>
     
    </div>
  </template>
<script>

export default {
    props:['text','data'],
   data(){
    return{
      pname:'',
      answer:false
    }
   },methods:{
    handle(an){
        this.answer=an;
        this.close();
    },
    close(){
      this.$emit('deleteTh',false,this.answer,this.data)
    }
   },mounted(){
    console.log(this.data);
   }
   
  
}
</script>
<style lang="">
    
</style>
<template lang="">
    <div class="bg-gray-50 z-10 border-secondary-button border-2 md:text-xl text-primary-button md:font-semibold  fixed left-[100px]  top-5 md:top-1/3 md:left-1/3  h-24 md:w-96 rounded-lg  pt-8 p-5 text-center ">
      {{ msg}}
    </div>
</template>
<script>
export default {
    props:['msg']
}
</script>
<style lang="">
    
</style>
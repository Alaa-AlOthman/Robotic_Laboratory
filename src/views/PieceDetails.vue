<template>
  <div>
    <div class="flex justify-end my-4">
      <div
        class="bg-gradient-to-b rounded-full px-4 py-3 inline-block mt-2 mr-5 cursor-pointer hover:rotate-6"
        @click="back"
      >
        <img src="../assets/image/Arrow.png" class="w-10 h-5" alt="" />
      </div>
    </div>

    <div class="p-6 max-w-5xl mx-auto">
      <div class="bg-primary text-primary-bg rounded-2xl shadow-black shadow-xl p-6 space-y-4">

        <h2 class="text-3xl font-bold text-center text-primary-button animate-pulse"> {{ piece.piece_name }}</h2>

          <div class="flex items-center space-x-28 justify-center ">
        
        <div  class="flex-col">
        <div>
            <strong>العدد الكلي:</strong> {{ piece.Base_num }}
          </div>
          <div>
            <strong>عدد المعطوب:</strong> <span class="text-red-500">{{ brokenCount }}</span>
          </div>
          <div>
            <strong>عدد الناقص:</strong> <span class="text-yellow-500">{{ lostCount }}</span>
          </div>
        </div>
        <div class="flex justify-center">
          <img
            :src="piece.image_url"
            v-if="piece.image_url"
            alt="صورة القطعة"
            class="w-52 h-52 object-contain rounded-xl border-2 border-gray-300 shadow-md"
          />
          <div v-else class="text-gray-500">لا توجد صورة</div>
        </div>
        </div>
   

        <!-- محاضر التركيب -->
        <div class="text-right my-2">
        <div class="w-full justify-center flex text-center  ">
          <h3 class="text-2xl font-bold text-primary-button mb-2   p-4 w-80 ">محاضر التركيب المرتبطة</h3>
          </div>
          <div v-if="compoundList.length">
            <div class="space-y-2  space-x-4 flex flex-wrap gap-4 justify-end   items-stretch">
              <div
                v-for="(compound, index) in compoundList"
                :key="index"
                class="bg-white   text-black p-4 text-xl rounded-lg shadow-black shadow-sm border sm:w-[48%] lg:w-[30%] w-full max-h-44 h-44 flex flex-col justify-between   "
              >
                <p> {{ compound.compound_name }}  <strong  class="text-gray-600 font-semibold">اسم المحضر</strong></p>
                <p><strong class="text-gray-600 font-semibold">رقم الديوان:</strong>   {{ compound.diwan_num }}</p>
                <p><strong class="text-gray-600 font-semibold">تاريخ الديوان:</strong>   {{ compound.diwan_date }}</p>
                <p><strong class="text-gray-600 font-semibold">عدد القطع في المحضر:</strong>   {{ compound.pieces_num }}</p>
              </div>
            </div>
          </div>
          <p v-else class="text-gray-500">لا توجد محاضر تركيب مرتبطة.</p>
        </div>

        <!-- طلبات الاستعارة -->
        <div  class="text-right m-4">
        <div class="w-full justify-center flex text-center  ">
        <h3 class="text-2xl font-bold text-primary-button mb-2     p-4 w-80">طلبات الاستعارة المرتبطة</h3>
        </div>
          
          <div v-if="borrowList.length">
            <div class="space-y-2  space-x-4 flex flex-wrap gap-4 justify-end  items-stretch">
              <div
                v-for="(borrow, index) in borrowList"
                :key="index"
                class="bg-white text-black p-4 rounded-lg text-xl shadow-black shadow-sm border  sm:w-[48%] lg:w-[30%] w-full max-h-44 h-44 flex flex-col justify-between "
              >
                <p><strong class="text-gray-600 font-semibold">رقم الديوان:</strong> {{ borrow.diwan_num }}</p>
                <p><strong class="text-gray-600 font-semibold">تاريخ الديوان:</strong> {{ borrow.diwan_date }}</p>
                <p><strong class="text-gray-600 font-semibold">العدد المطلوب:</strong> {{ borrow.requested_pieces_num }}</p>
                <p><strong class="text-gray-600 font-semibold">العدد المسلم:</strong> {{ borrow.delivered_pieces_num }}</p>
                <p><strong class="text-gray-600 font-semibold">العدد المستلم:</strong> {{ borrow.piece_recieve_num }}</p>

                
              </div>
            </div>
          </div>
          <p v-else class="text-gray-500">لا توجد طلبات استعارة مرتبطة.</p>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      piece: {},
      brokenCount: 0,
      lostCount: 0,
      borrowList: [],
      compoundList: [],
    }
  },
  async mounted() {
    const pieceId = this.$route.params.piece_id
    try {
      const res = await axios.post('/getPieceDetails', { piece_id: pieceId })
      const data = res.data

      this.piece = data.piece
      this.brokenCount = data.broken_count
      this.lostCount = data.lost_count
      this.borrowList = data.borrow_list
      this.compoundList = data.compound_list
    } catch (e) {
      console.error('فشل في جلب معلومات القطعة:', e)
    }
  },
  methods: {
    back() {
      this.$router.go(-1)
    },
  },
}
</script>

<style scoped>
</style>


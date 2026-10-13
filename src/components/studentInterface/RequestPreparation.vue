<template>
  <div class="mt-1 ">

    <div class="border border-primary-button rounded-lg md:w-3/4 p-2 mx-auto flex md:justify-between justify-end my-1">

      <div class="flex justify-end space-x-3 items-center  px-3 py-1 md:text-xl ">
        <select id="piece_type" dir="auto" v-model="piece_type"
          class=" bg-primary text-primary-bg text-center rounded-lg  h-9  px-4 ">
          <option value="spike">spike</option>
          <option value="EV3">EV3</option>
          <option value="consume">consumables</option>
          <option value="electronic">electronics</option>
          <option value="other">Other</option>
          <option value="">all</option>
        </select>
        <label for="piece_type" class="  md:text-2xl">نوع القطعة</label>
      </div>
      <h2 class="text-2xl animate-flicker text-center text-primary tracking-widest font-arial only-desktop mb-2">إعداد طلب استعارة
      </h2>
    </div>
    <div class="  max-h-96 overflow-y-auto py-0 my-0 md:w-3/4 mx-auto text-center">
      <table class="w-full  px-1  text-primary-bg bg-primary md:text-xl table-auto rounded-xl">
        <thead class="bg-primary text-primary-bg sticky top-0 z-10 shadow-sm">
          <tr class="bg-primary-button text-primary md:text-xl py-1  text-center">

            <th>تحديد العدد</th>
            <th>اسم القطعة</th>
            <th>#</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(piece, index) in findPiece" :key="piece.id">
            <td>
              <input type="number" min="0"
                class="text-center w-20 bg-primary text-primary-bg rounded-lg border border-primary-button"
                v-model.number="request[piece.id]" />
            </td>
            <td>{{ piece.piece_name }}</td>
            <td>{{ index + 1 }}</td>

          </tr>
        </tbody>
      </table>
    </div>
    <div class="overflow-hidden only-desktop absolute z-10 animate-hand-push " style="top:36.5%; left:6%;">
      <transition name="">
        <img src="@/assets/image/robot6.png" class=" h-32 w-32  " alt=""></img>

      </transition>
    </div>
    <div class="overflow-hidden absolute  only-desktop z-10 animate-zigzag" style="top:47%; left:5%;">
      <transition name="">
        <img src="@/assets/image/iconRotate.png" class=" h-12 w-12 animate-spin  " alt=""></img>

      </transition>
    </div>
  </div>
</template>

<script>
export default {
  props: ['pieces'],
  data() {
    return {
      request: {},
      piece_type: ''
    };
  },computed: {
     findPiece() {
      if(this.piece_type=='all'){
        return this.pieces;
      }else{
        return this.pieces.filter((ps) => {
          // console.log(ps.piece_type)
          return ps.piece_type.includes(this.piece_type);
        });
      }
  }},
  watch: {
    request: {
      deep: true,
      handler(newRequest) {
        // فقط القطع المطلوبة > 0
        const filtered = Object.entries(newRequest)
          .filter(([id, quantity]) => quantity > 0)
          .map(([id, quantity]) => {
            const piece = this.pieces.find(p => p.id == id);
            return {
              piece_id: piece.id,
              piece_name: piece.piece_name,
              quantity: quantity
            };
          });

        this.$emit('updateRequest', filtered);
      }
    }
  }
};
</script>
<style scoped>
@keyframes zigzagMove {
  0% {
    transform: translate(0, 0);
  }

  10% {
    transform: translate(-30px, 20px);
  }

  20% {
    transform: translate(-60px, 40px);
  }

  30% {
    transform: translate(-30px, 60px);
  }

  40% {
    transform: translate(0px, 80px);
  }

  50% {
    transform: translate(30px, 100px);
  }

  60% {
    transform: translate(60px, 120px);
  }

  70% {
    transform: translate(30px, 140px);
  }

  80% {
    transform: translate(0px, 160px);
  }

  90% {
    transform: translate(-60px, 180px);
  }

  100% {
    transform: translate(0, 200px);
  }
}

.animate-zigzag {
  animation: zigzagMove 5s linear 2 alternate;
  animation-delay: 0.7s;
  animation-fill-mode: both;
}

@keyframes handPush {
  0% {
    transform: translate(0, 0);
  }

  100% {
    transform: translate(-8px, 8px);
  }

  /* 20% {
    transform: translate(-5px, 2px)  ;
  }
 
  40% {
    transform:translate(-8px, 4px)  ;
  }
  60% {
    transform: translate(-5px, 6px)  ;
  }

  80% {
    transform:translate(-3px, 7px)  ;
  }

  100% {
    transform: translate(0px, 8px)  ;
  } */
}

.animate-hand-push {
  animation: handPush 0.4s linear 1 alternate-reverse;
  animation-delay: 0.6s;
  /* animation-fill-mode: both; */
}
</style>

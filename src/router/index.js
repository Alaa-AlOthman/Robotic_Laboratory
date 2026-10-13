import { createRouter,
  createMemoryHistory
  //  createWebHistory 
  } from 'vue-router'
import HomeView from '../views/Home.vue';
import Pieces from '../views/Pieces.vue';
import Orders from '../views/Orders.vue';
import Teams from '../views/Teams.vue';
import AllOrder from '../views/AllOrder.vue';
import MergePieces from '../views/MergePieces.vue';
import AllMergePieces from '../views/AllMergePieces.vue';
import AdminProfile from '../views/AdminProfile.vue';
import StudentDashboard from '@/views/StudentDashboard.vue';
import PieceDetails from '@/views/PieceDetails.vue';
const router = createRouter({
  // history: createWebHistory(import.meta.env.BASE_URL),
  history: createMemoryHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },  {
      path: '/StudentDashboard/:team_id',
      name: 'StudentDashboard',
      component: StudentDashboard,
      props:true
    }, {
      path: '/PieceDetails/:piece_id',
      name: 'PieceDetails',
      component: PieceDetails,
      props:true
    },
    {
      path:'/AdminProfile',
      name:'AdminProfile',
      component:AdminProfile
    },
    {
      path:'/pieces',
      name:'pieces',
      component:Pieces
    },{
      path:'/orders',
      name:'orders',
      component:Orders
    },{
      path:'/teams',
      name:'teams',
      component:Teams
    },{
      path:'/allorder',
      name:'allorder',
      component:AllOrder
    },{
      path:'/merge',
      name:'merge',
      component:MergePieces
    },{
      path:'/allmerge',
      name:'allmerge',
      component:AllMergePieces
    }
   
  ]
})

export default router

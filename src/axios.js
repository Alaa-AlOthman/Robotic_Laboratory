import axios from "axios";
axios.defaults.baseURL = `http://${window.location.hostname}/Robotic/BACKEND/project/public/api/`;

axios.interceptors.request.use(config =>{
   const token=localStorage.getItem('token')
   if(token){
    config.headers.Authorization=`Bearer ${token}`
   } 
   return config
})
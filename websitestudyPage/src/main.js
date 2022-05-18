import Vue from 'vue';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import App from './App.vue';
import router from './router';
import axios from 'axios';

Vue.use(ElementUI); //全局引入element ui
Vue.config.productionTip = true; //开发模式
Vue.prototype.$username = localStorage.getItem('username') //用户名
Vue.prototype.$token = ()=>{
  return localStorage.getItem('token') //token
}

//---------------router start---------------
//配置路由守卫判断有没有登录，如果没有登录就跳转到登录页面
router.beforeEach((to, from, next)=>{
  if(Vue.prototype.$token() || to.path === "/login" || to.path === "/root"){
    next()
  }else{
    Vue.prototype.$message.error('你还没有登录')
    next('/login')
  }
})


//---------------router end---------------



//---------------axios start---------------
//默认配置
Vue.prototype.$axios = axios.create({
  baseURL: "http://192.168.1.50/websitestudyServe/public/index.php/api/",
  timeout: 10000,
})

//请求拦截器, 每次请求都带上token
Vue.prototype.$axios.interceptors.request.use(function(config){
  config.url = `${config.url}?token=${localStorage.getItem('token')}`
  return config
})

//相应拦截器, 对相应状态码进行处理
Vue.prototype.$axios.interceptors.response.use((response) => {
  if(response.data.state === 1){  //状态码1 成功
    return response
  }else if(response.data.state === 2){  //状态码2 失败有错误信息
    Vue.prototype.$message.error(response.data.msg)
    return response
  }else if(response.data.state === 3){  //状态码3  需要登录
    Vue.prototype.$message.error(response.data.msg)
    router.push('/login')
  }else if(response.data.state === 4){  //状态码4 需要登录
    Vue.prototype.$message.error(response.data.msg)
    router.push('/login')
  }else if(response.data.state === 5){  //状态码5 表单出错
    Object.keys(response.data.msg).forEach(item => {
      Vue.prototype.$message.error(response.data.msg[item][0])
    })
  }else{
    return response
  }
})

//---------------axios end---------------


new Vue({
  router,
  render: h => h(App)
}).$mount('#app')


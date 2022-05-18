import Vue from 'vue'
import VueRouter from 'vue-router'
import loginVis from '../views/loginVis.vue'
import indexVis from '../views/indexVis.vue'
import videoVis from '../views/videoVis.vue'
import practiseVis from '../views/practiseVis.vue'

import rootVis from '../views/rootVis.vue'
import rootIndexVis from '../views/rootIndexVis.vue'
import rootStudentVis  from '../views/rootStudentVis.vue'
import rootAddStudentVis from '../views/rootAddStudentVis.vue'
import rootVideoVis from '../views/rootVideoVis.vue'
import rootInfoVis from '../views/rootInfoVis.vue'
import rootAddVideoVis from '../views/rootAddVideoVis.vue'
import rootPractiseVis from '../views/rootPractiseVis.vue'
import rootAddPractiseVis from '../views/rootAddPractiseVis.vue'
import page404Vis from '../views/404Vis.vue'

Vue.use(VueRouter)

const originalPush = VueRouter.prototype.push
VueRouter.prototype.push = function push(location, onResolve, onReject) {undefined
if (onResolve || onReject) return originalPush.call(this, location, onResolve, onReject)
return originalPush.call(this, location).catch(err => err)
}

const routes = [
  //404
  {
    path: '*',
    name: '404',
    component: page404Vis
  },
  
  //主页路由
  {
    path: '/',
    name: 'indexVis',
    component: indexVis
  },

  //登录路由
  {
    path: '/login',
    name: 'loginVis',
    component: loginVis
  },
  
  //视频路由
  {
    path: '/video',
    name: 'videoVis',
    component: videoVis,
  },

  //后台登录
  {
    path: '/root',
    name: 'rootVis',
    component: rootVis,
  },

  //练习中心
  {
    path: '/practise',
    name: 'practiseVis',
    component: practiseVis,
  },

  //后台主页
  {
    path: '/rootIndex',
    name: 'rootIndexVis',
    component: rootIndexVis,
    redirect: '/rootIndex/info',
    children: [
    {
      path: 'student',
      name: 'student',
      component: rootStudentVis,
    },
    {
      path: 'video',
      name: 'video',
      component: rootVideoVis,
    },
    {
      path: 'addvideo',
      name: 'addvideo',
      component: rootAddVideoVis
    },
    {
      path: 'addstudent',
      name: 'addstudent',
      component: rootAddStudentVis
    },
    {
      path: 'practise',
      name: 'practise',
      component: rootPractiseVis
    },
    {
      path: 'addpractise',
      name: 'addpractise',
      component: rootAddPractiseVis
    },
    {
      path: 'info',
      name: 'info',
      component: rootInfoVis,
    }]
  },
]

const router = new VueRouter({
  base: process.env.BASE_URL,
  routes
})

export default router

<template>
  <div id="rootIndex">
    <div class="main">
      <div class="mainLeft">
        <header class="leftheader">
          <router-link to="/rootIndex">websitstudy</router-link>
        </header>
        <div class="nav">
          <router-link to="/rootIndex"><div id="info" @click="routlinkClick($event)" class="navContent"><i class="el-icon-s-data"></i>信息统计</div></router-link>
          <router-link to="/rootIndex/student"><div id="student" @click="routlinkClick($event)" class="navContent"><i class="el-icon-user-solid"></i>学生管理</div></router-link>
          <router-link to="/rootIndex/addstudent"><div id="addstudent" @click="routlinkClick($event)" class="navContent"><i class="el-icon-s-custom"></i>添加学生</div></router-link>
          <router-link to="/rootIndex/video"><div id="video" @click="routlinkClick($event)" class="navContent"><i class="el-icon-s-management"></i>视频管理</div></router-link>
          <router-link to="/rootIndex/addvideo"><div id="addvideo" @click="routlinkClick($event)"  class="navContent"><i class="el-icon-video-camera-solid"></i>添加视频</div></router-link>
          <router-link to="/rootIndex/practise"><div id="practise" @click="routlinkClick($event)"  class="navContent"><i class="el-icon-s-cooperation"></i>资源管理</div></router-link>
          <router-link to="/rootIndex/addpractise"><div id="addpractise" @click="routlinkClick($event)"  class="navContent"><i class="el-icon-upload"></i>添加资源</div></router-link>
        </div>
      </div>

      <div class="mainRight">
        <header-com :styleA="1"></header-com>
        <div class="rightRouter">
          <router-view></router-view>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import headerCom from '../components/headerCom.vue'

export default {
  components: { headerCom },
  data() {
    return {
      
    }
  },
  methods: {
    routlinkClick(dom){
      document.querySelectorAll('.navContent').forEach(element => {
        element.style.color = "rgb(255, 255, 255)"
      });
      dom.srcElement.style.color = "rgb(255, 208, 75)";

    }
  },
  mounted() {
    document.querySelector(`#${this.$route.name}`).style.color = "rgb(255, 208, 75)"
  },
}
</script>

<style lang="less" scoped>
  @back: rgb(84, 92, 100);
  .main{
    display: flex;
    background-color: rgb(211, 211, 211);
  }
  .mainLeft{
    width: 250px;
    height: 100vh;
    background-color: @back;
    .nav{
      width: 100%;
      color: white;
      .navContent{
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px 0px;
        cursor: pointer;
        transition: .3s;
        font-size: 15px;
        background-color: @back;
        i{
          margin-right: 10px;
        }
      }
      .navContent:hover{
        color: rgb(255, 208, 75);
        background-color: rgb(40, 40, 40);
      }
    }
  }
  .mainRight{
    flex: 1;
    background-color: rgb(211, 211, 211);

  }

  //导航栏
  .leftheader, .rightheader{
    height: 60px;
    display: flex;
    align-items: center;
  }
  .leftheader{
    color: white;
    justify-content: center;
    font-size: 25px;
    font-weight: 600;
    background-color: @back;
  }
  .rightheader{
    background-color: white;
    justify-content: flex-end;
    padding: 0px 5vw 0px;
    .user{
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0px 20px;
      .userImg{
        width: 40px;
        height: 40px;
        margin: 0px 10px;
        img{
          width: 100%;
          height: 100%;
          border-radius: 100px;
        }
      }
    }
  }

  //路由
  .rightRouter{
    background-color: white;
    margin: 20px auto;
    width: 1100px;
  }
</style>
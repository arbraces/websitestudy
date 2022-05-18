<template>
  <div id="login">
      <div class="lnContent">
        <div class="lnLeft">
          <div class="leftText">
            你走独木桥会害怕，走平地不会，但走平地的时候，你的轨迹其实和走独木桥是差不多宽。不会害怕是因为有旁边的路，而旁边的路就是无用的知识铺就的，它会让你走得更平稳，更有安全感。
          </div>
        </div>
        <div class="lnRight">
          <div class="lnRightTitle">用户登录</div>
          <div class="lnRightUserName">
            <el-input v-model="username" placeholder="请输入账号" clearable></el-input>
          </div>
          <div class="lnRightPassword">
            <el-input placeholder="请输入密码" v-model="password" show-password></el-input>
          </div>
          <div class="loginTips">{{loginTips}}</div>
          <div class="lnRightSubmit">
            <el-row>
              <el-button type="primary" @click="submitClick" size="medium">登录</el-button>
            </el-row>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: '',
      password: '',
      loginTips: '',
    }
  },
  methods: {
    //当登录按钮被点击
    submitClick(){
      //判断账号密码是否为空
      if(this.username.replace(/\s/,"") == ""){
        this.loginTips = "账号不能为空"
      }else if(this.password.replace(/\s/,"") == ""){
        this.loginTips = "密码不能为空"
      }else{
        this.$axios.post('/login',{
          username: this.username,
          password: this.password
        })
        .then(({data})=>{
          if(data.msg === "成功"){
            //当后端返回成功的时候设置token并跳转到主页
            this.$message.success('登录成功');
            this.loginTips = ""
            localStorage.setItem('username',this.username)
            localStorage.setItem('token',data.data.token)
            this.$router.push('/')
          }else if(data.msg === "账号或密码错误"){
            this.loginTips = "账号或密码错误"
          }
        })
      }
    },
  },
}
</script>

<style lang="less" scope>
  //主页面布局
  #login{
    width: 100vw;
    height: 100vh;
    background-image: url(../assets/img/loginBgr.jpg);
    background-repeat: no-repeat;
    background-size: cover;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  //内容区域
  .lnContent{
    display: flex;
    height: 60vh;
    overflow: hidden;
    //左边区域
    .lnLeft{
      width: 30vw;
      height: 60vh;
      background-color: rgba(90, 90, 90, .4);
      font-weight: 600;
      font-size: 30px;
      text-indent: 2vw;
      padding: 1vw;
      box-sizing: border-box;
      letter-spacing: .3vw;
      color: rgb(230, 230, 230);
      box-shadow: 1px 1px 1px black;
    }
    //右边区域
    .lnRight{
      width: 30vw;
      height: 60vh;
      background-color: white;
      padding: 5vw 2vw;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      .lnRightTitle{
        font-size: 30px;
      }
      .lnRightUserName{
        margin: 2vw 0px;

      }
      .lnRightPassword{
        margin-bottom: 1vw;
      }
      .lnRightSubmit{
        width: 100%;
        .el-button{
          width: 100%;
          height: 4.5vh;
          font-size: 17px;
          display: flex;
          align-items: center;
          justify-content: center;
        }
      }
      .loginTips{
        color: red;
        margin: 1vh 0px;
      }
    }
  }
</style>
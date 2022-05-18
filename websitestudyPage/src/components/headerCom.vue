<template>
<div>
  <div v-if="!styleA">
  <header id="header">
    <div class="headerContent">
      <!-- 头部标题 -->
      <div class="headerTitle">websitestudy</div>
      <!-- 用户区域 -->
      <div title="查看个人中心">
        <span style="user-select: none;cursor: pointer; margin-right: 10px" @click="drawer = !drawer">{{username}}</span>
        <el-popconfirm
          confirm-button-text='确定'
          cancel-button-text='手滑了'
          icon="el-icon-info"
          icon-color="red"
          @confirm="logoutClick('loginVis')"
          title="确定要退出登录吗"
        >
          <el-button slot="reference" type="primary">退出登录</el-button>
        </el-popconfirm>
      </div>
    </div>
  </header>
   <nav id="nav">
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item :to="{ path: '/practise' }">练习中心</el-breadcrumb-item>
    </el-breadcrumb>
  </nav>
  <el-drawer
    title="修改密码"
    size="30%"
    @closed="clearForm('formRules')"
    :visible.sync="drawer"
    :with-header="true">
    <el-form :model="form" :rules="formRules" ref="formRules">
      <el-form-item prop="password" label="当前密码" label-width="90px">
        <el-input  style="width:90%" v-model="form.password"></el-input>
      </el-form-item>
      <el-form-item prop="newpassword" label="新密码" label-width="90px">
        <el-input style="width:90%" show-password v-model="form.newpassword"></el-input>
      </el-form-item>
      <el-form-item prop="compassword" label="确认密码" label-width="90px">
        <el-input style="width:90%" show-password v-model="form.compassword"></el-input>
      </el-form-item>
      <el-form-item label-width="90px">
        <el-button type="primary" @click="submitForm('formRules')">提交</el-button>
        <el-button @click="clearForm('formRules')">重置</el-button>
      </el-form-item>
    </el-form>
  </el-drawer>
  </div>
  <div v-else>
    <header id="header">
      <div class="headerContent">
        <!-- 头部标题 -->
          <div class="headerTitle"></div>
        <!-- 用户区域 -->
        <div style="cursor: pointer">{{username}}
          <el-popconfirm
            confirm-button-text='确定'
            cancel-button-text='手滑了'
            icon="el-icon-info"
            icon-color="red"
            @confirm="logoutClick('rootVis')"
            title="确定要退出登录吗"
          >
            <el-button slot="reference" type="primary">退出登录</el-button>
          </el-popconfirm>
        </div>
      </div>
    </header>
  </div>
</div>
</template>

<script>
export default {
  props:['styleA'],
  data() {
    var checkCompassword = (rule, value, callback) => {
      if(value === ''){
        callback(new Error('请确认密码'))
      }else if(value !== this.form.newpassword){
        callback(new Error('两次密码不一致'))
      }else{
        callback()
      }
    }
    return {
      username: '',
      drawer: false,
      form: {
        password: "",
        newpassword: "",
        compassword: "",
      },
      formRules: {
        password:
        [
          {required: true, message: '请输入当前密码', trigger: 'blur'},
          {min: 6, max: 18, message: '长度在 6 到 18 个字符', trigger: 'blur'}
        ],
        newpassword: 
        [
          {required: true, message: '请输入新密码', trigger: 'blur'},
          {min: 6, max: 18, message: '长度在 6 到 18 个字符', trigger: 'blur'}
        ],
        compassword:
        [
          {validator: checkCompassword, trigger: 'blur'}
        ]
      }
    }
  },
  methods: {
    //退出登录
    logoutClick(routerName){
      this.$axios.post('logout')
      .then(({data})=>{
        if(data.msg == "成功"){
          localStorage.clear();
          document.cookie = ""
          this.$router.push({name: routerName})
          this.$nextTick(()=>{
            this.$message.success('退出成功')
          })
        }else{
          this.$message.error('退出失败，请稍后重试')
        }
      })
    },

    //提交修改密码表单
    submitForm(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.$axios.request({
            method: 'put',
            url: 'user',
            params: this.form,
            headers: {'Content-Type':'application/x-www-form-urlencoded',
                      'Accept': '*/*'}
          }).then(({data}) => {
            if(data.msg == "成功"){
              localStorage.clear();
              document.cookie = ""
              this.drawer = false
              this.$router.push('/login')
              this.$nextTick(()=>{
                this.$message.success('修改密码成功，请重新登录')
              })
            }else{
              this.$message.error('修改密码失败，请联系管理员')
            }
          }, err=>{
            console.log(err)
            this.$message.error('修改密码失败，请联系管理员')
          })
        } else {
          return false;
        }
      });
    },

    //清除表单
    clearForm(formName){
      this.$refs[formName].resetFields();
      Object.keys(this.form).forEach(item => {
        this.form[item] = ""
      })
    }
  },
  mounted() {
    this.username = this.$username
  },
}
</script>

<style scoped lang="less">
  #nav{
    max-width: 1200px;
    margin: 20px auto;
    height: 30px;
  }
  #header{
    height: 60px;
    position: relative;
    background: white;
    display: block;
    box-shadow: 0px 0px 10px #00000033;
    .headerContent{
      max-width: 1200px;
      width: 100%;
      display: flex;
      margin: auto;
      height: 100%;
      align-items: center;
      justify-content: space-between;
    }
    .headerTitle{
      font-weight: 600;
      font-size: 30px;
      user-select: none;
    }
  }
</style>
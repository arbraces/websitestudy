<template>
  <div class="rootAddStudentVis">
    <root-title-com text="添加学生"></root-title-com>
    <div class="form">
      <el-form :model="registerForm" :rules="registerRules" ref="addStudent" label-width="100px" class="demo-ruleForm">
        <el-form-item label="账号" prop="username" >
          <el-input v-model="registerForm.username"></el-input>
        </el-form-item>
        <el-form-item label="密码" prop="password" >
          <el-input show-password v-model="registerForm.password"></el-input>
        </el-form-item>
        <el-form-item label="确认密码" prop="confirmPassword" >
          <el-input show-password v-model="registerForm.confirmPassword"></el-input>
        </el-form-item>
        <el-form-item label="权限" prop="is_admin">
          <el-radio v-model="registerForm.is_admin" label="0">普通用户</el-radio>
          <el-radio v-model="registerForm.is_admin" label="1">管理员</el-radio>
        </el-form-item>
        <el-form-item prop="registerForm">
          <el-button type="primary" @click="submitForm('addStudent')">添加学生</el-button>
          <el-button @click="resetForm()">重置</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import rootTitleCom from '../components/rootTitleCom.vue'
export default {
  components: { rootTitleCom },
  data() {
    var checkPassword = (rule, value, callback) => {
      if(value !== this.registerForm.password){
        callback(new Error('两次密码不一致'))
      }else{
        callback();
      }
    }
    return {
      registerForm: {
        username: "",
        password: "",
        confirmPassword: "",
        is_admin: "0"
      },
      registerRules: 
      {
        username: 
        [
          {required: true, message: '请输入账号', trigger: 'blur'}
        ],

        password: 
        [
          {required: true, message: '请输入密码', trigger: 'blur'}
        ],
        is_admin: 
        [
          {required: true, message: '请选择用户权限', trigger: 'blur'}
        ],
        confirmPassword: 
        [
          {required: true, message: '请确认密码', trigger: 'blur'},
          {validator: checkPassword, trigger: 'blur'}
        ]
      }
    }
  },
  methods: {
    //提交表单
    submitForm(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.$axios.post('register',{
            username: this.registerForm.username,
            password: this.registerForm.password,
            is_admin: this.registerForm.is_admin
          })
          .then(({data}) => {
            if(data.msg == "成功"){
              this.$message.success('添加成功')
              this.resetForm()
            }
          })
        } else {
          return false;
        }
      });
    },
    //清空表单
    resetForm(){
      Object.keys(this.registerForm).forEach(item => {
        this.registerForm[item] = ""
      })
    }
  },
}
</script>

<style>

</style>
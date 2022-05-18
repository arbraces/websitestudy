<template>
  <div class="rootAddVideo">
    <root-title-com text="添加视频"></root-title-com>
    <div class="form">
      <el-form :model="videoForm" :rules="videoRules" ref="addVideoForm" label-width="100px" class="demo-ruleForm">
        <el-form-item label="文件地址" prop="fileUrl" >
          <el-input v-model="videoForm.fileUrl"></el-input>
        </el-form-item>
        <el-form-item label="分类" prop="sort">
          <el-select v-model="videoForm.sort" placeholder="请选择">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="videoForm">
          <el-button :loading="submitLog" type="primary" @click="submitForm('addVideoForm')">添加视频</el-button>
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
    return {
      //form表单
      videoForm: {
        fileUrl: '',
        sort: ''
      },
      //ruls验证规则
      videoRules: {
        fileUrl: 
        [
          {required: true, message: '请输入视频地址', trigger: 'blur'}
        ],
        sort: 
        [
          {required: true, message: '请选择视频分类', trigger: 'blur'}
        ]
      },
      //类型选项
      options: [
      {
        value: 'HTML+CSS',
        label: 'HTML+CSS'
      },
      {
        value: 'JavaScript',
        label: 'JavaScript'
      },
      {
        value: 'PHP+MySql',
        label: 'PHP+MySql'
      }],
      //提交按钮是否被加载
      submitLog: false,
    }
  },
  methods: {
    //提交表单
    submitForm(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.submitLog = true
          this.$axios.post('video',{
            fileUrl: this.videoForm.fileUrl,
            sort: this.videoForm.sort
          })
          .then(({data}) => {
            this.submitLog = false
            if(data.msg == "成功"){
              this.$message.success('添加成功')
              this.resetForm();
            }
          }, err=>{
            this.submitLog = false
            this.$message.warning('文件过大，请稍后查看文件状态')
          })
        } else {
          return false;
        }
      });
    },
    //清空表单
    resetForm(){
      Object.keys(this.videoForm).forEach(item => {
        this.videoForm[item] = ""
      })
    }
  }
}
</script>

<style lang="less" scope>
  .form{
    width: 1000px;
    padding: 20px 0px;
  }
</style>
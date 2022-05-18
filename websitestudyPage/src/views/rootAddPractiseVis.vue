<template>
  <div class="rootAddPractiseVis">
    <root-title-com text="添加资源"></root-title-com>
    <div class="form">
      <!-- 资源名称 -->
      <el-form :model="practiseForm" :rules="practiseRules" ref="addPractiseForm" label-width="100px" class="demo-ruleForm">
        <el-form-item label="资源名称" prop="project_name" >
          <el-input v-model="practiseForm.project_name"></el-input>
        </el-form-item>

        <!-- 文件 -->
        <el-form-item label="选择文件" prop="project_file">
          <el-upload
            class="upload-demo"
            drag
            ref="upload"
            :action="'http://192.168.1.51:8000/api'"
            :on-change="fileChange"
            :auto-upload="false"
            :multiple="false">
            <i class="el-icon-upload"></i>
            <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
            <div class="el-upload__tip" slot="tip">只能上传zip文件，且不超过50MB</div>
          </el-upload>
        </el-form-item>

        <!-- 标签 -->
        <el-form-item label="添加标签" prop="project_needstudy">
          <el-tag
            closable
            @close="tagClose(tag)"
            :key="tag"
            v-for="tag in practiseForm.project_needstudy"
          >
          {{tag}}
          </el-tag>
          <el-input
            class="input-new-tag"
            v-if="inputVisible"
            v-model="inputValue"
            ref="saveTagInput"
            size="small"
            @keyup.enter.native="handleInputConfirm"
            @blur="handleInputConfirm"
          >
          </el-input>
          <el-button v-else class="button-new-tag" size="small" @click="showInput">+ 添加所需知识点</el-button>
        </el-form-item>

        <!-- 操作按钮区 -->
        <el-form-item prop="videoForm">
          <el-button :loading="submitLog" type="primary" @click="submitForm('addPractiseForm')">添加资源</el-button>
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
      //表单
      practiseForm: {
        project_name: '',
        project_file: '',
        project_needstudy: [],
      },
      //表单验证
      practiseRules: {
        project_name: 
        [
          {required: true, message: '请输入资源名称', trigger: 'blur'},
          {max: 20, message: '长度应该控制在20个字符内', trigger: 'blur'}
        ],
        project_file:
        [
          {required: true, message: '请选择文件', trigger: 'change'},
        ]
      },
      //tag的input变量
      inputVisible: false,
      inputValue: '',

      //提交按钮是否加载
      submitLog: false,
    }
  },
  methods: {
    //处理tag关闭
    tagClose(tag){
      this.practiseForm.project_needstudy.splice(this.practiseForm.project_needstudy.indexOf(tag), 1);
    },
    //显示input
    showInput() {
      this.inputVisible = true;
      this.$nextTick(_ => {
        this.$refs.saveTagInput.$refs.input.focus();
      });
    },
     handleInputConfirm() {
      let inputValue = this.inputValue;
      if (inputValue) {
        try {
          this.practiseForm.project_needstudy.push(inputValue);
        } catch (error) {
          this.practiseForm.project_needstudy = []
          this.practiseForm.project_needstudy.push(inputValue);
        }
      }
      this.inputVisible = false;
      this.inputValue = '';
    },
    //上传资源被点击
    submitForm(formName){
       this.$refs[formName].validate((valid) => {
          if (valid) {
            this.submitLog = true
            //提交form表单
            var formdata = new FormData();
            formdata.append('project_name',this.practiseForm.project_name)
            formdata.append('project_file',this.practiseForm.project_file)
            formdata.append('project_needstudy',this.practiseForm.project_needstudy.join(","))
            this.$axios.request({
              url: 'project',
              method: 'POST',
              data: formdata
            })
            .then(({data}) => {
              this.submitLog = false
              if(data.msg == "成功"){
                this.resetForm()
                this.$refs.upload.clearFiles()
                this.$message.success('添加成功')
              }else{
                console.log(data)
                this.$message.error('添加失败，请联系管理员')
              }
            },err=>{
              this.submitLog = false
              console.log(err)
            })
          } else {
            return false;
          }
        });
    },
    //文件状态改变
    fileChange(file){
      if(file.raw.type == "application/x-zip-compressed"){
        if(file.size > 52428800){
          this.$refs.upload.clearFiles()
          this.$message.error('上传的文件大小不能超过 50 MB！')  
        }else{
          this.practiseForm.project_file = file.raw
        }
      }else{
        this.$refs.upload.clearFiles()
        this.$message.error('上传的文件类型只能是zip')
      }
    },
    //清空表单
    resetForm(){
      Object.keys(this.practiseForm).forEach(item => {
        this.practiseForm[item] = ""
      })
    }
  },
}
</script>

<style lang="less" scoped>
  .input-new-tag {
    width: 90px;
    margin-left: 10px;
    vertical-align: bottom;
  }
</style>
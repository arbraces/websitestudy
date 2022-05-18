<template>
  <div class="practiseVis">
    <header-com></header-com>
    <main id="main">
      <div class="table">
        <el-table
          :data="tableData"
          v-loading="tableLoad"
          :empty-text="'加载中~'"
          style="width: 100%">
          <el-table-column
            label="项目名称"
            width="250">
            <template slot-scope="scope">
              <span style="margin-left: 10px">{{ scope.row.project_name }}</span>
            </template>
          </el-table-column>

          <el-table-column
            label="需学习"
            width="600">
            <template slot-scope="scope">
              <span v-for="(item,index) in scope.row.project_needstudy.split(',')" 
                :key="index"
              >
              <el-tag
                :type="scope.row.project_needstudy"
                disable-transitions>
                {{item}}
              </el-tag>
              <span>&nbsp;</span>
            </span>
            </template>
          </el-table-column>

          <el-table-column width="350" align="right" label="操作">
            <template slot-scope="scope">
              <div style="display:flex; justify-content: flex-end;">
              <el-button
                size="mini"
                icon="el-icon-download"
                type="primary"
                style="margin-right: 10px; flex-shrink: 0"
                @click="handleEdit(scope.$index, scope.row)">下载</el-button>
              <el-upload
                class="upload-demo"
                style="flex-shrink: 0"
                ref="upload"
                action="http://192.168.1.51:8000/api"
                :show-file-list="false"
                :auto-upload="false"
                :on-change="(file)=>{fileChange(file, scope.row.id)}"
                :on-progress="fileClick"
                multiple
                :limit="3"
                >
                <el-button size="small" icon="el-icon-upload2" type="success">上传</el-button>
              </el-upload>
              </div>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </main>
  </div>  
</template>

<script>
import headerCom from '../components/headerCom.vue'

export default {
  components: { headerCom },
  data() {
    return {
      tableData: [],
      tableLoad: false,
    }
  },
  methods: {
    //获取资源
    getPractise(){
      this.$axios.get('/project')
      .then(({data}) => {
        this.tableData = data.data
      })
    },
    //下载被点击
    handleEdit(index, row){
      this.$axios.request('projectfile',{
        method: 'get',
        responseType: "blob",
        params:{
          id: row.id
        }
      })
      .then((res) => {
        const fileName = decodeURIComponent(res.headers['content-disposition'])
        const blob = new Blob([res.data]);
        var downloadElement = document.createElement("a");
        var href = window.URL.createObjectURL(blob);
        downloadElement.href = href;
        downloadElement.download = fileName
        document.body.appendChild(downloadElement);
        downloadElement.click();
        document.body.removeChild(downloadElement);
        window.URL.revokeObjectURL(href);   
      })
    },
    //加载函数
    load(bool){
      bool?this.tableLoad=true:this.tableLoad=false
    },
    //文件状态改变
    fileChange(file, project_id){
      if(file.raw.type == "application/x-zip-compressed" || file.raw.type == "application/zip"){
        if(file.size > 52428800){
          this.$refs.upload.clearFiles()
          this.$message.error('上传的文件大小不能超过 50 MB！')  
        }else{
          //上传文件
          this.uploadFile(file.raw, project_id)
        }
      }else{
        this.$refs.upload.clearFiles()
        this.$message.error('上传的文件类型只能是zip')
      }
    },
    //上传文件(需要传入文件和项目id)
    uploadFile(file, project_id){
      let formdata = new FormData()
      formdata.append('file', file);
      formdata.append('project_id', project_id)
      this.$axios.request({
        method: 'post',
        url: 'take',
        data: formdata
      }).then(({data}) => {
        if(data.msg == "成功"){
          this.$message.success('上传成功')
        }else{
          this.$message.success('上传失败，请联系管理员')
        }
      }, err=>{
        console.log(err)
        this.$message.success('上传失败，请联系管理员')
      })
    },
  },
  mounted() {
    this.getPractise()
  },
}
</script>

<style lang="less" scoped>
   #main{
    max-width: 1200px;
    margin: 0 auto;
    height: 100%;
  }
  .sort{
    font-size: 20px;
    margin-top: 30px;
  }
</style>
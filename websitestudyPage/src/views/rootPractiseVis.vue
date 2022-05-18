<template>
  <div class="rootPractiseVis">
    <root-title-com text="资源管理"></root-title-com>
    <template>
        <el-table
          :data="practiseData"
          empty-text="加载中~"
          style="width: 100%">
          <el-table-column
            prop="project_name"
            label="项目名称"
            width="250">
          </el-table-column>
          <el-table-column
            prop="ctetime"
            label="创建时间"
            width="280">
          </el-table-column>
          <el-table-column
            label="操作"
            prop="id"
            align="right"
          >
            <template slot-scope="scope">
              <el-popconfirm
                confirm-button-text='确定'
                cancel-button-text='手滑了'
                icon="el-icon-info"
                icon-color="red"
                title="确定要删除该资源吗？"
                @confirm="deleteClick(scope.row.id)"
              >
                <el-button type="danger" slot="reference">删除</el-button>
              </el-popconfirm>
            </template>
          </el-table-column>
        </el-table>
      </template>
    <div class="pagination">
      <el-pagination
        background
        layout="prev, pager, next"
        :page-size=pageNum
        :current-page="currentPage"
        @current-change="currentChange"
        :total="total">
      </el-pagination>
    </div>
  </div>
</template>

<script>
import rootTitleCom from "../components/rootTitleCom.vue"

export default {
  components: { rootTitleCom },
  data() {
    return {
      //请求回来的数据(未进行分页处理)
      data: [],
      //分页数据
      practiseData: [],
      //数据分页的数量
      pageNum: 5,
      //用户总数
      total: 0,
      //当前页数
      currentPage: 1,

    }
  },
  methods: {
    //获取资源
    getPractise(){
      this.$axios.get('/project')
      .then(({data}) => {
        this.data = data.data
        this.total = this.data.length
        this.practiseDataHanld(this.currentPage)
      })
    },
    //用户数据分页处理
    practiseDataHanld(page){
      this.practiseData = this.data.slice((page-1)*this.pageNum, page*this.pageNum);
    },
    //分页变化
    currentChange(pageNum){
      this.practiseDataHanld(pageNum)
    },
    //删除资源
    deleteClick(id){
      this.$axios.delete('project',{
        data: {
          id,
        }
      }).then(({data}) => {
        if(data.msg == "成功"){
          this.$message.success('删除成功')
          this.getPractise()
        }else{
          console.log(data)
          this.$message.error('删除失败,请联系管理员')
        }
      }, err => {
        console.log(err)
        this.$message.error('删除失败,请联系管理员')
      })
    }
  },
  mounted() {
    this.getPractise();
  },
}
</script>

<style lang="less" scoped>
  .pagination{
    margin: 10px 0px;
  }
</style>
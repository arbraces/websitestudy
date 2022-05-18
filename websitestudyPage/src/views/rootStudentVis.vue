<template>
  <div id="rootInfoVis">
    <root-title text="学生列表"></root-title>
      <template>
        <el-table
          :data="studentInfoData"
          empty-text="加载中~"
          style="width: 100%">
          <el-table-column
            prop="username"
            label="学生账号"
            width="250">
          </el-table-column>
          <el-table-column
            prop="is_admin"
            label="管理员"
            width="280">
          </el-table-column>
          <el-table-column
            prop="created_at"
            label="创建时间"
            width="350">
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
                title="确定要删除该学生吗？"
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
import rootTitle from '../components/rootTitleCom.vue'
export default {
  components: { rootTitle },
  data() {
    return {
      //用户数据
      studentInfoData: [],
      //请求回来的用户数据(未处理)
      userData: [],
      //数据分页的数量
      pageNum: 5,
      //用户总数
      total: 0,
      //当前页数
      currentPage: 1,
    }
  },
  methods: {
    //获取用户信息
    async getUserData(){
      const {data} = await this.$axios.get('user')
      let arr = []
        data.data.forEach(item => {
        arr.push({
          id: item.id,
          username: item.username,
          created_at:  item.created_at.replace(/(T)|(\.000000Z)/g, " "),
          is_admin: item.is_admin?'管理员':'普通用户'
          })
        });
        this.total = arr.length
        this.userData = arr.reverse()
        this.userDataHanld(this.currentPage)
    },
    //用户数据分页处理
    userDataHanld(page){
      this.studentInfoData = this.userData.slice((page-1)*this.pageNum, page*this.pageNum);
    },
    //当分页数据发生变化
    currentChange(pageNum){
      this.userDataHanld(pageNum)
    },
    //删除学生按钮被点击
    deleteClick(id){
      this.$axios.delete('user',{
        data: {
          id: id
        }
      })
      .then(({data}) => {
        if(data.msg == "成功"){
          this.$message.success('删除成功')
          this.getUserData()
        }
      })
    }
  },
  mounted() {
    this.getUserData()
  },
}
</script>

<style leng="less" scoped>
  .pagination{
    text-align: center;
    margin: 10px 0px;
    padding: 10px 0px;
  }
</style>
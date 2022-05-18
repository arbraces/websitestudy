<template>
  <div id="rootInfoVis">
    <root-title text="学生信息"></root-title>
      <template>
        <el-table
          :data="studentInfoData"
          empty-text="加载中~"
          style="width: 100%">
          <el-table-column
            prop="username"
            label="学生账号"
            width="380">
          </el-table-column>
          <el-table-column
            prop="created_at"
            label="创建时间"
            width="380">
          </el-table-column>
          <el-table-column
            prop="is_admin"
            label="管理员"
            width="280">
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

    <root-title text="服务器信息"></root-title>
    <template>
      <el-table
        :data="serverInfoData"
        empty-text="加载中~"
        style="width: 100%">
        <el-table-column
          prop="infoName"
          label="配置名称"
          width="380">
        </el-table-column>
        <el-table-column
          prop="infoText"
          label="配置信息"
          width="380">
        </el-table-column>
      </el-table>
    </template>
  </div>
</template>

<script>
import rootTitle from '../components/rootTitleCom.vue'
export default {
  components: { rootTitle },
  data() {
    return {
      //服务器信息
      serverInfoData: [],
      //用户数据
      studentInfoData: [],
      //请求回来的用户数据(未处理)
      userData: [],
      //数据分页的数量
      pageNum: 3,
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
          username: item.username,
          created_at:  item.created_at.replace(/(T)|(\.000000Z)/g, " "),
          is_admin: item.is_admin?'管理员':'普通用户'
          })
        });
        this.total = arr.length
        this.userData = arr.reverse()
        this.userDataHanld(this.currentPage)
    },
    //获取服务器数据
    async getServerData(){
      const {data} = await this.$axios.get('server')
      let arr = []
      Object.keys(data.data).forEach((item) => {
        arr.push({
          infoName: item,
          infoText: data.data[item]
        })
      })
      this.serverInfoData = arr
    },
    //用户数据分页处理
    userDataHanld(page){
      this.studentInfoData = this.userData.slice((page-1)*3, page*3);
    },
    //当分页数据发生变化
    currentChange(pageNum){
      this.userDataHanld(pageNum)
    }
  },
  mounted() {
    this.getUserData()
    this.getServerData()
  },
}
</script>

<style leng="less" scoped>
  .pagination{
    text-align: center;
    margin: 10px 0px;
  }
</style>
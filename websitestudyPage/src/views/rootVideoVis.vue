<template>
  <div class="rootVideoVis">
    <root-title-com text="视频列表"></root-title-com>
    <div v-for="item,index in hanldData" :key="index">
      <root-video-com
        @request="getVideoList"
        :img="item.cover"
        :collection_id="item.collection_id"
        :name="item.name"
        :sort="item.sort"
        :createtime="item.ctetime"
      ></root-video-com>
      <el-divider></el-divider>
    </div>
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
import rootTitleCom from '../components/rootTitleCom.vue'
import RootVideoCom from '../components/rootVideoCom.vue'
export default {
  components: { rootTitleCom,  RootVideoCom },
  data() {
    return {
      img: '',
      collection_id: '',
      name: '',
      sort: '',
      videoData: [],

      hanldData: "",  //分页数据
      pageNum: 3,   //数据分页的数量
      total: 0, //用户总数
      currentPage: 1, //当前页数
    }
  },
  methods: {
    getVideoList(){
      this.$axios.get('videolist')
      .then(({data}) => {
        this.videoData = data.data
        this.total = data.data.length
        this.videoDataHanld(this.currentPage)
      })
    },
    //用户数据分页处理
    videoDataHanld(page){
      this.hanldData = this.videoData.slice((page-1)*this.pageNum, page*this.pageNum);
    },
    //当分页数据发生变化
    currentChange(pageNum){
      this.videoDataHanld(pageNum)
    },
  },
  mounted() {
    this.getVideoList()
  },
}
</script>

<style lang="less" scope>
  .pagination{
    width: 100%;
    text-align: center;
    padding-bottom: 20px;
  }
</style>
<template>
  <div id="index">
      <header-com></header-com>
      <main id="main">
        <div v-for="(item, index) in data" :key="item.id">
          <div class="sort">{{index}}</div>
          <el-divider></el-divider>
          <div class="content">
            <show-video-com v-for="(video) in item" :key="video.id"
              :collection_id="video.collection_id"
              :cover="video.cover"
              :ctetime="video.ctetime"
              :name="video.name"
            ></show-video-com>
          </div>
        </div>
        <div v-if="isNotData">
          <el-empty :image-size="200"></el-empty>
        </div>
      </main>




  </div>
</template>

<script>
import headerCom from '../components/headerCom.vue'
import showVideoCom from '../components/showVideoCom.vue'
import loginVisVue from './loginVis.vue'
export default {
  components: { showVideoCom, headerCom },
  data() {
    return {
      //是否有数据
      isNotData: false,
      //是否加载
      isLoad: true,
      data: "",
    }
  },
  methods: {
    //请求数据
    getIndexData(){
      //加载数据
      const log = this.$loading({
        text: '加载中~',
        target: '#main',
        spinner: 'el-icon-loading',
        background: 'hsla(0,0%,100%,.9)'
      });
        this.$axios.get('video')
        .then(({data}) => {
          if(data.msg == "成功"){
            log.close()
            data.data === {}?this.isNotData = true:this.data = data.data
          }else{
            this.isNotData = true
          }
        },err => {
          this.isNotData = true
          this.$message.warning('网络连接超时，请稍后刷新重试')
        })
    },
  },
  mounted(){
    this.getIndexData()
  }
}
</script>

<style lang="less" scoped>
  //-----------主页面布局-----------
  #index{
    width: 100%;
    margin: auto;
  }

  //-----------内容区域-----------
  #main{
    max-width: 1200px;
    margin: 0px auto;
    height: 100%;
  }
  .sort{
    font-size: 20px;
    margin-top: 30px;
  }
  .content{
    display: grid;
    grid-template-columns: 300px 300px 300px 300px;
    grid-template-rows: 220px;
    justify-items: center;
  }
</style>
<style>
  .el-loading-spinner{
    margin-top: 300px;
  }
  .el-breadcrumb__inner{
    cursor: pointer !important;
  }
  ::-webkit-scrollbar {
    width: 7px;
  }
  ::-webkit-scrollbar-thumb{
    background: rgba(59, 59, 59, 0.6);
    border-radius: 5px;
  }
  ::-webkit-scrollbar-track-piece{
    width: 10px;
    background: rgba(59, 59, 59, 0.2);
  }
</style>
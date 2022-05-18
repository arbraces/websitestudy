<template>
  <div id="video" @keyup.70="fKeyCodeClick" @keyup.71="gKeyCodeClick" @keyup.219="sKeyCodeClick(videoId)" @keyup.221="xKeyCodeClick(videoId)">
    <div class="right">
      <div class="artive">
        <div style="position: fixed; background: #ebebeb; width: 20%;z-index: 1000">
          <el-button style="padding-left: 10px" @click="goIndex" type="text" icon="el-icon-arrow-left">返回主页</el-button>
        </div>
        <div style="margin-top: 40px">
          <div class="videoTitle" v-for="(item) in videoData" :key="item.id">
            <div :id="item.id" class="videoTitleContent" @click.stop="itemClick(item.id, item.url)" >
              <span class="titleText">{{item.title}}</span><img class="titleState" :src="item.is_finish?completeImg:unfinishedImg" alt="">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="left">
      <div class="paly">
        <video ref="videoRef" @ended="videoEnd(videoId)" autoplay="autoplay" :src="videoUrl" controls="controls"></video>
      </div>
    </div>
  </div>
</template>

<script>
import playing from '../assets/img/playing.gif'
import completeImg from '../assets/img/已完成.png'
import unfinishedImg from '../assets/img/未完成.png'
export default {
  data() {
    return {
      //静态资源
      playing,
      completeImg,
      unfinishedImg,

    
      //数据
      collection_id: '',
      videoData: '',
      videoUrl: '',
      videoId: '',
      historyVideo: '',

      //用于select被选中
      newItemId: '',
      oldItemId: '',

      //是否宽屏显示
      isG: false,
      isf: false,
    }
  },
  methods: {
    //返回主页
    goIndex(){
      this.$router.push('/')
    },

    //获取视频数据
    async getVideoData(){
      const {data} = await this.$axios.get(`video/${this.collection_id}`)
      this.videoData = data.data  
    },

    //每个视频被点击
    itemClick(id, url){
      //获取所有历史播放
      let historyVideo = this.historyVideo
      this.videoUrl = url
      this.videoId = id
      
      //记录当前播放
      historyVideo[this.collection_id] = 
      {
        historyId: id,
        historyUrl: url
      }

      localStorage.setItem('historyVideo', JSON.stringify(historyVideo))
      if(this.oldItemId){
        document.getElementById(this.oldItemId).className = "videoTitleContent"
        document.getElementById(id).className = "videoTitleContent checkvideo"
        this.oldItemId = id
      }else{
        document.getElementById(id).className = "videoTitleContent checkvideo"
        this.oldItemId = id
      }
    },

    //跳转到历史播放位置
    playHistory(){
      //获取历史播放记录
      let historyVideo = this.historyVideo
      if(historyVideo[this.collection_id]){
        this.$message.success('已为你跳转到历史播放进度')
        this.itemClick(historyVideo[this.collection_id].historyId, historyVideo[this.collection_id].historyUrl)
      }else{
        this.itemClick(this.videoData[0].id, this.videoData[0].url)
      }
      //定位到指定位置
      const hisDom = document.getElementById(historyVideo[this.collection_id].historyId-="")
      hisDom.offsetTop
      const artiveDom = document.getElementsByClassName('artive')[0]
      artiveDom.scrollTo(0,hisDom.offsetTop-30)
    },

    //视频播放结束
    async videoEnd(id){
      //请求api完成当前视频的学习
      await this.$axios.post('/video/finish',{videoId: id})
      this.videoData[(id-this.videoData[0].id)].is_finish = true

      //播放下一集(需要判断一下是否是最后一集)
      if(!(this.videoData[this.videoData.length-1].id == id)){
        this.itemClick(parseInt(id)+1, this.videoData[(id-this.videoData[0].id)+1].url)
      }
    },

    //当键盘的F键被点击
    fKeyCodeClick(){
      if(!this.isf){
        const videoDom = this.$refs.videoRef
        videoDom.requestFullscreen()
        this.isf = true
      }else{
        this.exitFullScreen()
        this.isf = false
      }
    },
    //当键盘的【被点击
    sKeyCodeClick(id){
      //播放上一集(需要判断一下是否是第一集)
      if(!(this.videoData[0].id == id)){
        this.itemClick(parseInt(id)-1, this.videoData[(id-this.videoData[0].id)-1].url)
      }
    },
    //当键盘的】被点击
    xKeyCodeClick(id){
      //播放下一集(需要判断一下是否是最后一集)
      if(!(this.videoData[this.videoData.length-1].id == id)){
        this.itemClick(parseInt(id)+1, this.videoData[(id-this.videoData[0].id)+1].url)
      }
    },
    //当键盘的g被点击
    gKeyCodeClick(){
      if(!this.isG){
        document.querySelector('.right').style.width = "0%"
        document.querySelector('.artive').style.display = "none"
        document.querySelector('.left').style.width = "100%"
        this.isG = true
      }else{
        document.querySelector('.right').style.width = "20%"
        document.querySelector('.artive').style.display = "block"
        document.querySelector('.left').style.width = "80%"
        this.isG = false
      }
    },
    exitFullScreen () {
      let el = document
      let cfs = el.cancelFullScreen || el.mozCancelFullScreen || el.msExitFullscreen || el.webkitExitFullscreen || el.exitFullscreen
      if (cfs) { // typeof cfs != "undefined" && cfs
        cfs.call(el)
      } else if (typeof window.ActiveXObject !== 'undefined') {
        // for IE，这里和fullScreen相同，模拟按下F11键退出全屏
        let wscript = new ActiveXObject('WScript.Shell')
        if (wscript != null) {
          wscript.SendKeys('{F11}')
        }
      }
    }
  },

  beforeMount() {
    this.collection_id = this.$route.query.videoid
    if(!(this.collection_id)){
      this.$message.error('缺少必要参数! ')
      setTimeout(()=>{
        this.goIndex
      }, 1000)
    }
  },

  async mounted() {
    //判断是否有必要的参数，如果没有无法进行请求
    this.historyVideo = JSON.parse(localStorage.getItem('historyVideo'))
    if(this.historyVideo == null){
      this.historyVideo = {}
    }
    await this.getVideoData() //请求数据
    this.playHistory()  //跳转到历史播放
  },
}
</script>

<style lang="less" scoped>
  #video{
    width: 100%;
    display: flex;
  }
  .left{
    width: 80%;
      .paly{
        width: 100%;
        height: 100vh;
      video{
        width: 100%;
        height: 100vh;
        display: block;
        outline: none;
      }
    }
  }
  .right{
    width: 20%;
    height: 100vh;
    .artive{
      background: rgb(235, 235, 235);
      width: 100%;
      height: 100%;
      overflow: auto;
      position: relative;
    }
  }
  .videoTitleContent{
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 15px 15px;
    transition: .3s;
    cursor: pointer;
    color: rgb(53, 53, 53);
    .titleText{
      width: 90%;
      text-indent: 5px;
      overflow:hidden;
      word-break:keep-all;
      white-space:nowrap;
      text-overflow:ellipsis;
      font-size: 15px;
    }
    .titleState{
      width: 20px;
      height: 20px;
    }
  }
  .videoTitleContent:hover{
    background: rgb(255, 255, 255);
  }
  .checkvideo{
    position: relative;
    color: #66b1ff;
  }
  .checkvideo::after{
    content: "";
    position: absolute;
    top: 40%;
    left: 5px;
    width: 10px;
    height: 10px;
    background-image: url('../assets/img/playing.gif');
    background-size: 10px;
  }
</style>
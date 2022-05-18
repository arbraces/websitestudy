<template>
  <div @click="goVideo(collection_id)" class="showVideoCom">
    <div class="img">
      <img :src="cover" alt="">
      <el-button class="play" icon="el-icon-caret-right" circle></el-button>
    </div>
    <div class="title">
      {{name}}
    </div>
    <div class="time">
      创建时间：{{ctetime}}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    //视频集合id
    collection_id: {
      type: String,
      required: true
    },
    //封面图片地址
    cover: {
      type: String,
      required: true
    },
    //创建时间
    ctetime: {
      type: String,
      required: true
    },
    //视频标题
    name: {
      type: String,
      required: true
    }
    

  },
  data() {
    return {
      
    }
  },
  methods: {
    goVideo(id){
      this.$router.push({
        path: '/video',
        query: {
          videoid: id,
        }
      })
    }
  }
}
</script>

<style lang="less" scoped>
  .showVideoCom{
    width: 280px;
    height: 200px;
    position: relative;
    cursor: pointer;
    transition: .3s;
  }
  .showVideoCom:hover{
    color: #409EFF
  }
  .showVideoCom:hover .play{
    opacity: 1;
  }
  .img{
    width:100%;
    height: 80%;
    overflow: hidden;
    position: relative;
    img{
      width: 100%;
      height: 100%;
      // border-radius: 3%;
      object-fit: cover;
    }
    .play{
      position: absolute;
      top: 32%;
      z-index: 1000;
      left: 40%;
      font-size: 20px;
      opacity: 0;
    }
  }
  .title{
    font-size: 14px;
    text-align: left;
    overflow:hidden;
    word-break:keep-all;
    white-space:nowrap;
    text-overflow:ellipsis;
  }
  .time{
    font-size: 13px;
    color: rgb(90, 90, 90);
    margin-top: 5px;
  }
</style>
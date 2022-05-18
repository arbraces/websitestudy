<template>
  <div id="dom">
    <keep-alive include="indexVis">
      <router-view></router-view>
    </keep-alive>
  </div>
</template>

<script>
export default {
  data() {
    return {
    }
  }
}
</script>

<style lang="less">
  *{
    margin: 0px;
    padding: 0px;
    font-family: "Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;
  }
  body, html{
    width: 100%;
    height: 100%;
  }
  a{
    color: inherit;
    text-decoration: none;
  }
  #dom{
    height: 100%;
  }
</style>
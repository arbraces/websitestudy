<template>
  <div id="body">
      <img :src="src404" alt="">
      <div style="font-size: 30px;margin: 30px 0px">找不到你想要的页面！</div>
      <a href="/"><el-button type="primary">返回首页</el-button></a>
  </div>
</template>

<script>
import src404 from '../assets/img/404.png'
export default {
    data() {
        return {
            src404,
        }
    },
}
</script>

<style scoped>
    #body{
        width: 100vw;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }
</style>
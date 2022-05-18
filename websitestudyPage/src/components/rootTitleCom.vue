<template>
  <div id="rootTitleCom">
    <div class="title">{{text}}</div>
    <div class="line"></div>
  </div>
</template>

<script>
export default {
  props: ['text']
}
</script>

<style lang="less" scoped>
  .title{
    font-size: 17px;
    font-weight: 600;
    padding: 10px;
    margin-top: 10px; 
  }
  .line{
    width: 100%;
    height: 3px;
    background-color: #66b1ff;
    margin-bottom: 10px;
  }
</style>
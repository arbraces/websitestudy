<template>
  <div class="rootVideoCom">
    <img class="img" :src="img" alt="">
    <div class="info">
      <div class="name">{{name}}</div>
      <div class="sort">{{sort}}</div>
      <div class="createtime">{{createtime}}</div>
    </div>
    <div class="button">
      <template>
        <el-popconfirm
        confirm-button-text='确定'
        cancel-button-text='手滑了'
        icon="el-icon-info"
        icon-color="red"
        @confirm="videoDelete(collection_id)"
        title="确定要删除该合集视频吗？"
        >
        <el-button type="danger" slot="reference">删除</el-button>
        </el-popconfirm>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  props: ['img','collection_id','name','sort','createtime'],
  methods: {
    videoDelete(collection_id){
      this.$axios.delete('video',{
        data: {
          collection_id,
        }
      })
      .then(({data}) => {
        if(data.msg == "成功"){
          this.$message.success('删除成功')
          this.$emit('request')
        }
      })
    }
  }
}
</script>

<style lang="less" scope>
  .rootVideoCom{
    display: flex;
    padding: 5px 15px;
    padding-top: 15px;
    
    .img{
      width: 180px;
      height: 100px;
      margin-right: 10px;
    }
    .info{
      flex-shrink: 0;
      display: flex;
      width: 600px;
      font-size: 16px;
      margin: 0px 10px;
      flex-direction: column;
      justify-content: space-between;
      .createtime{
        font-size: 14px;
        color: rgb(110, 110, 110);
      }
    }
    .button{
      display: flex;
      flex: 1;
      justify-content: flex-end;
      align-items: center;
    }
  }
</style>
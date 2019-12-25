<template>
  <div class="main-div">
    <head-menu :user="user" :msgRows="msgRows" class="base-div" style="background-color: white;"></head-menu>
    <div v-if="type!=null" class="ui container base-div" style="padding: 2rem;margin-top: 1rem;">
      <router-view :user="user" :type="type" :msg-rows="msgRows"></router-view>
    </div>
    <div class="icp">
      <span>@QAQxm毕业设计 qaqxm.com 版权所有</span>
      <a href="http://www.beian.miit.gov.cn" target="_blank">闽ICP备19027143号</a>
      <br />
      <span>联系邮箱：906993503@qq.com</span>
      <span>联系电话：15980165890</span>
    </div>
  </div>
</template>

<script>
import Global from "../../Global";
import HeadMenu from "./HeadMenu";
export default {
  props: {
    user: Array,
    type: Array,
    msgRows: Number
  },
  data: function() {
    return {};
  },
  components: {
    "head-menu": HeadMenu
  }
};
</script>

<style>
.main-div {
  margin-top: 0;
}
.icp {
  height: 5rem;
  text-align: center;
  margin-top: 1rem;
  background-color: #1b1c1d;
  color: #aaa;
  padding: 1rem;
}
.icp a {
  color: #aaa;
}
</style>
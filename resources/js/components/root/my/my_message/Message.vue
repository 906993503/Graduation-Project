<template>
  <div>

    <sui-button basic secondary icon="reply" @click="backList()">返回</sui-button>
    <h1>{{msg_id}}</h1>
    <article>
      <p>我是一个苦b的前端程序员，今晚加班到快通宵了，困得快睁不开眼了，女上司很关心，问我要不要吃宵夜。我没好气地说，宵夜就算了，能让我睡一觉就行了。女上司红着脸说了句讨厌啊，然后坐在我身边不动，好像距离我很近，搞得我很紧张，难道她发现我的程序出了bug？</p>
      <p>
        <b>01</b>
      </p>
      <p>某人发帖子：“各位JR，我想做一个程序猿，请问有什么要注意的……”某猿：“等我下班跟你细说……”</p>
      <p>然后……就没有然后了。</p>
    </article>
  </div>
</template>
<script>
import env from "../../../../env";
export default {
  props: {
    msg_id: Number
  },
  methods: {
    backList() {
      console.log("666");
      env.$emit("msg_show", null);
    }
  }
};
</script>
<style>
</style>
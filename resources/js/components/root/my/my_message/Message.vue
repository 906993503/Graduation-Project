<template>
  <div>
    <sui-button basic secondary icon="reply" @click="backList()">返回</sui-button>
    <article class="ql-editor" v-html="msg.content"></article>
  </div>
</template>
<script>
import env from "../../../../env";
export default {
  props: {
    msg: Object
  },
  methods: {
    backList() {
      env.$emit("msg_show", null);
    }
  }
};
</script>
<style>
</style>
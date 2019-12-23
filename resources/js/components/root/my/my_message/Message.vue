<template>
  <div>

    <sui-button basic secondary icon="reply" @click="backList()">返回</sui-button>
    <h1>{{msg_id}}</h1>
    <article>
      <h2>I am Example</h2>
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
      env.$emit("msg_show", null);
    }
  }
};
</script>
<style>
</style>
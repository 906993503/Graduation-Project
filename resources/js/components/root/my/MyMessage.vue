<template>
  <div class="user-body">
    <sui-button v-if="msg_show == null" compact content="全部标为已读" color="red" floated="right" />
    <div v-if="msg_show == null">
      <sui-menu tabular style="margin-top: 2.2rem;">
        <sui-menu-item :active="true">系统通知</sui-menu-item>
      </sui-menu>
      <message-item :items="items"></message-item>
    </div>
    <message-qaq v-if="msg_show != null" :msg_id="msg_show" style="min-height: 50rem;"></message-qaq>
  </div>
</template>
<script>
import MessageItem from "./my_message/MessageItem";
import Message from "./my_message/Message";
import env from "../../../env";
export default {
  props: {
    user: Array
  },
  data() {
    return {
      items: [1, 2, 3],
      msg_show: null
    };
  },
  components: {
    "message-item": MessageItem,
    "message-qaq": Message
  },
  created() {
    var self = this;
    env.$on("msg_show", msg_show => {
      self.msg_show = msg_show;
    });
  },
  destroyed() {
    env.$off("msg_show");
  }
};
</script>
<style>
</style>
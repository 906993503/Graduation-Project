<template>
  <div class="user-body">
    <sui-button
      type="button"
      v-if="msg_show == null"
      compact
      content="全部标为已读"
      color="red"
      floated="right"
      @click="readAll()"
    />
    <div v-if="msg_show == null">
      <sui-menu tabular style="margin-top: 2.2rem;">
        <sui-menu-item :active="true">系统通知</sui-menu-item>
      </sui-menu>
      <message-item :items="items"></message-item>
    </div>
    <message-qaq v-if="msg_show != null" :msg="readMsg()" style="min-height: 50rem;"></message-qaq>
  </div>
</template>
<script>
import MessageItem from "./my_message/MessageItem";
import Message from "./my_message/Message";
import env from "../../../env";
export default {
  props: {
    user: Array,
    msgRows: Number
  },
  data() {
    return {
      msg_show: null,
      items: [],
      moreItems: 0,
      page: 1,
      limit: 8
    };
  },
  components: {
    "message-item": MessageItem,
    "message-qaq": Message
  },
  methods: {
    getMessageItemList() {
      var self = this;
      axios({
        method: "post",
        url: "/home/getMessageItemList",
        data: {
          page: self.page,
          limit: self.limit
        }
      }).then(function(res) {
        var data = res.data;
        self.items = data.list;
        self.moreItems = data.moreItems;
        self.page++;
      });
    },
    readMsg() {
      for (var i in this.items) {
        if (this.items[i].id == this.msg_show) {
          if (this.items[i].is_read == 0) {
            this.read([this.items[i].id]);
          }
          return this.items[i];
        }
      }
    },
    readAll() {
      var list = [];
      for (var i in this.items) {
        if (this.items[i].is_read == 0) {
          list.push(this.items[i].id);
        }
      }
      this.read(list);
    },
    read(list) {
      env.$emit("msgRows", list.length);
      var self = this;
      axios({
        method: "post",
        url: "/home/readMsg",
        data: {
          id: list
        }
      }).then(function(res) {
        self.getMessageItemList();
      });
    }
  },
  created() {
    var self = this;
    env.$on("msg_show", msg_show => {
      self.msg_show = msg_show;
    });
    this.getMessageItemList();
  },
  destroyed() {
    env.$off("msg_show");
  }
};
</script>
<style>
</style>
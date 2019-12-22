<template>
  <div class="user-box">
    <a class="img-box">
      <img :src="text.user.avatar" />
    </a>
    <div class="text-box">
      <a class="name-box">
        <label>{{text.user.name}}</label>
      </a>
      <div class="msg-box">
        <time>{{showdate(text.updated_at)}}</time>
        <span>阅读 {{text.show_num}}</span>
        <span>收藏 {{text.collects.length}}</span>
      </div>
    </div>
    <sui-button
      class="collect-but"
      style="position: absolute;top: -3rem;"
      v-if="isOwner()"
      icon="edit"
      label-position="left"
      color="orange"
      content="编辑文章"
      @click="goEdit()"
    />
    <sui-button
      class="collect-but"
      style="position: absolute;"
      v-if="user!=null"
      color="red"
      icon="heart"
      label-position="left"
      :content="isCollect"
      @click="collectThis()"
    />
  </div>
</template>

<script>
import env from "../../../env";
export default {
  props: {
    text: Object,
    user: Array
  },
  data: function() {
    return {};
  },
  computed: {
    isCollect() {
      if (this.user == null) {
        return "收藏";
      }
      var list = this.text.collects;
      for (var i in list) {
        if (list[i].user_id == this.user.id && list[i].status == 1) {
          return "取消收藏";
        }
      }
      return "收藏";
    }
  },
  methods: {
    showdate(t) {
      var date = new Date(parseInt(t) * 1000);
      var Y = date.getFullYear() + "-";
      var M =
        (date.getMonth() + 1 < 10
          ? "0" + (date.getMonth() + 1)
          : date.getMonth() + 1) + "-";
      var D = date.getDate() + " ";
      var h = date.getHours() + ":";
      var m = date.getMinutes();
      return Y + M + D + h + m;
    },
    collectThis() {
      var self = this;
      axios({
        method: "post",
        url: "/home/collectThis",
        data: {
          id: self.text.id
        }
      })
        .then(function(res) {
          env.$emit("collectThis", res.data);
        });
    },
    goEdit() {
      this.$router.push({
        name: "Editor",
        params: {
          id: this.text.id
        }
      });
    },
    isOwner() {
      if (this.user == null) {
        return false;
      }
      if (this.user.id == this.text.user_id) {
        return true;
      } else {
        return false;
      }
    }
  }
};
</script>
<style>
.user-box {
  display: flex;
  margin-bottom: 2rem;
  position: relative;
}
.img-box {
  display: flex;
  width: 4rem;
  height: 4rem;
}
.img-box img {
  width: 100%;
  height: 100%;
  border-radius: 50%;
}
.text-box {
  margin-left: 1rem;
}
.name-box {
  display: block;
  font-weight: bold;
  font-size: 1.2rem;
}
.msg-box {
  color: rgb(150, 150, 150);
}
.msg-box * {
  margin-right: 1rem;
}
.collect-but {
  right: 0;
}
</style>
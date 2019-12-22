<template>
  <div class="manage-head">
    <sui-menu large>
      <sui-menu-item link :icon="switch_icon" @click="menuSwitch()"></sui-menu-item>
      <sui-menu-menu position="right">
        <sui-menu-item icon="home" link content="回到首页" @click="goHome()"></sui-menu-item>
        <sui-menu-item icon="sign-out" link content="退出登录" @click="logout()"></sui-menu-item>
        <sui-menu-item></sui-menu-item>
      </sui-menu-menu>
    </sui-menu>
  </div>
</template>
<script>
import env from "../../env";
export default {
  props: {
    user: Array,
    switch_icon: String,
    menu_switch: Boolean
  },
  data: function() {
    return {};
  },
  methods: {
    goHome() {
      this.$router.push({
        name: "Home"
      });
    },
    logout() {
      var self = this;
      axios({
        method: "post",
        url: "/logout"
      })
        .then(function(res) {
          env.$emit("user", null);
          self.$router.push({
            name: "Home"
          });
        });
    },
    menuSwitch() {
      env.$emit("menu_switch", !this.menu_switch);
    }
  }
};
</script>
<style>
.manage-head {
  width: 100%;
}
</style>
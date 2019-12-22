<template>
  <div class="manage-menu" :style="manage_left">
    <sui-menu vertical inverted fluid class="huge" :style="manage_left_menu">
      <sui-menu-item style="text-align: center;">
        <div class="manage-user">
          <img :src="user.avatar" class="manage-avatar" style="width:50%;margin-top: 1rem;" />
        </div>
        <div class="manage-user">
          <span style="margin-top: 1rem;">{{user.name}}</span>
        </div>
      </sui-menu-item>
      <sui-menu-item
        :key="item.opt"
        v-for="item in items"
        :active="isActive(item.opt)"
        @click="select(item.opt)"
        link
      >
        <sui-icon :name="item.icon" style="float:left;margin-right: 1em" />
        {{item.name}}
      </sui-menu-item>
    </sui-menu>
  </div>
</template>
<script>
import Global from "../../Global";
export default {
  props: {
    user: Array,
    manage_left: Object,
    manage_left_menu: Object
  },
  data: function() {
    return {
      active: "",
      items: Global.ManageMenu
    };
  },
  methods: {
    isActive(name) {
      var fdStart = window.location.href.indexOf(name);
      if (fdStart >= 0) {
        this.active = name;
      }
      return this.active == name;
    },
    select(name) {
      var fdStart = this.active.indexOf(name);
      if (fdStart !== 0) {
        var path = "/manage/" + name;
        this.$router.push({ path: path });
      }
      this.active = name;
    }
  }
};
</script>
<style>
.manage-menu {
  transition: width 0.25s;
  height: 100%;
  background-color: #1b1c1d;
  float: left;
}
.manage-user {
  margin-top: 0.5rem;
  width: 100%;
}
.manage-avatar {
  border-radius: 50%;
}
</style>
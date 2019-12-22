<template>
  <div v-if="user != null" class="manage-base-div" :style="base">
    <manage-menu :user="user" :manage_left="manage_left" :manage_left_menu="manage_left_menu"></manage-menu>
    <div class="manage-right" :style="manage_right">
      <manage-top :user="user" :switch_icon="switch_icon" :menu_switch="menu_switch"></manage-top>
      <div class="manage-main">
        <router-view :user="user" :type="type"></router-view>
      </div>
    </div>
  </div>
</template>

<script>
import ManageMenu from "./ManageMenu";
import ManageTop from "./ManageTop";
import env from "../../env";
export default {
  props: {
    user: Array,
    type: Array
  },
  data: function() {
    return {
      base: {
        height: document.body.clientHeight + "px"
      },
      manage_right: { width: "87.5%" },
      manage_left: { width: "12.5%" },
      manage_left_menu: { display: "" },
      switch_icon: "angle double left",
      menu_switch: true
    };
  },
  watch: {
    user: function() {
      if (this.user.is_admin != 1) {
        this.$router.push({
          name: "Home"
        });
      }
    },
    menu_switch: function() {
      if (this.menu_switch) {
        this.manage_left.width = "12.5%";
        this.manage_right.width = "87.5%";
        this.manage_left_menu.display = "";
        this.switch_icon = "angle double left";
      } else {
        this.manage_left.width = "0%";
        this.manage_right.width = "100%";
        this.manage_left_menu.display = "none";
        this.switch_icon = "angle double right";
      }
    }
  },
  mounted() {
    this.base.height = document.body.clientHeight + "px";
    const self = this;
    window.onresize = function temp() {
      self.base.height = document.body.clientHeight + "px";
    };
    env.$on("menu_switch", menu_switch => {
      self.menu_switch = menu_switch;
    });
  },
  methods: {},
  components: {
    "manage-menu": ManageMenu,
    "manage-top": ManageTop
  },
  destroyed() {
    env.$off("menu_switch");
    console.log("destroyed");
  }
};
</script>
<style>
.manage-base-div {
  width: 100%;
  color: black;
}
.manage-right {
  transition: width 0.25s;
  height: 100%;
  float: right;
}
.manage-main {
  width: 100%;
  height: 100%;
  margin-top: -42px;
  padding-top: 42px;
}
.manage-main-base {
  height: 100%;
  background-color: white;
  border-radius: 1.5rem;
  padding: 3rem;
  overflow: auto;
}
</style>
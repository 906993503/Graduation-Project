<template>
  <div class="main-div">
    <head-menu :user="user" class="base-div" style="background-color: white;"></head-menu>
    <div v-if="type!=null" class="ui container base-div" style="padding: 2rem;margin-top: 1rem;">
      <router-view :user="user" :type="type"></router-view>
    </div>
  </div>
</template>

<script>
import Global from "../../Global";
import HeadMenu from "./HeadMenu";
export default {
  props: {
    user: Array,
    type: Array
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
</style>
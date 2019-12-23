<template>
  <div>
    <sui-modal size="tiny" v-model="open">
      <sui-modal-header>提示</sui-modal-header>
      <sui-modal-content>
        <sui-modal-description v-for="m in msg" :key="m">
          <p>{{m}}</p>
        </sui-modal-description>
      </sui-modal-content>
      <sui-modal-actions>
        <sui-button positive @click.native="toggle">OK</sui-button>
      </sui-modal-actions>
    </sui-modal>
  </div>
</template>

<script>
import env from "../env";
export default {
  data() {
    return { open: false, msg: [] };
  },
  methods: {
    toggle() {
      this.open = !this.open;
    }
  },
  created() {
    env.$on("msg", msg => {
      this.msg = msg;
      this.toggle();
    });
  },
  destroyed() {
    env.$off("msg");
  }
};
</script>

<style lang="css">
</style>
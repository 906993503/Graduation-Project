<template>
  <sui-table-cell>
    <a v-on:click="delComment(item.id)" class="table-a">删除</a>
  </sui-table-cell>
</template>
<script>
import env from "../../../../env";
export default {
  props: {
    item: Object
  },
  methods: {
    delComment(id) {
      env.$emit("delComment", id);
    }
  },
  destroyed() {}
};
</script>
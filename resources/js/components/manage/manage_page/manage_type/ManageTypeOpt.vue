<template>
  <sui-table-cell>
    <a v-on:click="editType(item.id)" class="table-a">编辑</a>
    <a v-on:click="delType(item.id)" class="table-a">删除</a>
  </sui-table-cell>
</template>
<script>
import env from "../../../../env";
export default {
  props: {
    item: Object
  },
  methods: {
    delType(id) {
      env.$emit("delType", id);
    },
    editType(id) {
      env.$emit("editType", id);
    }
  },
  destroyed() {}
};
</script>
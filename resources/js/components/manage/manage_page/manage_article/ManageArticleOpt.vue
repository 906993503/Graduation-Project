<template>
  <sui-table-cell>
    <a v-on:click="editArticle(item.id)" class="table-a">编辑</a>
    <a v-on:click="banArticle(item.id)" v-if="item.active" class="table-a">驳回</a>
    <a v-on:click="delArticle(item.id)" class="table-a">删除</a>
  </sui-table-cell>
</template>
<script>
import env from "../../../../env";
export default {
  props: {
    item: Object
  },
  methods: {
    banArticle(id) {
      env.$emit("banArticle", id);
    },
    editArticle(id) {
      env.$emit("editArticle", id);
    },
    delArticle(id) {
      env.$emit("delArticle", id);
    }
  },
  destroyed() {}
};
</script>
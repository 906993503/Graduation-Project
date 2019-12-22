<template>
  <sui-table-cell>
    <a v-on:click="editUser(item.id)" class="table-a">编辑</a>
    <a v-on:click="banUser(item.id)" class="table-a">{{item.active ? "封禁" : "解封"}}</a>
  </sui-table-cell>
</template>
<script>
import env from "../../../../env";
export default {
  props: {
    item: Object
  },
  methods: {
    banUser(id) {
      env.$emit("banUser", id);
    },
    editUser(id) {
      env.$emit("editUser", id);
    }
  },
  destroyed() {}
};
</script>
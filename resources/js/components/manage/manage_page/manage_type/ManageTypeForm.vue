<template>
  <div class="manage-form">
    <sui-form>
      <sui-form-fields inline>
        <sui-form-field>
          <label>搜索：</label>
          <input type="text" v-model="search" placeholder="分类名" />
        </sui-form-field>
        <sui-button type="button"  color="blue" @click="toSearch()">搜索</sui-button>
        <sui-button type="button"  color="red" @click="newType()">新增分类</sui-button>
      </sui-form-fields>
    </sui-form>
  </div>
</template>
<script>
import env from "../../../../env";
export default {
  data: function() {
    return {
      search: ""
    };
  },
  methods: {
    toSearch() {
      var data = {};
      if (this.search != "") {
        data.search = this.search;
      }
      env.$emit("typeSearch", data);
    },
    newType() {
      env.$emit("editType", 0);
    }
  },
  destroyed() {}
};
</script>
<style>
.manage-form {
  margin-bottom: 2rem;
}
</style>
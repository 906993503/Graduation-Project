<template>
  <div class="manage-form">
    <sui-form>
      <sui-form-fields inline>
        <sui-form-field>
          <label>搜索：</label>
          <input type="text" v-model="search" placeholder="Search..." />
        </sui-form-field>
        <sui-button color="blue" @click="toSearch()">搜索</sui-button>
      </sui-form-fields>
    </sui-form>
  </div>
</template>
<script>
import env from "../../../../env";
export default {
  data: function() {
    return {
      search: "",
      active: "",
      is_admin: "",
      begin_date: "",
      end_date: ""
    };
  },
  methods: {
    toSearch() {
      var data = {};
      if (this.search != "") {
        data.search = this.search;
      }
      console.log(data);
      env.$emit("commentSearch", data);
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
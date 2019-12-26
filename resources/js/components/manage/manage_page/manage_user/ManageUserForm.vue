<template>
  <div class="manage-form">
    <sui-form>
      <sui-form-fields inline>
        <sui-form-field>
          <label>搜索：</label>
          <input type="text" v-model="search" placeholder="昵称、电子邮箱" />
        </sui-form-field>
        <sui-form-field>
          <label>状态：</label>
          <select v-model="active">
            <option value>全部</option>
            <option value="1">正常</option>
            <option value="0">封禁</option>
          </select>
        </sui-form-field>
        <sui-form-field>
          <label>身份：</label>
          <select v-model="is_admin">
            <option value>全部</option>
            <option value="1">管理员</option>
            <option value="0">用户</option>
          </select>
        </sui-form-field>
        <sui-form-field>
          <label>注册时间：</label>
          <input type="date" v-model="begin_date" />
          <label>-</label>
          <input type="date" v-model="end_date" />
        </sui-form-field>
        <sui-button type="button"  color="blue" @click="toSearch()">搜索</sui-button>
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
      if (this.active != "") {
        data.active = this.active;
      }
      if (this.is_admin != "") {
        data.is_admin = this.is_admin;
      }
      if (this.begin_date != "") {
        var d = new Date(this.begin_date);
        data.begin_date = Math.round(d.valueOf() / 1000) - 8 * 60 * 60;
      }
      if (this.end_date != "") {
        var d = new Date(this.end_date);
        data.end_date = Math.round(d.valueOf() / 1000) - 8 * 60 * 60;
      }

      env.$emit("userSearch", data);
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
<template>
  <div class="manage-form">
    <sui-form>
      <sui-form-fields inline>
        <sui-form-field>
          <label>搜索：</label>
          <input type="text" v-model="search" placeholder="Search..." />
        </sui-form-field>
        <sui-form-field>
          <label>分类：</label>
          <select v-model="type_id">
            <option value>全部</option>
            <option v-for="item in type" :key="item.id" :value="item.id">{{item.name}}</option>
            <option value="0">其他</option>
          </select>
        </sui-form-field>
        <sui-form-field>
          <label>状态：</label>
          <select v-model="active">
            <option value>全部</option>
            <option value="1">正常</option>
            <option value="0">驳回</option>
          </select>
        </sui-form-field>
        <sui-form-field>
          <label>编辑时间：</label>
          <input type="date" v-model="begin_date" />
          <label>-</label>
          <input type="date" v-model="end_date" />
        </sui-form-field>
        <sui-button color="blue" @click="toSearch()">搜索</sui-button>
      </sui-form-fields>
    </sui-form>
  </div>
</template>
<script>
import env from "../../../../env";
export default {
  props: {
    type: Array
  },
  data: function() {
    return {
      search: "",
      type_id: "",
      active: "",
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
      if (this.type_id != "") {
        data.type_id = this.type_id;
      }
      if (this.active != "") {
        data.active = this.active;
      }
      if (this.begin_date != "") {
        var d = new Date(this.begin_date);
        data.begin_date = Math.round(d.valueOf() / 1000) - 8 * 60 * 60;
      }
      if (this.end_date != "") {
        var d = new Date(this.end_date);
        data.end_date = Math.round(d.valueOf() / 1000) - 8 * 60 * 60;
      }

      env.$emit("articleSearch", data);
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
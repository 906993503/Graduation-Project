<template>
  <div class="manage-main-base">
    <manage-type-form></manage-type-form>
    <manage-table :user="user" :res="res" opt="ManageTypeOpt"></manage-table>
    <manage-type-edit :res="res"></manage-type-edit>
  </div>
</template>
<script>
import env from "../../../../env";
import ManageTable from "../ManageTable";
import ManageTypeForm from "./ManageTypeForm";
import ManageTypeEdit from "./ManageTypeEdit";
export default {
  props: {
    user: Array
  },
  data: function() {
    return {
      res: {
        page: 1,
        limit: 8,
        rows: 0,
        items: [],
        item_header: [
          {
            title: "分类名",
            field: "name"
          },
          {
            title: "排列顺序",
            field: "sort_by"
          },
          {
            title: "所属文章数",
            field: "child_num"
          }
        ]
      },
      search: {}
    };
  },
  components: {
    "manage-table": ManageTable,
    "manage-type-form": ManageTypeForm,
    "manage-type-edit": ManageTypeEdit
  },
  created() {
    var self = this;
    self.getTypeList();
    env.$on("delType", id => {
      axios({
        method: "post",
        url: "/manage/delType",
        data: {
          id: id
        }
      }).then(function(res) {
        self.getTypeList();
        self.sendTopType();
        var msg = [];
        msg.push("删除成功");
        env.$emit("msg", msg);
      });
    });
    env.$on("submitType", data => {
      console.log(data);
      axios({
        method: "post",
        url: "/manage/saveType",
        data: data
      }).then(function(res) {
        self.getTypeList();
        self.sendTopType();
        var msg = [];
        msg.push("修改成功");
        env.$emit("msg", msg);
      });
    });
    env.$on("typeSearch", data => {
      self.search = data;
      self.getCommentList();
    });
    env.$on("page", page => {
      self.res.page = page;
      self.getTypeList();
    });
  },
  destroyed() {
    env.$off("delType");
    env.$off("submitType");
    env.$off("typeSearch");
    env.$off("page");
    console.log("destroyed");
  },
  methods: {
    getTypeList() {
      var self = this;
      var data = self.search;
      data.page = self.res.page;
      data.limit = self.res.limit;
      axios({
        method: "post",
        url: "/manage/getTypeList",
        data: data
      }).then(function(res) {
        var data = res.data;
        self.res.rows = data.rows;
        self.res.items = data.list;
      });
    },
    sendTopType() {
      axios({
        method: "post",
        url: "/open/getType"
      }).then(function(res) {
        var data = res.data;
        console.log(data.type);
        env.$emit("type", data.type);
      });
    }
  }
};
</script>
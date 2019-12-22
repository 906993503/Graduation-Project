<template>
  <div class="manage-main-base">
    <manage-user-form></manage-user-form>
    <manage-table :user="user" :res="res" opt="ManageUserOpt"></manage-table>
    <manage-user-edit :res="res"></manage-user-edit>
  </div>
</template>
<script>
import env from "../../../../env";
import ManageTable from "../ManageTable";
import ManageUserForm from "./ManageUserForm";
import ManageUserEdit from "./ManageUserEdit";
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
            title: "昵称",
            field: "name"
          },
          {
            title: "头像",
            field: "avatar",
            templet: function(data) {
              return "<img src='" + data.avatar + "' class='table-img'>";
            }
          },
          {
            title: "电子邮箱",
            field: "email"
          },
          {
            title: "状态",
            field: "active",
            templet: function(data) {
              if (data.active) {
                return "正常";
              } else {
                return "封禁";
              }
            }
          },
          {
            title: "身份",
            field: "is_admin",
            templet: function(data) {
              if (data.is_admin) {
                return "管理员";
              } else {
                return "用户";
              }
            }
          },
          {
            title: "注册时间",
            field: "created_at",
            templet: function(data) {
              var date = new Date(parseInt(data.created_at) * 1000);
              var Y = date.getFullYear() + "-";
              var M =
                (date.getMonth() + 1 < 10
                  ? "0" + (date.getMonth() + 1)
                  : date.getMonth() + 1) + "-";
              var D = date.getDate() + " ";
              var h = date.getHours() + ":";
              var m = date.getMinutes();
              return Y + M + D + h + m;
            }
          }
        ]
      },
      search: {}
    };
  },
  components: {
    "manage-table": ManageTable,
    "manage-user-form": ManageUserForm,
    "manage-user-edit": ManageUserEdit
  },
  created() {
    var self = this;
    console.log("create");
    self.getUserList();
    env.$on("banUser", id => {
      console.log('banuser');
      axios({
        method: "post",
        url: "/manage/banUser",
        data: {
          id: id
        }
      })
        .then(function(res) {
          if (res.status)
            for (var i in self.res.items) {
              if (self.res.items[i].id == id) {
                self.res.items[i].active = !self.res.items[i].active;
                break;
              }
            }
          var msg = [];
          msg.push("修改成功");
          env.$emit("msg", msg);
        });
    });

    env.$on("page", page => {
      self.res.page = page;
      console.log("page");
      self.getUserList();
    });
    env.$on("userSearch", data => {
      self.search = data;
      self.getCommentList();
    });

    env.$on("submitUser", data => {
      axios({
        method: "post",
        url: "/manage/saveUser",
        data: {
          id: data.id,
          name: data.name
        }
      })
        .then(function(res) {
          var data = res.data;
          for (var i in self.res.items) {
            if (self.res.items[i].id == data.id) {
              self.res.items[i].name = data.name;
              break;
            }
          }
          var msg = [];
          msg.push("修改成功");
          env.$emit("msg", msg);
        });
    });
  },
  destroyed() {
    env.$off("banUser");
    env.$off("page");
    env.$off("userSearch");
    env.$off("submitUser");
    console.log("destroyed");
  },
  methods: {
    getUserList() {
      var self = this;
      var data = self.search;
      data.page = self.res.page;
      data.limit = self.res.limit;
      axios({
        method: "post",
        url: "/manage/getUserList",
        data: data
      })
        .then(function(res) {
          var data = res.data;
          self.res.rows = data.rows;
          self.res.items = data.list;
        });
    }
  }
};
</script>
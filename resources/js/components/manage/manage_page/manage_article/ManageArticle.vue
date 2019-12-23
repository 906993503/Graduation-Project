<template>
  <div class="manage-main-base">
    <manage-article-form :type="type"></manage-article-form>
    <manage-table :user="user" :type="type" :res="res" opt="ManageArticleOpt"></manage-table>
    <manage-article-edit :res="res" :type="type"></manage-article-edit>
    <manage-article-ban :res="res"></manage-article-ban>
  </div>
</template>
<script>
import env from "../../../../env";
import ManageTable from "../ManageTable";
import ManageArticleForm from "./ManageArticleForm";
import ManageArticleEdit from "./ManageArticleEdit";
import ManageArticleBan from "./ManageArticleBan";
export default {
  props: {
    user: Array,
    type: Array
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
            title: "用户名称",
            field: "user_name"
          },
          {
            title: "标题",
            field: "title"
          },
          {
            title: "文章封面",
            field: "face_img",
            templet: function(data) {
              if (data.face_img == null) {
                return "";
              } else {
                return "<img src='" + data.face_img + "' class='table-img'>";
              }
            }
          },
          {
            title: "内容",
            field: "",
            templet: function(data) {
              return (
                "<a href='/#/content/" +
                data.id +
                "' target='_blank' class='table-a'>查看</a>"
              );
            }
          },
          {
            title: "所属分类",
            field: "type_id",
            templet: function(data) {
              var items = data.type;
              for (var i in items) {
                if (items[i].id == data.type_id) {
                  return items[i].name;
                }
              }
            }
          },
          {
            title: "浏览次数",
            field: "show_num"
          },
          {
            title: "状态",
            field: "active",
            templet: function(data) {
              if (data.active) {
                return "正常";
              } else {
                return "驳回";
              }
            }
          },
          {
            title: "编辑时间",
            field: "updated_at",
            templet: function(data) {
              var date = new Date(parseInt(data.updated_at) * 1000);
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
    "manage-article-form": ManageArticleForm,
    "manage-article-edit": ManageArticleEdit,
    "manage-article-ban": ManageArticleBan
  },
  created() {
    var self = this;
    self.getArticleList();
    env.$on("submitArticleBan", data => {
      axios({
        method: "post",
        url: "/manage/banArticle",
        data: data
      }).then(function(res) {
        self.getArticleList();
        var msg = [];
        msg.push("修改成功");
        env.$emit("msg", msg);
      });
    });

    env.$on("submitArticle", data => {
      axios({
        method: "post",
        url: "/manage/saveArticle",
        data: data
      }).then(function(res) {
        self.getArticleList();
        var msg = [];
        msg.push("修改成功");
        env.$emit("msg", msg);
      });
    });

    env.$on("delArticle", id => {
      axios({
        method: "post",
        url: "/manage/delArticle",
        data: {
          id: id
        }
      }).then(function(res) {
        self.getArticleList();
        var msg = [];
        msg.push("删除成功");
        env.$emit("msg", msg);
      });
    });

    env.$on("page", page => {
      self.res.page = page;
      self.getArticleList();
    });

    env.$on("articleSearch", data => {
      self.search = data;
      self.getArticleList();
    });
  },
  destroyed() {
    env.$off("submitArticleBan");
    env.$off("submitArticle");
    env.$off("delArticle");
    env.$off("articleSearch");
    env.$off("page");

  },
  methods: {
    getArticleList() {
      var self = this;
      var data = self.search;
      data.page = self.res.page;
      data.limit = self.res.limit;
      axios({
        method: "post",
        url: "/manage/getArticleList",
        data: data
      }).then(function(res) {
        var data = res.data;
        self.res.rows = data.rows;
        for (var i in data.list) {
          data.list[i].type = self.type;
        }
        self.res.items = data.list;
      });
    }
  }
};
</script>
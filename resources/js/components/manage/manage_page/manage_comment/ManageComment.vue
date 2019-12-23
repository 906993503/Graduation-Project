<template>
  <div class="manage-main-base">
    <manage-comment-form></manage-comment-form>
    <manage-table :user="user" :res="res" opt="ManageCommentOpt"></manage-table>
  </div>
</template>
<script>
import env from "../../../../env";
import ManageTable from "../ManageTable";
import ManageCommentForm from "./ManageCommentForm";
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
            title: "评论用户",
            field: "user_name",
            templet: function(data) {
              return data.user.name;
            }
          },
          {
            title: "评论用户头像",
            field: "user_img",
            templet: function(data) {
              return "<img src='" + data.user.avatar + "' class='table-img'>";
            }
          },
          {
            title: "评论内容",
            field: "content",
            templet: function(data) {
              var url =
                "<a href='/#/content/" +
                data.article_id +
                "/" +
                data.id +
                "' target='_blank' class='table-a' title='查看'>" +
                data.content +
                "</a>";
              return url;
            },
            width: 9
          },
          {
            title: "被评论用户",
            field: "be_user_name",
            templet: function(data) {
              return data.be_user.name;
            }
          },
          {
            title: "被评论用户头像",
            field: "be_user_img",
            templet: function(data) {
              return (
                "<img src='" + data.be_user.avatar + "' class='table-img'>"
              );
            }
          },
          {
            title: "评论时间",
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
    "manage-comment-form": ManageCommentForm
  },
  created() {
    var self = this;
    self.getCommentList();
    env.$on("delComment", id => {
      axios({
        method: "post",
        url: "/manage/delComment",
        data: {
          id: id
        }
      }).then(function(res) {
        self.getCommentList();
      });
    });
    env.$on("page", page => {
      self.res.page = page;
      self.getCommentList();
    });
    env.$on("commentSearch", data => {
      self.search = data;
      self.getCommentList();
    });
  },
  methods: {
    getCommentList() {
      var self = this;
      var data = self.search;
      data.page = self.res.page;
      data.limit = self.res.limit;
      axios({
        method: "post",
        url: "/manage/getCommentList",
        data: data
      }).then(function(res) {
        var data = res.data;
        self.res.rows = data.rows;
        self.res.items = data.list;
      });
    }
  },
  destroyed() {
    env.$off("delComment");
    env.$off("page");
    env.$off("commentSearch");
  }
};
</script>
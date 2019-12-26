<template>
  <sui-comment-group>
    <h3 is="sui-header" dividing>评论</h3>

    <sui-comment
      v-for="item in comment_list"
      :key="item.id"
      :id="setId(item.id)"
      @load="testJump(item.id)"
    >
      <sui-comment-avatar :src="item.user.avatar" />
      <sui-comment-content>
        <sui-comment-author>{{item.user.name}}</sui-comment-author>
        <sui-comment-metadata>
          <div>{{showdate(item.created_at)}}</div>
        </sui-comment-metadata>
        <sui-comment-text v-html="item.content"></sui-comment-text>
        <sui-comment-actions>
          <sui-comment-action @click="toCommentThis(item.user.id,item.user.name,item.id)">回复</sui-comment-action>
        </sui-comment-actions>
      </sui-comment-content>
    </sui-comment>

    <sui-menu pagination floated="right">
      <a is="sui-menu-item" title="第一页" icon="angle double left" @click="pageTop()"></a>
      <a is="sui-menu-item" title="上一页" icon="angle left" @click="pageUp()"></a>
      <a
        is="sui-menu-item"
        v-for="i in page_list"
        :key="i"
        @click="pageTo(i)"
        :active="isActive(i)"
      >{{i}}</a>
      <a is="sui-menu-item" title="下一页" icon="angle right" @click="pageDown()"></a>
      <a is="sui-menu-item" title="最后一页" icon="angle double right" @click="pageEnd()"></a>
    </sui-menu>
    <div style="clear:both;"></div>
    <div class="comment" v-if="user != null" id="c0">
      <quill-editor v-model="txt" ref="myQuillEditor" :options="editorOption"></quill-editor>
      <sui-button
        content="添加评论"
        label-position="left"
        icon="edit"
        floated="right"
        primary
        @click="addComment()"
      />
      <div style="clear:both;"></div>
    </div>
  </sui-comment-group>
</template>

<script>
import { quillEditor } from "vue-quill-editor";
import env from "../../../env";
export default {
  props: {
    comment: Array,
    user: Array,
    text: Object
  },
  data() {
    return {
      page: 1,
      limit: 20,
      be_user_id: this.text.user_id,
      txt: "",
      set_user: "",
      editorOption: {
        modules: {
          toolbar: [
            ["bold", "italic", "underline", "strike"],
            ["blockquote", "code-block"]
          ]
        }
      }
    };
  },
  methods: {
    toCommentThis(user_id, user_name, comment_id) {
      this.set_user = "@" + user_name + "</a>";
      this.txt = this.txt.replace(/<a[^>]+>[\s\S]*<\/a>/g, "");
      this.txt =
        "<a href='/#/content/" +
        this.text.id +
        "/" +
        comment_id +
        "' target='_self'>@" +
        user_name +
        "</a><span style='display: inline;'>&nbsp;</span>" +
        this.txt;
      this.be_user_id = user_id;
      this.commentJump(0);
    },
    showdate(t) {
      var date = new Date(parseInt(t) * 1000);
      var Y = date.getFullYear() + "-";
      var M =
        (date.getMonth() + 1 < 10
          ? "0" + (date.getMonth() + 1)
          : date.getMonth() + 1) + "-";
      var D = date.getDate() + " ";
      var h = date.getHours() + ":";
      var m = date.getMinutes();
      return Y + M + D + h + m;
    },
    setId(id) {
      return "c" + id;
    },
    isActive(i) {
      return i == this.page;
    },
    pageTop() {
      this.page = 1;
    },
    pageUp() {
      if (this.page - 1 >= 1) {
        this.page = this.page - 1;
      }
    },
    pageTo(page) {
      this.page = page;
    },
    pageDown() {
      var end =
        Math.floor(this.rows / this.limit) + (this.rows % this.limit ? 1 : 0);
      if (this.page + 1 <= end) {
        this.page++;
      }
    },
    pageEnd() {
      var end =
        Math.floor(this.rows / this.limit) + (this.rows % this.limit ? 1 : 0);
      this.page = end;
    },
    commentJump(id) {
      var find = "#c" + id;
      var Ele = document.querySelector(find);
      if (!!Ele) {
        Ele.scrollIntoView(true);
      }
    },
    addComment() {
      var self = this;
      if (self.user.email_verified_at == null) {
        self.$router.go(-1);
        var data = [];
        data.push("请先验证邮箱");
        env.$emit("msg", data);
      }
      if (self.txt.length < 6) {
        var data = [];
        data.push("评论至少6个字符");
        env.$emit("msg", data);
        return false;
      }
      axios({
        method: "post",
        url: "/home/addComment",
        data: {
          id: self.text.id,
          be_user_id: self.be_user_id,
          content: self.txt
        }
      }).then(function(res) {
        var data = res.data;
        self.txt = "";
        self.$parent.getArticle();
      });
    },
    postComment(cid) {
      var self = this;
      if (cid != null) {
        var cnt = 0;
        for (var i in this.comment) {
          cnt++;
          if (this.comment[i].id == cid) {
            this.page =
              Math.floor(cnt / this.limit) + (cnt % this.limit ? 1 : 0);
            setTimeout(function() {
              self.commentJump(cid);
            }, 200);
            break;
          }
        }
      }
    },
    testJump(id) {
      if (id == this.$route.params.cid) {
      }
    }
  },
  computed: {
    page_list: function() {
      var begin = 1;
      var end =
        Math.floor(this.rows / this.limit) + (this.rows % this.limit ? 1 : 0);
      var list = [];
      var w = 5;
      if (end - begin + 1 <= w) {
        for (var i = begin; i <= end; i++) {
          list.push(i);
        }
        return list;
      }
      var l = this.page - 2;
      var r = this.page + 2;
      while (l < begin || r > end) {
        if (l < begin) {
          l++;
          r++;
        }
        if (r > end) {
          l--;
          r--;
        }
      }
      for (var i = l; i <= r; i++) {
        list.push(i);
      }
      return list;
    },
    rows: function() {
      return this.comment.length;
    },
    comment_list: function() {
      return this.comment.slice(
        (this.page - 1) * this.limit,
        this.page * this.limit
      );
    },
    editor() {
      return this.$refs.myQuillEditor.quill;
    }
  },
  created() {},
  watch: {
    $route(to, from) {
      this.postComment(to.params.cid);
    },
    txt(newTxt, oldTxt) {
      if (this.txt.match("</a>") !== null) {
        if (this.txt.match(this.set_user) === null) {
          this.be_user_id = this.text.user_id;
          this.set_user = "";
          this.txt = this.txt.replace(/<a[^>]+>[\s\S]*<\/a>/g, "");
        }
      }
    }
  },
  mounted() {
    this.postComment(this.$route.params.cid);
  },
  components: {
    quillEditor
  }
};
</script>
<style>
.ui.comments {
  max-width: unset;
}
.comment {
  margin-top: 1rem;
  margin-bottom: 0.5rem;
}
.comment .ql-container.ql-snow {
  min-height: 8rem;
}
.comment .ql-editor {
  min-height: 8rem;
}
.comment .ql-tooltip.ql-flip {
  display: none !important;
}
</style>
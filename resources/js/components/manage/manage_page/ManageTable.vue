<template>
  <div>
    <sui-table celled>
      <sui-table-header>
        <sui-table-row>
          <sui-table-header-cell
            v-for="item in res.item_header"
            :key="item.field"
            :width="checkWidth(item)"
          >{{item.title}}</sui-table-header-cell>
          <sui-table-header-cell>操作</sui-table-header-cell>
        </sui-table-row>
      </sui-table-header>

      <sui-table-body>
        <sui-table-row v-for="item in res.items" :key="item.id">
          <sui-table-cell
            v-for="i in res.item_header"
            :key="i.field"
            v-html="true_item(item,i)"
            :width="checkWidth(i)"
            style="word-break: break-all;"
          ></sui-table-cell>
          <manage-user-opt v-if="opt == 'ManageUserOpt'" :item="item"></manage-user-opt>
          <manage-article-opt v-if="opt == 'ManageArticleOpt'" :item="item"></manage-article-opt>
          <manage-type-opt v-if="opt == 'ManageTypeOpt'" :item="item"></manage-type-opt>
          <manage-comment-opt v-if="opt == 'ManageCommentOpt'" :item="item"></manage-comment-opt>
        </sui-table-row>
      </sui-table-body>
    </sui-table>
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
  </div>
</template>
<script>
import ManageUserOpt from "./manage_user/ManageUserOpt";
import ManageArticleOpt from "./manage_article/ManageArticleOpt";
import ManageTypeOpt from "./manage_type/ManageTypeOpt";
import ManageCommentOpt from "./manage_comment/ManageCommentOpt";
import env from "../../../env";

export default {
  props: {
    user: Array,
    opt: String,
    res: Object
  },
  data: function() {
    return {};
  },
  watch: {},
  methods: {
    true_item: function(item, i) {
      if (i.templet != undefined) {
        return i.templet(item);
      }
      return item[i.field];
    },
    checkWidth(i) {
      if (i.width != undefined) {
        return i.width;
      }
      return null;
    },
    isActive(i) {
      return i == this.res.page;
    },
    pageTop() {
      env.$emit("page", 1);
    },
    pageUp() {
      if (this.res.page - 1 >= 1) {
        env.$emit("page", this.res.page - 1);
      }
    },
    pageTo(page) {
      env.$emit("page", page);
    },
    pageDown() {
      var end =
        Math.floor(this.res.rows / this.res.limit) +
        (this.res.rows % this.res.limit ? 1 : 0);
      if (this.res.page + 1 <= end) {
        env.$emit("page", this.res.page + 1);
      }
    },
    pageEnd() {
      var end =
        Math.floor(this.res.rows / this.res.limit) +
        (this.res.rows % this.res.limit ? 1 : 0);
      env.$emit("page", end);
    }
  },
  destroyed() {},
  computed: {
    page_list: function() {
      var begin = 1;
      var end =
        Math.floor(this.res.rows / this.res.limit) +
        (this.res.rows % this.res.limit ? 1 : 0);
      var list = [];
      var w = 5;
      if (end - begin + 1 <= w) {
        for (var i = begin; i <= end; i++) {
          list.push(i);
        }
        return list;
      }
      var l = this.res.page - 2;
      var r = this.res.page + 2;
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
    }
  },
  components: {
    "manage-user-opt": ManageUserOpt,
    "manage-article-opt": ManageArticleOpt,
    "manage-type-opt": ManageTypeOpt,
    "manage-comment-opt": ManageCommentOpt
  }
};
</script>
<style>
.table-img {
  width: 4rem;
}
.table-a {
  color: blue;
  margin: 0.5rem;
  text-decoration: underline;
  cursor: pointer;
}
</style>
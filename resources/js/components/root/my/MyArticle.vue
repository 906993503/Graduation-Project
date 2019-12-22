<template>
  <div class="user-body">
    <sui-button
      compact
      icon="plus"
      label-position="left"
      content="新建文章"
      color="red"
      floated="right"
      @click="goEdit(0)"
    />
    <sui-menu tabular style="margin-top: 2.2rem;">
      <sui-menu-item :active="true">我的文章</sui-menu-item>
    </sui-menu>
    <article-item :items="items" style="min-height: 50rem;"></article-item>
    <sui-button v-if="moreItems>0" fluid content="加载更多" @click="getArticleItemList()" />
  </div>
</template>
<script>
import ArticleItem from "../ArticleItem";
export default {
  props: {
    user: Array
  },
  data() {
    return {
      items: [],
      moreItems: 0,
      page: 1,
      limit: 8
    };
  },
  components: {
    "article-item": ArticleItem
  },
  methods: {
    goEdit(id) {
      this.$router.push({
        name: "Editor",
        params: {
          id: id
        }
      });
    },
    getArticleItemList() {
      var self = this;
      axios({
        method: "post",
        url: "/home/getMyArticleItemList",
        data: {
          page: self.page,
          limit: self.limit
        }
      })
        .then(function(res) {
          var data = res.data;
          self.items = data.list;
          self.moreItems = data.moreItems;
          self.page++;
        });
    }
  },
  created() {
    this.getArticleItemList();
  }
};
</script>
<style>
.user-body {
  width: 100%;
}
</style>
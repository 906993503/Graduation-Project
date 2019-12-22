<template>
  <div style="min-height: 56rem;">
    <article-item :items="items"></article-item>
    <sui-button v-if="moreItems>0" fluid content="加载更多" @click="getArticleItemList()" />
  </div>
</template>

<script>
import ArticleItem from "../ArticleItem";
import env from "../../../env";
export default {
  props: {
    user: Array,
    type: Array
  },
  data: function() {
    return {
      type_id: 0,
      items: [],
      moreItems: 0,
      page: 1,
      limit: 8
    };
  },
  watch: {
    $route(to, from) {
      this.type_id = to.params.type;
      this.page = 1;
      this.getArticleItemList();
    }
  },
  methods: {
    getArticleItemList() {
      var self = this;
      axios({
        method: "post",
        url: "/open/getArticleItemList",
        data: {
          page: self.page,
          limit: self.limit,
          type_id: self.type_id
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
    this.type_id = parseInt(this.$route.params.type);
    this.page = 1;
    this.getArticleItemList();
  },
  components: {
    "article-item": ArticleItem
  }
};
</script>
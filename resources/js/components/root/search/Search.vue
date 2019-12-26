<template>
  <div>
    <sui-form>
      <sui-form-field inline>
        <input placeholder="First Name" />
        <sui-button type="button" color="blue" icon="search" labelPosition="left" @click="toNewSearch()">搜索</sui-button>
      </sui-form-field>
    </sui-form>
    <article-item :items="items" style="min-height: 51rem;margin-top: 2rem;"></article-item>
    <sui-button type="button" v-if="moreItems>0" fluid content="加载更多" @click="getArticleItemList()" />
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
  data() {
    return {
      text: "",
      page: 1,
      limit: 8,
      items: [],
      moreItems: 0
    };
  },
  components: {
    "article-item": ArticleItem
  },
  methods: {
    toSearch() {
      var self = this;
      axios({
        method: "post",
        url: "/open/toSearch",
        data: {
          page: self.page,
          limit: self.limit,
          search: self.text
        }
      }).then(function(res) {
        var data = res.data;
        self.items = data.list;
        self.moreItems = data.moreItems;
        self.page++;
      });
    },
    getArticleItemList() {
      this.page++;
      this.toSearch();
    },
    toNewSearch() {
      this.page = 1;
      this.toSearch();
    }
  },
  watch: {
    $route(to, from) {
      this.text = to.params.text;
      this.page = 1;
      this.toSearch();
    }
  },
  created() {
    this.text = this.$route.params.text;
    this.page = 1;
    this.toSearch();
  }
};
</script>
<style>
</style>
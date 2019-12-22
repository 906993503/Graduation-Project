<template>
  <div v-if="text!=null">
    <h1>{{text.title}}</h1>
    <user-item :user="user" :text="text"></user-item>
    <article-qaq :text="text"></article-qaq>
    <comment-qaq :comment="text.comments" :user="user" :text="text"></comment-qaq>
  </div>
</template>

<script>
import UserItem from "./UserItem";
import Article from "./Article";
import env from "../../../env";
import Comment from "./Comment";
export default {
  props: {
    user: Array,
    type: Array
  },
  data: function() {
    return {
      id: 0,
      text: null
    };
  },
  components: {
    "user-item": UserItem,
    "article-qaq": Article,
    "comment-qaq": Comment
  },
  methods: {
    getArticle() {
      var self = this;
      axios({
        method: "post",
        url: "/open/getArticle",
        data: {
          id: self.id
        }
      })
        .then(function(res) {
          var data = res.data;
          console.log(data.text);
          if (data.status) {
            self.text = data.text;
          } else {
            self.$router.push({
              name: "Home"
            });
          }
        });
    }
  },
  created() {
    var self = this;
    this.id = this.$route.params.id;
    this.getArticle();
    env.$on("collectThis", data => {
      self.text.collects = data;
    });
  },
  watch: {
    $route(to, from) {
      if (this.id != to.params.id) {
        this.id = to.params.id;
        this.getArticle();
      }
    }
  },
  destroyed() {
    env.$off("collectThis");
  }
};
</script>
<style>
</style>
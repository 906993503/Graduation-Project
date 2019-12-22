<template>
  <div>
    <sui-item-group divided link>
      <sui-item
        v-for="item in items"
        :key="item.id"
        :href="goThisItem(item)"
        :style="isActive(item)"
      >
        <sui-item-image size="small" v-if="item.face_img != null" :src="item.face_img" />
        <sui-item-content>
          <sui-item-header>{{ item.title }}</sui-item-header>
          <sui-item-meta>
            <span class="author">{{item.user.name}}</span>
            <span class="date-time">{{showdate(item.updated_at)}}</span>
          </sui-item-meta>
          <sui-item-description>
            <p v-html="item.show_text"></p>
          </sui-item-description>
        </sui-item-content>
      </sui-item>
    </sui-item-group>
  </div>
</template>

<script>
export default {
  props: ["items"],
  data: function() {
    return {};
  },
  methods: {
    goThisItem: function(item) {
      if (item.active) {
        return "/#/content/" + item.id;
      } else {
        return "/#/editor/" + item.id;
      }
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
    isActive(item) {
      if (item.active) {
        return {};
      } else {
        return {
          "background-color": "#fff9eb"
        };
      }
    }
  }
};
</script>
<style>
.ui.items > .item > .content > .description {
  font-size: 1.2em;
}
</style>
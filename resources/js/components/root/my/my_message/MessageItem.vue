<template>
  <div style="min-height: 50rem;">
    <sui-item-group divided link>
      <sui-item
        v-for="item in items"
        :key="item.id"
        href
        @click.native="goThisItem(item.id)"
        :style="isRead(item)"
      >
        <sui-item-content>
          <sui-item-header>{{ item.title }}</sui-item-header>
          <sui-item-description>
            <p v-html="item.show_text"></p>
          </sui-item-description>
        </sui-item-content>
      </sui-item>
    </sui-item-group>
  </div>
</template>
<script>
import env from "../../../../env";
export default {
  props: {
    items: Array
  },
  data: function() {
    return {};
  },
  methods: {
    goThisItem: function(item) {
      env.$emit("msg_show", item);
    },
    isRead: function(item) {
      if (item.is_read == false) {
        return {
          "background-color": "#fff9eb",
          padding: "1.2rem !important"
        };
      }
      return {
        padding: "1.2rem !important"
      };
    }
  }
};
</script>
<style>
</style>
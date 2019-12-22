<template>
  <div>
    <sui-item-group divided link>
      <sui-item
        v-for="item in items"
        :key="item"
        href
        @click.native="goThisItem(item)"
        :style="isRead(item)"
      >
        <sui-item-content>
          <sui-item-header>{{ item }} World!</sui-item-header>
          <sui-item-description>
            <p>
              Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor
              invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
            </p>
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
      console.log("233");
      env.$emit("msg_show", item);
    },
    isRead: function(item) {
      if (item % 2) {
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
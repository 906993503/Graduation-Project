<template>
  <div>
    <sui-grid>
      <sui-grid-column :width="4">
        <sui-menu fluid vertical secondary>
          <sui-menu-item :active="isActive(0)" @click="select(0)" link>全部</sui-menu-item>
          <a
            v-for="item in type"
            :key="item.id"
            is="sui-menu-item"
            :content="item.name"
            :active="isActive(item.id)"
            @click="select(item.id)"
          />
        </sui-menu>
      </sui-grid-column>
      <sui-grid-column stretched :width="12">
        <router-view :user="user"></router-view>
      </sui-grid-column>
    </sui-grid>
  </div>
</template>

<script>
import Global from "../../../Global";

export default {
  props: {
    user: Array,
    type: Array
  },
  data: function() {
    return {
      active: this.$route.params.type
    };
  },
  components: {},
  methods: {
    itemName(name) {
      return Global.SubMenu[name];
    },
    isActive(name) {
      return this.active == name;
    },
    select(name) {
      if (this.active !== name) {
        this.$router.push({
          name: "HomeContent",
          params: {
            type: name
          }
        });
      }
      this.active = name;
    }
  }
};
</script>
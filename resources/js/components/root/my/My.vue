<template>
  <div v-if="user != null">
    <sui-grid>
      <sui-grid-column :width="3">
        <sui-menu fluid vertical tabular class="massive">
          <a
            v-for="item in menuItems"
            :key="item.opt"
            is="sui-menu-item"
            :active="isActive(item.opt)"
            @click="select(item.opt)"
          >
            {{item.name}}
            <span v-if="item.opt == 'message'" class="descriptio">2</span>
          </a>
        </sui-menu>
      </sui-grid-column>
      <sui-grid-column stretched :width="13">
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
  data() {
    return {
      menuItems: Global.MyHomeMenu,
      active: ""
    };
  },
  methods: {
    isActive(name) {
      var fdStart = window.location.href.indexOf(name);
      if (fdStart >= 0) {
        this.active = name;
      }
      return this.active == name;
    },
    select(name) {
      if (this.active !== name) {
        this.$router.push({
          path: "/my/" + name
        });
      }
      this.active = name;
    }
  }
};
</script>

<style>
.descriptio {
  float: right;
  margin: 0 0 0 1em;
  color: rgba(0, 0, 0, 0.4);
}
</style>
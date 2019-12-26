<template>
  <div class="label-base">
    <div class="label-left">
      <span class="label-item" v-for="item in label" :key="item">
        {{item}}
        <sui-icon class="label-del" name="close icon" @click="delLabel(item)" />
      </span>
    </div>
    <div class="label-right">
      <sui-form style="position: static;">
        <sui-form-field>
          <label>新增标签：</label>
          <input placeholder="Label" v-model="l" />
        </sui-form-field>
        <sui-button type="button" color="olive" @click="addLabel()">添加标签</sui-button>
      </sui-form>

      <div style="clear:both;"></div>
    </div>
  </div>
</template>
<script>
import env from "../env";
export default {
  props: {
    label: Array
  },
  data() {
    return {
      l: ""
    };
  },
  methods: {
    delLabel(item) {
      var label = this.label;
      for (var i in label) {
        if (label[i] == item) {
          label.splice(i, 1);
          break;
        }
      }
      env.$emit("label", label);
    },
    addLabel() {
      var label = this.label;
      if (this.l != "") {
        var flag = true;
        for (var i in label) {
          if (label[i] == this.l) {
            flag = false;
            break;
          }
        }
        if (flag) {
          label.push(this.l);
          env.$emit("label", label);
          this.l = "";
        }
      }
    }
  }
};
</script>
<style>
.label-base {
  width: 100%;
  margin: 1rem;
}
.label-item {
  background-color: greenyellow;
  border-radius: 15px;
  margin: 0.3rem;
  display: inline-block;
  padding-left: 1rem;
  padding-right: 0.5rem;
}
.label-del {
  cursor: pointer;
}
.label-left {
  width: 69%;
  float: left;
  line-height: 3rem;
}
.label-right {
  width: 29%;
  float: right;
  margin-right: 1rem;
}
</style>
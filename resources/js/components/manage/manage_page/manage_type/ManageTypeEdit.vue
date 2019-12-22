<template>
  <div>
    <sui-modal size="tiny" v-model="open" closeIcon>
      <sui-modal-header>分类信息编辑</sui-modal-header>
      <sui-modal-content>
        <sui-form>
          <sui-form-field>
            <label>分类名：</label>
            <input type="text" v-model="name" />
          </sui-form-field>
          <sui-form-field>
            <label>排列顺序：</label>
            <input type="number" v-model="sort_by" />
          </sui-form-field>
          <div class="submit-div">
            <sui-button color="blue" @click="submit()">提交</sui-button>
          </div>
        </sui-form>
      </sui-modal-content>
    </sui-modal>
  </div>
</template>
<script>
import env from "../../../../env";
export default {
  props: {
    res: Object
  },
  data: function() {
    return { open: false, name: "", id: "", sort_by: "" };
  },
  methods: {
    toggle() {
      this.open = !this.open;
    },
    submit() {
      var data = {
        id: this.id,
        name: this.name,
        sort_by: this.sort_by
      };
      env.$emit("submitType", data);
      this.toggle();
    }
  },
  created() {
    var self = this;
    env.$on("editType", id => {
      if (id == 0) {
        self.id = 0;
        self.name = "";
        self.sort_by = "";
        self.toggle();
      } else {
        for (var i in self.res.items) {
          if (self.res.items[i].id == id) {
            self.id = self.res.items[i].id;
            self.name = self.res.items[i].name;
            self.sort_by = self.res.items[i].sort_by;
            self.toggle();
          }
        }
      }
    });
  },
  destroyed() {
    env.$off("editType");
    console.log("destroyed");
  }
};
</script>
<style>
.submit-div {
  text-align: center;
  width: 100%;
}
</style>
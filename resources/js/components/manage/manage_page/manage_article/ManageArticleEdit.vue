<template>
  <div>
    <sui-modal size="small" v-model="open" closeIcon>
      <sui-modal-header>文章信息编辑</sui-modal-header>
      <sui-modal-content>
        <sui-form>
          <sui-form-field>
            <label>标题：</label>
            <input type="text" v-model="title" />
          </sui-form-field>
          <sui-form-field>
            <label>所属分类：</label>
            <select v-model="type_id">
              <option v-for="item in type" :key="item.id" :value="item.id">{{item.name}}</option>
            </select>
          </sui-form-field>
        </sui-form>
        <div style="width:100%;">
          <label-item :label="label"></label-item>
          <div style="clear:both;"></div>
        </div>
        <div class="submit-div">
          <sui-button color="blue" @click="submit()">提交</sui-button>
        </div>
      </sui-modal-content>
    </sui-modal>
  </div>
</template>
<script>
import env from "../../../../env";
import LabelItem from "../../../LabelItem";
export default {
  props: {
    res: Object,
    type: Array
  },
  data: function() {
    return {
      open: false,
      title: "",
      id: "",
      type_id: "",
      label: []
    };
  },
  methods: {
    toggle() {
      this.open = !this.open;
    },
    submit() {
      var data = {
        id: this.id,
        title: this.title,
        type_id: this.type_id,
        label: this.label
      };
      env.$emit("submitArticle", data);
      this.toggle();
    }
  },
  created() {
    var self = this;
    env.$on("editArticle", id => {
      for (var i in self.res.items) {
        if (self.res.items[i].id == id) {
          self.id = self.res.items[i].id;
          self.title = self.res.items[i].title;
          self.type_id = self.res.items[i].type_id;
          self.label = self.res.items[i].label;
          this.toggle();
        }
      }
    });

    env.$on("label", label => {
      self.label = label;
      console.log(self.label);
    });
  },
  destroyed() {
    env.$off("editArticle");
    env.$off("label");
    console.log("destroyed");
  },
  components: {
    "label-item": LabelItem
  }
};
</script>
<style>
.submit-div {
  text-align: center;
  width: 100%;
}
</style>
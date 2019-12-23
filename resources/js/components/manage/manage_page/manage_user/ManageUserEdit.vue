<template>
  <div>
    <sui-modal size="tiny" v-model="open" closeIcon>
      <sui-modal-header>用户信息编辑</sui-modal-header>
      <sui-modal-content>
        <sui-form>
          <sui-form-field>
            <label>昵称：</label>
            <input type="text" v-model="name" />
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
    return { open: false, name: "", id: "" };
  },
  methods: {
    toggle() {
      this.open = !this.open;
    },
    submit() {
      var data = {
        id: this.id,
        name: this.name
      };
      env.$emit("submitUser", data);
      this.toggle();
    }
  },
  created() {
    var self = this;
    env.$on("editUser", id => {
      for (var i in self.res.items) {
        if (self.res.items[i].id == id) {
          self.id = self.res.items[i].id;
          self.name = self.res.items[i].name;
          this.toggle();
        }
      }
    });
  },
  destroyed() {
    env.$off("editUser");
  }
};
</script>
<style>
.submit-div {
  text-align: center;
  width: 100%;
}
</style>
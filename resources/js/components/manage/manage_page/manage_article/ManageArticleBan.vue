<template>
  <div>
    <sui-modal size="tiny" v-model="open" closeIcon>
      <sui-modal-header>驳回文章</sui-modal-header>
      <sui-modal-content>
        <sui-form>
          <sui-form-field>
            <label>理由：</label>
            <input type="text" v-model="reason" />
          </sui-form-field>
        </sui-form>
        <div class="submit-div">
          <sui-button color="blue" @click="submit()">驳回</sui-button>
        </div>
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
    return {
      open: false,
      reason: "",
      id: ""
    };
  },
  methods: {
    toggle() {
      this.open = !this.open;
    },
    submit() {
      var data = {
        id: this.id,
        reason: this.reason
      };
      env.$emit("submitArticleBan", data);
      this.toggle();
    }
  },
  created() {
    var self = this;
    env.$on("banArticle", id => {
      for (var i in self.res.items) {
        if (self.res.items[i].id == id) {
          self.id = self.res.items[i].id;
          self.reason = self.res.items[i].reason;
          this.toggle();
        }
      }
    });
  },
  destroyed() {
    env.$off("delArticle");
  }
};
</script>
<style>
.submit-div {
  text-align: center;
  width: 100%;
}
</style>
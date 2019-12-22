<template>
  <div class="ui container login-form" :style="{marginTop:toTopLen}">
    <sui-grid stackable>
      <sui-grid-column :width="4"></sui-grid-column>
      <sui-grid-column :width="8">
        <sui-segment class="base-div" style="padding: 2rem 3rem;">
          <div class="center-div">
            <img class="login-logo" src="/images/QAQxm.png" />
          </div>
          <form class="ui form">
            <input type="hidden" name="_token" v-model="token" />
            <div class="field">
              <label>电子邮箱：</label>
              <input type="text" name="email" placeholder="电子邮箱" v-model="email" />
              <span class="err-tips">{{emailerr}}</span>
            </div>
            <div class="field">
              <label>密码：</label>
              <input type="password" name="password" placeholder="密码" v-model="password" />
              <span class="err-tips">{{passworderr}}</span>
            </div>
            <div style="clear:both;"></div>
          </form>
          <div class="center-div">
            <button class="ui blue button" v-on:click="login()">登录</button>
          </div>
          <div style="margin-bottom:1rem;">
            <router-link :to="{ name: 'Reset'}" style="float:right;">忘记密码？</router-link>
            <div style="clear:both;"></div>
          </div>
          <router-link :to="{ name: 'Home'}" style="float:left;">返回首页</router-link>
          <router-link :to="{ name: 'Register'}" style="float:right;">没有账号？ 立即注册</router-link>
          <div style="clear:both;"></div>
        </sui-segment>
      </sui-grid-column>
      <sui-grid-column :width="4"></sui-grid-column>
    </sui-grid>
  </div>
</template>

<script>
import env from "../../env";
export default {
  props: {
    user: Array
  },
  data() {
    return {
      email: "",
      password: "",
      emailerr: "",
      passworderr: "",
      token: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content")
    };
  },
  computed: {
    toTopLen: function() {
      return "10rem";
    }
  },
  methods: {
    login: function() {
      var self = this;

      var data = {
        email: this.email,
        password: this.password
      };

      axios({
        method: "post",
        url: "/login",
        data: data
      }).then(function(res) {
        if (res.data == undefined) {
          var errors = res.response.data.errors;
          self.emailerr = "";
          if (errors.email != undefined) {
            self.emailerr = errors.email[0];
          }
          self.passworderr = "";
          if (errors.password != undefined) {
            self.passworderr = errors.password[0];
          }
        } else {
          var data = res.data;

          var user = [];
          for (var i in data.user) {
            user[i] = data.user[i];
          }

          env.$emit("user", user);

          if (data.status == true) {
            self.$router.push({
              name: "Home"
            });
          }
        }
      });
    }
  },
  created() {
    if (this.user !== null) {
      this.$router.push({
        name: "Home"
      });
    }
  },
  watch: {
    user: function() {
      if (this.user !== null) {
        this.$router.push({
          name: "Home"
        });
      }
    }
  }
};
</script>

<style>
.center-div {
  margin-top: 1rem;
  width: 100%;
  text-align: center;
}
.center-div img {
  width: 50%;
}
.err-tips {
  color: red;
  font-size: 1rem;
}
</style>

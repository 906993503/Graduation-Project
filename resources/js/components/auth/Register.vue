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
            <div class="field">
              <label>用户名：</label>
              <input type="text" name="name" placeholder="用户名" v-model="name" />
              <span class="err-tips">{{nameerr}}</span>
            </div>
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
            <div class="field">
              <label>重复密码：</label>
              <input
                type="password"
                name="password_confirmation"
                placeholder="重复密码"
                v-model="password_confirmation"
              />
              <span class="err-tips">{{password_confirmationerr}}</span>
            </div>
          </form>
          <div class="center-div">
            <button class="ui blue button" v-on:click="register()">注册</button>
          </div>
          <router-link :to="{ name: 'Home'}" style="float:left;">返回首页</router-link>
          <router-link :to="{ name: 'Login'}" style="float:right;">已有账号？ 返回登录</router-link>
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
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      nameerr: "",
      emailerr: "",
      passworderr: "",
      password_confirmationerr: ""
    };
  },
  computed: {
    toTopLen: function() {
      return "3rem";
    }
  },
  methods: {
    register() {
      var self = this;

      var data = {
        name: this.name,
        email: this.email,
        password: this.password,
        password_confirmation: this.password_confirmation
      };

      axios({
        method: "post",
        url: "/register",
        data: data
      }).then(function(res) {
        if (res.data == undefined) {
          var errors = res.response.data.errors;
          self.nameerr = "";
          if (errors.name != undefined) {
            self.nameerr = errors.name[0];
          }
          self.emailerr = "";
          if (errors.email != undefined) {
            self.emailerr = errors.email[0];
          }
          self.passworderr = "";
          if (errors.password != undefined) {
            self.passworderr = errors.password[0];
          }
          self.password_confirmationerr = "";
          if (errors.password_confirmation != undefined) {
            self.password_confirmationerr = errors.password_confirmation[0];
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
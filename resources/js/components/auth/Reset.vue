<template>
  <div class="ui container login-form" :style="{marginTop:toTopLen}">
    <sui-grid stackable>
      <sui-grid-column :width="4"></sui-grid-column>
      <sui-grid-column :width="8">
        <sui-segment class="base-div" style="padding: 2rem 3rem;">
          <div class="center-div">
            <img class="login-logo" src="/images/QAQxm.png" />
          </div>
          <form class="ui form" v-if="token == ''">
            <div class="field">
              <label>电子邮箱：</label>
              <input type="text" name="email" placeholder="电子邮箱" v-model="email" />
              <span class="err-tips">{{emailerr}}</span>
            </div>
            <div style="clear:both;"></div>
          </form>
          <div class="center-div" v-if="token == ''">
            <button class="ui blue button" v-on:click="send()">发送重置密码链接</button>
          </div>
          <form class="ui form" v-if="token != ''">
            <div class="field">
              <label>电子邮箱：</label>
              <input type="text" name="email" placeholder="电子邮箱" v-model="email" readonly />
              <span class="err-tips">{{emailerr}}</span>
            </div>
            <div class="field">
              <label>新密码：</label>
              <input type="password" name="password" placeholder="新密码" v-model="password" />
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
            <div style="clear:both;"></div>
          </form>
          <div class="center-div" v-if="token != ''">
            <button class="ui blue button" v-on:click="reset()">重置密码</button>
          </div>
          <router-link :to="{ name: 'Login'}" style="float:left;">返回登录</router-link>
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
      emailerr: "",
      token: "",
      password: "",
      passworderr: "",
      password_confirmation: "",
      password_confirmationerr: ""
    };
  },
  computed: {
    toTopLen: function() {
      return "10rem";
    }
  },
  methods: {
    send: function() {
      var self = this;
      var data = {
        email: this.email
      };

      axios({
        method: "post",
        url: "/password/email",
        data: data
      }).then(function(res) {
        var data = res.data;
        if (data.status) {
          self.email = "";
          var data = [];
          data.push("邮件已发送！请查收");
          env.$emit("msg", data);
          return false;
        } else {
          var errors = data.data;
          self.emailerr = "";
          if (errors.email != undefined) {
            self.emailerr = errors.email[0];
          }
        }
      });
    },
    reset: function() {
      var self = this;
      var data = {
        email: this.email,
        token: this.token,
        password: this.password,
        password_confirmation: this.password_confirmation
      };

      axios({
        method: "post",
        url: "/password/reset",
        data: data
      }).then(function(res) {
        var data = res.data;
        console.log(data);
        if (data.status) {
          var user = [];
          for (var i in data.user) {
            user[i] = data.user[i];
          }
          env.$emit("user", user);
          self.$router.push({
            name: "Home"
          });
        } else {
          var errors = data.data;
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
        }
      });
    },
    getQueryString(name) {
      var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
      var r = window.location.search.substr(1).match(reg);
      if (r != null) return decodeURIComponent(r[2]);
      return "";
    }
  },
  created() {
    if (this.user !== null) {
      this.$router.push({
        name: "Home"
      });
    }

    if (this.getQueryString("email") != "") {
      this.token = this.$route.params.token;
      this.email = this.getQueryString("email");
      this.emailerr = "";
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

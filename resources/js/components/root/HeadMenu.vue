<template>
  <sui-menu pointing secondary>
    <div class="ui container">
      <a class="logo" v-on:click="select(items[0].opt)"></a>
      <a
        is="sui-menu-item"
        v-for="item in items"
        :active="isActive(item.opt)"
        :key="item.opt"
        :content="item.name"
        @click="select(item.opt)"
      />
      <sui-menu-menu position="right" class="compact">
        <sui-menu-item>
          <sui-input
            style="background-color:white;"
            transparent
            placeholder="Search..."
            v-model="search"
          />
          <sui-icon name="search" @click="toSearch()" />
        </sui-menu-item>
        <div class="avatar-box item" style="padding: 0;" v-if="user !== null">
          <img :src="useravatar" style="width: 100%;height: 100%;" />
        </div>
        <sui-dropdown class="item" :text="username" style="width:11rem;" v-if="user !== null">
          <sui-dropdown-menu>
            <sui-dropdown-item @click.native="myHome()">
              <sui-icon name="user" />我的主页
            </sui-dropdown-item>
            <sui-dropdown-item @click.native="myArticle()">
              <sui-icon name="file" />我的文章
            </sui-dropdown-item>
            <sui-dropdown-item @click.native="myMessage()">
              <sui-icon name="envelope" />我的消息
            </sui-dropdown-item>
            <sui-dropdown-divider />
            <sui-dropdown-item v-if="user.is_admin == 1" @click.native="manage()">
              <sui-icon name="cogs" />管理后台
            </sui-dropdown-item>
            <sui-dropdown-item @click.native="logout()">
              <sui-icon name="sign-out" />退出登录
            </sui-dropdown-item>
          </sui-dropdown-menu>
        </sui-dropdown>
        <sui-menu-item v-if="user === null" style="margin-right:0;padding-right:0;">
          <sui-button basic @click.native="login()">登录</sui-button>
        </sui-menu-item>
        <sui-menu-item v-if="user === null">
          <sui-button color="red" @click.native="register()">注册</sui-button>
        </sui-menu-item>
      </sui-menu-menu>
    </div>
  </sui-menu>
</template>

<script>
import Global from "../../Global";
import env from "../../env";
export default {
  props: {
    user: Array
  },
  data() {
    return {
      items: Global.HeadMenu,
      active: this.$router.history.current.name,
      search: ""
    };
  },
  computed: {
    username() {
      return this.user.name;
    },
    useravatar() {
      return this.user.avatar;
    }
  },
  methods: {
    toSearch() {
      this.$router.push({
        name: "Search",
        params: {
          text: this.search
        }
      });
    },
    isActive(name) {
      var fdStart = this.active.indexOf(name);
      if (fdStart == 0) {
        return true;
      } else {
        return false;
      }
    },
    select(name) {
      var fdStart = this.active.indexOf(name);
      if (fdStart !== 0) {
        this.$router.push({
          name: name
        });
      }
      this.active = name;
    },
    login() {
      if (this.$router.history.current.name !== "Login") {
        this.$router.push({
          name: "Login"
        });
      }
    },
    register() {
      if (this.$router.history.current.name !== "Register") {
        this.$router.push({
          name: "Register"
        });
      }
    },
    manage() {
      window.location.href = "/manage/#/manage";
    },
    logout() {
      var self = this;
      axios({
        method: "post",
        url: "/logout"
      }).then(function(res) {
        env.$emit("user", null);
        self.$router.push({
          name: "Home"
        });
      });
    },
    myHome() {
      if (this.$router.history.current.name !== "MyHome") {
        this.$router.push({
          name: "MyHome"
        });
      }
    },
    myArticle() {
      if (this.$router.history.current.name !== "MyArticle") {
        this.$router.push({
          name: "MyArticle"
        });
      }
    },
    myMessage() {
      if (this.$router.history.current.name !== "MyMessage") {
        this.$router.push({
          name: "MyMessage"
        });
      }
    }
  },
  watch: {
    $route(to, from) {
      this.active = to.name;
    }
  }
};
</script>
<style>
.logo {
  float: left;
  display: block;
  width: 8.4rem;
  background: url(/images/QAQxm.png) no-repeat left center;
  -moz-background-size: 100%;
  -o-background-size: 100%;
  -webkit-background-size: 100%;
  background-size: 100%;
  text-indent: 110%;
  white-space: nowrap;
  overflow: hidden;
  text-transform: capitalize;
  cursor: pointer;
  margin-right: 1rem;
}
.avatar-box {
  width: 4rem;
  height: 4rem;
}
.avatar-box img {
  border-radius: 1rem;
}
.dropdown .text {
  font-size: 1.42rem;
}
.search.icon {
  cursor: pointer;
}
</style>
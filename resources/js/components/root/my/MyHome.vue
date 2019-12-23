<template>
  <div class="user-body">
    <sui-grid>
      <sui-grid-row>
        <sui-grid-column :width="4">
          <my-avatar :user="user"></my-avatar>
        </sui-grid-column>

        <sui-grid-column :width="12">
          <sui-table basic="very" style="margin-top: 1rem;">
            <sui-table-body>
              <sui-table-row>
                <sui-table-cell :width="2">昵称:</sui-table-cell>
                <sui-table-cell v-if="editName == false">{{user.name}}</sui-table-cell>
                <sui-table-cell v-if="editName == true">
                  <sui-form>
                    <sui-form-field>
                      <label>新昵称：</label>
                      <input v-model="name" />
                    </sui-form-field>
                  </sui-form>
                </sui-table-cell>
                <sui-table-cell :width="2">
                  <a class="user-edit" v-if="editName == false" @click="editNameAction()">编辑</a>
                  <a class="user-edit" v-if="editName == true" @click="saveNameAction()">保存</a>
                </sui-table-cell>
              </sui-table-row>
              <sui-table-row>
                <sui-table-cell :width="2">密码:</sui-table-cell>
                <sui-table-cell v-if="editPW == false">********</sui-table-cell>
                <sui-table-cell v-if="editPW == true">
                  <sui-form>
                    <sui-form-field>
                      <label>旧密码：</label>
                      <input type="password" v-model="oldPW" />
                    </sui-form-field>
                    <sui-form-field>
                      <label>新密码：</label>
                      <input type="password" v-model="newPW" />
                    </sui-form-field>
                    <sui-form-field>
                      <label>重复新密码：</label>
                      <input type="password" v-model="reNewPW" />
                    </sui-form-field>
                  </sui-form>
                </sui-table-cell>
                <sui-table-cell :width="2">
                  <a class="user-edit" @click="reset()" v-if="editPW == false">重置密码</a>
                  <a class="user-edit" @click="resetSave()" v-if="editPW == true">保存</a>
                </sui-table-cell>
              </sui-table-row>
              <sui-table-row>
                <sui-table-cell :width="2">电子邮箱:</sui-table-cell>
                <sui-table-cell>{{user.email}}</sui-table-cell>
                <sui-table-cell :width="2"></sui-table-cell>
              </sui-table-row>
            </sui-table-body>
          </sui-table>
        </sui-grid-column>
      </sui-grid-row>
    </sui-grid>
    <sui-menu tabular style="margin-top: 2rem;">
      <sui-menu-item :active="true">我的收藏夹</sui-menu-item>
    </sui-menu>
    <article-item :items="items" style="min-height: 39rem;"></article-item>
    <sui-button v-if="moreItems>0" fluid content="加载更多" @click="getArticleItemList()" />
  </div>
</template>

<script>
import MyAvatar from "./my_home/MyAvatar";
import ArticleItem from "../ArticleItem";
import Global from "../../../Global";
import env from "../../../env";
export default {
  props: {
    user: Array
  },
  data() {
    return {
      editName: false,
      editPW: false,
      name: this.user.name,
      items: [],
      moreItems: 0,
      page: 1,
      limit: 8,
      oldPW: "",
      newPW: "",
      reNewPW: ""
    };
  },
  methods: {
    reset() {
      this.editPW = true;
    },
    resetSave() {
      var self = this;
      axios({
        method: "post",
        url: "/home/resetPw",
        data: {
          oldPW: self.oldPW,
          newPW: self.newPW,
          reNewPW: self.reNewPW
        }
      }).then(function(res) {
        self.oldPW = "";
        self.newPW = "";
        self.reNewPW = "";
        var data = [];
        data.push(res.data.msg);
        env.$emit("msg", data);
        self.editPW = false;
      });
    },
    editNameAction() {
      this.editName = true;
    },
    saveNameAction() {
      var self = this;
      axios({
        method: "post",
        url: "/home/saveUserName",
        data: {
          name: self.name
        }
      }).then(function(res) {
        var u = self.user;
        u.name = self.name;
        env.$emit("user", u);
        self.editName = false;
      });
    },
    getArticleItemList() {
      var self = this;
      axios({
        method: "post",
        url: "/home/getCollectArticleItemList",
        data: {
          page: self.page,
          limit: self.limit
        }
      }).then(function(res) {
        var data = res.data;
        self.items = data.list;
        self.moreItems = data.moreItems;
        self.page++;
      });
    }
  },
  components: {
    "my-avatar": MyAvatar,
    "article-item": ArticleItem
  },
  created() {
    this.getArticleItemList();
  }
};
</script>

<style>
.user-body {
  width: 100%;
}
.user-edit {
  float: right;
  cursor: pointer;
}
</style>
<template>
  <div v-if="user != null">
    <h1>文章编辑</h1>
    <div class="ban-reason" v-if="active == 0">
      文章被驳回！请修改后重新发布
      <br />
      原因：{{reason}}
    </div>
    <sui-grid>
      <sui-grid-row>
        <sui-grid-column :width="12">
          <sui-form style="position: absolute;bottom: 0;">
            <sui-form-fields inline>
              <sui-form-field>
                <label>标题：</label>
                <input placeholder="Title" v-model="title" />
              </sui-form-field>
              <sui-form-field>
                <label>所属分类：</label>
                <select v-model="type_id">
                  <option value>尚未选择</option>
                  <option v-for="item in type" :key="item.id" :value="item.id">{{item.name}}</option>
                </select>
              </sui-form-field>
              <sui-button basic @click="openUpload()">上传封面</sui-button>
            </sui-form-fields>
          </sui-form>
        </sui-grid-column>
        <sui-grid-column :width="4">
          <sui-form style="position: absolute;bottom: 0;">
            <sui-form-fields inline>
              <sui-form-field>
                <label>文章封面：</label>
              </sui-form-field>
            </sui-form-fields>
          </sui-form>
          <div class="face-div" :style="facediv">
            <img :src="faceimg" :style="haveImg" />
            <input
              style="display:none;"
              ref="fileInput"
              accept="image/*"
              type="file"
              @change="tirggerFile($event)"
            />
          </div>
        </sui-grid-column>
      </sui-grid-row>
    </sui-grid>

    <quill-editor v-model="content" ref="myQuillEditor" :options="editorOption"></quill-editor>
    <div class="bottom-div">
      <div class="label-div">
        <label-item :label="label"></label-item>
      </div>
      <div style="clear:both;"></div>
    </div>

    <sui-button basic floated="right" @click.native="goBack()">返回</sui-button>
    <sui-button color="red" floated="right" @click.native="saveArticle()">发布</sui-button>
    <div style="clear:both;"></div>
  </div>
</template>
<script>
import { quillEditor } from "vue-quill-editor";
import { quillRedefine } from "vue-quill-editor-upload";
import env from "../../../env";
import LabelItem from "../../LabelItem";
export default {
  props: {
    user: Array,
    type: Array
  },
  data() {
    return {
      content: "<h2>I am Example</h2>",
      editorOption: {},
      type_id: "",
      title: "",
      faceimg: "",
      haveImg: { display: "none" },
      facediv: {},
      id: this.$route.params.id,
      label: [],
      reason: "",
      active: 1
    };
  },
  components: {
    quillEditor,
    quillRedefine,
    "label-item": LabelItem
  },
  methods: {
    goBack() {
      this.$router.go(-1);
    },
    openUpload() {
      this.$refs.fileInput.click();
    },
    tirggerFile() {
      var file = this.$refs.fileInput.files[0];
      var self = this;

      let formData = new FormData();
      formData.append("faceimg", file);
      axios
        .post("/home/uploadFace", formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(function(res) {
          self.faceimg = res.data.url;
        });
    },
    saveArticle() {
      //var file = this.faceimg;
      var self = this;
      var data = {
        id: this.id,
        title: this.title,
        type_id: this.type_id,
        content: this.content,
        face_img: this.faceimg,
        label: this.label
      };

      if (data.title == "") {
        var data = [];
        data.push("标题不能为空");
        env.$emit("msg", data);
        return false;
      }

      if (data.type_id == "") {
        var data = [];
        data.push("分类尚未选择");
        env.$emit("msg", data);
        return false;
      }

      axios({
        url: "/home/saveArticle",
        method: "post",
        data: data
      }).then(function(res) {
        var data = res.data;
        self.$router.push({
          name: "MyArticle"
        });
      });
    }
  },
  computed: {
    editor() {
      return this.$refs.myQuillEditor.quill;
    }
  },
  watch: {
    faceimg: function() {
      let img = new Image();
      img.src = this.faceimg;
      const self = this;
      img.onload = function() {
        if (img.width >= img.height) {
          self.haveImg = {
            width: "100%",
            display: "inline-block",
            "vertical-align": "middle"
          };
        } else {
          self.haveImg = {
            height: "100%",
            display: "inline-block",
            "vertical-align": "middle"
          };
        }
      };
    }
  },
  created() {
    this.editorOption = quillRedefine({
      // 图片上传的设置
      uploadConfig: {
        action: "/home/upload", // 必填参数 图片上传地址
        // 必选参数  res是一个函数，函数接收的response为上传成功时服务器返回的数据
        // 你必须把返回的数据中所包含的图片地址 return 回去
        res: respnse => {
          return respnse.url;
        },
        name: "img" // 图片上传参数名
      }
    });
    var self = this;
    env.$on("label", label => {
      self.label = label;
    });
    if (self.id != 0) {
      axios({
        url: "/home/editArticle",
        method: "post",
        data: {
          id: self.id
        }
      }).then(function(res) {
        var data = res.data;
        if (data.status) {
          self.content = data.article.content;
          self.type_id = data.article.type_id;
          self.title = data.article.title;
          self.faceimg = data.article.face_img;
          self.label = data.article.label;
          self.reason = data.article.reason;
          self.active = data.article.active;
        } else {
          self.$router.push({
            name: "Home"
          });
        }
      });
    }
    if (this.user.email_verified_at == null) {
      this.$router.go(-1);
      var data = [];
      data.push("请先验证邮箱");
      env.$emit("msg", data);
    }
  },
  mounted() {},
  destroyed() {
    env.$off("label");
  }
};
</script>
<style>
.ql-container.ql-snow {
  min-height: 28rem;
}
.ql-editor {
  min-height: 28rem;
}
.bottom-div {
  margin: 0.5rem;
}
.face-div {
  width: 12rem;
  height: 12rem;
  float: right;
  margin-top: -4rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  margin-bottom: 0.5rem;
  overflow: hidden;
  text-align: center;
  line-height: 12rem;
}
.label-div {
  width: 100%;
}
.ban-reason {
  margin-bottom: -4rem;
  color: red;
  font-weight: bold;
  font-size: 1.5rem;
  line-height: 1.5rem;
}
</style>
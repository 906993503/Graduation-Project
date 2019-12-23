<template>
  <div>
    <div class="profile__heading--avatar-warp" @mouseenter="showUp()" @mouseleave="hideUp()">
      <a>
        <img class="profile__heading--avatar avatar-160" :src="imgSrc" />
      </a>
      <div class="profile__avatar-uploader" :style="isShowUp" @click="showCutter=true">
        <span>上传头像</span>
      </div>
    </div>
    <avatar-cutter
      v-if="showCutter"
      @cancel="showCutter = false"
      return-type="file"
      @enter="uploadAvatar"
    ></avatar-cutter>
  </div>
</template>
<script>
import AvatarCutter from "../../avatar-cutter/avatar-cutter";
import env from "../../../../env";

export default {
  props: {
    user: Array
  },
  components: {
    AvatarCutter
  },
  data() {
    return {
      isShowUp: {
        display: "none"
      },
      imgSrc: this.user.avatar,
      showCutter: false, // 是否显示头像裁剪组件
      userInfo: {}
    };
  },

  methods: {
    // 上传裁剪好的头像
    uploadAvatar(file) {
      var self = this;

      let formData = new FormData();
      formData.append("avatar", file);
      axios
        .post("/home/uploadAvatar", formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(function(res) {
          var u = self.user;

          self.imgSrc = res.data.url;
          u.avatar = res.data.url;
          env.$emit("user", u);

          self.showCutter = false;
        });
    },
    showUp() {
      this.isShowUp.display = "block";
    },
    hideUp() {
      this.isShowUp.display = "none";
    }
  }
};
</script>

<style>
.profile__heading--avatar-warp {
  position: relative;
  border-radius: 1rem;
  overflow: hidden;
  width: 11rem;
  height: 11rem;
}
.profile__heading--avatar {
  background: #ffffff;
  width: 100%;
  height: 100%;
  vertical-align: middle;
  border: 0;
}
.profile__avatar-uploader {
  display: none;
  cursor: pointer;
  position: absolute;
  bottom: 0;
  width: 100%;
  text-align: center;
  background: rgba(0, 0, 0, 0.75);
  height: 40px;
  color: #fff;
  line-height: 40px;
}
</style>
require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';
import SuiVue from 'semantic-ui-vue';
import Tips from './components/Tips.vue';
import env from "./env";

axios.interceptors.request.use(function (config) {
    if (config.url == "/open/getUserMsgRows") {
        return config;
    }
    env.$emit("load", 1);
    return config;
}, function (error) {
    if (error.request.url == "/open/getUserMsgRows") {
        return false;
    }
    env.$emit("load", -1);
    var data = [];
    data.push(error.message);
    env.$emit("msg", data);
    return false;
});
axios.interceptors.response.use(function (response) {
    if (response.config.url == "/open/getUserMsgRows") {
        return response;
    }
    env.$emit("load", -1);
    return response;
}, function (error) {
    console.log(error.response);
    if (error.response.config.url == "/open/getUserMsgRows") {
        return error;
    }
    env.$emit("load", -1);
    if (error.response.config.url == "/login" || error.response.config.url == "/register") {
        return error;
    }
    var data = [];
    data.push(error.message);
    env.$emit("msg", data);
    return false;
});
Vue.use(VueRouter);
Vue.use(SuiVue);

import {
    home
} from './route';

const router = new VueRouter({
    routes: home
})

const app = new Vue({
    el: '#app',
    data: {
        user: null,
        type: null,
        load_flag: 0,
        load_style: {
            'margin-top': 0
        },
        timer: null,
        msgRows: null
    },
    router: router,
    components: {
        'tips': Tips
    },
    created() {
        var self = this;
        env.$on("user", user => {
            if (user == null) {
                self.user = null;
            } else {
                self.user = [];
                for (var i in user) {
                    self.$set(self.user, i, user[i]);
                }
            }
        });
        if (self.user === null) {
            axios({
                    method: "post",
                    url: "/open/getUser"
                })
                .then(function (res) {
                    var data = res.data;
                    if (data.status) {
                        self.user = [];
                        for (var i in data.user) {
                            self.$set(self.user, i, data.user[i]);
                        }
                    }
                });
        }
        env.$on("type", type => {
            self.type = type;
        });
        if (self.type === null) {
            axios({
                method: "post",
                url: "/open/getType"
            }).then(function (res) {
                var data = res.data;
                self.type = data.type;
            });
        }
        env.$on("load", n => {
            self.load_flag += n;
        });

        this.getUserMsgRows();
        env.$on("msgRows", n => {
            if (self.msgRows - n <= 0) {
                self.msgRows = null;
            } else {
                self.msgRows -= n;
            }
        });
    },
    mounted() {
        window.addEventListener('scroll', this.handleScroll, true);
        this.timer = setInterval(this.getUserMsgRows, 30000);
    },
    methods: {
        handleScroll(e) {
            var scrollTop = e.target.documentElement.scrollTop || e.target.body.scrollTop;
            this.load_style = {
                'margin-top': scrollTop + "px"
            };
        },
        getUserMsgRows() {
            var self = this;
            axios({
                method: "post",
                url: "/open/getUserMsgRows"
            }).then(function (res) {
                var data = res.data;
                if (data.rows != 0) {
                    self.msgRows = data.rows;
                } else {
                    self.msgRows = null;
                }
            });
        }
    },
    computed: {
        isLoad() {
            if (this.load_flag == 0) {
                return false;
            } else {
                return true;
            }
        }
    },
    beforeDestroy() {
        clearInterval(this.timer);
    },
    destroyed() {
        env.$off("user");
        env.$off("type");
        env.$off("load");
        env.$off("msgRows");
    },
});

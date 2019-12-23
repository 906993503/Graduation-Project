require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';
import SuiVue from 'semantic-ui-vue';
import Global from './Global.vue';
import '../css/Mycss.css';
import 'semantic-ui-css/semantic.min.css';
import Tips from './components/Tips.vue';
import env from "./env";

axios.interceptors.request.use(function (config) {
    env.$emit("load", 1);
    return config;
}, function (error) {
    env.$emit("load", -1);
    var data = [];
    data.push(error.message);
    env.$emit("msg", data);
    return false;
});
axios.interceptors.response.use(function (response) {
    env.$emit("load", -1);
    return response;
}, function (error) {
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
    manage
} from './route';

const router = new VueRouter({
    routes: manage
})

const app = new Vue({
    el: '#app',
    data: {
        user: null,
        type: null,
        load_flag: 0,
        load_style: {
            'margin-top': 0
        }
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
    },
    mounted() {
        window.addEventListener('scroll', this.handleScroll, true);
    },
    methods: {
        handleScroll(e) {
            var scrollTop = e.target.documentElement.scrollTop || e.target.body.scrollTop;
            this.load_style = {
                'margin-top': scrollTop + "px"
            };
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
    destroyed() {
        env.$off("user");
        env.$off("type");
        env.$off("load");
    },
});

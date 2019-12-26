import Root from './components/root/Root.vue';
import Login from './components/auth/Login.vue';
import Register from './components/auth/Register.vue';
import Home from './components/root/home/Home.vue';
import HomeContent from './components/root/home/HomeContent.vue';
import Content from './components/root/article/Content.vue';
import My from './components/root/my/My.vue';
import MyHome from './components/root/my/MyHome.vue';
import MyArticle from './components/root/my/MyArticle.vue';
import MyMessage from './components/root/my/MyMessage.vue';
import Editor from './components/root/editor/Editor.vue';
import Reset from './components/auth/Reset.vue';
import Search from './components/root/search/Search.vue';



var home = [{
    path: '/',
    name: "root",
    component: Root,
    redirect: {
        name: 'Home'
    },
    children: [{
        path: '/home',
        name: "Home",
        component: Home,
        redirect: {
            name: 'HomeContent',
            params: {
                type: 0
            }
        },
        children: [{
            path: '/home/content/:type',
            name: "HomeContent",
            component: HomeContent
        }]
    }, {
        path: '/content/:id/:cid?',
        name: "Content",
        component: Content
    }, {
        path: '/my',
        name: "My",
        component: My,
        redirect: {
            name: 'MyHome'
        },
        children: [{
            path: '/my/home',
            name: "MyHome",
            component: MyHome
        }, {
            path: '/my/article',
            name: "MyArticle",
            component: MyArticle
        }, {
            path: '/my/message',
            name: "MyMessage",
            component: MyMessage
        }]
    }, {
        path: '/editor/:id',
        name: "Editor",
        component: Editor
    }, {
        path: '/search/:text',
        name: "Search",
        component: Search
    }]
}, {
    path: '/login',
    name: "Login",
    component: Login
}, {
    path: '/register',
    name: "Register",
    component: Register
}, {
    path: '/password/reset/:token?',
    name: "Reset",
    component: Reset
}];
export {
    home
}

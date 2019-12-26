import Manage from './components/manage/Manage.vue';
import ManageArticle from './components/manage/manage_page/manage_article/ManageArticle.vue';
import ManageComment from './components/manage/manage_page/manage_comment/ManageComment.vue';
import ManageType from './components/manage/manage_page/manage_type/ManageType.vue';
import ManageUser from './components/manage/manage_page/manage_user/ManageUser.vue';


var manage = [{
    path: '/manage',
    name: "Manage",
    component: Manage,
    children: [{
        path: '/manage/article',
        name: "ManageArticle",
        component: ManageArticle
    }, {
        path: '/manage/type',
        name: "ManageType",
        component: ManageType
    }, {
        path: '/manage/comment',
        name: "ManageComment",
        component: ManageComment
    }, {
        path: '/manage/user',
        name: "ManageUser",
        component: ManageUser
    }]
}];
export {
    manage
}

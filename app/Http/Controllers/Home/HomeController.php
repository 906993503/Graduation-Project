<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Article;
use App\Collect;
use App\Comment;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web,admin');
    }

    /**
     * 文章图片上传
     */
    public function upload(Request $request)
    {
        try {
            $path                           = $request->file('img')->store('public/img');
            $url                            = Storage::url($path);
            return response()->json(['url' => $url]);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * 文章封面图片上传
     */
    public function uploadFace(Request $request)
    {
        try {
            $path                           = $request->file('faceimg')->store('public/faceimg');
            $url                            = Storage::url($path);
            return response()->json(['url' => $url]);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
    /**
     * 用户头像上传
     */
    public function uploadAvatar(Request $request)
    {
        try {
            $path                           = $request->file('avatar')->store('public/avatar');
            $url                            = Storage::url($path);
            $user                           = Auth::user() ?: Auth::guard('admin')->user();
            $u                              = User::find($user->id);
            $u->avatar                      = $url;
            $u->save();
            return response()->json(['url' => $url]);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
    /**
     *文章保存
     */
    public function saveArticle(Request $request)
    {

        try {
            $user                           = Auth::user() ?: Auth::guard('admin')->user();

            $id                             = $request->input('id');
            $param = [
                'user_id'                   => $user['id'],
                'content'                   => $request->input('content'),
                'title'                     => $request->input('title'),
                'face_img'                  => $request->input('face_img'),
                'type_id'                   => $request->input('type_id'),
                'label'                     => $request->input('label'),
                'active'                    => 1
            ];

            $param['show_text']             = strip_tags($param['content']);

            $param['show_text']             = "&nbsp;&nbsp;&nbsp;&nbsp;" . mb_substr($param['show_text'], 0, 196, "utf-8") . "...";

            $article                        = new Article();
            $res                            = $article->saveArticle($id, $param);
            return response()->json($res);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
    /**
     * 文章编辑获取信息
     * 
     */
    public function editArticle(Request $request)
    {
        try {
            $id                             = $request->input('id');
            $article                        = Article::find($id);
            $user                           = Auth::user() ?: Auth::guard('admin')->user();
            if ($article === null || $user->id !== $article->user_id) {
                return response()->json(['status' => false, 'article' => null]);
            } else {
                //$article->label           = json_decode($article->label, true);
                return response()->json(['status' => true, 'article' => $article]);
            }
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
    /**
     * 获取收藏文章缩略列表
     * 
     */
    public function getCollectArticleItemList(Request $request)
    {
        try {
            $page                           = $request->input('page');
            $limit                          = $request->input('limit');
            $user                           = Auth::user() ?: Auth::guard('admin')->user();
            $collect                        = new Collect();

            $res                            = $collect->getCollectArticleItemList($user, $page, $limit);
            return response()->json($res);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
    /**
     * 获取我的文章缩略列表
     */
    public function getMyArticleItemList(Request $request)
    {
        try {
            $page                           = $request->input('page');
            $limit                          = $request->input('limit');

            $user                           = Auth::user() ?: Auth::guard('admin')->user();
            $param                          = [
                'user_id'                   => $user->id
            ];

            $article                        = new Article();
            $res                            = $article->getArticleItemList($param, $page, $limit);

            return response()->json($res);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * 收藏/取消收藏
     * 
     */
    public function collectThis(Request $request)
    {
        try {
            $article_id                     = $request->input('id');
            $user                           = Auth::user() ?: Auth::guard('admin')->user();
            $user_id                        = $user->id;

            $collect                        = new Collect();
            $res                            = $collect->collectThis($article_id, $user_id);

            return response()->json($res);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
    /**
     * 新增评论
     */
    public function addComment(Request $request)
    {
        try {
            $user                           = Auth::user() ?: Auth::guard('admin')->user();

            $param                          = [
                'article_id'                => $request->input('id'),
                'user_id'                   => $user->id,
                'be_user_id'                => $request->input('be_user_id'),
                'content'                   => $request->input('content')
            ];
            $param['content']               = str_replace("_blank", "_self", $param['content']);
            $comment                        = new Comment();
            $res                            = $comment->addComment($param);

            return response()->json($res);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * 更新用户昵称
     */
    public function saveUserName(Request $request)
    {
        try {
            $name                           = $request->input('name');
            $user                           = Auth::user() ?: Auth::guard('admin')->user();

            $u                              = User::find($user->id);

            $u->name                        = $name;
            $res                            = $u->save();

            return response()->json($res);
        } catch (Exception $e) {
            report($e);
        }
    }
    /**
     * 重置密码
     */
    public function resetPw(Request $request)
    {
        try {
            $user                           = Auth::user() ?: Auth::guard('admin')->user();
            $id                             = $user->id;
            $oldPW                          = $request->input('oldPW');
            $newPW                          = $request->input('newPW');
            $reNewPW                        = $request->input('reNewPW');

            $res = DB::table('users')->where('id', $id)->select('password')->first();
            if (!Hash::check($oldPW, $res->password)) {
                return response()->json(['status' => false, 'msg' => "旧密码输入错误"]);
            }
            if ($newPW !== $reNewPW) {
                return response()->json(['status' => false, 'msg' => "两次新密码输入不一致"]);
            }
            $update = array(
                'password'  => bcrypt($newPW),
            );
            $result = DB::table('users')->where('id', $id)->update($update);
            if ($request !== false) {
                return response()->json(['status' => true, 'msg' => "密码修改成功"]);
            } else {
                return response()->json(['status' => false, 'msg' => "密码修改失败"]);
            }
        } catch (Exception $e) {
            report($e);
        }
    }
}

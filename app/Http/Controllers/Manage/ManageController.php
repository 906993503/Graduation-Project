<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Types;
use App\Article;
use App\Comment;
use App\Message;

class ManageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * 封禁用户
     * @return json
     */
    public function banUser(Request $request)
    {
        try {
            $id                             = $request->input('id');
            $user                           = new User();
            $res                            = $user->banUser($id);
            return response()->json(['status' => $res]);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
    /**
     * 获取用户列表
     */
    public function getUserList(Request $request)
    {
        try {
            $input                          = $request->all();
            $user                           = new User();
            $res                            = $user->getUserList($input);
            return response()->json($res);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
    /**
     * 更新用户信息
     */
    public function saveUser(Request $request)
    {
        try {
            $id                             = $request->input('id');
            $param                          = [
                'name'                      => $request->input('name')
            ];
            $user                           = new User();
            $res                            = $user->saveUser($id, $param);
            return response()->json($res);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * 获取分类列表
     */
    public function getTypeList(Request $request)
    {
        try {
            $input                          = $request->all();
            $type                           = new Types();
            $res                            = $type->getTypeList($input);
            return response()->json($res);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
    /**
     * 更新分类信息
     */
    public function saveType(Request $request)
    {
        try {
            $id                             = $request->input('id');
            $param                          = [
                'name'                      => $request->input('name'),
                'sort_by'                   => $request->input('sort_by')
            ];
            $type                           = new Types();
            $res                            = $type->saveType($id, $param);
            return response()->json($res);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
    /**
     * 删除分类信息
     */
    public function delType(Request $request)
    {
        try {
            $id                             = $request->input('id');

            $type                           = new Types();
            $res                            = $type->delType($id);
            return response()->json($res);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * 查询文章列表
     */
    public function getArticleList(Request $request)
    {
        try {
            $input                          = $request->all();
            $article                        = new Article();
            $res                            = $article->getArticleList($input);
            return response()->json($res);
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
            $id                             = $request->input('id');
            $param = [
                'title'                     => $request->input('title'),
                'type_id'                   => $request->input('type_id'),
                'label'                     => $request->input('label'),
            ];

            $article                        = new Article();
            $res                            = $article->saveArticle($id, $param);
            return response()->json($res);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
    /**
     * 驳回文章
     */
    public function banArticle(Request $request)
    {

        try {
            $id                             = $request->input('id');
            $param = [
                'reason'                    => $request->input('reason'),
                'active'                    => 0
            ];

            $article                        = new Article();
            $res                            = $article->saveArticle($id, $param);
            return response()->json($res);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
    /**
     * 删除文章
     */
    public function delArticle(Request $request)
    {
        try {
            DB::beginTransaction();
            $id                             = $request->input('id');

            $article                        = new Article();
            $res                            = $article->delArticle($id);
            DB::commit();
            return response()->json($res);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return false;
        }
    }
    /**
     * 获取评论列表
     */
    public function getCommentList(Request $request)
    {
        try {
            $input                          = $request->all();
            $comment                        = new Comment();
            $res                            = $comment->getCommentList($input);
            return response()->json($res);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * 删除评论
     */
    public function delComment(Request $request)
    {
        try {
            $id                             = $request->input('id');

            $comment                        = new Comment();
            $res                            = $comment->delComment($id);

            return response()->json($res);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
}

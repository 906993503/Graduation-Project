<?php

namespace App\Http\Controllers\Open;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Types;
use App\Article;
use App\User;
use App\Message;
use Illuminate\Support\Facades\DB;


class OpenController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * 获取当前登陆用户
     *
     * 
     */
    public function getUser()
    {
        try {
            if (Auth::check() || Auth::guard('admin')->check()) {
                $user = Auth::user() ?: Auth::guard('admin')->user();
                return response()->json(['status' => true, 'user' => $user]);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
    /**
     * 获取分类目录
     * 
     */
    public function getType()
    {
        try {
            $types = Types::orderBy('sort_by', 'asc')->all();
            return response()->json(['type' => $types]);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * 获取文章缩略列表
     */
    public function getArticleItemList(Request $request)
    {
        try {
            $page                           = $request->input('page');
            $limit                          = $request->input('limit');

            $param                          = [
                'type_id'                   => $request->input('type_id'),
                'active'                    => 1
            ];
            if ($param['type_id'] == null) {
                unset($param['type_id']);
            }

            $article                        = new Article();
            $res                            = $article->getArticleItemList($param, $page, $limit);

            return response()->json($res);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
    /**
     * 获取文章详情
     */
    public function getArticle(Request $request)
    {
        try {
            $id                             = $request->input('id');

            $article                        = new Article();
            $res                            = $article->getArticle($id);

            return response()->json($res);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
    /**
     * 获取搜索文章列表
     */
    public function toSearch(Request $request)
    {
        try {
            $page                           = $request->input('page');
            $limit                          = $request->input('limit');

            $search                         = $request->input('search');

            $where                          = [];
            $where[]                        = ['name', 'like', "%$search%"];
            $types                          = Types::where($where)->get();
            $type_id                        = [];
            foreach ($types as $k => $v) {
                $type_id[]                  = $v->id;
            }

            $users                          = User::where($where)->get();
            $user_id                        = [];
            foreach ($users as $k => $v) {
                $user_id[]                  = $v->id;
            }

            $list                           = Article::whereIn('user_id', $user_id)
                ->orWhereIn('type_id', $type_id)
                ->orWhere('title', 'like', "%$search%")
                ->orWhere('label', 'like', "%$search%")
                ->select('id', 'user_id', 'show_text', 'title', 'face_img', 'type_id', 'label', 'show_num', 'updated_at', 'active')
                ->get();
            $rows                       = Article::whereIn('user_id', $user_id)
                ->orWhereIn('type_id', $type_id)
                ->orWhere('title', 'like', "%$search%")
                ->orWhere('label', 'like', "%$search%")
                ->count();

            foreach ($list as $k => $v) {
                $list[$k]->collect_num  = $v->collects->count();
                $list[$k]->user         = $v->user;
            }
            $list                       = $list->sortByDesc('collect_num');

            return [
                'moreItems'             => $rows - $page * $limit,
                'list'                  => $list->take($page * $limit)
            ];
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
    /**
     * 获取消息数量
     */
    public function getUserMsgRows(Request $request)
    {
        try {
            if (Auth::check() || Auth::guard('admin')->check()) {
                $user                           = Auth::user() ?: Auth::guard('admin')->user();

                $message                        = new Message();
                $res                            = $message->getUserMsgRows($user);

                return response()->json($res);
            } else {
                return response()->json([
                    'rows'                      => 0
                ]);
            }
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
}

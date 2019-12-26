<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    use SoftDeletes;

    protected $table = 'articles';
    protected $attributes = [
        'active' => true,
        'show_num' => 0
    ];
    protected $dateFormat = 'U';
    protected $casts = [
        'label' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function comments()
    {
        return $this->hasMany('App\Comment', 'article_id');
    }
    public function collects()
    {
        return $this->hasMany('App\Collect', 'article_id');
    }

    public function saveArticle($id, $param)
    {
        try {
            DB::beginTransaction();
            if ($id == 0) {
                $article                        = new Article();
            } else {
                $article                        = $this->find($id);
                if ($article == null || (isset($param['user_id']) && $article->user_id != $param['user_id'])) {
                    return ['status' => false];
                }
            }

            foreach ($param as $k => $v) {
                $article->$k                    = $v;
            }
            $article->save();
            if (!$article->active) {
                Message::newBanArticleMsg($article->id);
            }
            DB::commit();
            return ['status' => true];
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return false;
        }
    }

    public function getArticleList($param)
    {
        $where                      = [];
        if (isset($param['search'])) {

            $title[]                = ['title', 'LIKE', '%' . $param['search'] . '%'];
            $label[]                = ['label', 'LIKE', '%' . $param['search'] . '%'];
            $username[]             = ['name', 'LIKE', '%' . $param['search'] . '%'];
            $list                   = $this->where($title)->orWhere($label)->orWhereHas('user', function ($query) use ($username) {
                $query->where($username);
            });
        }

        if (isset($param['active'])) {
            $where[]                = ['active', '=', $param['active']];
        }

        if (isset($param['type_id'])) {
            $where[]                = ['type_id', '=', $param['type_id']];
        }

        if (isset($param['begin_date']) && isset($param['end_date'])) {
            $where[]                = ['created_at', '>', min($param['begin_date'], $param['end_date'])];
            $where[]                = ['created_at', '<', max($param['begin_date'], $param['end_date'])];
        }
        if (!isset($list)) {
            $list                   = $this;
        }
        $articles                      = $list->where($where)
            ->orderBy('updated_at', 'desc')
            ->skip(($param['page'] - 1) * $param['limit'])
            ->take($param['limit'])
            ->select('id', 'user_id', 'title', 'face_img', 'type_id', 'label', 'show_num', 'active', 'reason', 'updated_at')
            ->get();
        $rows                       = $list->where($where)->count();
        foreach ($articles as $k => $v) {
            $articles[$k]->user_name = $v->user->name;
        }
        //return response()->json(DB::getQueryLog());
        return [
            'list' => $articles,
            'rows' => $rows
        ];
    }

    public function delArticle($id)
    {
        $article                    = $this->find($id);
        $comments                   = $article->comments;
        $collects                   = $article->collects;

        foreach ($comments as $v) {
            $v->delete();
        }
        foreach ($collects as $v) {
            $v->delete();
        }

        $res                        = $this->destroy($id);
        return $res;
    }

    public function getArticleItemList($param, $page, $limit)
    {
        $where                      = [];
        foreach ($param as $k => $v) {
            $where[]            = [$k, '=', $v];
        }

        if (!isset($list)) {
            $list                   = $this;
        }

        $rows                       = $list->where($where)->count();
        $list                       = $list->where($where)->select('id', 'user_id', 'show_text', 'title', 'face_img', 'type_id', 'label', 'show_num', 'updated_at', 'active')->get();

        foreach ($list as $k => $v) {
            $list[$k]->collect_num  = $v->collects->count();
            $list[$k]->user         = $v->user;
        }

        $list                       = $list->sortByDesc('collect_num');

        return [
            'moreItems'             => $rows - $page * $limit,
            'list'                  => $list->take($page * $limit)
        ];
    }
    public function getArticle($id)
    {
        $where                      = [];
        $where[]                    = ['active', '=', 1];
        $where[]                    = ['id', '=', $id];

        $list                       = $this->where($where)->first();
        if ($list == null) {
            return [
                'status'            => false,
                'text'              => null
            ];
        } else {
            $list->show_num         = $list->show_num + 1;
            $list->save();
            $list->user;
            $list->collects;
            $list->comments;
            foreach ($list->comments as $k => $v) {
                $list->comments[$k]->user;
            }
            return [
                'status'            => true,
                'text'              => $list
            ];
        }
    }
}

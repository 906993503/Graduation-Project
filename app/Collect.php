<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collect extends Model
{
    use SoftDeletes;
    protected $table = 'collect';
    protected $attributes = [
        'status' => true
    ];
    protected $dateFormat = 'U';

    public function article()
    {
        return $this->belongsTo('App\Article', 'article_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function getCollectArticleItemList($user, $page, $limit)
    {

        $where[]                    = ['status', '=', 1];
        $where[]                    = ['user_id', '=', $user->id];
        $collects                   = $this->where($where)->get();

        $list                       = $collects;
        foreach ($collects as $k => $v) {
            $list[$k]               = $v->article;
            $list[$k]->collect_num  = $list[$k]->collects->count();
            $list[$k]->content      = "";
            $list[$k]->user         = $list[$k]->user;
        }

        $rows                       = count($list);

        return [
            'moreItems'             => $rows - $page * $limit,
            'list'                  => $list->take($page * $limit)
        ];
    }

    public function collectThis($article_id, $user_id)
    {
        $where[]                    = ['article_id', '=', $article_id];
        $where[]                    = ['user_id', '=', $user_id];

        $collect                    = $this->where($where)->first();

        if ($collect == null) {
            $collect                = new Collect();
            $collect->user_id       = $user_id;
            $collect->article_id    = $article_id;
        } else {
            $collect->status        = !$collect->status;
        }
        $collect->save();

        $where                      = [];
        $where[]                    = ['article_id', '=', $article_id];
        $collects                   = $this->where($where)->get();

        return $collects;
    }
}

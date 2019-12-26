<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;


class Comment extends Model
{
    use SoftDeletes;

    protected $table = 'comments';
    protected $dateFormat = 'U';

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function be_user()
    {
        return $this->belongsTo('App\User', 'be_user_id');
    }

    public function addComment($param)
    {

        try {
            DB::beginTransaction();
            $comment                        = new Comment();
            foreach ($param as $k => $v) {
                $comment->$k                = $v;
            }

            $res                            = $comment->save();
            if ($comment->user_id != $comment->be_user_id) {
                Message::newCommentMsg($comment->id);
            }
            DB::commit();
            return $res;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return false;
        }
    }

    public function getCommentList($param)
    {
        $where                      = [];
        if (isset($param['search'])) {
            $where[]                = ['content', 'LIKE', '%' . $param['search'] . '%'];
        }

        $rows                       = $this->where($where)->count();
        $comments                   = $this->where($where)
            ->orderBy('created_at', 'desc')
            ->skip(($param['page'] - 1) * $param['limit'])
            ->take($param['limit'])
            ->get();

        foreach ($comments as $k => $v) {
            $comments[$k]->user;
            $comments[$k]->be_user;
            $comments[$k]->content  = strip_tags($v->content);
        }
        return [
            'list' => $comments,
            'rows' => $rows
        ];
    }
    public function delComment($id)
    {
        $res                        = $this->destroy($id);
        return $res;
    }
}

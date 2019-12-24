<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $table = 'messages';

    protected $attributes = [
        'is_read' => false
    ];
    protected $dateFormat = 'U';

    public function getUserMsgList($param, $page, $limit)
    {
        $where                      = [];
        foreach ($param as $k => $v) {
            $where[]            = [$k, '=', $v];
        }
        if (!isset($list)) {
            $list                   = $this;
        }
        $rows                       = $this->where($where)->count();
        $list                       = $list->where($where)->orderBy('created_at', 'desc')->get();
        foreach ($list as $k => $v) {
            $list[$k]->show_text    = "&nbsp;&nbsp;&nbsp;&nbsp;" . mb_substr(strip_tags($v->content), 0, 45, "utf-8") . " . . .";
        }

        return [
            'moreItems'             => $rows - $page * $limit,
            'list'                  => $list->take($page * $limit)
        ];
    }
    public function getUserMsgRows($user)
    {
        $where[]                    = ['user_id', '=', $user->id];
        $where[]                    = ['is_read', '=', false];

        $rows                       = $this->where($where)->count();

        return [
            'rows'                  => $rows
        ];
    }

    public function readMsg($id)
    {
        $list                       = $this->whereIn('id', $id)->update(['is_read' => true]);

        return ['status' => true];
    }

    public static function newCommentMsg($comment_id)
    {
        $msg                        = new Message();
        $comment                    = Comment::find($comment_id);

        $create                     = date('Y-m-d H:i:s');
        $text                       = "<h2 class='ql-align-center'>评论通知</h2><p>	尊敬的用户{$comment->be_user->name}：</p><p>	您好，您的文章/评论被其他用户评论啦，<a href='/#/content/{$comment->article_id}/{$comment->id}' rel='noopener noreferrer' target='_blank'>去看看吧</a>！</p><p class='ql-align-right'>对您造成的不便，我们深感抱歉</p><p class='ql-align-right'>QAQxm系统通知</p><p class='ql-align-right'>{$create}</p>";

        $msg->user_id               = $comment->be_user_id;
        $msg->content               = $text;
        $msg->title                 = "评论通知";
        $msg->save();

        return $msg;
    }

    public static function newBanArticleMsg($article_id)
    {
        $msg                        = new Message();
        $article                    = Article::find($article_id);

        $create                     = date('Y-m-d H:i:s');
        $text                       = "<h2 class='ql-align-center'>文章驳回通知</h2><p>尊敬的用户{$article->user->name}：</p><p><br></p><p>您好，您的文章 “{$article->title}”，因{$article->reason}而被驳回，暂时无法正常显示，<a href='/#/editor/{$article->id}' rel='noopener noreferrer' target='_blank'>请点击此次进行修改</a>。</p><p><br></p><p class='ql-align-right'>对您造成的不便，我们深感抱歉</p><p class='ql-align-right'>QAQxm系统通知</p><p class='ql-align-right'>{$create}</p>";

        $msg->user_id               = $article->user->id;
        $msg->content               = $text;
        $msg->title                 = "文章驳回通知";
        $msg->save();

        return $msg;
    }
}

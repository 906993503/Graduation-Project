<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Article;

class Types extends Model
{
    use SoftDeletes;

    protected $table = 'types';
    protected $attributes = [
        'sort_by' => 1
    ];
    protected $dateFormat = 'U';
    public function articles()
    {
        return $this->hasMany('App\Article', 'type_id');
    }
    public function getTypeList($param)
    {
        $where                      = [];
        if (isset($param['search'])) {
            $where[]                = ['name', 'LIKE', '%' . $param['search'] . '%'];
        }

        $types                      = $this->where($where)
            ->orderBy('sort_by', 'asc')
            ->skip(($param['page'] - 1) * $param['limit'])
            ->take($param['limit'])
            ->get();

        foreach ($types as $k => $v) {
            $types[$k]->child_num   = $v->articles->count();
        }
        $rows                       = $this->where($where)->count();
        return [
            'list' => $types,
            'rows' => $rows
        ];
    }

    public function saveType($id, $param)
    {
        if ($id == 0) {
            $type                   = new Types();
        } else {
            $type                   = $this->find($id);
        }
        foreach ($param as $k => $v) {
            $type->$k               = $v;
        }

        $type->save();
        return ($type);
    }
    public function delType($id)
    {

        try {
            DB::beginTransaction();
            $type                       = $this->find($id);
            $articles                   = $type->articles;
            foreach ($articles as $article) {
                $article->type_id  = 0;
                $article->active   = 0;
                $article->reason   = "分类被删除，请重新选择分类";
                $article->save();
            }
            $res                        = $this->destroy($id);
            DB::commit();
            return $res;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return false;
        }
    }
}

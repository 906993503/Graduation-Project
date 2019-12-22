<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $dateFormat = 'U';

    protected $attributes = [
        'avatar' => '/images/userImg/avatar.jpg',
        'is_admin' => false,
        'active' => true
    ];

    public function banUser($id)
    {
        $user                       = $this->find($id);
        $user->active               = !$user->active;
        $user->save();
        return true;
    }
    public function getUserList($param)
    {
        $where                      = [];
        if (isset($param['search'])) {
            $name[]                 = ['name', 'LIKE', '%' . $param['search'] . '%'];
            $email[]                = ['email', 'LIKE', '%' . $param['search'] . '%'];
            $list                   = $this->where($name)->orWhere($email);
        }

        if (isset($param['active'])) {
            $where[]                = ['active', '=', $param['active']];
        }

        if (isset($param['is_admin'])) {
            $where[]                = ['is_admin', '=', $param['is_admin']];
        }

        if (isset($param['begin_date']) && isset($param['end_date'])) {
            $where[]                = ['created_at', '>', min($param['begin_date'], $param['end_date'])];
            $where[]                = ['created_at', '<', max($param['begin_date'], $param['end_date'])];
        }
        if (!isset($list)) {
            $list                   = $this;
        }
        $users                      = $list->where($where)
            ->orderBy('created_at', 'desc')
            ->skip(($param['page'] - 1) * $param['limit'])
            ->take($param['limit'])
            ->get();
        $rows                       = $list->where($where)->count();
        //return response()->json(DB::getQueryLog());
        return [
            'list' => $users,
            'rows' => $rows
        ];
    }
    public function saveUser($id, $param)
    {
        $user                       = $this->find($id);
        foreach ($param as $k => $v) {
            $user->$k               = $v;
        }
        $user->save();
        return ($user);
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //默认生成的用户授权策略添加 update 方法，用于用户更新时的权限验证。
    //第一个参数默认为当前登录用户实例，第二个参数则为要进行授权的用户实例
    public function update(User $currentUser, User $user)
    {
        //当两个 id 相同时，则代表两个用户是相同用户，用户通过授权，可以接着进行下一个操作。
        //如果 id 不相同的话，将抛出 403 异常信息来拒绝访问。
        return $currentUser->id === $user->id;
    }
}

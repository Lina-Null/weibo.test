<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Status;

class StatusesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);

        Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);
        session()->flash('success', '发布成功！');
        return redirect()->back();
    }
    public function destroy(Status $status)
    {
        // 授权验证：只有当前用户为该微博的作者时，才能删除该微博
        //Laravel 会自动查找并注入对应 ID 的实例对象 $status，如果找不到就会抛出异常。
        $this->authorize('destroy', $status);
        //做删除授权的检测，不通过会抛出 403 异常。
        $status->delete();
        session()->flash('success', '微博已被成功删除！');
        //删除成功之后，将返回到执行删除微博操作的页面上。
        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function __construct()
    {
        //Auth 中间件提供的 guest 选项，用于指定一些只允许未登录用户访问的动作
        $this->middleware('guest',[
            'only' => ['create']
        ]);
         // 限流 10 分钟十次
         $this->middleware('throttle:10,10', [
            'only' => ['store']
        ]);
    }
    //
    public function create()
    {
        return view('sessions.create');
    }
    public function store(Request $request){
        $credentials = $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials,$request->has('remember'))) {
            if(Auth::user()->activated){
                // 登录成功后的相关操作
                session()->flash('success', '欢迎回来！');
                $fallback = route('users.show',Auth::user());
                return redirect()->intended($fallback);
            }else{
                Auth::logout();
                session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
                return redirect('/');
            }
        } else {
            // 登录失败后的相关操作
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }

        return;
    }

    public function destroy()
    {
        //退出
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }

}

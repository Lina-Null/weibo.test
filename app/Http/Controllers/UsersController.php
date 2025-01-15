<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
class UsersController extends Controller
{
    //使用中间件
    public function __construct(){
        // 指定动作 不使用 Auth 中间件进行过滤,除了此处指定的动作以外，所有其他动作都必须登录用户才能访问
        $this->middleware('auth',['except'=>['show','create','store','index','confirmEmail']]);
        //只让未登录用户访问注册页面
        $this->middleware('guest', [
            'only' => ['create']
        ]);

        // 限流 一个小时内只能提交 10 次请求；
        $this->middleware('throttle:10,60', [
            'only' => ['store']
        ]);
    }
    //创建用户
    public function create(){
        return view('users.create');
    }
    //显示个人
    public function show(User $user){
        $statuses = $user->statuses()
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        return view('users.show',compact('user','statuses'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>  bcrypt($request->password)
        ]);

        // Auth::login($user);
        // session()->flash('success','欢迎，您将在这里开启一段新的旅程~');
        // return redirect()->route('users.show',[$user]);
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');
    }
    //发送邮箱
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        //$from = 'summer@example.com';
        //$name = 'Summer';
        $to = $user->email;
        $subject = "感谢注册 Weibo 应用！请确认你的邮箱。";

        //Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
        //    $message->from($from, $name)->to($to)->subject($subject);
        //});
        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }
    //确认 激活邮箱
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }

    public function edit(User $user){
        $this->authorize('update', $user);
        //$this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user){
        $this->authorize('update', $user);
        $this->validate($request,[
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);
        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);
        session()->flash('success','个人资料更新成功');
        return redirect()->route('users.show', $user->id);
    }

    public function index()
    {
        //$users = User::all();
        $users = User::paginate(6);
        return view('users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        //
        //删除动作的授权中，我们规定只有当前用户为管理员，且被删除用户不是自己时，授权才能通过
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }
}

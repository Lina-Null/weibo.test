<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaticPagesController extends Controller
{
    //主页
    public function home(){
        //保存微博动态数据
        $feed_items = [];
        //用户在访问首页时，可能存在登录或未登录两种状态，因此我们需要确保当前用户已进行登录时才从数据库读取数据,每页只显示 30 条微博
        if (Auth::check()) {
            $feed_items = Auth::user()->feed()->paginate(30);
        }

        return view('static_pages/home', compact('feed_items'));
    }

    //帮助
    public function help(){
        return view('static_pages/help');
    }

    //关于
    public function about(){
        return view('static_pages/about');
    }
}

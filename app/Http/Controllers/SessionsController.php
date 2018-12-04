<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest',[
            'only'=>['create']
        ]);
    }

    /**显示登录页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('session.create');
    }

    /**用户登录
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = $this->validate($request,[
           'email'=>'required|email|max:255',
            'password'=>'required',
        ]);
        //进行登录验证
        if(Auth::attempt($validator,$request->has('remember'))){
            session()->flash('success','欢迎回来！');
            return redirect()->intended(route('users.show',[Auth::user()]));
        }else{
            session()->flash('danger','很抱歉，您的邮箱和密码不匹配');
            return redirect()->back();
        }
    }

    /**用户退出
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function destroy()
    {
        Auth::logout();
        session()->flash('success','您已成功退出');
        return redirect('login');
    }
}

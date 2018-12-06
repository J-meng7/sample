<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mail;

class UsersController extends Controller
{
    public function __construct()
    {
        //不使用中间件过滤
        $this->middleware('auth',[
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);
        //中间件未登录用户访问
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    /**用户列表
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(User $user)
    {
        $sortBy = null;
        //如果$sortBy为空，默认id排序写法
        $users = $user
            ->when($sortBy, function ($query) use ($sortBy) {
                return $query->orderBy($sortBy);
            }, function ($query) {
                return $query->orderBy('id');
            })
            ->paginate(10);
        return view('users.index',compact('users'));
    }

    /**展示用户个人信息
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        $statuses = $user->statuses()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('users.show', compact('user','statuses'));
    }

    /**创建用户信息页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**创建用户信息
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user =User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> bcrypt($request->password),
        ]);

        $this->seedEmailConfirmationTo($user);
        session()->flash('success','验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');

    }

    /**发送邮件
     * @param $user
     */
    public function seedEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';       //视图
        $data = compact('user'); //传递给视图的数据
        //$from = '523731804@qq.com';  //发送邮箱
        //$name = 'jia';   //发送人
        $to = $user->email;  //收件人
        $subject = "感谢注册 Sample 应用！请确认你的邮箱。"; //主题
        Mail::send($view,$data,function ($message) use ($to,$subject){
            $message->to($to)->subject($subject);
        });
    }

    /**激活邮件
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmEmail($token)
    {
        //查询出传递过来token相同的用户信息
        $user = User::where('activation_token',$token)->firstOrFail();
        //更新用户的token激活状态并把token赋值null
        $user->activated = true;
        $user->activation_token = null;
        $user->save();
        //登录
        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }

    /**更新用户个人信息页面
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        //authorize验证用户授权策略，第一个参数是授权策略的名称，第二个参数是授权策略数据
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    /**更新用户资料
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,User $user)
    {

        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);
        $this->authorize('update',$user);
        $data= [];
        $data['name']=$request->name;
        if ($request->password){
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);
       \session()->flash('success','修改资料成功');

       return redirect()->route('users.show',$user->id);

    }

    /**删除用户
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success','成功删除用户！');
        return back();
    }

}

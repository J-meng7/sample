<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Auth;

class StatusesController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
           'content'=>'required|max:140',
        ]);
        Auth::user()->statuses()->create([
            'content'=>$request->content,
        ]);
        session()->flash('success','发布成功！');
        return redirect()->back();
    }

    public function destroy(Status $status)
    {
        //做授权删除匹配，匹配不通过报403
        $this->authorize('destroy', $status);
        //删除
        $status->delete();
        session()->flash('success', '微博已被成功删除！');
        return redirect()->back();
    }
}

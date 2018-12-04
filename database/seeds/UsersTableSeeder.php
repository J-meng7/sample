<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(User::class)->times(50)->make();
        //makeVisible方法临时显示 User 模型里指定的隐藏属性 $hidden
        //insert 方法来将生成假用户列表数据批量插入到数据库中
        User::insert($users->makeVisible(['password', 'remember_token'])->toArray());
        //将第一个数据更新，方便测试
        $user = User::find(1);
        $user->name = 'Aufree';
        $user->email = 'aufree@yousails.com';
        $user->password = bcrypt('password');
        $user->is_admin = true;
        $user->save();
    }
}

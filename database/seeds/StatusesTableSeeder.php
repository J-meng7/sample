<?php

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_ids = ['1','2','3'];
        //app() 方法来获取一个 Faker 容器 的实例，
        $faker = app(Faker\Generator::class);
        //each()获取当前元素的键值
        $statuses = factory(Status::class)->times(100)->make()->each(function ($status) use ($faker, $user_ids) {
            //利用randomElement 方法来取出用户 id 数组中的任意一个元素并赋值给微博的 user_id
            $status->user_id = $faker->randomElement($user_ids);
        });
        //将生成数据转化数组插入Status表中

        Status::insert($statuses->toArray());
    }
}

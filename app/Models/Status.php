<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{

    protected $table = 'statuses';    //设置表名
    protected $primaryKey = 'id';     //设置主键
    protected $fillable = ['content']; //设置允许操作字段

    /**与用户一对一关系(一条微博对应一个用户)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

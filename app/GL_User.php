<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GL_User__ extends Model
{
    //
    // 設定資料表名稱
    protected $table = 'GL_USER';

    // 預設 primaryKey 為 id，如果不是的話需要另外設定
    protected $primaryKey = 'gl_code';

    // 關閉 timestamps 控制（預設為開啟）
    public $timestamps = false;

    // 將時間格式改成 Seconds since the Unix Epoch
    // protected $dateFormat = 'U';
}

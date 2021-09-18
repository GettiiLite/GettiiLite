<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionModel extends Model
{
    protected $table = 'GL_QUESTION';
    protected $primaryKey = 'question_id';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'performance_id',
        'use_flg',
        'require_flg',
        'disp_order',
        'update_account_cd',
    ];

    public function questionLang()
    {
        return $this->hasMany('App\Models\QuestionLangModel', 'question_id', 'question_id');
    }

    public function questionLangJa()
    {
        return $this->hasMany('App\Models\QuestionLangModel', 'question_id', 'question_id')->where('lang_code', 'ja');
    }
}

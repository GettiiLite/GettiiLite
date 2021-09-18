<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionAnswerModel extends Model
{
    protected $table = 'GL_QUESTION_ANSWER';
    protected $primaryKey = 'answer_id';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'question_id',
        'order_id',
        'answer_text',
        'update_account_cd',
    ];

    public function questionLang()
    {
        return $this->hasMany('App\Models\QuestionLangModel', 'question_id', 'question_id');
    }

    public function questionLangJa()
    {
        return $this->belongsTo('App\Models\QuestionLangModel', 'question_id', 'question_id')->where('lang_code', 'ja');
    }

    public function question()
    {
        return $this->hasOne('App\Models\QuestionModel', 'question_id', 'question_id');
    }
}

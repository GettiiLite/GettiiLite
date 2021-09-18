<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionLangModel extends Model
{
    protected $table = 'GL_QUESTION_LANG';
    protected $primaryKey = 'lang_id';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'question_id',
        'lang_code',
        'question_title',
        'question_text',
        'answer_placeholder',
        'update_account_cd',
    ];

    public function question()
    {
        return $this->belongsTo('App\Models\QuestionModel', 'question_id');
    }
}

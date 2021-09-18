<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvenManageModel extends Model
{
    // performance status
    const PERFORMANCE_STATUS_GOING      = 0;    //登録中（基本情報）
    const PERFORMANCE_STATUS_COMPLETE   = 1;    //登録済（基本情報）
    const PERFORMANCE_STATUS_BROWSE     = 2;    //公演表示可
    const PERFORMANCE_STATUS_SALE       = 3;    //販売可
    const PERFORMANCE_STATUS_CANSEL     = 4;    //中止
    const PERFORMANCE_STATUS_DELETE     = -1;   //削除

    
    const PERFORMANCE_DISPLAY_STATUS_GOING      = 0;    //登録中（基本情報）
    const PERFORMANCE_DISPLAY_STATUS_COMPLETE   = 1;    //登録済（基本情報）
    const PERFORMANCE_DISPLAY_STATUS_BROWSE     = 2;    //公演表示可
    const PERFORMANCE_DISPLAY_STATUS_PUBLIC     = 3;    //公演表示中
    const PERFORMANCE_DISPLAY_STATUS_SALE       = 4;    //販売可
    const PERFORMANCE_DISPLAY_STATUS_SALING     = 5;    //販売中
    const PERFORMANCE_DISPLAY_STATUS_ONGOING    = 6;    //期間中
    const PERFORMANCE_DISPLAY_STATUS_CLOSE      = 7;    //終了
    const PERFORMANCE_DISPLAY_STATUS_CANSEL     = 8;    //中止
    const PERFORMANCE_DISPLAY_STATUS_DELETE     = -1;   //削除
    const PERFORMANCE_DISPLAY_STATUS_UNKONW     = -99;  //unknow status

    // paid status
    const PAID_STATUS_NONE = 0;     //無し
    const PAID_STATUS_YET = 1;      //未対応
    const PAID_STATUS_GOING = 2;    //対応中
    const PAID_STATUS_COMPLETE = 3; //対応済

    //top content type
    const TOP_CONTENT_IMG   = 1; //画像
    const TOP_CONTENT_MOVIE = 2; //動画
   
    //schedule type
    const SCHEDULE_NON = 0; //0:スケジュール設定不要（毎日）　
    const SCHEDULE_SET = 1; //1:スケジュール設定

    //seat selection
    const SEAT_SELECTION_OFF = 0; //0:座席指定無し
    const SEAT_SELECTION_ON  = 1; //1:座席指定有り
    //    
    // GETTIIS trans flg
    const GETTIIS_TRANS_YET = 0;     //未連携
    const GETTIIS_TRANS_NEED = 1;    //要連携
    const GETTIIS_TRANS_ALREADY = 2; //連携済

    // performance edit status
    const PERFORMANCE_EDIT_STATUS_NOT       = 0;    //未編輯
    const PERFORMANCE_EDIT_STATUS_GOING     = 1;    //已編輯（不可發佈）
    const PERFORMANCE_EDIT_STATUS_COMPLETE  = 2;    //已編輯（可發佈）

    // portlanguage type
    const PORTAL_LANG_NONE   = 0;    //なし
    const PORTAL_LANG_ENG    = 1;    //英語
    const PORTAL_LANG_ZH     = 2;    //中国語
    const PORTAL_LANG_ENGZH  = 3;    //英語・中国語
    const PORTAL_LANG_ALL    = 4;    //全て

    protected $table = 'GL_PERFORMANCE';
    protected $primaryKey = 'performance_id';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'performance_id',
        'GLID',
        'performance_code', 
        'status', 
        'paid_status', 
        'performance_name', 
        'performance_name_k', 
        'performance_name_sub', 
        'performance_name_seven', 
        'sch_kbn', 
        'performance_st_dt', 
        'performance_end_dt',
        'hall_code', 
        'hall_disp_name', 
        'seatmap_profile_cd', 
        'disp_start', 
        'disp_end',
        'information_nm', 
        'information_tel', 
        'mail_address', 
        'genre_code', 
        'official_url', 
        'top_conten_type', 
        'top_conten_url', 
        'top_content_comment', 
        'thumbnail', 
        'context', 
        'article',
        'keywords',
        'selection_flg', 
        'purchasable_number', 
        'trans_flg', 
        'sale_type',
        'announce_date', 
        'temporary_info', 
        'insert_account_cd', 
        'update_account_cd',
        'edit_status',
        'autotranslation',
        'portlanguage',
    ];

    public function schedule()
    {
        return $this->hasMany('App\Models\ScheduleModel', 'performance_id', 'performance_id');
    }

    public function salesTerm()
    {
        return $this->hasMany('App\Models\SalesTermModel', 'performance_id');
    }

    public function Stagename()
    {
        return $this->hasOne('App\Models\StagenameModel', 'performance_id');
    }

    public function draft()
    {
        return $this->hasOne('App\Models\DraftModel', 'performance_id');
    } 

    public function seatmapProfile()
    {
        return $this->hasOne('App\Models\SeatMapProfileModel', 'profile_id', 'seatmap_profile_cd');
    }


    public function GLVStatsOfPerformace()
    {
        return $this->hasMany('App\Models\GLVStatsOfPerformaceModel', 'performance_id');
    }

    public function GLVSeatOfStage()
    {
        return $this->hasMany('App\Models\VSeatOfStageModal', 'performance_id', 'performance_id');
    }

    public function GLVSeatStaticOfStage()
    {
        return $this->hasManyThrough(
            'App\Models\GLVSeatStaticOfStageModel',
            'App\Models\ScheduleModel',
            'performance_id',
            'schedule_id',
            'performance_id',
            'schedule_id'
        );
    }

    public function GLVStatsOfStage()
    {
        return $this->hasManyThrough(
            'App\Models\GLVStatsOfStageModel',
            'App\Models\ScheduleModel',
            'performance_id',
            'schedule_id',
            'performance_id',
            'schedule_id'
        );
    }

    public function question()
    {
        return $this->hasMany('App\Models\QuestionModel', 'performance_id', 'performance_id');
    }

    /*
     * 活動查詢
     * 
     * @param array time
     */
    public function scopeFindPerformance($query, $performance_id)
    {
        return $query->when($performance_id, function ($query) use ($performance_id){
            $query->where('performance_id', $performance_id);
        });
    }   
}

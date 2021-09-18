<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Gldb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GL_USER', function (Blueprint $table) {
            $table->increments('GLID')->nullable(false)->comment('GLID ;PK');
            $table->unsignedInteger('user_code')->nullable(false)->comment('ユーザーコード;Unique');
            $table->unsignedInteger('gl_code')->nullable(true)->comment('GLコード');
            $table->string('user_id',32)->nullable(false)->comment('ユーザーID;Unique');
            $table->tinyInteger('user_status')->nullable(false)->comment('ユーザーステータス');
            $table->string('contract_name',80)->nullable(true)->comment('契約名称');
            $table->string('contract_name_kana',160)->nullable(true)->comment('契約名称カナ');
            $table->string('disp_name',80)->nullable(true)->comment('表示名称');
            $table->tinyInteger('GETTIIS_disp_flg')->nullable(true)->default(0)->comment('販売元表示フラグ   ');
            $table->tinyInteger('user_kbn')->nullable(true)->default(0)->comment('ユーザー区分  ');
            $table->string('department',80)->nullable(true)->comment('所属部署');
            $table->string('contact_person',80)->nullable(true)->comment('担当者名');
            $table->string('home_page',200)->nullable(true)->comment('ホームページ');
            $table->string('logo_image',255)->nullable(true)->comment('ロゴ画像');
            $table->string('post_code',8)->nullable(true)->comment('郵便番号');
            $table->string('address',100)->nullable(true)->comment('住所');
            $table->string('tel_num',26)->nullable(true)->comment('電話番号');
            $table->string('fax_num',26)->nullable(true)->comment('ＦＡＸ番号');
            $table->string('mail_address',200)->nullable(false)->comment('メールアドレス');
            $table->string('bank_name',60)->nullable(true)->comment('銀行名');
            $table->string('branch_name',60)->nullable(true)->comment('支店');
            $table->tinyInteger('account_kbn')->nullable(true)->default(0)->comment('口座種別  ');
            $table->string('account_num',20)->nullable(true)->comment('口座番号');
            $table->string('account_name',40)->nullable(true)->comment('口座名義人');
            $table->longText('id_image')->nullable(true)->comment('身分証画像');
            $table->text('introduction_text')->nullable(true)->comment('紹介テキスト');
            $table->timestamp('request_date')->nullable(true)->comment('編集申請日');
            $table->unsignedInteger('judge_account_cd')->nullable(true)->comment('審查担当者コード;FK');
            $table->timestamp('judgement_date')->nullable(true)->comment('判定日');
            $table->tinyInteger('event_publishable')->nullable(true)->default(0)->comment('公演公開フラグ');
            $table->longText('temporary_info')->nullable(true)->comment('登録中情報');
            $table->timestamp('app_date')->nullable(true)->comment('申請日');
            $table->unsignedInteger('update_account_cd')->nullable(true)->comment('更新担当者コード;FK');
            $table->timestamps();
            $table->unique('user_id');
            $table->unique('user_code');
        });

        Schema::create('GL_ACCOUNT', function (Blueprint $table) {
            $table->increments('account_cd')->nullable(false)->comment(';PK');
            $table->unsignedInteger('GLID')->nullable(false)->comment('GLID ;FK,Unique');
            $table->unsignedInteger('account_number')->nullable(false)->comment('アカウント№;Unique');
            $table->string('account_code',32)->nullable(false)->comment('アカウントコード');
            $table->string('password',255)->nullable(false)->comment('パスワード');
            $table->dateTime('expire_date')->nullable(true)->comment('有効期限');
            $table->string('mail_address',200)->nullable(false)->comment('メールアドレス');
            $table->tinyInteger('profile_info_flg')->nullable(false)->default(1)->comment('プロフィール操作');
            $table->tinyInteger('event_info_flg')->nullable(false)->default(1)->comment('イベント管理');
            $table->tinyInteger('sales_info_flg')->nullable(false)->default(1)->comment('販売管理');
            $table->tinyInteger('personal_info_flg')->nullable(false)->default(1)->comment('個人情報閲覧');
            $table->tinyInteger('status')->nullable(false)->default(0)->comment('ステータス');
            $table->string('remarks',255)->nullable(true)->comment('備考');
            $table->unsignedInteger('update_account_cd')->nullable(true)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('GLID')->references('GLID')->on('GL_USER');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['GLID', 'account_number']);
        });

        Schema::create('GL_PERFORMANCE', function (Blueprint $table) {
            $table->increments('performance_id')->nullable(false)->comment('公演ID;PK');
            $table->unsignedInteger('GLID')->nullable(false)->comment('GLID ;FK,unique');
            $table->string('performance_code',12)->nullable(false)->comment('公演コード;unique');
            $table->tinyInteger('status')->nullable(false)->comment('公演ステータス');
            $table->tinyInteger('paid_status')->nullable(false)->default(0)->comment('有料ステータス');
            $table->string('performance_name',255)->nullable(false)->comment('公演名');
            $table->string('performance_name_k',400)->nullable(true)->comment('公演名カナ');
            $table->string('performance_name_sub',255)->nullable(true)->comment('公演名副題');
            $table->string('performance_name_seven',80)->nullable(true)->comment('セブンイレブン公演名');
            $table->tinyInteger('sch_kbn')->nullable(true)->comment('日程区分');
            $table->date('performance_st_dt')->nullable(true)->comment('公演開始日');
            $table->date('performance_end_dt')->nullable(true)->comment('公演終了日');
            $table->unsignedInteger('hall_code')->nullable(true)->comment('会場コード;FK');
            $table->string('hall_disp_name',100)->nullable(true)->comment('会場表示名');
            $table->unsignedInteger('seatmap_profile_cd')->nullable(true)->comment('座席配置プロフィール');
            $table->dateTime('disp_start')->nullable(false)->comment('表示開始日時');
            $table->string('information_nm',80)->nullable(false)->comment('問い合せ先名');
            $table->string('information_tel',13)->nullable(false)->comment('問い合せ先電話番号');
            $table->string('mail_address',200)->nullable(false)->comment('問合せ先メールアドレス');
            $table->integer('genre_code')->nullable(true)->comment('ジャンル');
            $table->string('official_url',200)->nullable(true)->comment('公式サイトURL');
            $table->tinyInteger('top_conten_type')->nullable(true)->comment('TOPコンテンツタイプ');
            $table->string('top_conten_url',255)->nullable(true)->comment('TOPコンテンツURL');
            $table->string('top_content_comment',255)->nullable(true)->comment('TOPコンテンツコメント');
            $table->string('thumbnail',255)->nullable(true)->comment('サムネイル');
            $table->text('context')->nullable(true)->comment('公演内容概要');
            $table->tinyInteger('selection_flg')->nullable(true)->comment('座席指定機能利用フラグ');
            $table->tinyInteger('purchasable_number')->nullable(false)->comment('購入可能累計枚数');
            $table->tinyInteger('trans_flg')->nullable(true)->comment('GETTIIS連携フラグ');
            $table->timestamp('announce_date')->nullable(true)->comment('情報公開日');
            $table->longtext('temporary_info')->nullable(true)->comment('登録中情報');
            $table->unsignedInteger('insert_account_cd')->nullable(false)->comment('登録担当者コード');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード');
            $table->timestamps();

            $table->foreign('GLID')->references('GLID')->on('GL_USER');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['GLID', 'performance_code']);

        });

        Schema::create('GL_STAGENAME', function (Blueprint $table) {
            $table->increments('stcd')->nullable(false)->comment('ステージコード;PK');
            $table->unsignedInteger('performance_id')->nullable(false)->comment('公演ID;FK, unique');
            $table->unsignedSmallInteger('stage_num')->nullable(false)->comment('ステージ順;unique');
            $table->string('stage_name',80)->nullable(true)->comment('ステージ名');
            $table->tinyInteger('stage_disp_flg')->nullable(false)->default(0)->comment('ステージ名表示フラグ');
            $table->string('description',320)->nullable(true)->comment('備考');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            // $table->primary(['performance_id', 'stcd']);
            $table->foreign('performance_id')->references('performance_id')->on('GL_PERFORMANCE');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['performance_id', 'stage_num']);
        });

        Schema::create('GL_SCHEDULE', function (Blueprint $table) {
            $table->increments('schedule_id')->nullable(false)->comment('スケジュールID;PK');
            $table->unsignedInteger('performance_id')->nullable(false)->comment('公演ID;FK,unique');
            $table->date('performance_date')->nullable(false)->comment('公演日時;unique');
            $table->tinyInteger('performance_flg')->nullable(false)->default(1)->comment('公演フラグ');
            $table->string('open_date',15)->nullable(true)->comment('開場日時');
            $table->time('start_time')->nullable(false)->comment('開演時間;unique');
            $table->string('disp_performance_date',30)->nullable(true)->comment('表示公演日時');
            $table->tinyInteger('sch_kbn')->nullable(false)->comment('日程区分');
            $table->unsignedInteger('stcd')->nullable(false)->comment('ステージコード;FK');
            $table->tinyInteger('cancel_flg')->nullable(false)->default(0)->comment('公演中止フラグ');
            $table->string('cancel_messgae',500)->nullable(true)->comment('公演中止文言');
            $table->timestamp('cancel_date')->nullable(true)->comment('公演中止日');
            $table->unsignedInteger('cancel_account_cd')->nullable(true)->comment('中止担当者コード;FK');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('performance_id')->references('performance_id')->on('GL_PERFORMANCE');
            $table->foreign('stcd')->references('stcd')->on('GL_STAGENAME');
            $table->foreign('cancel_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['performance_id', 'performance_date', 'start_time']);
        });

        Schema::create('GL_HALL', function (Blueprint $table) {
            $table->increments('hall_code')->nullable(false)->comment('会場コード;PK');
            $table->string('hall_name',80)->nullable(false)->comment('会場名');
            $table->string('hall_name_short',20)->nullable(true)->comment('会場名略称');
            $table->string('hall_name_kana',160)->nullable(true)->comment('会場名カナ');
            $table->string('post_code',8)->nullable(false)->comment('郵便番号');
            $table->string('prefecture',10)->nullable(false)->comment('都道府県');
            $table->string('address1',100)->nullable(true)->comment('住所１');
            $table->string('address2',100)->nullable(true)->comment('住所２');
            $table->string('tel_num',26)->nullable(true)->comment('電話番号');
            $table->string('fax_num',26)->nullable(true)->comment('ＦＡＸ番号');
            $table->string('home_page',200)->nullable(true)->comment('ホームページアドレス');
            $table->string('mail_address',200)->nullable(true)->comment('メールアドレス');
            $table->string('description',320)->nullable(true)->comment('備考');
            $table->unsignedInteger('Owner_cd')->nullable(true)->comment('オーナー;FK');
            $table->tinyInteger('public')->nullable(false)->default(0)->comment('公開');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('Owner_cd')->references('GLID')->on('GL_USER');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
        });

        Schema::create('GL_SEATMAP_PROFILE', function (Blueprint $table) {
            $table->increments('profile_id')->nullable(false)->comment('profile id;PK');
            $table->unsignedInteger('hall_code')->nullable(false)->comment('会場コード;FK');
            $table->unsignedInteger('performance_id')->nullable(true)->comment('公演ID;FK');
            $table->tinyInteger('floor_ctrl')->nullable(false)->default(0)->comment('座席階管理');
            $table->tinyInteger('block_ctrl')->nullable(false)->default(0)->comment('座席ブロック管理');
            $table->tinyInteger('gate_ctrl')->nullable(false)->default(0)->comment('座席ゲート管理');
            $table->string('description',320)->nullable(true)->comment('備考');
            $table->unsignedInteger('Owner_cd')->nullable(true)->comment('オーナー;FK');
            $table->tinyInteger('public')->nullable(false)->default(0)->comment('公開');
            $table->string('version',20)->nullable(false)->default(0)->comment('バージョン');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();
            
            $table->foreign('hall_code')->references('hall_code')->on('GL_HALL');
            $table->foreign('performance_id')->references('performance_id')->on('GL_PERFORMANCE');
            $table->foreign('Owner_cd')->references('GLID')->on('GL_USER');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');

        });

        Schema::create('GL_FLOOR', function (Blueprint $table) {
            $table->increments('floor_id')->nullable(false)->comment('フロアid;PK');
            $table->unsignedInteger('profile_id')->nullable(false)->comment('profile id;FK');
            $table->string('floor_name',20)->nullable(false)->comment('フロア名');
            $table->tinyInteger('sequence')->nullable(false)->default(0)->comment('順序');
            $table->string('image_file_name',255)->nullable(true)->comment('画像ファイル名');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();
	
            $table->foreign('profile_id')->references('profile_id')->on('GL_SEATMAP_PROFILE');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
        });
        
        Schema::create('GL_BLOCK', function (Blueprint $table) {
            $table->increments('block_id')->nullable(false)->comment('ブロックid;PK');
            $table->unsignedInteger('profile_id')->nullable(false)->comment('profile id;FK');
            $table->string('block_name_short',40)->nullable(false)->comment('ブロック名略称');
            $table->string('block_name',40)->nullable(true)->comment('ブロック名');
            $table->string('app_block',100)->nullable(true)->comment('アプリブロック');
            $table->string('app_coordinate',1000)->nullable(true)->comment('アプリ座標');
            $table->string('net_coordinate',1000)->nullable(true)->comment('ネット座標');
            $table->string('image_file_name',160)->nullable(true)->comment('画像ファイル名');
            $table->tinyInteger('seat_direction')->nullable(false)->default(0)->comment('座席方向');
            $table->string('external_image',500)->nullable(true)->comment('展望画像');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('profile_id')->references('profile_id')->on('GL_SEATMAP_PROFILE');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
        });

        Schema::create('GL_HALL_SEAT', function (Blueprint $table) {
            $table->increments('seat_id')->nullable(false)->comment('座席id;PK');
            $table->unsignedInteger('profile_id')->nullable(false)->comment('profile id;FK,unique');
            $table->unsignedInteger('floor_id')->nullable(true)->comment('フロアid;FK');
            $table->unsignedInteger('block_id')->nullable(true)->comment('ブロックid;FK');
            $table->smallInteger('seat_seq')->nullable(false)->comment('座席連番;unique');
            $table->integer('x_coordinate')->nullable(true)->comment('座標Ｘ');
            $table->integer('y_coordinate')->nullable(true)->comment('座標Ｙ');
            $table->integer('x_position')->nullable(true)->comment('座標Ｘ');
            $table->integer('y_position')->nullable(true)->comment('座標Ｙ');
            $table->integer('seat_angle')->nullable(true)->default('0')->comment('角度');
            $table->string('seat_cols',20)->nullable(false)->comment('列;unique');
            $table->string('seat_number',20)->nullable(false)->comment('番号;unique');
            $table->string('gate',40)->nullable(true)->comment('ゲート');
            $table->smallInteger('prio_floor')->nullable(true)->comment('優先順位階');
            $table->smallInteger('prio_seat')->nullable(true)->default('0')->comment('優先順位座席');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('profile_id')->references('profile_id')->on('GL_SEATMAP_PROFILE');
            $table->foreign('floor_id')->references('floor_id')->on('GL_FLOOR');
            $table->foreign('block_id')->references('block_id')->on('GL_BLOCK');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['profile_id', 'seat_seq']);
            $table->unique(['profile_id', 'floor_id', 'block_id', 'seat_cols', 'seat_number'],'hall_seat_unique');
            
        });
        Schema::create('GL_SEAT_CLASS', function (Blueprint $table) {
            $table->increments('seat_class_id')->nullable(false)->comment('席種id;PK');
            $table->unsignedInteger('performance_id')->nullable(false)->comment('公演ID;FK,unique');
            $table->string('seat_class_name',50)->nullable(false)->comment('席種名;unique');
            $table->string('seat_class_name_short',20)->nullable(true)->comment('席種名略称');
            $table->tinyInteger('seat_class_kbn')->nullable(false)->comment('席種区分 ');
            $table->tinyInteger('next_seat_flg')->nullable(false)->comment('隣席管理フラグ');
            $table->string('gate',40)->nullable(true)->comment('ゲート');
            $table->smallInteger('disp_order')->nullable(false)->default(0)->comment('表示順');
            $table->string('seat_class_color',7)->nullable(false)->comment('表示色');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('performance_id')->references('performance_id')->on('GL_PERFORMANCE');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['performance_id', 'seat_class_name']);
        });

        Schema::create('GL_RESERVE', function (Blueprint $table) {
            $table->increments('reserve_code')->nullable(false)->comment('押えコード;PK');
            $table->unsignedInteger('performance_id')->nullable(false)->comment('公演ID;FK,unique');
            $table->string('reserve_name',80)->nullable(false)->comment('押え名');
            $table->string('reserve_symbol',2)->nullable(false)->comment('表示記号;unique');
            $table->string('reserve_color',7)->nullable(false)->default('#2F4F4F')->comment('表示色');
            $table->string('reserve_word_color',7)->nullable(false)->comment('表示文字色');
            $table->tinyInteger('sys_reserve_flg')->nullable(false)->default(0)->comment('システム押えフラグ');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('performance_id')->references('performance_id')->on('GL_PERFORMANCE');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['performance_id', 'reserve_symbol']);

        });

        Schema::create('GL_SEAT', function (Blueprint $table) {
            $table->increments('alloc_seat_id')->nullable(false)->comment('座席id;PK');
            $table->unsignedInteger('performance_id')->nullable(false)->comment('公演ID;FK,unique');
            $table->unsignedInteger('seat_id')->nullable(false)->comment('会場座席id;FK,Unique');
            $table->unsignedInteger('seat_class_id')->nullable(true)->comment('席種id;FK');
            $table->unsignedInteger('reserve_code')->nullable(true)->comment('押えコード;FK');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('performance_id')->references('performance_id')->on('GL_PERFORMANCE');
            $table->foreign('seat_id')->references('seat_id')->on('GL_HALL_SEAT');
            $table->foreign('seat_class_id')->references('seat_class_id')->on('GL_SEAT_CLASS');
            $table->foreign('reserve_code')->references('reserve_code')->on('GL_RESERVE');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['performance_id','seat_id']);
        });

        Schema::create('GL_STAGE_SEAT', function (Blueprint $table) {
            $table->increments('stage_seat_id')->nullable(false)->comment(';PK');
            $table->unsignedInteger('alloc_seat_id')->nullable(false)->comment('座席id;FK,unique');
            $table->unsignedInteger('schedule_id')->nullable(false)->comment('スケジュールID;FK,unique');
            $table->unsignedInteger('seat_class_id')->nullable(true)->comment('席種id;FK');
            $table->unsignedInteger('reserve_code')->nullable(true)->comment('押えコード;FK');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('alloc_seat_id')->references('alloc_seat_id')->on('GL_SEAT');
            $table->foreign('schedule_id')->references('schedule_id')->on('GL_SCHEDULE');
            $table->foreign('seat_class_id')->references('seat_class_id')->on('GL_SEAT_CLASS');
            $table->foreign('reserve_code')->references('reserve_code')->on('GL_RESERVE');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['alloc_seat_id','schedule_id']);
        });

        Schema::create('GL_TICKET_CLASS', function (Blueprint $table) {
            $table->increments('ticket_class_id')->nullable(false)->comment('券種id;PK');
            $table->unsignedInteger('seat_class_id')->nullable(false)->comment('席種id;FK,unique');
            $table->string('ticket_class_name',80)->nullable(false)->comment('券種名;unique');
            $table->string('ticket_class_name_short',20)->nullable(true)->comment('券種名略称');
            $table->tinyInteger('ticket_sales_kbn')->nullable(false)->comment('前売当日区分;unique');
            $table->tinyInteger('sheets_unit')->nullable(false)->default(1)->comment('枚数単位');
            $table->smallInteger('disp_order')->nullable(false)->default(0)->comment('表示順');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('seat_class_id')->references('seat_class_id')->on('GL_SEAT_CLASS');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['seat_class_id','ticket_sales_kbn', 'ticket_class_name'], 'ticket_class_unique');
        });

        Schema::create('GL_PRICE', function (Blueprint $table) {
            $table->increments('price_id')->nullable(false)->comment('料金設定id;PK');
            $table->unsignedInteger('ticket_class_id')->nullable(true)->comment('券種id;FK, unique');
            $table->unsignedInteger('member_kbn')->nullable(true)->default(0)->comment('会員区分');
            $table->decimal('price',8,2)->nullable(true)->comment('料金');
            $table->integer('pattern_code')->nullable(true)->default(1)->comment('パターンコード');
            $table->tinyInteger('treat_flg')->nullable(true)->default(1)->comment('取扱フラグ');
            $table->tinyInteger('treat_kbn')->nullable(true)->default(3)->comment('取扱区分');
            $table->unsignedInteger('update_account_cd')->nullable(true)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('ticket_class_id')->references('ticket_class_id')->on('GL_TICKET_CLASS');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['ticket_class_id','member_kbn', 'treat_kbn']);
        });

        Schema::create('GL_GENERAL_RESERVATION', function (Blueprint $table) {
            $table->increments('order_id')->nullable(false)->comment('予約_ID;PK');
            $table->unsignedInteger('GLID')->nullable(false)->comment('GLID ;FK, Unique');
            $table->string('reserve_no',40)->nullable(false)->comment('予約番号;Unique');
            $table->tinyInteger('receipt_kbn')->nullable(false)->comment('受付区分');
            $table->datetime('reserve_date')->nullable(false)->comment('予約日');
            $table->datetime('reserve_expire')->nullable(true)->comment('予約有効期日');
            $table->smallInteger('member_kbn')->nullable(false)->default(0)->comment('会員区分');
            $table->string('member_id',100)->nullable(true)->comment('会員ＩＤ');
            $table->string('consumer_name',160)->nullable(true)->comment('予約者');
            $table->string('consumer_kana',160)->nullable(true)->comment('予約者カナ');
            $table->string('consumer_kana2',160)->nullable(true)->comment('予約者カナ２');
            $table->string('tel_num',26)->nullable(true)->comment('電話番号');
            $table->tinyInteger('pay_method')->nullable(false)->comment('支払方法');
            $table->tinyInteger('pickup_method')->nullable(false)->comment('引取方法');
            $table->string('cs_payment_no',26)->nullable(true)->comment('コンビニ払込票番号');
            $table->string('cs_pickup_no',26)->nullable(true)->comment('コンビニ引換票番号');
            $table->string('mail_address',200)->nullable(false)->comment('メールアドレス');
            $table->datetime('pickup_st_date')->nullable(false)->comment('引取開始日時');
            $table->datetime('pickup_due_date')->nullable(false)->comment('引取締切日時');
            $table->tinyInteger('cancel_flg')->nullable(false)->default(0)->comment('取消フラグ');
            $table->string('comment',800)->nullable(true)->comment('コメント');
            $table->unsignedInteger('receive_account_cd')->nullable(false)->comment('予約担当者コード');
            $table->integer('use_point')->nullable(true)->default(0)->comment('Pポイント利用');
            $table->string('receive_lang',10)->nullable(true)->comment('予約時言語');
            $table->integer('receipt_no')->nullable(true)->comment('受付番号');
            $table->decimal('commission_sv',6,2)->nullable(false)->comment('手数料SV');
            $table->decimal('commission_payment',6,2)->nullable(false)->comment('手数料決済');
            $table->decimal('commission_ticket',6,2)->nullable(false)->comment('手数料発券');
            $table->decimal('commission_delivery',6,2)->nullable(false)->comment('手数料発券');
            $table->decimal('commission_sub',6,2)->nullable(false)->comment('手数料副券');
            $table->decimal('commission_uc',6,2)->nullable(false)->comment('手数料UC');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('GLID')->references('GLID')->on('GL_USER');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['GLID', 'reserve_no']);
        });

        Schema::create('GL_SEAT_SALE', function (Blueprint $table) {
            $table->increments('seat_sale_id')->nullable(false)->comment('座席販売id;PK');
            $table->unsignedInteger('alloc_seat_id')->nullable(false)->comment('座席id;FK');
            $table->unsignedInteger('schedule_id')->nullable(false)->comment('スケジュールID;FK');
            $table->unsignedInteger('ticket_class_id')->nullable(false)->comment('券種id;FK');
            $table->unsignedInteger('order_id')->nullable(false)->comment('予約_ID;FK,Unique');
            $table->unsignedInteger('reserve_seq')->nullable(false)->comment('予約連番;Unique');
            $table->smallInteger('seat_seq')->nullable(false)->comment('座席連番');
            $table->string('seat_class_name',50)->nullable(false)->comment('席種名');
            $table->string('seat_class_name_short',20)->nullable(true)->comment('席種名略称');
            $table->string('ticket_class_name',80)->nullable(false)->comment('券種名');
            $table->string('ticket_class_name_short',20)->nullable(true)->comment('券種名略称');
            $table->timestamp('temp_reserve_date')->nullable(false)->comment('仮予約日時');
            $table->unsignedInteger('temp_receive_account_cd')->nullable(false)->comment('仮予約担当者コード;FK');
            $table->tinyInteger('seat_status')->nullable(false)->comment('座席状態');
            $table->tinyInteger('member_kbn')->nullable(false)->default(0)->comment('会員区分');
            $table->string('member_id',100)->nullable(true)->comment('会員ＩＤ');
            $table->decimal('sale_price',8,2)->nullable(false)->default(0)->comment('販売料金');
            $table->tinyInteger('issue_flg')->nullable(false)->default(0)->comment('発行フラグ');
            $table->timestamp('issue_date')->nullable(true)->comment('発行日');
            $table->unsignedInteger('issue_account_cd')->nullable(true)->comment('発券担当者コード;FK');
            $table->tinyInteger('payment_flg')->nullable(false)->default(0)->comment('入金フラグ');
            $table->timestamp('payment_date')->nullable(true)->comment('入金日');
            $table->unsignedInteger('payment_account_cd')->nullable(true)->comment('入金担当者コード;FK');
            $table->string('ticket_barcode_no',26)->nullable(true)->comment('チケットバーコード番号');
            $table->smallInteger('pattern_code')->nullable(true)->default(1)->comment('パターンコード');
            $table->tinyInteger('visit_flg')->nullable(false)->default(0)->comment('来場フラグ  ');
            $table->timestamp('visit_date')->nullable(true)->comment('来場日');
            $table->tinyInteger('reserve_period_code')->nullable(false)->comment('予約期間コード');
            $table->decimal('commission_sv',6,2)->nullable(false)->comment('手数料SV');
            $table->decimal('commission_payment',6,2)->nullable(false)->comment('手数料決済');
            $table->decimal('commission_ticket',6,2)->nullable(false)->comment('手数料発券');
            $table->decimal('commission_delivery',6,2)->nullable(false)->comment('手数料発券');
            $table->decimal('commission_sub',6,2)->nullable(false)->comment('手数料副券');
            $table->decimal('commission_uc',6,2)->nullable(false)->comment('手数料UC');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('alloc_seat_id')->references('alloc_seat_id')->on('GL_SEAT');
            $table->foreign('ticket_class_id')->references('ticket_class_id')->on('GL_TICKET_CLASS');
            $table->foreign('order_id')->references('order_id')->on('GL_GENERAL_RESERVATION');
            $table->foreign('temp_receive_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->foreign('issue_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->foreign('payment_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['order_id','reserve_seq']);
        });

        Schema::create('GL_COMMISSION', function (Blueprint $table) {
            $table->increments('commission_code')->nullable(false)->comment('手数料コード;PK');
            $table->unsignedInteger('GLID')->nullable(false)->comment('GLコード ;FK,unique');
            $table->unsignedInteger('performance_id')->nullable(true)->comment('公演ID;FK,unique');
            $table->tinyInteger('comission_kbn')->nullable(false)->comment('手数料区分;unique');
            $table->tinyInteger('comission_unit')->nullable(false)->comment('手数料単位');
            $table->decimal('comission_percent',3,2)->nullable(false)->default(0)->comment('手数料%');
            $table->decimal('comission_fee',6,2)->nullable(false)->comment('手数料金額');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('GLID')->references('GLID')->on('GL_USER');
            $table->foreign('performance_id')->references('performance_id')->on('GL_PERFORMANCE');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['GLID', 'performance_id', 'comission_kbn']);
        });
        Schema::create('GL_NONRESERVED_STOCK', function (Blueprint $table) {
            $table->increments('stock_id')->nullable(false)->comment('在庫id;PK');
            $table->unsignedInteger('schedule_id')->nullable(false)->comment('スケジュールID;FK,unique');
            $table->unsignedInteger('seat_class_id')->nullable(false)->comment('席種id;FK,unique');
            $table->integer('stock_limit')->nullable(false)->comment('在庫上限');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('schedule_id')->references('schedule_id')->on('GL_SCHEDULE');
            $table->foreign('seat_class_id')->references('seat_class_id')->on('GL_SEAT_CLASS');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['schedule_id', 'seat_class_id']);
        });
        Schema::create('GL_TICKET_LAYOUT', function (Blueprint $table) {
            $table->increments('layout_id')->nullable(false)->comment('layout_id;PK');
            $table->unsignedInteger('performance_id')->nullable(false)->comment('公演ID;FK,unique');
            $table->unsignedInteger('schedule_id')->nullable(true)->comment('公演日程ID;FK,unique');
            $table->tinyInteger('ticket_kbn')->nullable(false)->comment('チケット区分;unique');
            $table->string('thumbnail',255)->nullable(false)->comment('サムネイル');
            $table->string('free_word',200)->nullable(false)->comment('自由表示欄');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('performance_id')->references('performance_id')->on('GL_PERFORMANCE');
            $table->foreign('schedule_id')->references('schedule_id')->on('GL_SCHEDULE');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['performance_id','schedule_id','ticket_kbn']);
        });

        Schema::create('GL_SALES_TERM', function (Blueprint $table) {
            $table->increments('term_id')->nullable(false)->comment('term_id;PK');
            $table->unsignedInteger('performance_id')->nullable(true)->comment('公演ID;FK,unique');
            $table->tinyInteger('reserve_period_code')->nullable(true)->comment('予約期間コード;unique');
            $table->tinyInteger('member_kbn')->nullable(true)->default(0)->comment('会員区分;unique');
            $table->tinyInteger('treat_kbn')->nullable(true)->comment('取扱区分;unique');
            $table->tinyInteger('treat_flg')->nullable(true)->default('0')->comment('取扱フラグ;unique');
            $table->tinyInteger('sales_kbn')->nullable(true)->default('1')->comment('販売区分;unique');
            $table->tinyInteger('reserve_st_kbn')->nullable(true)->default('2')->comment('予約開始区分');
            $table->dateTime('reserve_st_date')->nullable(false)->comment('予約開始日時');
            $table->smallInteger('reserve_st_days')->nullable(true)->default('60')->comment('予約開始日数');
            $table->time('reserve_st_time')->nullable(true)->default('10:00')->comment('予約開始時刻');
            $table->smallInteger('reserve_st_count')->nullable(true)->default('0')->comment('予約開始時間数');
            $table->tinyInteger('reserve_cl_kbn')->nullable(true)->default('2')->comment('予約締切区分');
            $table->dateTime('reserve_cl_date')->nullable(false)->comment('予約締切日時');
            $table->smallInteger('reserve_cl_days')->nullable(true)->default('10')->comment('予約締切日数');
            $table->time('reserve_cl_time')->nullable(true)->default('18:00')->comment('予約締切時刻');
            $table->smallInteger('reserve_cl_count')->nullable(true)->default('0')->comment('予約締切時間数');
            $table->smallInteger('reserve_period')->nullable(true)->comment('予約有効期間');
            $table->tinyInteger('seat_no_notice')->nullable(true)->default('0')->comment('座席番号通知');
            $table->string('sales_kbn_nm',20)->nullable(false)->comment('販売区分名称');
            $table->unsignedInteger('update_account_cd')->nullable(true)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('performance_id')->references('performance_id')->on('GL_PERFORMANCE');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['performance_id','reserve_period_code','member_kbn','treat_flg','sales_kbn'],'term_index_unique');
        });
        Schema::create('GL_PAY_PICK', function (Blueprint $table) {
            $table->increments('paynpick_id')->nullable(false)->comment('paynpick_id;PK');
            $table->unsignedInteger('term_id')->nullable(false)->comment('term_id;FK,unique');
            $table->tinyInteger('pay_method')->nullable(false)->comment('支払方法;unique');
            $table->tinyInteger('pickup_method')->nullable(false)->comment('引取方法;unique');
            $table->tinyInteger('treat_flg')->nullable(false)->default(0)->comment('取扱フラグ');
            $table->tinyInteger('treat_end_kbn')->nullable(false)->default(4)->comment('取扱終了区分');
            $table->dateTime('treat_end_date')->nullable(true)->comment('取扱終了日時');
            $table->smallInteger('treat_end_days')->nullable(false)->comment('取扱終了日数');
            $table->time('treat_end_time')->nullable(false)->default('18:00')->comment('取扱終了時刻');
            $table->smallInteger('pay_due_days')->nullable(false)->comment('支払期限日数');
            $table->tinyInteger('pickup_st_kbn')->nullable(false)->default(2)->comment('引取開始区分');
            $table->dateTime('pickup_st_date')->nullable(true)->comment('引取開始日時');
            $table->smallInteger('pickup_st_days')->nullable(false)->default(10)->comment('引取開始日数');
            $table->time('pickup_st_time')->nullable(false)->default('10:00')->comment('引取開始時刻');
            $table->smallInteger('pickup_st_count')->nullable(false)->default(1)->comment('引取開始時間数');
            $table->tinyInteger('pickup_due_kbn')->nullable(false)->default(0)->comment('引取締切区分');
            $table->dateTime('pickup_due_date')->nullable(true)->comment('引取締切日時');
            $table->smallInteger('pickup_due_days')->nullable(false)->default(0)->comment('引取締切日数');
            $table->time('pickup_due_time')->nullable(false)->default('18:00')->comment('引取締切時刻');
            $table->smallInteger('pickup_due_count')->nullable(false)->default(1)->comment('引取締切時間数');
            $table->smallInteger('receive_limit')->nullable(false)->default(10)->comment('予約制限枚数');
            $table->unsignedInteger('commission_code')->nullable(false)->comment('手数料コード;FK');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('term_id')->references('term_id')->on('GL_SALES_TERM');
            $table->foreign('commission_code')->references('commission_code')->on('GL_COMMISSION');
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['term_id','pay_method','pickup_method']);
        });

        // Schema::table('GL_USER', function (Blueprint $table) {
        //     $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
        //     $table->foreign('judge_account_cd')->references('account_cd')->on('GL_ACCOUNT');
        // });

        // Schema::create('GL_SEAT_AREA', function (Blueprint $table) {
        //     $table->integer('user_code')->nullable(false)->comment('ユーザーコード');
        //     $table->string('performance_code',12)->nullable(false)->comment('公演コード');
        //     $table->integer('member_kbn')->nullable(false)->default(0)->comment('会員区分');
        //     $table->string('performance_date')->nullable(false)->comment('公演日時');
        //     $table->integer('seat_seq',15)->nullable(false)->comment('座席連番');
        //     $table->string('update_account_cd')->nullable(false)->comment('更新担当者コード');
        //     $table->string('update_date',32)->nullable(false)->comment('更新日');
        //     $table->tinyInteger('counter_sale',19)->nullable(false)->comment('窓口販売  ');
        //     $table->tinyInteger('tel_sale')->nullable(false)->comment('電話販売  ');
        //     $table->tinyInteger('net_sale')->nullable(false)->comment('ネット販売 ');
        // });
        // Schema::create('GL_NONRESERVED_SEAT', function (Blueprint $table) {
        //     $table->integer('user_code')->nullable(false)->comment('ユーザーコード');
        //     $table->string('performance_code',12)->nullable(false)->comment('公演コード');
        //     $table->integer('member_kbn')->nullable(false)->default(0)->comment('会員区分');
        //     $table->string('performance_date',15)->nullable(false)->comment('公演日時');
        //     $table->integer('seat_class')->nullable(false)->comment('席種');
        //     $table->integer('stock_id')->nullable(false)->comment('在庫id');
        //     $table->string('update_account_cd',32)->nullable(false)->comment('更新担当者コード');
        //     $table->string('update_date',19)->nullable(false)->comment('更新日');
        // });

        // Schema::create('GL_CARD', function (Blueprint $table) {
        //     $table->string('shop_id',13)->nullable(false)->comment('ショップＩＤ');
        //     $table->string('order_id',27)->nullable(false)->comment('オーダーＩＤ');
        //     $table->string('status',20)->nullable(false)->comment('ステータス');
        //     $table->string('issue_date',38)->nullable(false)->comment('発行日');
        //     $table->string('transaction_id',32)->nullable(true)->comment('取引ＩＤ');
        //     $table->string('transaction_pass',38)->nullable(true)->comment('取引パスワード');
        //     $table->string('transaction_date',38)->nullable(true)->comment('取引日時');
        //     $table->tinyInteger('card_kbn')->nullable(false)->comment('カード区分');
        //     $table->integer('user_code')->nullable(false)->comment('ユーザーコード');
        //     $table->string('reserve_no',100)->nullable(true)->comment('予約番号');
        //     $table->string('credit_card_no',32)->nullable(true)->comment('クレジットカード番号;マスクデータ');
        //     $table->string('expire_date',14)->nullable(true)->comment('有効期限');
        //     $table->string('card_holder',320)->nullable(true)->comment('名義人名;マスクデータ');
        //     $table->string('dest_code',20)->nullable(true)->comment('仕向け先コード');
        //     $table->integer('price')->nullable(false)->comment('金額');
        //     $table->integer('return_value')->nullable(false)->comment('戻り値');
        //     $table->string('update_account_cd',32)->nullable(false)->comment('更新担当者コード');
        //     $table->string('update_date',19)->nullable(false)->comment('更新日');
        // });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('GL_CARD');
        // Schema::dropIfExists('GL_SEAT_AREA');
        // Schema::dropIfExists('GL_NONRESERVED_SEAT');

        Schema::dropIfExists('GL_PAY_PICK');
        Schema::dropIfExists('GL_SALES_TERM');
        Schema::dropIfExists('GL_TICKET_LAYOUT');
        Schema::dropIfExists('GL_NONRESERVED_STOCK');
        Schema::dropIfExists('GL_COMMISSION');
        Schema::dropIfExists('GL_SEAT_SALE');
        Schema::dropIfExists('GL_GENERAL_RESERVATION');
        Schema::dropIfExists('GL_PRICE');
        Schema::dropIfExists('GL_TICKET_CLASS');
        Schema::dropIfExists('GL_STAGE_SEAT');
        Schema::dropIfExists('GL_SEAT');
        Schema::dropIfExists('GL_RESERVE');
        Schema::dropIfExists('GL_SEAT_CLASS');
        Schema::dropIfExists('GL_HALL_SEAT');
        Schema::dropIfExists('GL_BLOCK');
        Schema::dropIfExists('GL_FLOOR');
        Schema::dropIfExists('GL_SEATMAP_PROFILE');
        Schema::dropIfExists('GL_HALL');
        Schema::dropIfExists('GL_SCHEDULE');
        Schema::dropIfExists('GL_STAGENAME');
        Schema::dropIfExists('GL_PERFORMANCE');
        Schema::dropIfExists('GL_ACCOUNT');
        Schema::dropIfExists('GL_USER');
    }
}

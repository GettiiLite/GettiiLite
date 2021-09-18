<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAccountCollation extends Migration
{     
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('GL_USER','user_id')) {
            DB::statement(" ALTER TABLE GL_USER CHANGE COLUMN `user_id` `user_id` VARCHAR(32) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_bin' NOT NULL COMMENT 'ユーザーID;Unique'");
            // Schema::table('GL_USER', function (Blueprint $table) {
            //     $table->string('user_id',32)->nullable(false)->comment('ユーザーID;Unique')->collation('utf8mb4_bin')->change();
            // });
        }

        if (Schema::hasColumn('GL_ACCOUNT','account_code')) {
            DB::statement(" ALTER TABLE GL_ACCOUNT CHANGE COLUMN `account_code` `account_code` VARCHAR(32) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_bin' NOT NULL COMMENT 'アカウントコード'");
            // Schema::table('GL_ACCOUNT', function (Blueprint $table) {
            //     $table->string('account_code',32)->nullable(false)->comment('アカウントコード')->collation('utf8mb4_bin')->change();
            // });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('GL_ACCOUNT','account_code')) {
            DB::statement(" ALTER TABLE GL_ACCOUNT CHANGE COLUMN `account_code` `account_code` VARCHAR(32) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NOT NULL COMMENT 'アカウントコード'");
            // Schema::table('GL_ACCOUNT', function (Blueprint $table) {
            //     $table->string('account_code',32)->nullable(false)->comment('アカウントコード')->collation('utf8mb4_unicode_ci')->change();
            // });
        }
        if (Schema::hasColumn('GL_USER','user_id')) {
            DB::statement(" ALTER TABLE GL_USER CHANGE COLUMN `user_id` `user_id` VARCHAR(32) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NOT NULL COMMENT 'ユーザーID;Unique'");
            // Schema::table('GL_USER', function (Blueprint $table) {
            //     $table->string('user_id',32)->nullable(false)->comment('ユーザーID;Unique')->collation('utf8mb4_unicode_ci')->change();
            // });
        }

    }
}

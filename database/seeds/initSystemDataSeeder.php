<?php

use Illuminate\Database\Seeder;

class initSystemDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('GL_GSSITE')->insert([
            'aid' =>  'gettiis',
            'xcdkey' => 'test_key',
            'url_gs' => 'https://www.dev.gettiis.com',
            'url_api' => 'http://api.dev.gettiis.com',
        ]);
        
        DB::table('GL_USER')->insert([
            'user_code' => 199999,
            'user_id' => 'gtl_super_admin',
            'user_status' => 1,
            'contract_name' => 'Link Station Taiwan Co.',
            'contract_name_kana' => 'LINK SATATION TAIWAN Co.',
            'disp_name' => 'Link Station Taiwan',
            'GETTIIS_disp_flg' => 0,
            'user_kbn' => 1,
            'department' => '技術部',
            'contact_person' => 'James Lai',
            'home_page' => 'www.linkst-tw.com',
            // 'logo_image' => $faker->   , //varchar(255),
            'post_code' => '11493',
            'address' => '台北市內湖區洲子街79號9樓之1',
            'tel_num' => '8862228271939',
            'fax_num' => '8862228275939',
            'mail_address' => 'info@linkst-tw.com',
            // 'bank_name' => $faker->bank, //varchar(60)
            // 'bank_name' => $faker->company, //varchar(60)
            // 'branch_name' => $faker->numerify('branch###'), //varchar(60)
            // 'account_kbn' => $faker->randomElement($array = array (1,2)), //int(1)
            // 'account_num' => $faker->numerify('#######'), //int(7)
            // 'account_name' => $faker->company, //varchar(40)
            // 'id_image' => $faker->   , //text
            // 'introduction_text' => $faker->text($maxNbChars = 200), //text
            'app_date' => date("Y-m-d H:i:s"),
            'request_date' => date("Y-m-d H:i:s"),
            'judge_account_cd' => 0,
            'judgement_date' => date("Y-m-d H:i:s"),
            'event_publishable' => '1',
            'SID' => 1,
        ]);

        DB::table('GL_ACCOUNT')->insert([
            'GLID' => 1, 
            'account_number' => 0, 
            'account_code' => 'admin', 
            'password' => '$2y$10$byyBAUvKCCm.UIErlCN5BekEw8kmdh4wdvn/aQoAvypeRXIZe/E/m', 
            'expire_date' => '9999-12-31',
            'mail_address' => 'superadmin@compName.com', 
            'profile_info_flg' => 2, 
            'event_info_flg' => 2, 
            'sales_info_flg' => 2, 
            'personal_info_flg' => 0, 
            'status' => 1, 
        ]);

        DB::table('GL_COMMISSION_CLIENT')->insert([
            'GLID' => 1, 
            'commission_type' => 0, 
            'apply_date' => '2019/10/01', 
            'rate' => 10, 
            'amount' => 0, 
            'delete_flg' => 0, 
            'update_account_cd' => 1, 
        ]);
        
    }
}

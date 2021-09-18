<?php

use Faker\Generator as Faker;

$factory->define(App\GL_User::class, function (Faker $faker) {
    $company = $faker->company;
    return [
        //
        'user_code' => $faker->unique()->numberBetween($min = 100000, $max = 199999), //int(10)
        'user_id' => $faker->unique()->userName, //varchar(32)
        'user_status' => $faker->randomElement($array = array (0,1,2)), //int(1)
        'contract_name' => $company, //varchar(80)
        'contract_name_kana' => $company, //varchar(160)
        'disp_name' => $company, //varchar(80)
        'GETTIIS_disp_flg' => $faker->randomElement($array = array (0,1)), //int(1)
        'user_kbn' => $faker->randomElement($array = array (0,1)), //int(1)
        'department' => $faker->jobTitle, //varchar(80)
        'contact_person' => $faker->name, //varchar(80)
        'home_page' => $faker->url, //varchar(200)
        // 'logo_image' => $faker->   , //varchar(255)
        'post_code' => $faker->postcode, //varchar(8)
        'address' => $faker->address, //varchar(100)
        'tel_num' => $faker->phoneNumber, //varchar(26)
        'fax_num' => $faker->phoneNumber, //varchar(26)
        'mail_address' => $faker->email, //varchar(200)
        // 'bank_name' => $faker->bank, //varchar(60)
        'bank_name' => $faker->company, //varchar(60)
        'branch_name' => $faker->numerify('branch###'), //varchar(60)
        'account_kbn' => $faker->randomElement($array = array (1,2)), //int(1)
        'account_num' => $faker->numerify('#######'), //int(7)
        'account_name' => $company, //varchar(40)
        // 'id_image' => $faker->   , //text
        'introduction_text' => $faker->text($maxNbChars = 200), //text
        'app_date' => $faker->dateTimeBetween($startDate = '-100 days', $endDate = 'now', $timezone = null), //TIMESTAMPS
        'request_date' => $faker->dateTimeBetween($startDate = '-100 days', $endDate = 'now', $timezone = null), //TIMESTAMPS
        'judge_account_cd' => $faker->numberBetween($min = 100000, $max = 199999)  , //unsignint(10)
        'judgement_date' => $faker->dateTimeBetween($startDate = '-100 days', $endDate = 'now', $timezone = null), //TIMESTAMPS
    ];
});

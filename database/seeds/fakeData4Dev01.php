<?php

use Illuminate\Database\Seeder;
use App\GL_User;

class fakeData4Dev01 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('GL_USER')->truncate();
        GL_User::unguard();
        factory(GL_User::class, 10)->create();
        GL_User::reguard();
    }
}

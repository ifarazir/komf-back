<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'fname' => 'فراز',
            'lname' => 'صدری',
            'email'=>'ifarazir@gmail.com',
            'password' => Hash::make('faraz13791379'),
            'phone'=>'09392676126',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);


        DB::table('roles')->insert([
            'name' => 'admin',
            'persian_name' => 'مدیر اصلی',
        ]);

        DB::table('role_user')->insert([
            'user_id'=>1,
            'role_id'=>1,
        ]);
    }
}

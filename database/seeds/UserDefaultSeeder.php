<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class UserDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

	$faker = Faker::create();

         DB::table('users')->insert([
            'name' => "UserLambda",
            'email' => 'user@user.com',
            'password' => bcrypt('password'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'permission_level' => 1,
        ]);

	DB::table('users')->insert([
            'name' => "Editor",
            'email' => 'editor@editor.com',
            'password' => bcrypt('password'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'permission_level' => 2,
        ]);

        DB::table('users')->insert([
            'name' => "Admin",
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'permission_level' => 3,
        ]);
    }
}

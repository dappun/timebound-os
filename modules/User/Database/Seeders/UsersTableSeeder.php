<?php namespace TB\User\Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert([
        	'id' => 1,
            'name' => 'admin',
            'email' => 'hello@koodi.ph',
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'password' => bcrypt('admin'),
            'verified' => 1,
            'status_id' => 1,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        \DB::table('users')->insert([
            'id' => 2,
            'name' => 'test_pm',
            'email' => 'hello+pm@koodi.ph',
            'first_name' => 'Joe',
            'last_name' => 'David',
            'password' => bcrypt('admin'),
            'verified' => 1,
            'status_id' => 1,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        \DB::table('users')->insert([
            'id' => 3,
            'name' => 'test_client',
            'email' => 'hello+client@koodi.ph',
            'first_name' => 'Maria',
            'last_name' => 'Scott',
            'password' => bcrypt('admin'),
            'verified' => 1,
            'status_id' => 1,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        \DB::table('users')->insert([
            'id' => 4,
            'name' => 'test_member',
            'email' => 'hello+member@koodi.ph',
            'first_name' => 'Alex',
            'last_name' => 'De Jesus',
            'password' => bcrypt('admin'),
            'verified' => 1,
            'status_id' => 1,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
    }
}

<?php namespace TB\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserDatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// Model::unguard();
		
		$this->call("\TB\User\Database\Seeders\UsersTableSeeder");
		$this->call("\TB\User\Database\Seeders\RolesTableSeeder");
		$this->call("\TB\User\Database\Seeders\UserRolesTableSeeder");
	}

}
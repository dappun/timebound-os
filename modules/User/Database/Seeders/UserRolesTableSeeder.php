<?php namespace TB\User\Database\Seeders;

use Illuminate\Database\Seeder;
use TB\User\Entities\Role;
use TB\User\Entities\User;
use TB\User\Entities\Permission;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Admin
    	$user = User::find(1);

    	$role1 = Role::find('1');
    	$role2 = Role::find('2');
    	$role3 = Role::find('3');
    	$role4 = Role::find('4');

		$user->attachRole($role1);
		$user->attachRole($role2);
		$user->attachRole($role3);
		$user->attachRole($role4);

		// PM
    	$user = User::find(2);
		$user->attachRole($role2);

		// Client
		$user = User::find(3);
		$user->attachRole($role3);

		// member
		$user = User::find(4);
		$user->attachRole($role4);

		// $perm = Permission::find(1);
		// $user->attachPermission($perm);

		// $perm = Permission::find(2);
		// $user->attachPermission($perm);

		// $perm = Permission::find(3);
		// $user->attachPermission($perm);

		// $perm = Permission::find(4);
		// $user->attachPermission($perm);
    }
}

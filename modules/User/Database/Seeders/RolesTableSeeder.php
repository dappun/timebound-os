<?php namespace TB\User\Database\Seeders;

use Illuminate\Database\Seeder;
use TB\User\Entities\Role;
use TB\User\Entities\User;
use TB\User\Entities\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$roles = [
    		['name' => 'admin', 'display_name' => 'Admin', 'description' => ''],
    		['name' => 'project-manager', 'display_name' => 'Project Manager', 'description' => ''],
    		['name' => 'client', 'display_name' => 'Client & Stakeholder', 'description' => ''],
    		['name' => 'member', 'display_name' => 'Team Member', 'description' => ''],
    	];

    	foreach ($roles as $info) {
    		$role = new Role();
			$role->name         = $info['name'];
			$role->display_name = $info['display_name'];
			$role->description  = $info['description'];
			$role->save();
    	}

		// $permissions = [
		// 	['name' => 'manage-projects', 'display_name' => 'Manage projects', 'description' => ''],
		// 	['name' => 'manage-users', 'display_name' => 'Manage users', 'description' => ''],
		// 	['name' => 'manage-clients', 'display_name' => 'Manage clients', 'description' => ''],
		// 	['name' => 'manage-reports', 'display_name' => 'Manager reports', 'description' => ''],
		// ];

		// foreach ($permissions as $permission) {
		// 	$perm = new Permission();
		// 	$perm->name         = $permission['name'];
		// 	$perm->display_name = $permission['display_name'];
		// 	$perm->description  = $permission['description'];
		// 	$perm->save();
		// }
    }
}

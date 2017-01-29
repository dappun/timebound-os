<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use TB\User\Entities\User;
use TB\User\Entities\Role;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tbimport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fp = fopen( __DIR__ . '/users.csv', 'r' );
            
        $column = [];
            
        $ctr = 0;
        while (($lineArray = fgetcsv($fp, 4000)) !== FALSE) { 
            if ($ctr == 0) {
                $ctr = 1; continue;
            }

            $fname = $lname = '';
            $name = explode(' ', $lineArray[4]);
            if (count($name) == 2) {
                $fname = $name[0];
                $lname = $name[1];
            } else {
                $fname = $lineArray[4];
            }

            $user = User::create([
                'id'            => $lineArray[0],
                'name'          => $lineArray[1],
                'email'         => $lineArray[2],
                'first_name'    => $fname,
                'last_name'     => $lname,
                'password'      => $lineArray[3],
                'profile_image' => $lineArray[12],
                'verified'      => $lineArray[10],
                'verification_token'      => $lineArray[11],
                'status_id'     => $lineArray[5],
                'created_at'    => $lineArray[8],
                'updated_at'    => $lineArray[9],
            ]);

            if ($lineArray[1] == 'admin') {
                $role1 = Role::find('1');
                $user->attachRole($role1);
                $this->comment('added admin');
            } else {
                $role4 = Role::find('4');
                $user->attachRole($role4);
                $this->comment('added user ' . $lineArray[1]);
            }   
        }
        fclose($fp);
    }
}

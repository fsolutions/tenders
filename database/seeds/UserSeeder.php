<?php

use App\Role;
use App\User;
use App\Permission;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $developer = Role::where('slug', 'administrator')->first();
        $manager = Role::where('slug', 'company')->first();
        $manager = Role::where('slug', 'worker')->first();
        // $createTasks = Permission::where('slug', 'create-tasks')->first();
        // $manageUsers = Permission::where('slug', 'manage-users')->first();

        // $user1 = User::find(1);
        $user1 = new User();
        $user1->name = 'Administration';
        $user1->email = 'info@gravescare.com';
        $user1->phone = '+79150670468';
        $user1->password = bcrypt('addqdd55');
        $user1->save();
        $user1->roles()->attach($developer);
        // $user1->permissions()->attach($createTasks);
    }
}

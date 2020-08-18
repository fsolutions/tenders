<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manager = new Role();
        $manager->name = 'Admin';
        $manager->slug = 'administrator';
        $manager->save();
        $developer = new Role();
        $developer->name = 'Ритуальные агентства';
        $developer->slug = 'company';
        $developer->save();
        $developer = new Role();
        $developer->name = 'Частные исполнители';
        $developer->slug = 'worker';
        $developer->save();
        $developer = new Role();
        $developer->name = 'Администратор кладбища';
        $developer->slug = 'graves-admin';
        $developer->save();
        $developer = new Role();
        $developer->name = 'Администратор церкви';
        $developer->slug = 'church-admin';
        $developer->save();
        $developer = new Role();
        $developer->name = 'Гранитная мастерская';
        $developer->slug = 'company-granit';
        $developer->save();
    }
}

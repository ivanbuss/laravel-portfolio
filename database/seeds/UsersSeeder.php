<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Role;


class UsersSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $adminName = 'admin';
        $admin = User::create([
            'name' => $adminName,
            'password' => bcrypt('admin'), // TODO: make strong password
            'email' => 'admin@mail.com', // TODO: replace with existing email
            'uid' => 0,
        ]);

        // Roles
        $adminRole = new Role();
        $adminRole->name         = Role::ADMIN;
        $adminRole->display_name = 'Administrator'; // optional
        $adminRole->description  = 'User is allowed to manage and edit everything on the project'; // optional
        $adminRole->save();
        $admin->attachRole($adminRole);

        $admin = new Role();
        $admin->name         = Role::CONTRIBUTOR;
        $admin->display_name = 'Contributor'; // optional
        $admin->description  = 'User is allowed to add and decks'; // optional
        $admin->save();
    }
}

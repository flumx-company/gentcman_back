<?php
namespace Database\Seeders;

use Gentcmen\Models\BonusPoint;
use Gentcmen\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Gentcmen\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::find(Role::IS_ADMIN);
        $userRole = Role::find(Role::IS_USER);

        User::factory(1)
            ->create(array_merge([
                'name' => 'admin',
                'password' => Hash::make('admin'),
                'email' => 'admin@gmail.com',
            ]))->each(function ($admin) use ($adminRole) {
                $bonusPoint = BonusPoint::factory(1)->make();
                $admin->roles()->attach($adminRole);
                $admin->bonusPoints()->saveMany($bonusPoint);
            });

        //User::factory(2)
        //    ->create()
        //    ->each(function ($user) use ($userRole) {
        //        $user->roles()->attach($userRole);
        //    });
    }
}

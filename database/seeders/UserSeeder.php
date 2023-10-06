<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Enums\RoleName;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Leonardo Gomes',
            'email' => 'test@abc.com',
            'password' => bcrypt('12345678'),
        ])->roles()
            ->attach(
                Role::whereName(RoleName::ADMIN->value)->first()
            );

        User::factory(10)
            ->create([
                'password' => bcrypt('12345678'),
                'email_verified_at' => null,
                'remember_token' => null,
            ])
            ->each(
                fn ($user) => $user->roles()->attach(Role::whereName(RoleName::STAFF->value)->first())
            );
    }
}

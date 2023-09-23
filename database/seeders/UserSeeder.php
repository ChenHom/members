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
            ->sync(
                Role::where('name', RoleName::ADMIN->value)->first()
            );
    }
}

<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actions = [
            'viewAny',
            'view',
            'create',
            'update',
            'delete',
            'restore',
            'forceDelete',
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => "user.{$action}"]);
        }
    }
}

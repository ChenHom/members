<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Collection;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createAdminRole();
        $this->createStaffRole();
    }

    protected function createAdminRole(): void
    {
        $this->createRole(
            RoleName::ADMIN,
            Permission::query()
                ->where('name', 'like', 'user.%')
                ->pluck('id')
        );
    }

    protected function createStaffRole(): void
    {
        $this->createRole(
            RoleName::STAFF,
            Permission::query()
                ->whereIn('name', ['user.viewAny', 'user.view', 'user.update'])
                ->pluck('id')
        );
    }

    protected function createRole(RoleName $roleName, Collection $permissionsId): void
    {
        Role::create([
            'name' => $roleName->value,
        ])->permissions()->sync($permissionsId);
    }
}

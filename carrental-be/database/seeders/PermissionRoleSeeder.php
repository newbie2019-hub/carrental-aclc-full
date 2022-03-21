<?php

namespace Database\Seeders;

use App\Models\RolePermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RolePermission::create([
            'role_id' => 1,
            'permission_id' => 1
        ]);

        RolePermission::create([
            'role_id' => 2,
            'permission_id' => 2
        ]);

        RolePermission::create([
            'role_id' => 3,
            'permission_id' => 3
        ]);
    }
}

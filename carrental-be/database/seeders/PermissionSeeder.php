<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [
            [
                'permission' => 'is_admin'
            ],
            [
                'permission' => 'is_user'
            ],
            [
                'permission' => 'branch_admin'
            ],
        ];

        foreach($permission as $perm){
            Permission::create($perm);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::first();

        $permissions = [
            [
                'name' => 'Access',
                'slug' => 'access',
                'description' => 'Permission to access records',
                'is_default' => true,
                'module' => '1',
                'created_by_id' => $adminUser->id,
                'updated_by_id' => $adminUser->id,
            ],
            [
                'name' => 'Create',
                'slug' => 'create',
                'description' => 'Permission to create records',
                'is_default' => true,
                'module' => '1',
                'created_by_id' => $adminUser->id,
                'updated_by_id' => $adminUser->id,
            ],
            [
                'name' => 'Read',
                'slug' => 'read',
                'description' => 'Permission to read records',
                'is_default' => true,
                'module' => '1',
                'created_by_id' => $adminUser->id,
                'updated_by_id' => $adminUser->id,
            ],
            [
                'name' => 'Update',
                'slug' => 'update',
                'description' => 'Permission to update records',
                'is_default' => true,
                'module' => '1',
                'created_by_id' => $adminUser->id,
                'updated_by_id' => $adminUser->id,
            ],
            [
                'name' => 'Delete',
                'slug' => 'delete',
                'description' => 'Permission to delete records',
                'is_default' => true,
                'module' => '1',
                'created_by_id' => $adminUser->id,
                'updated_by_id' => $adminUser->id,
            ],
            [
                'name' => 'Print',
                'slug' => 'print',
                'description' => 'Permission to print records',
                'is_default' => true,
                'module' => '1',
                'created_by_id' => $adminUser->id,
                'updated_by_id' => $adminUser->id,
            ],
            [
                'name' => 'Download',
                'slug' => 'download',
                'description' => 'Permission to download records',
                'is_default' => true,
                'module' => '1',
                'created_by_id' => $adminUser->id,
                'updated_by_id' => $adminUser->id,
            ],
            [
                'name' => 'Export',
                'slug' => 'export',
                'description' => 'Permission to export records',
                'is_default' => true,
                'module' => '1',
                'created_by_id' => $adminUser->id,
                'updated_by_id' => $adminUser->id,
            ],
            [
                'name' => 'See All',
                'slug' => 'see_all',
                'description' => 'Permission to view all records',
                'is_default' => true,
                'module' => '1',
                'created_by_id' => $adminUser->id,
                'updated_by_id' => $adminUser->id,
            ],
        ];

        Permission::insert($permissions);
    }
}

<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = [
            [
                'name' => 'Home',
                'description' => 'Entity representing system Home.',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'name' => 'User',
                'description' => 'Entity representing system users.',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'name' => 'Profile',
                'description' => 'Entity representing user profiles.',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'name' => 'Menu',
                'description' => 'Entity representing system menus.',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'name' => 'Entity',
                'description' => 'Entity representing system entities.',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'name' => 'Role',
                'description' => 'Entity representing system roles.',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'name' => 'Configuration',
                'description' => 'Entity representing system configurations.',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
        ];

        DB::table('entities')->insert($entities);
    }
}

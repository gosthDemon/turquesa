<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $adminId = 1;

        // Obtén los ids de las entidades necesarias
        $homeEntityId = DB::table('entities')->where('name', 'Home')->first()->id;
        $userEntityId = DB::table('entities')->where('name', 'User')->first()->id;
        $profileEntityId = DB::table('entities')->where('name', 'Profile')->first()->id;
        $configEntityId = DB::table('entities')->where('name', 'Configuration')->first()->id;

        // Menús principales
        $dashboardId = DB::table('menus')->insertGetId([
            'label'       => 'Dashboard',
            'path'        => 'dashboard/index',
            'icon'        => 'fas fa-home',
            'order'       => 1,
            'is_routable' => true,
            'parent_id'   => null,
            'entity_id'   => $homeEntityId,
            'report_name' => 'home_dashboard_report',
            'created_by_id' => $adminId,
            'updated_by_id' => $adminId,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);

        $configId = DB::table('menus')->insertGetId([
            'label'       => 'Config',
            'path'        => '',
            'icon'        => 'fas fa-cog',
            'order'       => 2,
            'is_routable' => false,
            'parent_id'   => null,
            'entity_id'   => $configEntityId,
            'report_name' => 'configuration_report',
            'created_by_id' => $adminId,
            'updated_by_id' => $adminId,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);

        $profileId = DB::table('menus')->insertGetId([
            'label'       => 'Profile',
            'path'        => 'profile/index',
            'icon'        => 'fas fa-user',
            'order'       => 3,
            'is_routable' => true,
            'parent_id'   => null,
            'entity_id'   => $profileEntityId,
            'report_name' => 'profile_report',
            'created_by_id' => $adminId,
            'updated_by_id' => $adminId,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);

        DB::table('menus')->insert([
            'label'       => 'Users',
            'path'        => 'User',
            'icon'        => 'fas fa-user',
            'order'       => 4,
            'is_routable' => true,
            'parent_id'   => $configId,
            'entity_id'   => $userEntityId,
            'report_name' => 'users_report',
            'created_by_id' => $adminId,
            'updated_by_id' => $adminId,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
    }
}

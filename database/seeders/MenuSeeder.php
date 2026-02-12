<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // 1. INSERT PARENT MENU
        // =========================
        $parents = [
            ['kode_menu' => 'A000', 'title' => 'Dashboards',  'icon' => 'ri-home-smile-line', 'path' => '/dashboard', 'order_no' => 1],
            ['kode_menu' => 'B000', 'title' => 'Master Data', 'icon' => 'ri-file-copy-line',  'path' => null,         'order_no' => 1],
            ['kode_menu' => 'C000', 'title' => 'Transaksi',   'icon' => 'ri-layout-4-line',   'path' => null,         'order_no' => 1],
            ['kode_menu' => 'Z000', 'title' => 'Maintenance', 'icon' => 'ri-layout-4-line',   'path' => null,         'order_no' => 1],
        ];

        foreach ($parents as $menu) {
            DB::table('menus')->insert([
                'kode_menu' => $menu['kode_menu'],
                'title'     => $menu['title'],
                'icon'      => $menu['icon'],
                'path'      => $menu['path'],
                'parent_id' => null,
                'order_no'  => $menu['order_no'],
                'is_active' => true,
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);
        }

        // =========================
        // 2. MAP KODE → ID
        // =========================
        $parentMap = DB::table('menus')
            ->whereNull('parent_id')
            ->pluck('id', 'kode_menu'); // ['B000' => 2]

        // =========================
        // 3. INSERT CHILD MENU
        // =========================
        $children = [
            ['kode_menu'=>'B001','title'=>'Group Arisan','path'=>'/MasterData/GroupArisan','parent'=>'B000','order'=>2],
            ['kode_menu'=>'B002','title'=>'Anggota Arisan','path'=>'/MasterData/Participants','parent'=>'B000','order'=>3],
            ['kode_menu'=>'B003','title'=>'User','path'=>'/MasterData/User','parent'=>'B000','order'=>4],

            ['kode_menu'=>'C001','title'=>'Putaran','path'=>'/transaksi/putaran','parent'=>'C000','order'=>2],
            ['kode_menu'=>'C002','title'=>'Pembayaran','path'=>'/transaksi/pembayaran','parent'=>'C000','order'=>3],

            ['kode_menu'=>'Z001','title'=>'Menu','path'=>'/maintenance/menu','parent'=>'Z000','order'=>2],
        ];

        foreach ($children as $menu) {
            DB::table('menus')->insert([
                'kode_menu' => $menu['kode_menu'],
                'title'     => $menu['title'],
                'icon'      => null,
                'path'      => $menu['path'],
                'parent_id' => $parentMap[$menu['parent']] ?? null,
                'order_no'  => $menu['order'],
                'is_active' => true,
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);
        }
    }
}

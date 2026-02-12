<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ambil mapping kode_menu => id
        $menus = DB::table('menus')->pluck('id', 'kode_menu');

        $data = [

            // ======================
            // ADMIN (FULL ACCESS)
            // ======================
            ['kode' => 'A000', 'role' => 'admin'],
            ['kode' => 'B000', 'role' => 'admin'],
            ['kode' => 'B001', 'role' => 'admin'],
            ['kode' => 'B002', 'role' => 'admin'],
            ['kode' => 'B003', 'role' => 'admin'],
            ['kode' => 'C000', 'role' => 'admin'],
            ['kode' => 'C001', 'role' => 'admin'],
            ['kode' => 'C002', 'role' => 'admin'],
            ['kode' => 'Z000', 'role' => 'admin'],
            ['kode' => 'Z001', 'role' => 'admin'],

            // ======================
            // PETUGAS
            // ======================
            ['kode' => 'A000', 'role' => 'petugas'],
            ['kode' => 'B000', 'role' => 'petugas'],
            ['kode' => 'B001', 'role' => 'petugas'],
            ['kode' => 'B002', 'role' => 'petugas'],
            ['kode' => 'C000', 'role' => 'petugas'],
            ['kode' => 'C001', 'role' => 'petugas'],
            ['kode' => 'C002', 'role' => 'petugas'],

            // ======================
            // PESERTA
            // ======================
            ['kode' => 'A000', 'role' => 'peserta'],
            ['kode' => 'C000', 'role' => 'peserta'],
            ['kode' => 'C002', 'role' => 'peserta'],
        ];

        foreach ($data as $row) {
            if (!isset($menus[$row['kode']])) {
                continue;
            }

            DB::table('menu_permissions')->insertOrIgnore([
                'menu_id'    => $menus[$row['kode']],
                'role'       => $row['role'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

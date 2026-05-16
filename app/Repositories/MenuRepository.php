<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class MenuRepository
{
    public function getMenu($role)
    {
        $sql = "
           SELECT m.*
            FROM menus m
            JOIN menu_permissions p ON m.id = p.menu_id
            WHERE p.role = ?
              AND m.is_active = 1
            ORDER BY m.parent_id, m.order_no, m.kode_menu
        ";

        return DB::select($sql, [$role]);
    }
}
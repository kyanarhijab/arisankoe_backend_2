<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->user()->role;

        $sql = "
            SELECT m.*
            FROM menus m
            JOIN menu_permissions p ON m.id = p.menu_id
            WHERE p.role = ?
              AND m.is_active = 1
            ORDER BY m.parent_id, m.order_no, m.kode_menu
        ";

        $menus = DB::select($sql, [$role]);

        return response()->json([
            'success' => true,
            'role' => $role,
            'data' => $menus
        ]);
    }
}

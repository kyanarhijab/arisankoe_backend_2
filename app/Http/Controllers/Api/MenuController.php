<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\MenuRepository;

class MenuController extends Controller
{

    protected $menuRepo;

    public function __construct(MenuRepository $menuRepo)
    {
        $this->menuRepo = $menuRepo;
    }

    public function index(Request $request)
    {
        $role = $request->user()->role;

        $data = $this->menuRepo->getMenu($role);

        return response()->json([
            'success' => true,
            'role' => $role,
            'data' => $data
        ]);
    }
}

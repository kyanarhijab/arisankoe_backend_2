<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\GroupRepository;
use Illuminate\Support\Facades\Auth;


class GroupController extends Controller
{

    protected $groupRepo;

    public function __construct(GroupRepository $groupRepo)
    {
        $this->groupRepo = $groupRepo;
    }

    // ======================
    // GET LIST GROUP
    // ======================
    public function index()
    {

        $data = $this->groupRepo->getAll();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // ======================
    // CREATE GROUP
    // ======================
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:arisan_groups,kode',
            'name' => 'required|string',
            'total_rounds' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
        ], [
            'kode.required' => 'Kode arisan wajib diisi',
            'kode.unique' => 'Kode arisan sudah dipakai, gunakan kode lain'
        ]);

        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $username = $user?->username;

        $inserted = $this->groupRepo->insertGroup(
            $request->kode,
            $request->name,
            $request->description,
            $request->total_rounds,
            $request->amount,
            $request->start_date,
            $username
        );

        return response()->json([
            'message' => 'Group arisan berhasil dibuat'
        ], 201);
    }

    // ======================
    // UPDATE GROUP
    // ======================
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'total_rounds' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'status' => 'required|in:active,finished',
        ]);

        $this->groupRepo->updateGroup(
            $id,
            $request->name,
            $request->description,
            $request->total_rounds,
            $request->amount,
            $request->start_date,
            $request->status
        );

        return response()->json([
            'message' => 'Group arisan berhasil diupdate'
        ]);
    }

    // ======================
    // DELETE GROUP
    // ======================
    public function destroy($id)
    {
        $this->groupRepo->delete($id);

        return response()->json([
            'message' => 'Group arisan berhasil dihapus'
        ]);
    }

    public function exportExcel(Request $request)
    {
        $group_id = $request->group_id;

        $data = $this->groupRepo->getExportExcel($group_id);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    // ======================
    // GET LIST GROUP
    // ======================
    public function index()
    {
        $data = DB::select("
            SELECT
                id,
                kode,
                name,
                description,
                total_rounds,
                amount,
                start_date,
                status,
                created_at
            FROM arisan_groups
            ORDER BY created_at DESC
        ");

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
        ]);

        DB::insert("
            INSERT INTO arisan_groups
            (kode, name, description, total_rounds, amount, start_date, status, created_by, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, 'active', ?, NOW(), NOW())
        ", [
            $request->kode,
            $request->name,
            $request->description,
            $request->total_rounds,
            $request->amount,
            $request->start_date,
            auth()->user()->username ?? null
        ]);

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

        DB::update("
            UPDATE arisan_groups
            SET
                name = ?,
                description = ?,
                total_rounds = ?,
                amount = ?,
                start_date = ?,
                status = ?,
                updated_at = NOW()
            WHERE id = ?
        ", [
            $request->name,
            $request->description,
            $request->total_rounds,
            $request->amount,
            $request->start_date,
            $request->status,
            $id
        ]);

        return response()->json([
            'message' => 'Group arisan berhasil diupdate'
        ]);
    }

    // ======================
    // DELETE GROUP
    // ======================
    public function destroy($id)
    {
        DB::delete("
            DELETE FROM arisan_groups
            WHERE id = ?
        ", [$id]);

        return response()->json([
            'message' => 'Group arisan berhasil dihapus'
        ]);
    }
}

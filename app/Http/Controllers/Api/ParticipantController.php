<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipantController extends Controller
{
    /**
     * GET /participants?group_id=1
     * List peserta dalam satu group
     */
    public function index(Request $request)
    {
        /*
        $request->validate([
            'group_id' => 'required'
        ]);
        */

        $data = DB::select("
            SELECT 
                p.id,
                p.user_id,
                u.name AS user_name,
                p.group_id,
                ag.name as group_name,
                p.join_date,
                p.status
            FROM participants p
            JOIN users u ON u.username = p.user_id
            JOIN arisan_groups ag on ag.kode = p.group_id
            WHERE p.group_id = ?
            ORDER BY p.user_id
        ", [$request->group_id]);

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * POST /participants
     * Tambah peserta ke group
     */
    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required',
            'users'    => 'required|array|min:1',
        ]);

        $inserted = 0;

        foreach ($request->users as $userId) {

            // cek apakah sudah join
            $exists = DB::selectOne("
                SELECT id FROM participants
                WHERE user_id = ? AND group_id = ?
            ", [$userId, $request->group_id]);

            if ($exists) {
                continue; // skip kalau sudah ada
            }

            DB::insert("
                INSERT INTO participants (user_id, group_id, join_date, status)
                VALUES (?, ?, CURDATE(), 'active')
            ", [$userId, $request->group_id]);

            $inserted++;
        }

        if ($inserted === 0) {
            return response()->json([
                'message' => 'Semua user sudah terdaftar di group ini'
            ], 422);
        }

        return response()->json([
            'message' => "$inserted peserta berhasil ditambahkan"
        ]);
    }

    /**
     * PUT /participants/{id}
     * Update status (active / resign)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,resign'
        ]);

        DB::update("
            UPDATE participants 
            SET status = ?
            WHERE id = ?
        ", [$request->status, $id]);

        return response()->json([
            'message' => 'Status peserta berhasil diperbarui'
        ]);
    }

    /**
     * DELETE /participants/{id}
     * Hapus peserta dari group
     */
    public function destroy($id)
    {
        DB::delete("
            DELETE FROM participants WHERE id = ?
        ", [$id]);

        return response()->json([
            'message' => 'Peserta berhasil dihapus'
        ]);
    }
}

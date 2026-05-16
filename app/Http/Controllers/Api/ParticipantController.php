<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ParticipantRepository;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{

     protected $participantRepo;

    public function __construct(ParticipantRepository $participantRepo)
    {
        $this->participantRepo = $participantRepo;
    }

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

        $data = $this->participantRepo->getAll($request->group_id);

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

            if ($this->participantRepo->addParticipant($userId, $request->group_id)) {
                $inserted++;
            }

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

        $updated = $this->participantRepo->updateStatus(
            $id,
            $request->status
        );

        if (!$updated) {
            return response()->json([
                'message' => 'Peserta tidak ditemukan'
            ], 404);
        }

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

        $deleted = $this->participantRepo->deleted(
            $id
        );

        return response()->json([
            'message' => 'Peserta berhasil dihapus'
        ]);
    }
}

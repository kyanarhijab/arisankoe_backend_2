<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\RoundRepository;
use Illuminate\Support\Facades\Auth;

class RoundController extends Controller
{

    protected $roundRepo;

    public function __construct(RoundRepository $roundRepo)
    {
        $this->roundRepo = $roundRepo;
    }

    public function index(Request $request)
    {
        /*
        $request->validate([
            'group_id' => 'required'
        ]);
        */

        $data = $this->roundRepo->getRoundArisan($request->group_id , $request->putaran);

        return response()->json([
            'data' => $data
        ]);
    }

     public function getHistoriArisan()
    {

        $data = $this->roundRepo->getHistoriArisan();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function get_arisan()
    {

        $data = $this->roundRepo->getArisan();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * POST /round
     * Tambah header
     */
    public function store(Request $request)
    {
        $request->validate([
            'group_kode' => 'required|string',
            'putaran' => 'required|numeric|min:1',
            'tanggal_putaran' => 'required|date',
        ], [
            'group_kode.required' => 'Kode arisan wajib diisi',
            'putaran.required' => 'Putaran wajib diisi',
            'putaran.min' => 'Putaran minimal 1',
            'tanggal_putaran.required' => 'Tanggal wajib diisi',
        ]);

        // CEK DUPLIKAT
        $exists = $this->roundRepo->checkPutaranExists(
            $request->group_kode,
            $request->putaran
        );

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Putaran tersebut sudah ada untuk grup ini'
            ], 200);
        }

        $inserted = 0;

        $user = Auth::user();
        $username = $user?->username;

        $inserted = $this->roundRepo->insertHeader(
            $request->group_kode,
            $request->putaran,
            $request->tanggal_putaran
        );

        return response()->json([
            'success' => true,
            'message' => 'Header Arisan berhasil dibuat'
        ], 201);
    }

    public function payment(Request $request)
    {
        $request->validate([
            'group_id' => 'required|string',
            'user_id' => 'required|string|',
            'putaran' => 'required|numeric|min:1',
        ], [
            'group_id.required' => 'Kode arisan wajib diisi',
            'putaran.required' => 'Putaran wajib diisi',
            'putaran.min' => 'Putaran minimal 1',
            'user_id.required' => 'User wajib diisi',
        ]);

        $inserted = 0;

        $user = Auth::user();
        $username = $user?->username;


        $exists = $this->roundRepo->checkPaymentExists(
           $request->group_id,
            $request->user_id,
            $request->putaran
        );

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Payment tersebut sudah Terbayar'
            ], 200);
        }

        $inserted = $this->roundRepo->insertPayment(
            $request->group_id,
            $request->user_id,
            $request->putaran
        );

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran Arisan berhasil dibuat'
        ], 201);
    }

    public function winner(Request $request)
    {
        $request->validate([
            'group_id' => 'required|string',
            'user_id' => 'required|string|',
            'putaran' => 'required|numeric|min:1',
        ], [
            'group_id.required' => 'Kode arisan wajib diisi',
            'putaran.required' => 'Putaran wajib diisi',
            'putaran.min' => 'Putaran minimal 1',
            'user_id.required' => 'User wajib diisi',
        ]);

        $inserted = 0;

        $user = Auth::user();
        $username = $user?->username;


        $exists = $this->roundRepo->checkWinnerExists(
           $request->group_id,
            $request->user_id,
            $request->putaran
        );

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Pemenang tersebut sudah Terpilih'
            ], 200);
        }

        $inserted = $this->roundRepo->insertWinner(
            $request->group_id,
            $request->user_id,
            $request->putaran
        );

        return response()->json([
            'success' => true,
            'message' => 'Pemenang Arisan berhasil dibuat'
        ], 201);
    }


}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // GET /api/users (ADMIN)
    public function index()
    {
        return response()->json([
            'data' => User::select('id', 'name', 'username', 'email', 'role')->get()
        ]);
    }

    // POST /api/users (ADMIN)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,petugas,peserta'
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return response()->json([
            'message' => 'User berhasil dibuat',
            'data' => $user
        ], 201);
    }

        // PUT /api/users/{id} (ADMIN)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users,username,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:admin,petugas,peserta'
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role
        ];

        // password hanya diupdate kalau diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'message' => 'User berhasil diupdate',
            'data' => $user
        ]);
    }

        // DELETE /api/users/{id} (ADMIN)
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // optional: cegah hapus diri sendiri
        if (auth()->id() === $user->id) {
            return response()->json([
                'message' => 'Tidak bisa menghapus user yang sedang login'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'User berhasil dihapus'
        ]);
    }

    



}

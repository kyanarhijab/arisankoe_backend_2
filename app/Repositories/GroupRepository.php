<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class GroupRepository
{
    public function getAll()
    {
        $sql = "
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
            WHERE status = 'active'
            ORDER BY id DESC
        ";

        return DB::select($sql);
    }

    public function insertGroup($kode, $name,$description,$total_rounds,$amount,$start_date,$created_by)
    {
    
        return  DB::insert("
                INSERT INTO arisan_groups
                    (kode, name, description, total_rounds, amount, start_date, status, created_by, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, 'active', ?, NOW(), NOW())
            ", [$kode, $name,$description,$total_rounds,$amount,$start_date,$created_by]);
         
    }

    public function updateGroup($id,$name,$description,$total_rounds,$amount,$start_date,$status)
    {
        return DB::update("
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
        ", [$name, $description,$total_rounds,$amount,$start_date,$status,$id]);
    }

    public function delete($id)
    {
        return DB::delete("
            DELETE FROM arisan_groups
            WHERE id = ?
        ", [$id]);
    }

    public function getExportExcel($group_id)
    {
        // 🔥 ambil max_round
        $group = DB::table('arisan_groups')
            ->where('kode', $group_id)
            ->first();

        if (!$group) {
            return [];
        }

        $maxPutaran = $group->total_rounds;

        // 🔥 raw query
        $data = DB::select("
            SELECT 
            u.username as user_id,
            u.name,
            case when P.round_numbers is null then 0 ELSE g.amount END as amount,
            p.round_numbers as putaran
        FROM participants pt
        JOIN users u ON u.username = pt.user_id
        JOIN arisan_groups g ON g.kode = pt.group_id
        LEFT JOIN arisan_payments p 
            ON p.user_id = u.username 
            AND p.group_id = g.kode
        WHERE g.kode = ?
        ", [$group_id]);

        $grouped = [];

        foreach ($data as $row) {
            $uid = $row->user_id;

            // 🔥 init user
            if (!isset($grouped[$uid])) {
                $grouped[$uid] = [
                    'nama' => $row->name,
                ];

                // 🔥 default semua putaran = 0
                for ($i = 1; $i <= $maxPutaran; $i++) {
                    $grouped[$uid][(string)$i] = 0;
                }
            }

            // 🔥 isi kalau sudah bayar
            if (!is_null($row->putaran)) {
                $grouped[$uid][(string)$row->putaran] = $row->amount;
            }
        }

        return array_values($grouped);
    }

}
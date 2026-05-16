<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ParticipantRepository
{
    public function getAll($group_id)
    {
        $sql = "
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
        ";

        return DB::select($sql, [$group_id]);
    }

    public function addParticipant($userId, $groupId)
    {
        $exists = DB::selectOne("
        SELECT id FROM participants
        WHERE user_id = ? AND group_id = ?
    ", [$userId, $groupId]);

        if ($exists) {
            return false;
        }

        DB::insert("
        INSERT INTO participants (user_id, group_id, join_date, status)
        VALUES (?, ?, CURDATE(), 'active')
    ", [$userId, $groupId]);

        return true;
    }

    public function updateStatus($id, $status)
    {
        return DB::update("
            UPDATE participants 
            SET status = ?
            WHERE id = ?
        ", [$status, $id]);
    }

    public function deleted($id)
    {
        return DB::delete("
            DELETE FROM participants WHERE id = ?
        ", [$id]);
    }

}

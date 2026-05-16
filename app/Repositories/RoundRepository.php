<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class RoundRepository
{
    
    public function getHistoriArisan()
    {
        $sql = "
             select ar.group_id as kode , 
                ( select name from arisan_groups ag where ag.kode = ar.group_id ) as name , round_numbers as putaran , 
                ar.draw_date as tgl_putaran ,
                concat((concat(ar.winner_id,' - ')),(select name from users u where u.username = ar.winner_id)) as pemenang
                from arisan_rounds ar
                order by group_id , round_numbers asc , draw_date desc
        ";

        return DB::select($sql);
    }


    
    
    public function getArisan()
    {
        $sql = "
             select ag.kode as kode  , ag.name as nama , ag.description as keterangan  ,  ifnull(max(ar.round_numbers),0)+1 as urutan , curdate() as tanggal
             from arisan_rounds ar right join arisan_groups ag 
             on ar.group_id = ag.kode 
             where ag.status = 'active'
             group by ag.kode , ag.name , ag.description
             order by ag.kode
        ";

        return DB::select($sql);
    }

    public function getRoundArisan($group_id,$putaran)
    {
        $sql = "
                  select ar.group_id, ar.round_numbers,p.user_id , ( select u.name  from users u where u.username = p.user_id ) name , 
                     ( case when ap.status = 'paid' then  ( select ag.amount nt from arisan_groups ag where ag.kode = ar.group_id ) else 0 end )  as amount_paid , 
                      ap.payment_date ,   ( case when ap.status = 'paid' then 'SUDAH BAYAR' else 'BELOM BAYAR' end ) as status , 
                       ( case when ar.winner_id is null then 'BELOM' else 'SUDAH' end ) as status2     
                    from arisan_rounds ar left join participants p   
                    on ar.group_id  = p.group_id
                    left join arisan_payments ap on ar.group_id = ap.group_id and ar.round_numbers = ap.round_numbers and p.user_id  = ap.user_id
                    where ar.group_id = ?
                    and ar.round_numbers = ?
        ";
        return DB::select($sql, [$group_id,$putaran]);
    }

    public function insertHeader($group_id, $round_numbers, $draw_date)
    {

        return  DB::insert("
                INSERT INTO arisan_rounds
                    (group_id , round_numbers, draw_date, created_at, updated_at)
                VALUES (?, ?, ?, NOW(), NOW())
            ", [$group_id, $round_numbers, $draw_date]);
    }

    public function checkPutaranExists($group_id, $round_numbers)
    {
        $sql = "  SELECT id
                    FROM arisan_rounds
                    WHERE group_id = ?
                    AND round_numbers = ?
                LIMIT 1 
                ";
        $result = DB::select($sql, [$group_id, $round_numbers]);

        return !empty($result);
    }

    public function insertPayment($group_id, $user_id, $putaran)
    {

        return  DB::insert("
                INSERT INTO arisan_payments
                    (group_id,user_id,round_numbers , payment_date , status , created_at , updated_at)
                VALUES (?, ?, ?, NOW(),'paid',NOW(), NOW())
            ", [$group_id, $user_id, $putaran]);
    }

    public function checkPaymentExists($group_id, $user_id, $putaran)
    {
        $sql = "  SELECT id
                    FROM arisan_payments
                    WHERE group_id = ?
                    AND USER_ID = ?
                    AND round_numbers = ?
                LIMIT 1 
                ";
        $result = DB::select($sql, [$group_id, $user_id, $putaran]);

        return !empty($result);
    }

    public function insertWinner($group_id, $user_id, $putaran)
    {

        return  DB::update('update arisan_rounds set winner_id = ? where group_id = ? and round_numbers = ? ', [$user_id,$group_id,$putaran]);
    }

    public function checkWinnerExists($group_id, $user_id, $putaran)
    {
        $sql = "  SELECT id
                    FROM arisan_rounds
                    WHERE group_id = ?
                    AND winner_id = ?
                    AND round_numbers = ?
                LIMIT 1 
                ";
        $result = DB::select($sql, [$group_id, $user_id, $putaran]);

        return !empty($result);
    }


}

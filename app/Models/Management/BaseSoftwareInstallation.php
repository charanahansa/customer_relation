<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class BaseSoftwareInstallation extends Model{

    use HasFactory;

	public function get_base_software_installation_count($from_date, $to_date){

        $sql_query=" select     count(bd.ticketno) as 'base_software_installation_count'
                     from       base_software_install b inner join base_software_install_detail bd on b.batchno = bd.batchno
                     where      b.cancel=0 && b.tdate between ? and  ?; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;

    }

    public function get_bank_wise_base_software_installation_count($from_date, $to_date){

        $sql_query=" select     b.bank, count(bd.ticketno) as 'count'
                     from       base_software_install b inner join base_software_install_detail bd on b.batchno = bd.batchno
                     where      b.cancel=0 && b.tdate between ? and  ?
                     group by   b.bank; ";
      $result = DB::select($sql_query,[$from_date, $to_date]);

      return $result;
    }

    public function get_status_wise_base_software_installation_count($from_date, $to_date){

        $sql_query=" select     bd.status, count(bd.ticketno) as 'count'
                     from       base_software_install b inner join base_software_install_detail bd on b.batchno = bd.batchno
                     where      b.cancel=0 && b.tdate between ? and  ?
                     group by   bd.status; ";
        $result = DB::select($sql_query,[$from_date, $to_date]);

        return $result;
    }

    public function get_bank_wise_status_wise_base_software_installation_count($bank, $from_date, $to_date){

        $sql_query=" select     bd.status, count(bd.ticketno) as 'count'
                     from       base_software_install b inner join base_software_install_detail bd on b.batchno = bd.batchno
                     where      b.cancel=0 && b.bank = ? && b.tdate between ? and  ?
                     group by   bd.status; ";
        $result = DB::select($sql_query,[$bank, $from_date, $to_date]);

        return $result;
    }

    public function get_status_wise_bank_wise_base_software_installation_count($status, $from_date, $to_date){

        $sql_query=" select     b.bank, count(bd.ticketno) as 'count'
                     from       base_software_install b inner join base_software_install_detail bd on b.batchno = bd.batchno
                     where      b.cancel=0 && bd.status = ? && b.tdate between ? and  ?
                     group by   b.bank; ";
        $result = DB::select($sql_query,[$status, $from_date, $to_date]);

        return $result;
    }

    public function get_base_software_installation_detail($bank, $status, $from_date, $to_date){

        $sql_query=" select     bd.ticketno, b.tdate, b.bank, bd.serialno, bd.model, o.officer_name, bd.status
                     from       base_software_install b
                                    inner join base_software_install_detail bd on b.batchno = bd.batchno
                                    left outer join officers o on bd.officer = o.id
                     where      b.cancel=0 && b.bank = ? && bd.status = ? && b.tdate between ? and  ? ";

        $result = DB::select($sql_query,[$bank, $status, $from_date, $to_date]);

        return $result;
    }


}

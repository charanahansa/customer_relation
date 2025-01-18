<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait ZoneName {

    public function getZoneName($workflowId, $ticketno) {

        $tables = [
            1 => 'breakdown',
            2 => 'newinstall',
            3 => 're_initialization',
            4 => 'software_updation_detail',
            5 => 'terminal_replacement',
            6 => 'backup_remove_note'
        ];

        if (!isset($tables[$workflowId])) {
            return "";
        }

        $table = $tables[$workflowId];
        $column = $workflowId == 6 ? 'brn_no' : 'ticketno';

        $zoneId = DB::table($table)->where($column, $ticketno)->value('zone_id');

        if (!$zoneId) {
            return "";
        }

        $zoneName = DB::table('zones')->where('active', 1)->where('zone_id', $zoneId)->value('zone_name');
        return $zoneName ?? "";
    }
}

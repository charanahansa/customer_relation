<?php
namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

trait TimeZone {

    public function checkSLA( $tableName, $ticketno) {

        $tblWorkflow = DB::table($tableName)->where('ticketno', $ticketno)->first();

        if (!$tblWorkflow) {
            return ['timeZoneName' => '', 'slaMet' => ''];
        }

        $tblZone = DB::table('zones')->where('zone_id', $tblWorkflow->zone_id)->first();

        if (!$tblZone) {
            return ['timeZoneName' => '', 'slaMet' => ''];
        }

        $recomendedTime = $tblZone->resolution_time;
        $timeZoneName = $tblZone->zone_name;
        $reportedDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $tblWorkflow->saved_on);
        $expectedDateTime = $reportedDateTime->addHours($recomendedTime);
        $doneDateTime = $tblWorkflow->done_date_time ? Carbon::createFromFormat('Y-m-d H:i:s', $tblWorkflow->done_date_time) : null;

        $slaMet = ($doneDateTime && $expectedDateTime->gte($doneDateTime)) ? 'Yes' : 'No';

        return [
            'timeZoneName' => $timeZoneName,
            'slaMet' => $slaMet
        ];
    }

}

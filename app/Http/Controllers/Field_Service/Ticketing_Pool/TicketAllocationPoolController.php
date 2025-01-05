<?php

namespace App\Http\Controllers\Field_Service\Ticketing_Pool;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FieldService\Breakdown;
use App\Models\FieldService\NewInstallation;
use App\Models\FieldService\ReInitialization;
use App\Models\FieldService\SoftwareUpdate;
use App\Models\FieldService\TerminalReplacement;
use App\Models\FieldService\BackupRemoveProcess;

class TicketAllocationPoolController extends Controller {

    public function index(){

        $objBreakdown = new Breakdown();
        $objNewInstallation = new NewInstallation();
        $objReInitialization = new ReInitialization();
        $objSoftwareUpdate = new SoftwareUpdate();
        $objTerminalReplacement = new TerminalReplacement();
        $objBackupRemoveProcess = new BackupRemoveProcess();

        $data['breakdown'] = $objBreakdown->getBreakdownTicketForAllocate('');
        $data['new'] = $objNewInstallation->getNewInstallationTicketForAllocate('');
        $data['re_initialization'] = $objReInitialization->getReInitializationTicketForAllocate('');
        $data['software_update'] = $objSoftwareUpdate->getSoftwareUpdatingTicketForAllocate('');
        $data['terminal_replacemnt'] = $objTerminalReplacement->getTerminalReplacementTicketForAllocate('');
        $data['backup_remove'] = $objBackupRemoveProcess->getBackupRemoveTicketForAllocate('');

        return view('fsp.ticket_pool.ticket_allocation_pool')->with('TAP', $data);;
    }

    public function getTicketAllocationData(Request $request){

        $order = '';

        if( $request->order == 'desc' ){

            $order_by = ' order by '. $request->column_name .' desc ';
            $order = 'asc';

        }else{

            $order_by = ' order by '. $request->column_name .' asc ';
            $order = 'desc';
        }

		if( $request->workflow == 'breakdown' ){

            $table = '  <table id="tbl_software_updating" class="table table-hover table-sm table-bordered">
                            <thead>
                                <tr style="font-family: Consolas; font-size: 13px;">
									<th style="width: 8%;" class="column_sort_b" id="ticketno" data-order="'. $order .'">Ticket No. <span>&#8597;</span> </th>
									<th style="width: 8%;" class="column_sort_b" id="tdate" data-order="'. $order .'">Date <span>&#8597;</span> </th>
									<th style="width: 8%;" class="column_sort_b" id="bank" data-order="'. $order .'">Bank <span>&#8597;</span> </th>
									<th style="width: 8%;" class="column_sort_b" id="tid" data-order="'. $order .'">Tid <span>&#8597;</span> </th>
									<th style="width: 60%;" class="column_sort_b" id="merchant" data-order="'. $order .'">Merchant <span>&#8597;</span> </th>
									<th style="width: 8%;" class="column_sort_b" id="status" data-order="'. $order .'">Status <span>&#8597;</span> </th>
                                </tr>
                            </thead>
                    ';

            $table_part = '<tbody>';
			$objBreakdown = new Breakdown();
            $data_table = $objBreakdown->getBreakdownTicketForAllocate($order_by);
            foreach($data_table as $row){

                $table_part .= '  <tr style="font-family: Consolas; font-size: 13px;"> ';
                $table_part .= ' <td> ' . $row->ticketno . '</td> ';
                $table_part .= ' <td> ' . $row->tdate . '</td> ';
                $table_part .= ' <td> ' . $row->bank . '</td> ';
                $table_part .= ' <td> ' . $row->tid . '</td> ';
                $table_part .= ' <td> ' . $row->merchant . '</td> ';
                $table_part .= ' <td> ' . ucfirst($row->status) . '</td> ';
                $table_part .= ' <td> <input type="button" name="button" id="button" class="btn btn-primary btn-sm" style="width: 100%" value="Open" onclick="openTicket(this.parentNode.parentNode.rowIndex, 1)"> </td> ';
                $table_part .= '</tr>';
            }
            $table_part .= '</tbody>';
            $table_part .= '<table>';

            $table = $table . $table_part;

            echo $table;
        }


		if( $request->workflow == 'new' ){

            $table = '  <table id="tbl_software_updating" class="table table-hover table-sm table-bordered">
                            <thead>
                                <tr style="font-family: Consolas; font-size: 13px;">
									<th style="width: 8%;" class="column_sort_n" id="ticketno" data-order="'. $order .'">Ticket No. <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_n" id="tdate" data-order="'. $order .'">Date <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_n" id="bank" data-order="'. $order .'">Bank <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_n" id="tid" data-order="'. $order .'">Tid <span>&#8597;</span> </th>
                                    <th style="width: 60%;" class="column_sort_n" id="merchant" data-order="'. $order .'">Merchant <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_n" id="status" data-order="'. $order .'">Status <span>&#8597;</span> </th>
								</tr>
                            </thead>
                    ';

            $table_part = '<tbody>';
			$objNewInstallation = new NewInstallation();
            $data_table = $objNewInstallation->getNewInstallationTicketForAllocate($order_by);
            foreach($data_table as $row){

                $table_part .= '  <tr style="font-family: Consolas; font-size: 13px;"> ';
                $table_part .= ' <td> ' . $row->ticketno . '</td> ';
                $table_part .= ' <td> ' . $row->tdate . '</td> ';
                $table_part .= ' <td> ' . $row->bank . '</td> ';
                $table_part .= ' <td> ' . $row->tid . '</td> ';
                $table_part .= ' <td> ' . $row->merchant . '</td> ';
                $table_part .= ' <td> ' . ucfirst($row->status) . '</td> ';
                $table_part .= ' <td> <input type="button" name="button" id="button" class="btn btn-primary btn-sm" style="width: 100%" value="Open" onclick="openTicket(this.parentNode.parentNode.rowIndex, 2)"> </td> ';
                $table_part .= '</tr>';
            }
            $table_part .= '</tbody>';
            $table_part .= '<table>';

            $table = $table . $table_part;

            echo $table;
        }

		if( $request->workflow == 're_initialization' ){

            $table = '  <table id="tbl_software_updating" class="table table-hover table-sm table-bordered">
                            <thead>
                                <tr style="font-family: Consolas; font-size: 13px;">
									<th style="width: 8%;" class="column_sort_r" id="ticketno" data-order="'. $order .'">Ticket No. <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_r" id="tdate" data-order="'. $order .'">Date <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_r" id="bank" data-order="'. $order .'">Bank <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_r" id="tid" data-order="'. $order .'">Tid <span>&#8597;</span> </th>
                                    <th style="width: 60%;" class="column_sort_r" id="merchant" data-order="'. $order .'">Merchant <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_r" id="status" data-order="'. $order .'">Status <span>&#8597;</span> </th>
								</tr>
                            </thead>
                    ';

            $table_part = '<tbody>';
			$objReInitialization = new ReInitialization();
            $data_table = $objReInitialization->getReInitializationTicketForAllocate($order_by);
            foreach($data_table as $row){

                $table_part .= '  <tr style="font-family: Consolas; font-size: 13px;"> ';
                $table_part .= ' <td> ' . $row->ticketno . '</td> ';
                $table_part .= ' <td> ' . $row->tdate . '</td> ';
                $table_part .= ' <td> ' . $row->bank . '</td> ';
                $table_part .= ' <td> ' . $row->tid . '</td> ';
                $table_part .= ' <td> ' . $row->merchant . '</td> ';
                $table_part .= ' <td> ' . ucfirst($row->status) . '</td> ';
                $table_part .= ' <td> <input type="button" name="button" id="button" class="btn btn-primary btn-sm" style="width: 100%" value="Open" onclick="openTicket(this.parentNode.parentNode.rowIndex, 3)"> </td> ';
                $table_part .= '</tr>';
            }
            $table_part .= '</tbody>';
            $table_part .= '<table>';

            $table = $table . $table_part;

            echo $table;
        }


        if( $request->workflow == 'software_update' ){

            $table = '  <table id="tbl_software_updating" class="table table-hover table-sm table-bordered">
                            <thead>
                                <tr style="font-family: Consolas; font-size: 13px;">
                                    <th style="width: 8%;" class="column_sort" id="a.ticketno" data-order="'. $order .'"> Ticket No. <span>&#8597;</span> </th>
                                    <th style="width: 6%;" class="column_sort" id="s.tdate" data-order="'. $order .'"> Date <span>&#8597;</span> </th>
                                    <th style="width: 6%;" class="column_sort" id="s.bank" data-order="'. $order .'"> Bank <span>&#8597;</span> </th>
                                    <th style="width: 6%;" class="column_sort" id="sd.tid" data-order="'. $order .'"> Tid <span>&#8597;</span> </th>
                                    <th style="width: 60%;" class="column_sort" id="sd.merchant" data-order="'. $order .'"> Merchant <span>&#8597;</span> </th>
                                    <th style="width: 14%;" class="column_sort" id="ss.status" data-order="'. $order .'"> Status <span>&#8597;</span> </th>
                                </tr>
                            </thead>
                    ';

            $table_part = '<tbody>';
            $objSoftwareUpdate = new SoftwareUpdate();
            $data_table = $objSoftwareUpdate->getSoftwareUpdatingTicketForAllocate($order_by);
            foreach($data_table as $row){

                $table_part .= '  <tr style="font-family: Consolas; font-size: 13px;"> ';
                $table_part .= ' <td> ' . $row->ticketno . '</td> ';
                $table_part .= ' <td> ' . $row->tdate . '</td> ';
                $table_part .= ' <td> ' . $row->bank . '</td> ';
                $table_part .= ' <td> ' . $row->tid . '</td> ';
                $table_part .= ' <td> ' . $row->merchant . '</td> ';
                $table_part .= ' <td> ' . $row->status . '</td> ';
                $table_part .= ' <td> <input type="button" name="button" id="button" class="btn btn-primary btn-sm" style="width: 100%" value="Open" onclick="openTicket(this.parentNode.parentNode.rowIndex, 4)"> </td> ';
                $table_part .= '</tr>';
            }
            $table_part .= '</tbody>';
            $table_part .= '<table>';

            $table = $table . $table_part;

            echo $table;
        }

		if( $request->workflow == 'terminal_replacement' ){

            $table = '  <table id="tbl_software_updating" class="table table-hover table-sm table-bordered">
                            <thead>
                                <tr style="font-family: Consolas; font-size: 13px;">
									<th style="width: 8%;" class="column_sort_tr" id="ticketno" data-order="'. $order .'">Ticket No. <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_tr" id="tdate" data-order="'. $order .'">Date <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_tr" id="bank" data-order="'. $order .'">Bank <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_tr" id="based_tid" data-order="'. $order .'">Tid <span>&#8597;</span> </th>
                                    <th style="width: 60%;" class="column_sort_tr" id="merchant" data-order="'. $order .'">Merchant <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_tr" id="status" data-order="'. $order .'">Status <span>&#8597;</span> </th>
                                </tr>
                            </thead>
                    ';

            $table_part = '<tbody>';
            $objTerminalReplacement = new TerminalReplacement();
            $data_table = $objTerminalReplacement->getTerminalReplacementTicketForAllocate($order_by);
            foreach($data_table as $row){

                $table_part .= '  <tr style="font-family: Consolas; font-size: 13px;"> ';
                $table_part .= ' <td> ' . $row->ticketno . '</td> ';
                $table_part .= ' <td> ' . $row->tdate . '</td> ';
                $table_part .= ' <td> ' . $row->bank . '</td> ';
                $table_part .= ' <td> ' . $row->based_tid . '</td> ';
                $table_part .= ' <td> ' . $row->merchant . '</td> ';
                $table_part .= ' <td> ' . ucfirst($row->status) . '</td> ';
                $table_part .= ' <td> <input type="button" name="button" id="button" class="btn btn-primary btn-sm" style="width: 100%" value="Open" onclick="openTicket(this.parentNode.parentNode.rowIndex, 5)"> </td> ';
                $table_part .= '</tr>';
            }
            $table_part .= '</tbody>';
            $table_part .= '<table>';

            $table = $table . $table_part;

            echo $table;
        }

        if( $request->workflow == 'backup_removal' ){

            $table = '  <table id="tbl_backup_remove" class="table table-hover table-sm table-bordered">
                            <thead>
                                <tr style="font-family: Consolas; font-size: 13px;">
                                    <th style="width: 8%;" class="column_sort_br" id="b.brn_no" data-order="'. $order .'">Ticket No. <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_br" id="b.brn_date" data-order="'. $order .'">Date <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_br" id="b.bank" data-order="'. $order .'">Bank <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_br" id="b.tid" data-order="'. $order .'">Tid <span>&#8597;</span> </th>
                                    <th style="width: 60%;" class="column_sort_br" id="b.merchant" data-order="'. $order .'">Merchant <span>&#8597;</span> </th>
                                    <th style="width: 8%;" class="column_sort_br" id="b.status" data-order="'. $order .'">Status <span>&#8597;</span> </th>
                                </tr>
                            </thead>
                    ';

            $table_part = '<tbody>';
            $objBackupRemoveProcess = new BackupRemoveProcess();
            $data_table = $objBackupRemoveProcess->getBackupRemoveTicketForAllocate($order_by);
            foreach($data_table as $row){

                $table_part .= '  <tr style="font-family: Consolas; font-size: 13px;"> ';
                $table_part .= ' <td> ' . $row->brn_no . '</td> ';
                $table_part .= ' <td> ' . $row->brn_date . '</td> ';
                $table_part .= ' <td> ' . $row->bank . '</td> ';
                $table_part .= ' <td> ' . $row->tid . '</td> ';
                $table_part .= ' <td> ' . $row->merchant . '</td> ';
                $table_part .= ' <td> ' . ucfirst($row->status) . '</td> ';
                $table_part .= ' <td> <input type="button" name="button" id="button" class="btn btn-primary btn-sm" style="width: 100%" value="Open" onclick="openTicket(this.parentNode.parentNode.rowIndex, 6)"> </td> ';
                $table_part .= '</tr>';
            }
            $table_part .= '</tbody>';
            $table_part .= '<table>';

            $table = $table . $table_part;

            echo $table;
        }

    }


}

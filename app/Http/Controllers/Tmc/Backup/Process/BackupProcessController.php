<?php

namespace App\Http\Controllers\Tmc\Backup\Process;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Tmc\Backup\Process\BackupIssueNoteController;
use App\Http\Controllers\Tmc\Backup\Process\BackupRemoveNoteController;

use App\Models\Tmc\Operation\Breakdown;
use App\Models\Tmc\Operation\NewInstallation;
use App\Models\Tmc\Operation\ReInitilization;
use App\Models\Tmc\Operation\SoftwareUpdation;
use App\Models\Tmc\Operation\TerminalIn;
use App\Models\Tmc\Operation\TerminalReplacement;
use App\Models\Tmc\Backup\BackupProcess;

class BackupProcessController extends Controller {

    public function index(){

        return view('system_admin.backup_process');
    }


    public function backup_process_process(Request $request){

        ini_set('max_execution_time', 0); 

        $objBackupProcess = new BackupProcess();

        $objBackupIssueNoteController = new BackupIssueNoteController();
        $objBackupRemoveNoteController = new BackupRemoveNoteController();

        $objBreakdown = new Breakdown();
        $objNewInstallation = new NewInstallation();
        $objReInitilization = new ReInitilization();
        $objSoftwareUpdation = new SoftwareUpdation();
        $objTerminalReplacement = new TerminalReplacement();
        $objTerminalIn = new TerminalIn();

        $from_date_time = '2019-04-01 00:00:00';
        $to_date_time = '2019-04-05 23:59:59';

        $objBackupProcess->create_tmp_table();
        $objBreakdown->get_breakdown_detail_for_backup_process($from_date_time, $to_date_time);
        $objNewInstallation->get_new_installation_detail_for_backup_process($from_date_time, $to_date_time);
        $objReInitilization->get_re_initialization_detail_for_backup_process($from_date_time, $to_date_time);
        $objSoftwareUpdation->get_software_updation_detail_for_backup_process($from_date_time, $to_date_time);
        $objTerminalReplacement->get_terminal_replacement_detail_for_backup_process($from_date_time, $to_date_time);
        $objTerminalIn->get_terminal_in_process_detail_for_backup_process($from_date_time, $to_date_time);
        

        $workflow_result = $objBackupProcess->get_backup_info_detail();
        foreach($workflow_result as $row){

            $backup_in_process = NULL;
            $backup_out_process = NULL;

            // In Serial No. Process
            if( $objBackupProcess->isBackup($row->e_in_serialno) == TRUE ){

                if( $objBackupProcess->hasBackupIssueNote($row->e_in_serialno) == TRUE){

                    $backup_in_process['update_backup_issue_note'] = $objBackupIssueNoteController->update_backup_issue_note($row);
                    $backup_in_process['genarate_backup_remove_note'] = $objBackupRemoveNoteController->genarate_backup_remove_note($row, 1);

                    if($row->courier == 'Not'){

                        $backup_in_process['genarate_backup_remove_note_fs'] = $objBackupRemoveNoteController->genarate_backup_remove_note_fs_view($row, 1);
                    }

                }else{

                    if($row->courier == 'Not'){

                        $backup_in_process['genarate_backup_remove_note_fs'] = $objBackupRemoveNoteController->genarate_backup_remove_note_fs_view($row, 1);
                    }

                    $backup_in_process['genarate_backup_remove_note'] = $objBackupRemoveNoteController->genarate_backup_remove_note($row, 1);
                }
            }

            
            // Out Serial No. Process
            if( $objBackupProcess->isBackup($row->e_out_serialno) == TRUE ){

                if( $objBackupProcess->hasBackupIssueNote($row->e_out_serialno) == TRUE){

                    $backup_out_process['update_backup_issue_note'] = $objBackupIssueNoteController->update_backup_issue_note($row);
                    $backup_out_process['genarate_backup_remove_note'] = $objBackupRemoveNoteController->genarate_backup_remove_note($row, 2);

                    if($row->courier == 'Not'){

                        $backup_out_process['genarate_backup_remove_note_fs'] = $objBackupRemoveNoteController->genarate_backup_remove_note_fs_view($row, 2);
                    }

                    $backup_out_process['genarate_backup_issue_note'] =  $objBackupIssueNoteController->genarate_backup_issue_note_array($row);

                }else{

                    $backup_out_process['genarate_backup_issue_note'] = $objBackupIssueNoteController->genarate_backup_issue_note_array($row);
                }
            }

            if( ( is_null($backup_in_process) == FALSE) || ( is_null($backup_out_process) == FALSE) ){

                $data['process_information'] = $row;
                $data['backup_in_process'] = $backup_in_process;
                $data['backup_out_process'] = $backup_out_process;

                echo '<pre>';
				print_r($data);
				echo '</pre>';

                $objBackupProcess->backupProcess($data);
            }
            
            return view('system_admin.backup_process');
            
        }
    }
    
}

<?php

namespace App\Http\Controllers\System_Admin\Operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Management\Breakdown;
use App\Models\Management\NewInstallation;
use App\Models\Management\SoftwareUpdation;
use App\Models\Management\ReInitialization;
use App\Models\Hardware\Operation\Quotation;
use App\Models\Tmc\InOut\InvestigationQuotation;

use Illuminate\Support\Facades\Mail;

class IndividualEmailController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        return view('system_admin.system_admin_dashboard');
    }

    public function individual_email_process(Request $request){


        $objBreakdown = new Breakdown();

        $result = $objBreakdown->get_email_request();
        foreach($result  as $first_row){

            if($first_row->workflow == 'breakdown'){

                $this->breakdown_email_process($first_row);
            }

            if($first_row->workflow == 'new_installation'){

                $this->new_installation_email_process($first_row);
            }

            if($first_row->workflow == 'quotation'){

                $this->quotation_email_process($first_row);
            }

            if($first_row->workflow == 'vc_record_1'){

                $this->breakdown_vericentre_record_updation_process($first_row);
            }

            if($first_row->workflow == 'software_updation'){

                $this->software_updation_individual_email_process($first_row);
            }

            if($first_row->workflow == 're_initilization'){

                $this->re_initilization_individual_email_process($first_row);
            }

        }

        return view('system_admin.system_admin_dashboard');
    }


    private function breakdown_email_process($first_row){

        $objBreakdown = new Breakdown();

        $breakdown_result = $objBreakdown->get_breakdown($first_row->ticket_no);
        foreach($breakdown_result as $second_row){

            $breakdown_email['ticketno'] = $second_row->ticketno;
            $breakdown_email['date'] = $second_row->tdate;
            $breakdown_email['bank'] = $second_row->bank;
            $breakdown_email['tid'] = $second_row->tid;
            $breakdown_email['model'] = $second_row->model;
            $breakdown_email['merchant'] = $second_row->merchant;
            $breakdown_email['fault'] = $second_row->fault;
            $breakdown_email['contact_number'] = $second_row->contactno;
            $breakdown_email['contact_person'] = $second_row->contact_person;
            $breakdown_email['relevent_detail'] = $second_row->relevant_detail;
            $breakdown_email['action_taken'] = $second_row->action_taken;
            $breakdown_email['fault_serialno'] = $second_row->fault_serialno;
            $breakdown_email['replaced_serialno'] = $second_row->replaced_serialno;

            if($first_row->role == 1){

                $breakdown_email['remark'] = $second_row->cc_remark;
            }

            if($first_row->role == 4){

                $breakdown_email['remark'] = $second_row->fs_remark;
            }

            if($first_row->role == 6){

                $breakdown_email['remark'] = $second_row->ftl_remark;
            }
            $breakdown_email['status'] = $second_row->status;


            // Get Email Addresses
            $bank_email_result = $objBreakdown->get_client_email_address($second_row->bank);

            static $to_email_address = array();
            $icount = 1;
            unset($to_email_address);
            foreach($bank_email_result as $eml){

                $to_email_address[$icount] = $eml->EMAIL;
                $icount ++;
            }

            Mail::to($to_email_address)->cc('support@epiclanka.net')->send(new \App\Mail\Breakdown\breakdown_individual($breakdown_email));

            unset($to_email_address);

            //Update Email Request
            $objBreakdown->update_email_request($first_row->email_request_id);
        }

    }


    private function new_installation_email_process($first_row){

        $objNewInstallation = new NewInstallation();

        $new_installation_result = $objNewInstallation->get_new_installation($first_row->ticket_no);
        foreach($new_installation_result as $second_row){

            $new_installation_email['ticketno'] = $second_row->ticketno;
            $new_installation_email['date'] = $second_row->date;
            $new_installation_email['bank'] = $second_row->bank;
            $new_installation_email['tid'] = $second_row->tid;
            $new_installation_email['model'] = $second_row->model;
            $new_installation_email['serialno'] = $second_row->serialno;
            $new_installation_email['merchant'] = $second_row->merchant;
            $new_installation_email['contact_number'] = $second_row->contactno;
            $new_installation_email['contact_person'] = $second_row->contact_person;

            if($first_row->role == 1){

                $new_installation_email['remark'] = $second_row->remark;
            }

            if($first_row->role == 4){

                $new_installation_email['remark'] = $second_row->fs_remark;
            }

            if($first_row->role == 6){

                $new_installation_email['remark'] = $second_row->ftl_remark;
            }
            $new_installation_email['status'] = $second_row->status;


            // Get Email Addresses
            $bank_email_result = $objNewInstallation->get_client_email_address($second_row->bank);

            static $to_email_address = array();
            $icount = 1;
            unset($to_email_address);
            foreach($bank_email_result as $eml){

                $to_email_address[$icount] = $eml->EMAIL;
                $icount ++;
            }

            Mail::to($to_email_address)->cc('support@epiclanka.net')->send(new \App\Mail\NewInstallation\new_installation_individual($new_installation_email));

            unset($to_email_address);

            //Update Email Request
            $objNewInstallation->update_email_request($first_row->email_request_id);

        }
    }

    private function software_updation_individual_email_process($first_row){

        $objSoftwareUpdation = new SoftwareUpdation();
        $result = $objSoftwareUpdation->get_software_updation_detail_for_individual_email($first_row->ticket_no);
        foreach($result as $third_row){

            $software_updation_email['ticketno'] = $third_row->ticketno;
            $software_updation_email['date'] = $third_row->date;
            $software_updation_email['bank'] = $third_row->bank;
            $software_updation_email['tid'] = $third_row->tid;
            $software_updation_email['model'] = $third_row->model;
            $software_updation_email['serialno'] = $third_row->serialno;
            $software_updation_email['merchant'] = $third_row->merchant;
            $software_updation_email['contact_number'] = $third_row->contactno;
            $software_updation_email['contact_person'] = $third_row->contact_person;

            if($first_row->role == 1){

                $software_updation_email['remark'] = $third_row->fs_remark;
            }

            if($first_row->role == 4){

                $software_updation_email['remark'] = $third_row->fs_remark;
            }

            if($first_row->role == 6){

                $software_updation_email['remark'] = $third_row->ftl_remark;
            }

            $software_updation_email['status'] = $third_row->status;

            // Get Email Addresses
            $bank_email_result = $objSoftwareUpdation->get_client_email_address($third_row->bank);

            static $to_email_address = array();
            $icount = 1;
            unset($to_email_address);
            foreach($bank_email_result as $eml){

                $to_email_address[$icount] = $eml->EMAIL;
                $icount ++;
            }

            Mail::to($to_email_address)->cc('support@epiclanka.net')->send(new \App\Mail\SoftwareUpdation\software_updation_individual($software_updation_email));

            unset($to_email_address);

            //Update Email Request
            $objSoftwareUpdation->update_email_request($first_row->email_request_id);
        }

    }


    private function re_initilization_individual_email_process($first_row){

        $objReInitialization = new ReInitialization();

        $result = $objReInitialization->get_re_initialization_detail_for_individual_email($first_row->ticket_no);
        foreach($result as $third_row){

            $re_initilization_email['ticketno'] = $third_row->ticketno;
            $re_initilization_email['date'] = $third_row->date;
            $re_initilization_email['bank'] = $third_row->bank;
            $re_initilization_email['tid'] = $third_row->tid;
            $re_initilization_email['model'] = $third_row->model;
            $re_initilization_email['serialno'] = $third_row->serialno;
            $re_initilization_email['merchant'] = $third_row->merchant;
            $re_initilization_email['contact_number'] = $third_row->contactno;
            $re_initilization_email['contact_person'] = $third_row->contact_person;

            if($first_row->role == 1){

                $re_initilization_email['remark'] = $third_row->remark;
            }

            if($first_row->role == 2){

                $re_initilization_email['remark'] = $third_row->vc_remark;
            }

            if($first_row->role == 4){

                $re_initilization_email['remark'] = $third_row->fs_remark;
            }

            if($first_row->role == 6){

                $re_initilization_email['remark'] = $third_row->ftl_remark;
            }

            $re_initilization_email['status'] = $third_row->status;

            // Get Email Addresses
            $bank_email_result = $objReInitialization->get_client_email_address($third_row->bank);

            static $to_email_address = array();
            $icount = 1;
            unset($to_email_address);
            foreach($bank_email_result as $eml){

                $to_email_address[$icount] = $eml->EMAIL;
                $icount ++;
            }

             Mail::to($to_email_address)->cc('support@epiclanka.net')->send(new \App\Mail\ReInitialization\ReInitialization_individual($re_initilization_email));

            unset($to_email_address);

            //Update Email Request
            $objReInitialization->update_email_request($first_row->email_request_id);
        }

    }



    private function quotation_email_process($first_row){

        $objQuotation = new Quotation();

        $quotation_result = $objQuotation->get_quotation($first_row->ticket_no);
        foreach($quotation_result as $second_row){

            $quotation_email['qt_no'] = $second_row->QT_NO;
            $quotation_email['qt_date'] = $second_row->QT_DATE;
            $quotation_email['jobcard_no'] = $second_row->JOBCARD_NO;
            $quotation_email['bank'] = $second_row->BANK;
            $quotation_email['serial_no'] = $second_row->SERIAL_NO;
            $quotation_email['price'] = $second_row->PRICE;

            // Get Email Addresses
            $hw_officer_result = $objQuotation->get_hardware_officers();

            static $to_email_address = array();
            $icount = 1;
            unset($to_email_address);
            foreach($hw_officer_result as $eml){

                $to_email_address[$icount] = $eml->email;
                $icount ++;
            }
            $to_email_address[$icount] = 'hansa_j@epiclanka.net';
            $icount ++;
            $to_email_address[$icount] = 'anuruddha_p@epiclanka.net';
            $icount ++;
            $to_email_address[$icount] = 'ruwan_w@epiclanka.net';
            $icount ++;
            $to_email_address[$icount] = 'kushan_p@epiclanka.net';

            Mail::to($to_email_address)->send(new \App\Mail\Quotation\quotation_approved_individual($quotation_email));

            unset($to_email_address);

            //Update Email Request
            $objQuotation->update_email_request($first_row->email_request_id);
        }

        //Investigation Quotation

        $objInvestigationQuotation = new InvestigationQuotation();

        $quotation_result = $objInvestigationQuotation->getQuotation($first_row->ticket_no);
        foreach($quotation_result as $second_row){

            $quotation_email['qt_no'] = $second_row->qt_no;
            $quotation_email['qt_date'] = $second_row->qt_date;
            $quotation_email['jobcard_no'] = $second_row->jobcard_number;
            $quotation_email['bank'] = $second_row->bank;
            $quotation_email['serial_no'] = $second_row->serial_number;
            $quotation_email['price'] = $second_row->amount;

            // Get Email Addresses
            $hw_officer_result = $objQuotation->get_hardware_officers();

            static $to_email_address = array();
            $icount = 1;
            unset($to_email_address);
            foreach($hw_officer_result as $eml){

                $to_email_address[$icount] = $eml->email;
                $icount ++;
            }
            $to_email_address[$icount] = 'hansa_j@epiclanka.net';
            $icount ++;
            $to_email_address[$icount] = 'anuruddha_p@epiclanka.net';
            $icount ++;
            $to_email_address[$icount] = 'ruwan_w@epiclanka.net';
            $icount ++;
            $to_email_address[$icount] = 'kushan_p@epiclanka.net';

            Mail::to($to_email_address)->send(new \App\Mail\Quotation\quotation_approved_individual($quotation_email));

            unset($to_email_address);

            //Update Email Request
            $objQuotation->update_email_request($first_row->email_request_id);
        }


    }


    private function breakdown_vericentre_record_updation_process($first_row){

        $objBreakdown = new Breakdown();

        $breakdown_result = $objBreakdown->get_breakdown($first_row->ticket_no);
        foreach($breakdown_result as $second_row){

            $breakdown_email['ticketno'] = $second_row->ticketno;
            $breakdown_email['date'] = $second_row->tdate;
            $breakdown_email['bank'] = $second_row->bank;
            $breakdown_email['tid'] = $second_row->tid;
            $breakdown_email['model'] = $second_row->model;
            $breakdown_email['merchant'] = $second_row->merchant;

            static $to_email_address = array();
            $icount = 1;
            unset($to_email_address);

            $to_email_address[$icount] = 'harshaka_r@epiclanka.net';
            $icount ++;
            $to_email_address[$icount] = 'ekantha_e@epiclanka.net';
            $icount ++;
            $to_email_address[$icount] = 'dinesh_c@epiclanka.net';
            $icount ++;
            $to_email_address[$icount] = 'lalith_j@epiclanka.net';

            Mail::to($to_email_address)->cc('support@epiclanka.net')->send(new \App\Mail\Breakdown\breakdown_vericentre_record($breakdown_email));

            //Update Email Request
            $objBreakdown->update_email_request($first_row->email_request_id);

        }
    }



}

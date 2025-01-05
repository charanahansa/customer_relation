<?php

namespace App\Http\Controllers\Hardware\Dashboard\Operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Management\TerminalRepair;

class OperationDashboardTwoController extends Controller {

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        $objTerminalRepair = new TerminalRepair();

        $data['jobcard_not_accepted_count'] = count($objTerminalRepair->getNotAcceptedCount());
        $data['to_be_investigated_count'] = count($objTerminalRepair->getToBeInvestigatedCount());
        $data['testing_count'] = count($objTerminalRepair->getTestingCount());
        $data['done_count'] = count($objTerminalRepair->getDoneCount());
        $data['quoted_count'] = count($objTerminalRepair->getQuotedCount());
        $data['spare_part_pending_terminal_count'] = count($objTerminalRepair->getSparepartPendingTerminalCount());
        $data['dismantal_terminal_count'] = count($objTerminalRepair->getDismantalTerminalCount());
        $data['disposed_terminal_count'] = 0;
        $data['today_accepted_terminal_count'] = count($objTerminalRepair->getTodayAcceptedTerminalCount());
        $data['today_released_terminal_count'] = count($objTerminalRepair->getTodayReleasedTerminalCount());


        return view('hardware.dashboard.operation.operation_dashboard_two')->with('repair', $data);
    }


    public function getInformation(Request $request){

        if( $request->paraMeter == 1){

            $data = $this->getJobcardNotAcceptedTerminalCount();

            echo json_encode($data);
        }

        if( $request->paraMeter == 2){

            $data = $this->getToBeInvestigatedTerminalCount();

            echo json_encode($data);
        }

        if( $request->paraMeter == 3){

            $data = $this->getTodayAcceptedTerminalCount();

            echo json_encode($data);
        }

        if( $request->paraMeter == 4){

            $data = $this->getTodayReleasedTerminalCount();

            echo json_encode($data);
        }

        if( $request->paraMeter == 5){

            $data = $this->getQuotedTerminalCount();

            echo json_encode($data);
        }

        if( $request->paraMeter == 6){

            $data = $this->getSparepartPendingTerminalCount();

            echo json_encode($data);
        }

        if( $request->paraMeter == 7){

            $data = $this->getTestingTerminalCount();

            echo json_encode($data);
        }

        if( $request->paraMeter == 8){

            $data = $this->getDoneTerminalCount();

            echo json_encode($data);
        }

        if( $request->paraMeter == 9){

            $data = $this->getDismantledTerminalCount();

            echo json_encode($data);
        }

    }

    private function getJobcardNotAcceptedTerminalCount(){

        $objTerminalRepair = new TerminalRepair();

        $data['headTitle'] =  "Jobcard Not Accepted Count";
        $data['bankWise'] = $objTerminalRepair->getJobcardNotAcceptedTerminalCountBankWise();
        $data['modelWise'] = $objTerminalRepair->getJobcardNotAcceptedTerminalCountModelWise();

        return $data;
    }

    private function getToBeInvestigatedTerminalCount(){

        $objTerminalRepair = new TerminalRepair();

        $data['headTitle'] =  "To Be Investigated Count";
        $data['bankWise'] = $objTerminalRepair->getToBeInvestigatedTerminalCountBankWise();
        $data['modelWise'] = $objTerminalRepair->getToBeInvestigatedTerminalCountModelWise();

        return $data;
    }

    private function getTodayAcceptedTerminalCount(){

        $objTerminalRepair = new TerminalRepair();

        $data['headTitle'] =  "Today Accepted Terminal Count";
        $data['bankWise'] = $objTerminalRepair->getTodayAcceptedTerminalCountBankWise();
        $data['modelWise'] = $objTerminalRepair->getTodayAcceptedTerminalCountModelWise();

        return $data;
    }

    private function getTodayReleasedTerminalCount(){

        $objTerminalRepair = new TerminalRepair();

        $data['headTitle'] =  "Today Released Terminal Count";
        $data['bankWise'] = $objTerminalRepair->getTodayReleasedTerminalCountBankWise();
        $data['modelWise'] = $objTerminalRepair->getTodayReleasedTerminalCountModelWise();

        return $data;
    }

    private function getQuotedTerminalCount(){

        $objTerminalRepair = new TerminalRepair();

        $data['headTitle'] =  "Quoted Terminal Count";
        $data['bankWise'] = $objTerminalRepair->getQuotedTerminalCountBankWise();
        $data['modelWise'] = $objTerminalRepair->getQuotedTerminalCountModelWise();

        return $data;
    }

    private function getSparepartPendingTerminalCount(){

        $objTerminalRepair = new TerminalRepair();

        $data['headTitle'] =  "Spare Part Pending Terminal Count";
        $data['bankWise'] = $objTerminalRepair->getSparepartPendingTerminalCountBankWise();
        $data['modelWise'] = $objTerminalRepair->getSparepartPendingTerminalCountModelWise();

        return $data;
    }

    private function getTestingTerminalCount(){

        $objTerminalRepair = new TerminalRepair();

        $data['headTitle'] =  "Testing Terminal Count";
        $data['bankWise'] = $objTerminalRepair->getTestingTerminalCountBankWise();
        $data['modelWise'] = $objTerminalRepair->getTestingTerminalCountModelWise();

        return $data;
    }

    private function getDoneTerminalCount(){

        $objTerminalRepair = new TerminalRepair();

        $data['headTitle'] =  "Done Terminal Count";
        $data['bankWise'] = $objTerminalRepair->getDoneTerminalCountBankWise();
        $data['modelWise'] = $objTerminalRepair->getDoneTerminalCountModelWise();

        return $data;
    }

    private function getDismantledTerminalCount(){

        $objTerminalRepair = new TerminalRepair();

        $data['headTitle'] =  "Dismantled Terminal Count";
        $data['bankWise'] = $objTerminalRepair->getDismantledTerminalCountBankWise();
        $data['modelWise'] = $objTerminalRepair->getDismantledTerminalCountModelWise();

        return $data;
    }

}

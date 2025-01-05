<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Workflow extends Model {

    use HasFactory;

	public function get_workflow(){

		$result = DB::table('workflow')->get();

		return $result;
	}

    public function getWorkflowName($workflow_id){

		$workflow_name = DB::table('workflow')->where('workflow_id', $workflow_id)->value('workflow_name');

		return $workflow_name;
	}

    public function getWorkflowShortName($workflow_id){

		$short_name = DB::table('workflow')->where('workflow_id', $workflow_id)->value('short_name');

		return $short_name;
	}

}

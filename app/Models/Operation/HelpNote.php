<?php

namespace App\Models\Operation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class HelpNote extends Model {

    use HasFactory;

	public function isGenaratedTerminalRequestNote($ticket_number, $reference){

		$result = DB::table('terminal_request_note')->where('ticketno', $ticket_number)
													->where('ref', $reference)
													->where('settle', 0)
													->where('cancel', 0)
													->exists();

		return $result;
	}

	public function isSettledTerminalRequestNote($ticket_number, $reference){

		$result = DB::table('terminal_request_note')->where('ticketno', $ticket_number)
													->where('ref', $reference)
													->where('settle', 1)
													->exists();

		return $result;
	}

	public function getTerminalRequestNoteNumber($ticket_number, $reference){

		$terminal_request_number = DB::table('terminal_request_note')->where('ticketno', $ticket_number)
																	 ->where('ref', $reference)
																	 ->where('cancel', 0)
																	 ->value('request_no');

		return $terminal_request_number;
	}

	public function isGenaratedTerminalProgrammingNote($ticket_number, $reference){

		$result = DB::table('terminal_programme_note')->where('ticketno', $ticket_number)
													  ->where('ref', $reference)
													  ->where('cancel', 0)
													  ->exists();
		return $result;
	}

	public function isGenaratedTerminalProgrammeNote($ticket_number, $reference){

		$result = DB::table('terminal_programme_note')->where('ticketno', $ticket_number)
													  ->where('ref', $reference)
													  ->where('cancel', 0)
													  ->exists();
		return $result;
	}

	public function getTerminalProgrammeNoteNumber($ticket_number, $reference){

		$terminal_programme_number = DB::table('terminal_programme_note')->where('ticketno', $ticket_number)
																	 ->where('ref', $reference)
																	 ->where('cancel', 0)
																	 ->value('tp_no');
		return $terminal_programme_number;
	}


}

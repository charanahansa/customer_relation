<?php

namespace App\Mail\Breakdown;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class breakdown_individual extends Mailable {

    use Queueable, SerializesModels;

    public function __construct($details){

        $this->details = $details;
    }

    public function build(){

        $ticket_number = $this->details['ticketno'];

        return $this->subject($ticket_number)
                    ->view('email.breakdown.breakdown_individual')->with('result', $this->details);
    }
}

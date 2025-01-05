<?php

namespace App\Mail\ReInitialization;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReInitialization_individual extends Mailable {

    use Queueable, SerializesModels;


    public function __construct($details){

        $this->details = $details;
    }


    public function build(){

        $ticket_number = $this->details['ticketno'];

        return $this->subject('Re Initilization - ' . $ticket_number)
                    ->view('email.ReInitialization.ReInitialization_individual')->with('result', $this->details);
    }
}

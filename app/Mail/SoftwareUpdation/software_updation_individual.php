<?php

namespace App\Mail\SoftwareUpdation;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class software_updation_individual extends Mailable {

    use Queueable, SerializesModels;


    public function __construct($details){

        $this->details = $details;
    }


    public function build(){

        $ticket_number = $this->details['ticketno'];

        return $this->subject('Software Updation - ' . $ticket_number)
                    ->view('email.software_updation.software_updation_individual')->with('result', $this->details);
    }
}

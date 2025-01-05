<?php

namespace App\Mail\NewInstallation;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class new_installation_individual extends Mailable {

    use Queueable, SerializesModels;


    public function __construct( $details){

        $this->details = $details;
    }

    public function build(){

        $ticket_number = $this->details['ticketno'];

        return $this->subject($ticket_number)
                    ->view('email.newinstallation.new_installation_individual')->with('result', $this->details);
    }
}

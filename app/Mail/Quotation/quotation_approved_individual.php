<?php

namespace App\Mail\Quotation;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class quotation_approved_individual extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct($details){

        $this->details = $details;
    }


    public function build(){

        $Subject = $this->details['bank'] . ' - ' . $this->details['qt_no'];

        return $this->subject($Subject)
                    ->view('email.quotation.quotation_approved_individual')->with('result', $this->details);
    }
}

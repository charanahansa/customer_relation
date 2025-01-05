<?php

namespace App\Mail\Breakdown;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class breakdown_vericentre_record extends Mailable {

    use Queueable, SerializesModels;


    public function __construct($details){

        $this->details = $details;
    }

    public function build(){

        $subject = 'Field Service Vericentre Record Updation - ' . $this->details['tid'];

        return $this->subject($subject)
                    ->view('email.breakdown.breakdown_vericentre_record')->with('result', $this->details);
    }
}

<?php

namespace App\Mail;

use App\Models\Part;
use App\Models\Run;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RunResult extends Mailable
{
    use Queueable, SerializesModels;

    public $subject ="Testing";
    private $_run;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Run $run)
    {
        $this->_run=$run;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.runResult')->with(['run'=>$this->_run]);
    }
}

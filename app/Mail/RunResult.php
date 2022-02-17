<?php

namespace App\Mail;

use App\Models\Part;
use App\Models\Run;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use DateTime;

class RunResult extends Mailable
{
    use Queueable, SerializesModels;


    private $_run;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Run $run)
    {
        $this->_run = $run;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $id_run = $this->_run->id;
        $start_date = substr($this->_run->start_date, 0, 10);
        $customer = $this->_run->company->name;

        $current_date = new DateTime();
        $currentDate = $current_date->format('Y-m-d H:i:s');
        $created_at = new DateTime($this->_run->created_at);
        $createdDate = $created_at->format('Y-m-d H:i:s');
        $last_edit = new DateTime($this->_run->last_edit);
        $lastDate = $last_edit->format('Y-m-d H:i:s');
        $closed_date = new DateTime($this->_run->closed_date);
        $closedDate = $closed_date->format('Y-m-d H:i:s');
        $hours = 0;

        if ($this->_run->status == 1) {
            if ($this->_run->isEdit) {
                $hours = $this->_run->hours;
            } else {
                $closeNonEdit = intval(bcdiv((strtotime($closedDate) - strtotime($createdDate)) / 3600, '1', 0), 10);
                $hours = $closeNonEdit;
            }
        } else {
            if ($this->_run->isEdit) {
                // current - last
                //$hours = result + $run->hours 
                $activeEdit = intval(bcdiv((strtotime($currentDate) - strtotime($lastDate)) / 3600, '1', 0), 10);
                $hours = $this->_run->hours + $activeEdit;
            } else {
                // $hours = current - created
                $activeNonEdit = intval(bcdiv((strtotime($currentDate) - strtotime($createdDate)) / 3600, '1', 0), 10);
                $hours = $activeNonEdit;
            }
        }


        $status = '';
        if ($this->_run->status == 0) {
            $status = 'Active';
        } else {
            if ($this->_run->status == 1) {
                $status = 'Completed';
            }
        }
        $description = $this->_run->description;
        $content = '';
        $plate_type = '';
        $photos = $this->_run->photos;
        $allParts = $this->_run->parts;
        $pdf = PDF::loadView('pdf.runReportImages', compact(['allParts', 'photos', 'id_run', 'start_date', 'customer', 'status', 'hours', 'description']));
        return $this->view('emails.runResult')
            ->with(['run' => $this->_run])
            ->attachData($pdf->output(), 'test.pdf', [
                'mime' => 'application/pdf',
            ])
            ->subject('Pavco Salt Spray Database - Run Results for Run #' . $this->_run->id);
    }
}

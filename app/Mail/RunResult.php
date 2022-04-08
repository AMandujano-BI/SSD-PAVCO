<?php

namespace App\Mail;

use App\Models\Part;
use App\Models\Run;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use DateTime;

class RunResult extends Mailable
{
    use Queueable, SerializesModels;


    private $_run;
    private $_zone;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Run $run, String $zone)
    {
        $this->_run = $run;
        $this->_zone = $zone;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $id_run = $this->_run->id;
        // $localStartDate = Carbon::parse($this->_run->start_date)->setTimezone($this->_zone);
        $start_date = substr($this->_run->start_date, 0, 10);
        $customer = $this->_run->company->name;
        
        $hours = $this->_run->hours;
        $run_status = $this->_run->status;
        if ($this->_run->status == 0) {
            $status = 'Active';
        } else {
            if ($this->_run->status == 1) {
                $status = 'Completed';
            }
        }

        $run_status_img = $this->_run->status;
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


        //Count columns for hide or show
        $countPlateType= count( Part::where('run_id',$this->_run->id)->where('plate_types_id','!=',null)->get());
        $countChromate = count( Part::where('run_id',$this->_run->id)->where('primaryCoatId','!=',null)->get());
        $countTopCoat = count( Part::where('run_id',$this->_run->id)->where('topCoatId','!=',null)->get());
        $countSecondaryTopCoat = count( Part::where('run_id',$this->_run->id)->where('coatId','!=',null)->get());

        $pdf = PDF::loadView('pdf.runReportImages', compact(['allParts', 'photos', 'id_run', 'start_date', 'customer', 'status', 'hours', 'description', 'run_status_img', 'run_status','countChromate','countPlateType','countTopCoat','countSecondaryTopCoat']));
        $pdf->setPaper('a4', 'landscape');
        return $this->view('emails.runResult')
            ->with(['run' => $this->_run, 'hours' => $hours, 'start_date' => $start_date,'run_status'=>$run_status,'countChromate'=>$countChromate,'countPlateType'=>$countPlateType,'countTopCoat'=>$countTopCoat,'countSecondaryTopCoat'=>$countSecondaryTopCoat])
            ->attachData($pdf->output(), 'run_report_' . $this->_run->id . '.pdf', [
                'mime' => 'application/pdf',
            ])
            ->subject('Pavco Salt Spray Database - Run Results for Run #' . $this->_run->id);
    }
}

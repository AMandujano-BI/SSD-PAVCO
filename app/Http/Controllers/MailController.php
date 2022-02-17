<?php

namespace App\Http\Controllers;

use App\Mail\RunResult;
use App\Models\Part;
use App\Models\Run;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{

    private $_run;

    public function __construct(Run $run)
    {

        $this->_run = $run;
    }

    public function store(Request $request)
    {
        try {

            $id = $request->id;
            $run = $this->_run->getRun($id);
            $run->start_date = Carbon::parse($run->start_date)->format('Y/m/d');
            for($i =0;$i<count($request->emailSend);$i++){
                Mail::to($request->emailSend[$i])->send(new RunResult($run));
            }
            return   [
                'ok' => true,
                'message' => 'Email sent successfully',
                'value' => 0,
            ];
        } catch (\Exception $e) {
            return [
                'ok' => false,
                'message' => $e->getMessage(),
                'value' => 0
            ];
        }
    }
}

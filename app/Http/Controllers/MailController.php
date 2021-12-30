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

    private $_parts;
    private $_run;

    public function __construct(Part $part, Run $run)
    {

        $this->_parts = $part;
        $this->_run = $run;
    }

    public function store(Request $request)
    {
        try {

            $id = $request->id;
            $email =$request->emailSend;
            $run = $this->_run->getRun($id);
            $run->startDate = Carbon::parse($run->startDate)->format('Y/m/d');
            Mail::to($email)->send(new RunResult($run));
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

<?php

namespace App\Http\Controllers;

use App\Mail\RunResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{


    public function store(){
        Mail::to('fernandomercado529@gmail.com')->send(new RunResult);
        return 'message enviado';
    }
}


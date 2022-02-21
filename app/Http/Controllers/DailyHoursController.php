<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Run;



class DailyHoursController extends Controller
{
    public function __construct(Run $run)
    {
        $this->_run = $run;
    }

    private $_run;
    //
    public function index()
    {
        return Inertia::render('DailyHours/Index');
    }

    public function store(Request $request)
    {
        $runResponse = $this->_run->updateHours($request);
        return response()->json($runResponse);
    }
}

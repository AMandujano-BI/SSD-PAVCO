<?php

namespace App\Http\Controllers;

use App\Models\Hour;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Run;



class DailyHoursController extends Controller
{
    private $_hour;
    private $_run;
    public function __construct(Run $run,Hour $hour)
    {
        $this->_run = $run;
        $this->_hour =$hour;
    }

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

    
   
    public function show($id)
    {
        $hourResponse = $this->_hour->getHours();
        return response()->json($hourResponse);
        //
    }
    public function getHours(){
        $hourResponse = $this->_hour->getHours();
        return response()->json($hourResponse);
    }
 

    public function update(Request $request, Hour $hour)
    {
        $hours = $this->_run->updateHoursUpdate($request->id,$request);
        return $hours;
    }
    public function destroy($id)
    {
        $hours = $this->_run->deleteHour($id);
        return $hours;
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\RunRequest;
use App\Models\Chemical;
use App\Models\PlateMethod;
use App\Models\PlateType;
use App\Models\Run;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RunController extends Controller
{
    private $_run;
    private $_chemical;
    private $_plateMethod;
    private $_plateType;



    public function __construct(Run $run, PlateMethod $plateMethod, Chemical $chemical,PlateType $plateType)
    {
        $this->_run = $run;
        $this->_plateMethod = $plateMethod;
        $this->_chemical = $chemical;
        $this->_plateType= $plateType;
    }



    public function index()
    {
        $plateMethods = $this->_plateMethod->getPlateMethods();
        $topCoats = $this->_chemical->getByType(1);
        $Chromates = $this->_chemical->getByType(2);
        $plates = $this->_chemical->getByType(3);
        $SecondaryCoats = $this->_chemical->getByType(4);
        $plateType = $this->_plateType->getPlateTypes();


        return Inertia::render(
            'Run/Index',
            [
                'plateMethods' => $plateMethods,
                'topCoats' => $topCoats,
                'chromates' => $Chromates,
                'plates' => $plates,
                'secondaryCoats' => $SecondaryCoats,
                'plateTypes' => $plateType
            ]
        );
    }
    public function getAllRuns()
    {
        $runs =$this->_run->getAllRun();

        return datatables()->of($runs)->toJson();

        // return $runs;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RunRequest $request)
    {
        
        $run = $this->_run->createRun($request);
        return response()->json($run);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Run  $run
     * @return \Illuminate\Http\Response
     */
    public function show(Run $run)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Run  $run
     * @return \Illuminate\Http\Response
     */
    public function edit(Run $run)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Run  $run
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Run $run)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Run  $run
     * @return \Illuminate\Http\Response
     */
    public function destroy(Run $run)
    {
        //
    }
}

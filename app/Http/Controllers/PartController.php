<?php

namespace App\Http\Controllers;

use App\Models\Chemical;
use App\Models\Part;
use App\Models\PlateMethod;
use App\Models\Run;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PartController extends Controller
{

    private $_part;
    private $_plateMethod;
    private $_run;
    private $_chemical;
    public function __construct(Part $part,  PlateMethod $plateMethod, Run $run, Chemical $chemical)
    {
        $this->_part = $part;
        $this->_plateMethod = $plateMethod;
        $this->_run = $run;
        $this->_chemical = $chemical;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render(
            'Parts/Index',
            []
        );
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
    public function store(Request $request)
    {
        $part = $this->_part->createPart($request);
        return $part;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $parts = $this->_part->getPartsByRun($id);
        $run = $this->_run->getRun($id);
        $plateMethods = $this->_plateMethod->getPlateMethods();
        $topCoats = $this->_chemical->getByType(1);
        $Chromates = $this->_chemical->getByType(2);
        $plateType = $this->_chemical->getByType(3);
        $SecondaryCoats = $this->_chemical->getByType(4);
        // dd($run);
        // $image=  Storage::temporaryUrl(
        //     'images/run4/mountain.jpg', now()->addMinutes(5)
        // );
        return Inertia::render(
            'Parts/Detail',
            [
                'parts' => $parts,
                'plateMethods' => $plateMethods,
                'run' => $run,
                'topCoats' => $topCoats,
                'chromates' => $Chromates,
                'plateTypes' => $plateType,
                'secondaryCoats' => $SecondaryCoats,
                // 'image' => $image,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function edit(Part $part)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Part $part)
    {
        $partResponse = $this->_part->updatePart($request->id, $request);
        return  $partResponse;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $partDeleted = $this->_part->deletePart($id);
        return $partDeleted;
    }
}

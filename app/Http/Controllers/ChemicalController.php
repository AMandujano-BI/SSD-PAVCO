<?php

namespace App\Http\Controllers;

use App\Models\Chemical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ChemicalController extends Controller
{
    private $_chemical;

    public function __construct(Chemical $chemical) {
        $this->_chemical = $chemical;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $chemicals = $this->_chemical->getAllForCategory(0);
        return Inertia::render('Chemical/Index', ['chemicals'=>$chemicals]);
    }

    public function getChemicals($type)
    {
        $chemicals = $this->_chemical->getAllForCategory($type);
        return $chemicals;
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
        try {
            DB::beginTransaction();
            $chemical = $this->_chemical->createChemical($request);

            DB::commit();
            return $chemical;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chemical  $chemical
     * @return \Illuminate\Http\Response
     */
    public function show(Chemical $chemical)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chemical  $chemical
     * @return \Illuminate\Http\Response
     */
    public function edit(Chemical $chemical)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chemical  $chemical
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $chemical = $this->_chemical->updateChemical($request);
            DB::commit();
            return $chemical;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chemical  $chemical
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $chemical = $this->_chemical->deleteChemical($id);
            DB::commit();
            return $chemical;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }
}

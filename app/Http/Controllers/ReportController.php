<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Part;
use App\Models\Run;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Report/Index');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function runReportDetail(Request $request)
    {
        // return $request;
        $startDate = '2021-12-27'; //$request->startDate;
        $endDate = '2021-12-29'; //$request->endDate;
        $coatPer_less_than = 1;
        $coatPer_more_than = 5;
        $coatTemp_less_than = 1;
        $coatTemp_more_than = 2;
        $coatPH_less_than = 1;
        $coatPH_more_than = 2;
        $coatDiptime_less_than = 1;
        $coatDiptime_more_than = 2;




        $customer = Company::where('customer', 1)->where('id', 1)->first();
        if ($customer == null) {
            //ALL CUSTOMERS
            $parts = Part::whereBetween('created_at', [$startDate, $endDate])
                //Coat
                ->whereBetween('coatPer', [$coatPer_less_than, $coatPer_more_than])
                ->whereBetween('coatTemp', [$coatTemp_less_than, $coatTemp_more_than])
                ->whereBetween('coatPH', [$coatPH_less_than, $coatPH_more_than])
                ->whereBetween('coatDiptime', [$coatDiptime_less_than, $coatDiptime_more_than])

                ->get();
            dd(count($parts));
            return $parts;
        } else {
            //ONE CUSTOMER


        }
        $pdf = resolve('dompdf.wrapper');
        $html = "<h1>test</h1>";
        $pdf->loadHTML($html);

        return $pdf->download('run_report' . '.pdf');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Chemical;
use App\Models\Company;
use App\Models\Part;
use App\Models\Run;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    private $_run;
    private $_chemical;
    private $_plateMethod;
    private $_company;



    public function __construct(Run $run,  Chemical $chemical, Company $company)
    {
        $this->_run = $run;
        $this->_chemical = $chemical;
        $this->_company = $company;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topCoats = $this->_chemical->getByType(1);
        $Chromates = $this->_chemical->getByType(2);
        $plateType = $this->_chemical->getByType(3);
        $SecondaryCoats = $this->_chemical->getByType(4);
        $customers = $this->_company->getCustomers();

        return Inertia::render('Report/Index', [
            'topCoats' => $topCoats,
            'chromates' => $Chromates,
            'plateTypes' => $plateType,
            'secondaryCoats' => $SecondaryCoats,
            'customers' => $customers,
        ]);
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
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $company_id=$request->customer;
        $thickness_first_ml = $request->thickness_first_ml;
        $thickness_second_ml = $request->thickness_second_ml;
        // dd($request);
        $customer =$request->customer;
        $plate_type =$request->plate_type;
        $chromate =$request->chromate;
        $top_coat=$request->top_coat;
        $coat=$request->coat;
        //Coat
        $coatPer_less_than = $request->coatPer_less_than;
        $coatPer_more_than = $request->coatPer_more_than;
        $coatTemp_less_than = $request->coatTemp_less_than;
        $coatTemp_more_than = $request->coatTemp_more_than;
        $coatPH_less_than = $request->coatPH_less_than;
        $coatPH_more_than = $request->coatPH_more_than;
        $coatDiptime_less_than = $request->coatDiptime_less_than;
        $coatDiptime_more_than = $request->coatDiptime_more_than;
        //topCoat
        $topCoatPer_less_than= $request->topCoatPer_less_than;
        $topCoatPer_more_than= $request->topCoatPer_more_than;
        $topCoatTemp_less_than= $request->topCoatTemp_less_than;
        $topCoatTemp_more_than= $request->topCoatTemp_more_than;
        $topCoatPH_less_than= $request->topCoatPH_less_than;
        $topCoatPH_more_than= $request->topCoatPH_more_than;
        $topCoatDiptime_less_than= $request->topCoatDiptime_less_than;
        $topCoatDiptime_more_than= $request->topCoatDiptime_more_than;
        //chromate
        $primaryPer_less_than= $request->primaryPer_less_than;
        $primaryPer_more_than= $request->primaryPer_more_than;
        $primaryTemp_less_than= $request->primaryTemp_less_than;
        $primaryTemp_more_than= $request->primaryTemp_more_than;
        $primaryPH_less_than= $request->primaryPH_less_than;
        $primaryPH_more_than= $request->primaryPH_more_than;
        $primaryDiptime_less_than= $request->primaryDiptime_less_than;
        $primaryDiptime_more_than= $request->primaryDiptime_more_than;

        $customer = Company::where('customer', 1)->where('id', 1)->first();
        if ($customer == null) {
            //ALL CUSTOMERS
            $parts = Part::whereBetween('created_at', [$startDate, $endDate])
                ->where('plate_types_id',$plate_type)
                ->where('primaryCoatId',$chromate)
                ->where('coatId',$coat)
                ->where('topCoatId',$top_coat)
                //Coat
                ->whereBetween('coatPer', [$coatPer_less_than, $coatPer_more_than])
                ->whereBetween('coatTemp', [$coatTemp_less_than, $coatTemp_more_than])
                ->whereBetween('coatPH', [$coatPH_less_than, $coatPH_more_than])
                ->whereBetween('coatDiptime', [$coatDiptime_less_than, $coatDiptime_more_than])
                //TopCoat
                ->whereBetween('topCoatPer', [$topCoatPer_less_than, $topCoatPer_more_than])
                ->whereBetween('topCoatTemp', [$topCoatTemp_less_than, $topCoatTemp_more_than])
                ->whereBetween('topCoatPH', [$topCoatPH_less_than, $topCoatPH_more_than])
                ->whereBetween('topCoatDiptime', [$topCoatDiptime_less_than, $topCoatDiptime_more_than])
                //Chromate
                ->whereBetween('primaryPer', [$primaryPer_less_than, $primaryPer_more_than])
                ->whereBetween('primaryTemp', [$primaryTemp_less_than, $primaryTemp_more_than])
                ->whereBetween('primaryPH', [$primaryPH_less_than, $primaryPH_more_than])
                ->whereBetween('primaryDiptime', [$primaryDiptime_less_than, $primaryDiptime_more_than])

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

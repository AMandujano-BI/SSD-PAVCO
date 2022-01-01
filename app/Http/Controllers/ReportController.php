<?php

namespace App\Http\Controllers;

use App\Models\Chemical;
use App\Models\Company;
use App\Models\Part;
use App\Models\Run;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $company_id = $request->customer;
        // dd($request);
        $customer = $request->customer;
        $plate_type = $request->plate_type;
        $chromate = $request->chromate;
        $top_coat = $request->top_coat;
        $coat = $request->coat;
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
        $topCoatPer_less_than = $request->topCoatPer_less_than;
        $topCoatPer_more_than = $request->topCoatPer_more_than;
        $topCoatTemp_less_than = $request->topCoatTemp_less_than;
        $topCoatTemp_more_than = $request->topCoatTemp_more_than;
        $topCoatPH_less_than = $request->topCoatPH_less_than;
        $topCoatPH_more_than = $request->topCoatPH_more_than;
        $topCoatDiptime_less_than = $request->topCoatDiptime_less_than;
        $topCoatDiptime_more_than = $request->topCoatDiptime_more_than;
        //chromate
        $primaryPer_less_than = $request->primaryPer_less_than;
        $primaryPer_more_than = $request->primaryPer_more_than;
        $primaryTemp_less_than = $request->primaryTemp_less_than;
        $primaryTemp_more_than = $request->primaryTemp_more_than;
        $primaryPH_less_than = $request->primaryPH_less_than;
        $primaryPH_more_than = $request->primaryPH_more_than;
        $primaryDiptime_less_than = $request->primaryDiptime_less_than;
        $primaryDiptime_more_than = $request->primaryDiptime_more_than;




        $customer = Company::where('customer', 1)->where('id', $company_id)->first();
        if ($customer == null) {
            //ALL CUSTOMERS
            $parts = Part::whereBetween('created_at', [$startDate, $endDate])
                ->where('plate_types_id', $plate_type)
                ->where('primaryCoatId', $chromate)
                ->where('coatId', $coat)
                ->where('topCoatId', $top_coat)
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
            return $parts;
        } else {
            //ONE CUSTOMER
            $parts = DB::table('parts')
                ->join('runs', 'parts.run_id', '=', 'runs.id')
                ->join('companies', 'runs.company_id', '=', 'companies.id')
                ->whereBetween('parts.created_at', [$startDate, $endDate])
                ->where('plate_types_id', $plate_type)
                ->where('primaryCoatId', $chromate)
                ->where('coatId', $coat)
                ->where('topCoatId', $top_coat)
                ->where('runs.company_id', $company_id)
                //Coat
                ->whereBetween('parts.coatPer', [$coatPer_less_than, $coatPer_more_than])
                ->whereBetween('parts.coatTemp', [$coatTemp_less_than, $coatTemp_more_than])
                ->whereBetween('parts.coatPH', [$coatPH_less_than, $coatPH_more_than])
                ->whereBetween('parts.coatDiptime', [$coatDiptime_less_than, $coatDiptime_more_than])
                //TopCoat
                ->whereBetween('parts.topCoatPer', [$topCoatPer_less_than, $topCoatPer_more_than])
                ->whereBetween('parts.topCoatTemp', [$topCoatTemp_less_than, $topCoatTemp_more_than])
                ->whereBetween('parts.topCoatPH', [$topCoatPH_less_than, $topCoatPH_more_than])
                ->whereBetween('parts.topCoatDiptime', [$topCoatDiptime_less_than, $topCoatDiptime_more_than])
                //Chromate
                ->whereBetween('parts.primaryPer', [$primaryPer_less_than, $primaryPer_more_than])
                ->whereBetween('parts.primaryTemp', [$primaryTemp_less_than, $primaryTemp_more_than])
                ->whereBetween('parts.primaryPH', [$primaryPH_less_than, $primaryPH_more_than])
                ->whereBetween('parts.primaryDiptime', [$primaryDiptime_less_than, $primaryDiptime_more_than])
                ->select('parts.id', 'parts.plateThick', 'parts.description', 
                'parts.topCoatPer', 'parts.topCoatTemp', 'parts.topCoatPH', 'parts.topCoatDiptime', 
                'parts.primaryPer', 'parts.primaryTemp', 'parts.primaryPH', 'parts.primaryDiptime', 
                'parts.coatPer', 'parts.coatTemp', 'parts.coatPH', 'parts.coatDiptime', 
                'parts.plate_types_id', 'parts.primaryCoatId', 'parts.coatId', 'parts.topCoatId', 'parts.run_id', 'parts.created_at')
                ->get();
        }
        $pdf = resolve('dompdf.wrapper');
        $html = "
        <style>
        @page{margin: 0px;} body__first{margin: 0;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif}
        .rectangle {
            width: 130px;
            position:absolute;
            height: 40px;
            margin: 0 63px 0px 0;
            padding: 30px 54.8px 69px 54px;
            background-color: #0271c5 !important;
        }
        img.PavcoWhite {
          margin-top: 10px;
          width: 126.2px;
          object-fit: contain;
        }
        .content{
            background-color: white;
            height: 139px;
            position: relative;
        }
        .title {
            margin: 50px 69px 41px 300px;
            font-size: 20px;
            font-weight: 600;
            color: #3b4559;
            position: absolute;
        }
        .header{
            width: 100%;
            /* height: 50px; */
            background-color: #e1e8f3;
            padding-top: 35px;
            padding-bottom: 35px;
            /* display: grid; */
        }
        .top-separation{
            margin-top: 10px;
        }
        .subheader{
            /* height: 22px; */
            margin: 35px 70px 64px 54px;
            font-size: 16px;
            font-weight: 600;
            color: #34689c;
        }
        .subheader__content{
            /* height: 22px; */
            margin-left: 54px;
            margin-right: 70px;
            font-size: 16px;
            font-weight: 600;
            color: #34689c;
        }
        .subheader__label {
            margin-right: 70px;
            font-size: 16px;
            font-weight: 600;
            color: #34689c;
        }
        .subheader__value{
            font-weight: normal;
            color: #3b4559;
        }
        body {
            background-color: #f8fafc;
        }
        .table__container{
            margin: 34px 34px 34px 34px;
            
        }
        table{
            background-color: white;
            border: none;
            border-collapse: collapse;
        }
        th{
            color: #3b4559;
            font-size: 16px;
            font-weight: 600;
            height: 72px;
            border: none;
        }
        td{
            border-top: 1px solid #979797 !important;
            color: #3b4559;
            font-size: 16px;
            font-weight: normal;
            padding-top: 13px;
            padding-right: 13px;
            padding-bottom: 13px;
            padding-left: 13px;
        }
        .notes__label{
            color: #34689c;   
            font-size: 16px;
            font-weight: 600;
            margin-left: 34px;
            margin-right: 34px;
        }
        </style>
        <div class='body__first'>
            <div class='content'>
                <span class='rectangle'>
                    <img src='https://pavco1.sfo3.digitaloceanspaces.com/assets/assets/pavco.png'  class='PavcoWhite'>
                    <!-- srcset='pavco@2x.png 2x, pavco@3x.png 3x' -->
                </span>
                <span class='title'>Salt Spray Report Results</span>
            </div>
            <div class='header'>
                <div class='subheader__content'>
                    <span class='subheader__label'>Customer: <span class='subheader__value'>$customer</span></span>
                    <span class='subheader'>StartDate: <span class='subheader__value'>$startDate</span></span>
                    <span class='subheader'>EndDate: <span class='subheader__value'>$endDate</span></span>
                    <span class='subheader'>Plate: <span class='subheader__value'>$startDate</span></span>
                    <span class='subheader'>Chromate: <span class='subheader__value'>$startDate</span></span>
                    <span class='subheader'>Topcoat: <span class='subheader__value'>$startDate</span></span>
                    <span class='subheader'>Secondary Topcoat : <span class='subheader__value'>$startDate</span></span>
                </div>
                <div class='top-separation'>
                    <div class='subheader__content'>Description: <span class='subheader__value'>$startDate</span></div>
                </div>
                <div class='top-separation'>
                    <div class='subheader__content'>Hours: <span class='subheader__value'>$startDate</span></div>
                </div>
            </div>
            <div class='body'>
                <div class='table__container'>
                    <table cellspacing='10' >
                        <thead>
                            <tr>
                                <th>Desc</th>
                                <th>Plate</th>
                                <th>Chromate</th>
                                <th>Topcoat</th>
                                <th>Secondary Topcoat</th>
                            </tr>
                        </thead>
                        <tbody>
                            $startDate
                        </tbody>
                    </table>
                </div>
                <div class='top-separation'>
                    <div class='notes__label'>Notes: <span class='subheader__value'>Parts were salts sprayed using the guidelines of the ASTM B-117.</span></div>
                </div>
                <div class='top-separation'>
                    <div class='notes__label'>Notes: <span class='subheader__value'>Pavco is not an independent testing laboratory. These results are for your information only and should be verified by an independent testing laboratory. </span></div>
                </div>
            </div>
        </div>
        ";
        $pdf->loadHTML($html);

        return $pdf->download('run_report' . '.pdf');
    }
}

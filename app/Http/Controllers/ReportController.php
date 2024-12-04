<?php

namespace App\Http\Controllers;

use App\Models\Chemical;
use App\Models\Company;
use App\Models\Part;
use App\Models\Run;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
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



        $start_date = $request->start_date;
        $endDate = $request->endDate;
        $company_id = $request->customer;
        $customer = $request->customer;
        $plate_type = $request->plate_type;
        $chromate = $request->chromate;
        $top_coat = $request->top_coat;
        $coat = $request->coat;
        $filterAll = $request->filterOption;
        if ($filterAll == 0) $filterAll = 4;

        $customerName = $request->customerName;
        $chromateName = $request->chromateName;
        $top_coatName = $request->top_coatName;
        $coatName = $request->coatName;
        $plate_typeName = $request->plate_typeName;

        if ($plate_type == 0) {
            $plate_type == null;
            $plate_typeName = 'All';
        }
        if ($chromate == 0) {
            $chromateName = 'All';
        }
        if ($top_coat == 0) {
            $top_coatName = 'All';
        }
        if ($coat == 0) {
            $coatName = 'All';
        }
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




        // $customer = Company::where('customer', 1)->where('id', $company_id)->first();
        if ($company_id == 0) {
            //ALL CUSTOMERS

            $prepareQuery = DB::table('parts')
                ->join('runs', 'parts.run_id', '=', 'runs.id')
                ->join('companies', 'runs.company_id', '=', 'companies.id')
                ->leftJoin('chemicals as chromate', 'parts.primaryCoatId', '=', 'chromate.id')
                ->leftJoin('chemicals as topcoat', 'parts.topCoatId', '=', 'topcoat.id')
                ->leftJoin('chemicals as coat', 'parts.coatId', '=', 'coat.id')
                ->whereBetween('parts.created_at', [$start_date, $endDate])
                ->where('runs.status', '!=', 2)
                ->when($filterAll, function ($query, $filterAll) {
                    if ($filterAll != 3) {
                        if ($filterAll == 4) {
                            return $query->where('runs.status', 0);
                        } else {
                            return $query->where('runs.status', $filterAll);
                        }
                    }
                })
                ->when($plate_type, function ($query, $plate_type) {
                    return $query->where('plate_types_id', $plate_type);
                })
                ->select(
                    'parts.id',
                    'parts.description',
                    'parts.run_id',
                    'parts.created_at',
                    'parts.topCoatPer',
                    'parts.topCoatTemp',
                    'parts.topCoatPH',
                    'parts.topCoatDiptime',
                    'parts.primaryPer',
                    'parts.primaryTemp',
                    'parts.primaryPH',
                    'parts.primaryDiptime',
                    'parts.coatPer',
                    'parts.coatTemp',
                    'parts.coatPH',
                    'parts.coatDiptime',
                    'parts.plate_types_id',
                    'parts.primaryCoatId',
                    'parts.coatId',
                    'parts.topCoatId',
                    'parts.isWs',
                    'parts.isRs',
                    'parts.typePlateThick',
                    'parts.plateThick',
                    'parts.hoursWs',
                    'parts.hoursRs',
                    'companies.name as company',
                    'chromate.name as chromate',
                    'topcoat.name as topcoat',
                    'runs.hours',
                    'runs.hoursClosed',
                    'runs.status'

                );

            if ($coat == 0 && $chromate == 0 && $top_coat == 0) {
                $prepareQuery->whereNull('primaryCoatId');
                $prepareQuery->whereNull('topCoatId');
                $prepareQuery->whereNull('coatId');
            }

            if ($coat != 0) {
                $prepareQuery->where('coatId', $coat);
            }

            if ($chromate != 0) {
                $prepareQuery->where('primaryCoatId', $chromate);
            }

            if ($top_coat != 0) {
                $prepareQuery->where('topCoatId', $top_coat);
            }

            $parts = $prepareQuery->orderBy('parts.run_id', 'asc')->get();
        } else {
            //ONE CUSTOMER
            $prepareQuery = DB::table('parts')
                ->join('runs', 'parts.run_id', '=', 'runs.id')
                ->join('companies', 'runs.company_id', '=', 'companies.id')
                ->leftJoin('chemicals as chromate', 'parts.primaryCoatId', '=', 'chromate.id')
                ->leftJoin('chemicals as topcoat', 'parts.topCoatId', '=', 'topcoat.id')
                ->leftJoin('chemicals as coat', 'parts.coatId', '=', 'coat.id')
                ->whereBetween('parts.created_at', [$start_date, $endDate])
                ->where('runs.company_id', $company_id)
                ->where('runs.status', '!=', 2)
                ->when($filterAll, function ($query, $filterAll) {
                    if ($filterAll != 3) {
                        if ($filterAll == 4) {
                            return $query->where('runs.status', 0);
                        } else {
                            return $query->where('runs.status', $filterAll);
                        }
                    }
                })
                ->when($plate_type, function ($query, $plate_type) {
                    return $query->where('plate_types_id', $plate_type);
                })
                ->select(
                    'parts.id',
                    'parts.description',
                    'parts.run_id',
                    'parts.created_at',
                    'parts.topCoatPer',
                    'parts.topCoatTemp',
                    'parts.topCoatPH',
                    'parts.topCoatDiptime',
                    'parts.primaryPer',
                    'parts.primaryTemp',
                    'parts.primaryPH',
                    'parts.primaryDiptime',
                    'parts.coatPer',
                    'parts.coatTemp',
                    'parts.coatPH',
                    'parts.coatDiptime',
                    'parts.plate_types_id',
                    'parts.primaryCoatId',
                    'parts.coatId',
                    'parts.topCoatId',
                    'parts.isWs',
                    'parts.isRs',
                    'parts.typePlateThick',
                    'parts.plateThick',
                    'parts.hoursWs',
                    'parts.hoursRs',
                    'companies.name as company',
                    'parts.run_id',
                    'chromate.name as chromate',
                    'topcoat.name as topcoat',
                    'runs.hours',
                    'runs.hoursClosed',
                    'runs.status'
                );

            if ($coat == 0 && $chromate == 0 && $top_coat == 0) {
                $prepareQuery->whereNull('primaryCoatId');
                $prepareQuery->whereNull('topCoatId');
                $prepareQuery->whereNull('coatId');
            }

            if ($coat != 0) {
                $prepareQuery->where('coatId', $coat);
            }

            if ($chromate != 0) {
                $prepareQuery->where('primaryCoatId', $chromate);
            }

            if ($top_coat != 0) {
                $prepareQuery->where('topCoatId', $top_coat);
            }

            $parts = $prepareQuery->orderBy('parts.run_id', 'asc')->get();
        }

        $pdf = PDF::loadView('pdf.parts', compact(['start_date', 'customerName', 'endDate', 'plate_typeName', 'chromateName', 'top_coatName', 'coatName', 'parts', 'filterAll']));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->output();
    }

    public function runReportDetailCSV(Request $request)
    {
        $start_date = $request->start_date;
        $endDate = $request->endDate;
        $company_id = $request->customer;
        $customer = $request->customer;
        $plate_type = $request->plate_type;
        $chromate = $request->chromate;
        $top_coat = $request->top_coat;
        $coat = $request->coat;
        $filterAll = $request->filterOption;
        if ($filterAll == 0) $filterAll = 4;

        $customerName = $request->customerName;
        $chromateName = $request->chromateName;
        $top_coatName = $request->top_coatName;
        $coatName = $request->coatName;
        $plate_typeName = $request->plate_typeName;

        if ($plate_type == 0) {
            $plate_type == null;
            $plate_typeName = 'All';
        }
        if ($chromate == 0) {
            $chromateName = 'All';
        }
        if ($top_coat == 0) {
            $top_coatName = 'All';
        }
        if ($coat == 0) {
            $coatName = 'All';
        }
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

        if ($company_id == 0) {
            //ALL CUSTOMERS
            $prepareQuery = DB::table('parts')
                ->join('runs', 'parts.run_id', '=', 'runs.id')
                ->join('plate_methods', 'runs.plate_methods_id', '=', 'plate_methods.id')
                ->join('companies', 'runs.company_id', '=', 'companies.id')
                ->leftJoin('chemicals as chromate', 'parts.primaryCoatId', '=', 'chromate.id')
                ->leftJoin('chemicals as topcoat', 'parts.topCoatId', '=', 'topcoat.id')
                ->leftJoin('chemicals as sectopcoat', 'parts.coatId', '=', 'sectopcoat.id')
                ->leftJoin('chemicals as coat', 'parts.coatId', '=', 'coat.id')
                ->leftJoin('chemicals as plate', 'parts.plate_types_id', '=', 'plate.id')
                ->when(!empty($start_date) && !empty($endDate), function ($query) use ($start_date, $endDate) {
                    return $query->whereBetween('parts.created_at', [$start_date, $endDate]);
                })
                ->where('runs.status', '!=', 2)
                ->when($filterAll, function ($query, $filterAll) {
                    if ($filterAll != 3) {
                        if ($filterAll == 4) {
                            return $query->where('runs.status', 0);
                        } else {
                            return $query->where('runs.status', $filterAll);
                        }
                    }
                })
                ->when($plate_type, function ($query, $plate_type) {
                    return $query->where('plate_types_id', $plate_type);
                })
                ->select(
                    'parts.id',
                    'parts.description',
                    'parts.run_id',
                    'parts.created_at',
                    'parts.topCoatPer',
                    'parts.topCoatTemp',
                    'parts.topCoatPH',
                    'parts.topCoatDiptime',
                    'parts.primaryPer',
                    'parts.primaryTemp',
                    'parts.primaryPH',
                    'parts.primaryDiptime',
                    'parts.coatPer',
                    'parts.coatTemp',
                    'parts.coatPH',
                    'parts.coatDiptime',
                    'parts.plate_types_id',
                    'parts.primaryCoatId',
                    'parts.coatId',
                    'parts.topCoatId',
                    'parts.isWs',
                    'parts.isRs',
                    'parts.typePlateThick',
                    'parts.plateThick',
                    'parts.hoursWs',
                    'parts.hoursRs',
                    'companies.name as company',
                    'chromate.name as chromate',
                    'topcoat.name as topcoat',
                    'sectopcoat.name as sectopcoat',
                    'runs.hours as hours',
                    'runs.hoursClosed',
                    'runs.status',
                    'runs.description as description_run',
                    'runs.start_date as start_date_run',
                    'plate_methods.name as method',
                    'plate.name as plate_name',
                );

            if ($coat == 0 && $chromate == 0 && $top_coat == 0) {
                $prepareQuery->whereNull('primaryCoatId');
                $prepareQuery->whereNull('topCoatId');
                $prepareQuery->whereNull('coatId');
            }

            if ($coat != 0) {
                $prepareQuery->where('coatId', $coat);
            }

            if ($chromate != 0) {
                $prepareQuery->where('primaryCoatId', $chromate);
            }

            if ($top_coat != 0) {
                $prepareQuery->where('topCoatId', $top_coat);
            }

            $parts = $prepareQuery->orderBy('parts.run_id', 'asc')->get();
        } else {
            //ONE CUSTOMER
            $prepareQuery = DB::table('parts')
                ->join('runs', 'parts.run_id', '=', 'runs.id')
                ->join('plate_methods', 'runs.plate_methods_id', '=', 'plate_methods.id')
                ->join('companies', 'runs.company_id', '=', 'companies.id')
                ->leftJoin('chemicals as chromate', 'parts.primaryCoatId', '=', 'chromate.id')
                ->leftJoin('chemicals as topcoat', 'parts.topCoatId', '=', 'topcoat.id')
                ->leftJoin('chemicals as sectopcoat', 'parts.coatId', '=', 'sectopcoat.id')
                ->leftJoin('chemicals as coat', 'parts.coatId', '=', 'coat.id')
                ->leftJoin('chemicals as plate', 'parts.plate_types_id', '=', 'plate.id')
                ->when(!empty($start_date) && !empty($endDate), function ($query) use ($start_date, $endDate) {
                    return $query->whereBetween('parts.created_at', [$start_date, $endDate]);
                })
                ->where('runs.company_id', $company_id)
                ->where('runs.status', '!=', 2)
                ->when($filterAll, function ($query, $filterAll) {
                    if ($filterAll != 3) {
                        if ($filterAll == 4) {
                            return $query->where('runs.status', 0);
                        } else {
                            return $query->where('runs.status', $filterAll);
                        }
                    }
                })
                ->when($plate_type, function ($query, $plate_type) {
                    return $query->where('plate_types_id', $plate_type);
                })
                ->select(
                    'parts.id',
                    'parts.description',
                    'parts.run_id',
                    'parts.created_at',
                    'parts.topCoatPer',
                    'parts.topCoatTemp',
                    'parts.topCoatPH',
                    'parts.topCoatDiptime',
                    'parts.primaryPer',
                    'parts.primaryTemp',
                    'parts.primaryPH',
                    'parts.primaryDiptime',
                    'parts.coatPer',
                    'parts.coatTemp',
                    'parts.coatPH',
                    'parts.coatDiptime',
                    'parts.plate_types_id',
                    'parts.primaryCoatId',
                    'parts.coatId',
                    'parts.topCoatId',
                    'parts.isWs',
                    'parts.isRs',
                    'parts.typePlateThick',
                    'parts.plateThick',
                    'parts.hoursWs',
                    'parts.hoursRs',
                    'companies.name as company',
                    'parts.run_id',
                    'chromate.name as chromate',
                    'topcoat.name as topcoat',
                    'sectopcoat.name as sectopcoat',
                    'runs.hours as hours',
                    'runs.hoursClosed',
                    'runs.status',
                    'runs.description as description_run',
                    'runs.start_date as start_date_run',
                    'plate_methods.name as method',
                    'plate.name as plate_name',
                );

            if ($coat == 0 && $chromate == 0 && $top_coat == 0) {
                $prepareQuery->whereNull('primaryCoatId');
                $prepareQuery->whereNull('topCoatId');
                $prepareQuery->whereNull('coatId');
            }

            if ($coat != 0) {
                $prepareQuery->where('coatId', $coat);
            }

            if ($chromate != 0) {
                $prepareQuery->where('primaryCoatId', $chromate);
            }

            if ($top_coat != 0) {
                $prepareQuery->where('topCoatId', $top_coat);
            }

            $parts = $prepareQuery->orderBy('parts.run_id', 'asc')->get();
        }

        // Prepare the CSV data
        $csvData = [];
        $csvData[] = [
            'Run #',
            'Customer',
            'StartDate Run',
            'Method',
            'Description',
            'Plate',
            'Type Thickness',
            'Thickness',
            'Chromate',
            '%',
            '∘F',
            'pH',
            'sec',
            'Topcoat',
            '%',
            '∘F',
            'pH',
            'sec',
            'Secondary Topcoat',
            '%',
            '∘F',
            'pH',
            'sec',
            'White Salt',
            'Red Rust',
            'Hours',
            'Description Run',
        ];

        foreach ($parts as $part) {
            $csvData[] = [
                $part->run_id,
                $part->company,
                substr($part->start_date_run, 0, 10),
                $part->method,
                $part->description,

                $part->plate_name,
                ($part->typePlateThick == 0) ? '' : (($part->typePlateThick == 1) ? 'microns' : 'mils'),
                $part->plateThick,

                $part->chromate,
                $part->primaryPer,
                $part->primaryTemp,
                $part->primaryPH,
                $part->primaryDiptime,

                $part->topcoat,
                $part->topCoatPer,
                $part->topCoatTemp,
                $part->topCoatPH,
                $part->topCoatDiptime,

                $part->sectopcoat,
                $part->coatPer,
                $part->coatTemp,
                $part->coatPH,
                $part->coatDiptime,

                $part->hoursWs,
                $part->hoursRs,
                $part->hours,
                $part->description_run,
            ];
        }

        $csvData[] = [];

        $data = [
            'Customer' => $customerName,
            'StartDate' => $start_date,
            'EndDate' => $endDate,
            'Chromate' => $chromateName,
            'Status' => ($filterAll == 3) ? 'All' : (($filterAll == 4) ? 'Active' : 'Complete'),
            'Plate' => $plate_typeName,
            'TopCoat' => $top_coatName,
            'Coat' => $coatName,
        ];

        foreach ($data as $key => $value) {
            $csvData[] = [$key, $value];
        }

        // Generate the CSV file
        $filename = 'Run_Report.csv';
        $output = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=' . $filename);
        foreach ($csvData as $row) {
            fputcsv($output, $row);
        }
        fclose($output);

        // Return a response
        return response()->make('', 200);
    }
}

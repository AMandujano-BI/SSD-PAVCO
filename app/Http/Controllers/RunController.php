<?php

namespace App\Http\Controllers;

use App\Http\Requests\RunRequest;
use App\Models\Chemical;
use App\Models\Company;
use App\Models\Part;
use App\Models\PlateMethod;
use App\Models\Run;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use DateTime;

class RunController extends Controller
{
    private $_run;
    private $_chemical;
    private $_plateMethod;
    private $_company;



    public function __construct(Run $run, PlateMethod $plateMethod, Chemical $chemical, Company $company)
    {
        $this->_run = $run;
        $this->_plateMethod = $plateMethod;
        $this->_chemical = $chemical;
        $this->_company = $company;
    }



    public function index()
    {
        $plateMethods = $this->_plateMethod->getPlateMethods();
        $topCoats = $this->_chemical->getByType(1);
        $Chromates = $this->_chemical->getByType(2);
        $plateType = $this->_chemical->getByType(3);
        $SecondaryCoats = $this->_chemical->getByType(4);
        $customers = $this->_company->getCustomers();


        return Inertia::render(
            'Run/Index',
            [
                'plateMethods' => $plateMethods,
                'topCoats' => $topCoats,
                'chromates' => $Chromates,
                'plateTypes' => $plateType,
                'secondaryCoats' => $SecondaryCoats,
                'customers' => $customers,
            ]
        );
    }
    public function getAllRuns($status)
    {
        $runs = $this->_run->getAllRun($status);

        return datatables()->of($runs)->toJson();

    }
    public function getAllRunsByDate($startDate, $endDate, $status)
    {
        $runs = $this->_run->getAllRunByDate($startDate,$endDate,$status);

        // return datatables()->of($runs)->toJson();
        return $runs;

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
        // return Redirect::route('part.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Run  $run
     * @return \Illuminate\Http\Response
     */
    public function show(Run $run)
    {
        $runGet = $this->_run->getRun($run->id);
        return $runGet;
        // return datatables()->of($runGet)->toJson();
    }
    public function runDetail($id)
    {
        $run = $this->_run->getRun($id);
        return Inertia::render(
            'Run/DetailRun',
            [
                'run' => $run,

            ]
        );
    }
    public function viewerPhotos($id)
    {
        return Inertia::render(
            'Run/ViewerPhotos',
            [
                'id' => $id,

            ]
        );
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
        $runResponse = $this->_run->updateRunPart($request->id, $request);
        return response()->json($runResponse);
    }

    public function closeRun($id)
    {
        $run = $this->_run->closeRun($id);
        return response()->json($run);
    }

    public function reopenRun($id)
    {
        $run = $this->_run->reopenRun($id);
        return response()->json($run);
    }

    public function updatePartsWsRs(Request $request)
    {
        $partsResponse = $this->_run->updateParts($request);
        return response()->json($partsResponse);
    }

    public function deleteRun($id)
    {
        $run = $this->_run->deleteRun($id);
        return response()->json($run);
    }

    public function downloadPdf($id,  $date)
    {
        // dd($request);

        $run = $this->_run->getRun($id);
        // $pdf = PDF::loadView('runReport', compact('run'));
        $zone= str_replace("'", '', $date);
        $zone= str_replace("----", '/', $zone);

        $id_run = $run->id;
        // $localStartDate = Carbon::parse($run->start_date)->setTimezone($zone);
        $start_date = substr($run->start_date, 0, 10);
        $customer = $run->company->name;
        $hours = $run->hours;
        $hoursClosed = $run->hoursClosed;

        $status = '';
        $run_status = $run->status;
        if ($run->status == 0) {
            $status = 'Active';
        } else {
            if ($run->status == 1) {
                $status = 'Completed';
            }
        }
        $description = $run->description;
        $content = '';
        $plate_type = '';

        $allParts = $run->parts;

        $countPlateType= count( Part::where('run_id',$run->id)->where('plate_types_id','!=',null)->get());
        $countChromate = count( Part::where('run_id',$run->id)->where('primaryCoatId','!=',null)->get());
        $countTopCoat = count( Part::where('run_id',$run->id)->where('topCoatId','!=',null)->get());
        $countSecondaryTopCoat = count( Part::where('run_id',$run->id)->where('coatId','!=',null)->get());

        $pdf = PDF::loadView('pdf.runReport', compact(['allParts', 'id_run', 'start_date', 'customer', 'status', 'hours', 'hoursClosed', 'description', 'run_status','countChromate','countPlateType','countTopCoat','countSecondaryTopCoat']));
        $pdf->setPaper('a4', 'landscape');
        //$pdf->dpi(50);
        // return $pdf->output();
        return $pdf->download('run_report_' . $run->id . '.pdf');
    }

    public function downloadPlus($id,$date)
    {
        $run = $this->_run->getRun($id);
        $id_run = $run->id;
        $zone= str_replace("'", '', $date);
        $zone= str_replace("----", '/', $zone);
        // $localStartDate = Carbon::parse($run->start_date)->setTimezone($zone);
        $start_date = substr($run->start_date, 0, 10);
        $customer = $run->company->name;
        $hours = $run->hours;
        $hoursClosed = $run->hoursClosed;

        $run_status_img = $run->status;
        $status = '';
        if ($run->status == 0) {
            $status = 'Active';
        } else if ($run->status == 1) {
            $status = 'Completed';
        }
        $description = $run->description;
        $content = '';
        $plate_type = '';
        $allParts = $run->parts;
        $photos = $run->photos;
        //Count columns for hide or show
        $countPlateType= count( Part::where('run_id',$run->id)->where('plate_types_id','!=',null)->get());
        $countChromate = count( Part::where('run_id',$run->id)->where('primaryCoatId','!=',null)->get());
        $countTopCoat = count( Part::where('run_id',$run->id)->where('topCoatId','!=',null)->get());
        $countSecondaryTopCoat = count( Part::where('run_id',$run->id)->where('coatId','!=',null)->get());


        $pdf = PDF::loadView('pdf.runReportImages', compact(['allParts', 'photos', 'id_run', 'start_date', 'customer', 'status', 'hours', 'hoursClosed', 'description', 'run_status_img','countChromate','countPlateType','countTopCoat','countSecondaryTopCoat']));
        $pdf->setPaper('a4', 'landscape');
        // return $pdf->output();
        return $pdf->download('run_report_' . $run->id . '.pdf');
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

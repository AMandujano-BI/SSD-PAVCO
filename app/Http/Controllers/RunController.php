<?php

namespace App\Http\Controllers;

use App\Http\Requests\RunRequest;
use App\Models\Chemical;
use App\Models\PlateMethod;
use App\Models\Run;
use Illuminate\Http\Request;
use Inertia\Inertia;
use DateTime;
use Date;

class RunController extends Controller
{
    private $_run;
    private $_chemical;
    private $_plateMethod;



    public function __construct(Run $run, PlateMethod $plateMethod, Chemical $chemical)
    {
        $this->_run = $run;
        $this->_plateMethod = $plateMethod;
        $this->_chemical = $chemical;
    }



    public function index()
    {
        $plateMethods = $this->_plateMethod->getPlateMethods();
        $topCoats = $this->_chemical->getByType(1);
        $Chromates = $this->_chemical->getByType(2);
        $plateType = $this->_chemical->getByType(3);
        $SecondaryCoats = $this->_chemical->getByType(4);


        return Inertia::render(
            'Run/Index',
            [
                'plateMethods' => $plateMethods,
                'topCoats' => $topCoats,
                'chromates' => $Chromates,
                'plateTypes' => $plateType,
                'secondaryCoats' => $SecondaryCoats,
            ]
        );
    }
    public function getAllRuns($status)
    {
        $runs = $this->_run->getAllRun($status);

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
       $runGet = $this->_run->getRun($run->id) ;
       return $runGet;
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

    public function deleteRun($id)
    {
        $run = $this->_run->deleteRun($id);
        return response()->json($run);
    }

    public function downloadPdf($id)
    {
        $run = $this->_run->getRun($id);
        $id_run = $run->id;
        $startDate = substr($run->startDate, 0, 10);
        $customer = $run->user_id;

        $currentDate = new DateTime();
        $pastDate = new DateTime($run->startDate);
        $firstDate = $pastDate->format('Y-m-d H:i:s');
        $endDate = $currentDate->format('Y-m-d H:i:s');
        $hourdiff = round((strtotime($endDate) - strtotime($firstDate))/3600, 1);
        $hours = intval($hourdiff, 10);

        $status = '';
        if ($run->status == '1') {
            $status = 'Active';
        } else {
            if ($run->status == '0') {
                $status = 'Completed';
            }
        }
        $description = $run->description;
        $content = '';
        $plate_type = '';
        foreach ($run->parts as $part) {
            $plate_type = $part->plateType->name;

            $chromate= $part->chromate->name;
            $primaryPer = $part->primaryPer .'%';
            $primaryTemp = $part->primaryTemp .'º';
            $primaryPH = $part->primaryPH .'pH';
            $primaryDiptime = $part->primaryDiptime .'sec';
            
            $topCoat = $part->topCoat->name;
            $topCoatPer = $part->topCoatPer . '%';
            $topCoatTemp = $part->topCoatTemp . '°';
            $topCoatPH = $part->topCoatPH . 'pH';
            $topCoatDiptime = $part->topCoatDiptime . 'sec';

            $coat= $part->coat->name;
            $coatPer = $part->coatPer . '%';
            $coatTemp = $part->coatTemp . '°';
            $coatPH = $part->coatPH . 'pH';
            $coatDiptime = $part->coatDiptime . 'sec';

            $content .=
            "<tr>
                <td>$part->description</td>
                <td>$plate_type</td>
                
                <td>
                    $chromate /
                    $primaryPer /
                    $primaryTemp /
                    $primaryPH /
                    $primaryDiptime
                </td>
                <td>
                    $topCoat /
                    $topCoatPer /
                    $topCoatTemp /
                    $topCoatPH /
                    $topCoatDiptime
                </td>
                <td>
                    $coat /
                    $coatPer /
                    $coatTemp /
                    $coatPH /
                    $coatDiptime
                </td>
                <td>Active</td>
                <td>Active</td>
            </tr>
            ";
        }

        $pdf = resolve('dompdf.wrapper');
        $html = "
           
            <h1>Salt Spray Report Results</h1>
            <hr>
            <br>
            <p> <strong>Run #</strong> $id_run</p>
            <p> <strong>StartDate</strong> $startDate</p>
            <p> <strong>Customer</strong> $customer</p>
            <p> <strong>Status</strong> $status</p>
            <p> <strong>Description</strong> $description</p>
            <p> <strong>Hours</strong> $hours</p>
            <br>
            <br>

            <table cellspacing='0' border='1'>
                <thead>
                    <tr>
                        <th>Desc</th>
                        <th>Plate</th>
                        <th>Chromate</th>
                        <th>Topcoat</th>
                        <th>Secondary Topcoat</th>
                        <th>White Salts</th>
                        <th>Red Rust</th>
                    </tr>
                </thead>
                <tbody>
                    $content                    
                </tbody>
            </table>
            <br>
            <br>
            <hr>
            <p><strong>Notes</strong>: Parts were salts sprayed using the guidelines of the ASTM B-117.</p>
            <p><strong>Notes</strong>: Pavco is not an independent testing laboratory. These results are for your information only and should be verified by an independent testing laboratory.</p>
        ";
        $pdf->loadHTML($html);

        return $pdf->download('run_report_'.$run->id.'.pdf');
    }
    
    public function downloadPlus($id)
    {
        $run = $this->_run->getRun($id);
        $id_run = $run->id;
        $startDate = substr($run->startDate, 0, 10);
        $customer = $run->user_id;
        if ($run->status == '1') {
            $status = 'Active';
        } else {
            if ($run->status == '0') {
                $status = 'Completed';
            }
        }
        $description = $run->description;
        $content = '';
        $plate_type = '';
        foreach ($run->parts as $part) {
            $plate_type = $part->plateType->name;
            $coat= $part->coat->name;
            $topCoat = $part->topCoat->name;
            $chromate= $part->chromate->name;
            $content .=
            "<tr>
                <td>$part->description</td>
                <td>$plate_type</td>
                
                <td>
                    $chromate /
                    $part->primaryPer /
                    $part->primaryTemp /
                    $part->primaryPH /
                    $part->primaryDiptime
                </td>
                <td>
                    $topCoat /
                    $part->topCoatPer /
                    $part->topCoatTemp /
                    $part->topCoatPH /
                    $part->topCoatDiptime
                </td>
                <td>
                    $coat /
                    $part->coatPer /
                    $part->coatTemp /
                    $part->coatPH /
                    $part->coatDiptime
                </td>
                <td>Active</td>
                <td>Active</td>
            </tr>
            ";
        }

        $photos = $run->photos;
        $photosContent = '';

        if( strlen($photos) > 0) {
            foreach ($photos as $photo) {
                $photosContent .= 
                "
                    <div style='page-break-inside:avoid;'>
                        <p>Image - $photo->name</p>
                        <hr>
                        <img src='$photo->image' alt='$photo->name' style='width: auto; max-height: 500px; margin-top: 4.7em' >
                        <br>
                        <br>
                    </div>
                ";
            }
        } 


        $pdf = resolve('dompdf.wrapper');
        $html = "
            <h1>Salt Spray Report Results</h1>
            <hr>
            <br>
            <p> <strong>Run #</strong> $id_run</p>
            <p> <strong>StartDate</strong> $startDate</p>
            <p> <strong>Customer</strong> $customer</p>
            <p> <strong>Status</strong> $status</p>
            <p> <strong>Description</strong> $description</p>
            <br>
            <br>

            <table  cellspacing='0' border='1'>
                <thead>
                    <tr>
                        <th>Desc</th>
                        <th>Plate</th>
                        <th>Chromate</th>
                        <th>Topcoat</th>
                        <th>Secondary Topcoat</th>
                        <th>White Salts</th>
                        <th>Red Rust</th>
                    </tr>
                </thead>
                <tbody>
                    $content                    
                </tbody>
            </table>
            <br>
            <br>
            <hr>
            <p><strong>Notes</strong>: Parts were salts sprayed using the guidelines of the ASTM B-117.</p>
            <p><strong>Notes</strong>: Pavco is not an independent testing laboratory. These results are for your information only and should be verified by an independent testing laboratory.</p>
            <br>
            <br>
            <br>
            <br>
            $photosContent
        ";
        $pdf->loadHTML($html);

        return $pdf->download('report.pdf');
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

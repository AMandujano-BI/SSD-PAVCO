<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <style>
        @page {
            margin: 0px;
        }

        .page-break {
            page-break-before: always;
        }

        .body__first {
            margin: 0;
            font-family: Helvetica
        }

        .rectangle {
            width: 130px;
            position: absolute;
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

        .content {
            background-color: white;
            height: 139px;
            position: relative;
        }

        .title {
            margin: 50px 69px 41px 300px;
            font-size: 20px;
            font-weight: bold;
            color: #3b4559;
            position: absolute;
        }

        .header {
            width: 100%;
            /* height: 50px; */
            background-color: #e1e8f3;
            padding-top: 35px;
            padding-bottom: 35px;
            /* display: grid; */
        }

        .top-separation {
            margin-top: 10px;
        }

        .subheader {
            /* height: 22px; */
            margin: 35px 70px 64px 54px;
            font-size: 16px;
            font-weight: bold;
            color: #34689c;
        }

        .subheader__content {
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
            font-weight: bold;
            color: #34689c;
        }

        .subheader__value {
            font-weight: normal;
            color: #3b4559;
        }

        body {
            background-color: #f8fafc;
        }

        .table__container {
            margin: 34px 34px 34px 34px;

        }

        .table {
            background-color: white;
            border: none;
            border-collapse: collapse;
        }

        th {
            color: #3b4559;
            font-size: 16px;
            font-weight: bold;
            height: 72px;
            border: none;
        }

        td {
            color: #3b4559;
            font-size: 16px;
            font-weight: normal;
            padding: 8px;
        }

        .notes__label {
            color: #34689c;
            font-size: 16px;
            font-weight: bold;
            margin-left: 34px;
            margin-right: 34px;

        }

        .image {
            margin: 34px;
        }

        .clear {
            clear: both;
        }

        .object-fit {
            object-fit: contain;
        }

        .reset-p {
            margin-block-end: 0rem;
            margin-block-start: 0rem;
            display: block;
        }

        .py-1 {
            padding: 1rem 0;
        }

        .mt-1 {
            margin-top: 1rem;
        }

        p-2 {
            padding: 2rem;
        }

        .td-styles {
            width: 320px;
            height: 330px;
            text-align: center;
            vertical-align: bottom;
            overflow: hidden;
        }
    </style>

    <div class='body__first'>
        <div class='content'>
            <span class='rectangle'>
                <img src="https://pavcoprod.sfo3.digitaloceanspaces.com/images/assets/pavco@3x.png" alt='Pavco Icon' class='PavcoWhite'>
                <!-- srcset='pavco@2x.png 2x, pavco@3x.png 3x' -->
            </span>
            <span class='title'>Salt Spray Report Results</span>
        </div>
        <div class='header'>
            <div class='subheader__content'>
                <span class='subheader__label'>Run: <span class='subheader__value'>{{$id_run}}</span></span>
                <span class='subheader'>StartDate: <span class='subheader__value'>{{$start_date}}</span></span>
                <span class='subheader'>Customer: <span class='subheader__value'>{{$customer}}</span></span>
                @if(isset($method))
                    <span class='subheader'>Method: <span class='subheader__value'>{{$method}}</span></span>
                @endif
                <span class='subheader'>Status: <span class='subheader__value'>{{$status}}</span></span>
                <span class='subheader'>Hours: <span class='subheader__value'>{{$hours}}</span></span>
            </div>
            <div class='top-separation'>
                <div class='subheader__content'><span style="font-weight: bold;">Description:</span> <span class='subheader__value'>{{$description}}</span></div>
            </div>
        </div>
        <div class='body'>
            <div class='table__container'>
                <table class="table" cellspacing='10' style='width:100%;'>
                    >

                    <thead>
                        @if(count($allParts)==0)
                        <tr>
                            <th>Desc</th>
                            <th>Plate</th>
                            <th>Chromate</th>
                            <th>Topcoat</th>
                            <th>Secondary Topcoat</th>
                            <th>White Salt</th>
                            <th>Red Rust</th>
                        </tr>
                        @endif

                        @if(count($allParts)>0)
                        <tr>
                            <th>Desc</th>

                            @if($countPlateType > 0 )
                            <th>Plate</th>
                            @endif
                            @if($countChromate >0)
                            <th>Chromate </th>
                            @endif
                            @if($countTopCoat > 0)
                            <th>Topcoat</th>
                            @endif

                            @if($countSecondaryTopCoat > 0)
                            <th>Secondary Topcoat</th>

                            @endif
                            <th>White Salt</th>
                            <th>Red Rust</th>
                        </tr>
                        @endif
                    </thead>
                    <tbody>
                        @if(count($allParts)>0)
                        @foreach ($allParts as $part)
                        <tr style="text-align: center;">
                            <td style="text-align: center;">{{$part->description}}</td>
                            @if($countPlateType > 0 )
                            <td style="text-align: center;">
                                @if($part->plateType)
                                {{$part->plateType->name}}
                                                  - {{$part->plateThick}}
                                              @if($part->typePlateThick == 1)
                                                microns
                                              @endif

                                              @if($part->typePlateThick == 2)
                                              mils
                                              @endif

                                @endif
                            </td>
                            @endif
                            @if($countChromate >0)
                            <td style="text-align: center;">
                                @if($part->chromate)
                                {{$part->chromate->name}}

                                @if($part->primaryPer !=null)
                                <span> / {{$part->primaryPer}} % </span>

                                @endif


                                @if($part->primaryTemp!=null)
                                <span>/ {{$part->primaryTemp}} ° </span>
                                @endif

                                @if($part->primaryPH!=null)
                                <span>/ {{$part->primaryPH}} pH </span>
                                @endif


                                @if($part->primaryDiptime!=null)
                                <span>/ {{$part->primaryDiptime}} sec </span>
                                @endif



                                @endif
                            </td>
                            @endif

                            @if($countTopCoat > 0)
                            <td style="text-align: center;">
                                @if($part->topCoat)

                                {{$part->topCoat->name}}


                                @if($part->topCoatPer !=null)
                                <span>/ {{$part->topCoatPer}} %</span>
                                @endif


                                @if($part->topCoatTemp !=null)
                                <span>/ {{$part->topCoatTemp}} ° </span>
                                @endif

                                @if($part->topCoatPH!=null)
                                <span>/ {{$part->topCoatPH}} pH </span>
                                @endif

                                @if($part->topCoatDiptime!=null)
                                <span>/ {{$part->topCoatDiptime}} sec </span>
                                @endif

                                @endif
                            </td>
                            @endif
                            @if($countSecondaryTopCoat > 0)
                            <td style="text-align: center;">
                                @if($part->coat)

                                {{$part->coat->name}}

                                @if($part->coatPer!=null)
                                <span>/ {{$part->coatPer}} %</span>
                                @endif


                                @if($part->coatTemp!=null)
                                <span>/ {{$part->coatTemp}} °</span>
                                @endif

                                @if($part->coatPH!=null)
                                <span>/ {{$part->coatPH}} pH</span>
                                @endif

                                @if($part->coatDiptime!=null)
                                <span>/ {{$part->coatDiptime}} sec</span>
                                @endif

                                @endif
                            </td>
                            @endif




                            <td style="text-align: center;">
                                @if ( $part->isWs != null)
                                {{$part->hoursWs}} hrs
                                @else
                                @if ($run_status_img == 1)
                                Removed at {{$hoursClosed}} hrs
                                @else
                                Active
                                @endif
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if ( $part->isRs != null)
                                {{$part->hoursRs}} hrs
                                @else
                                @if ($run_status_img == 1)
                                Removed at {{$hoursClosed}} hrs
                                @else
                                Active
                                @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>

                </table>
            </div>
            <div class='top-separation'>
                <div class='notes__label'>Notes: <span class='subheader__value'>Parts were salt sprayed using the guidelines of the ASTM B-117.</span></div>
            </div>
            <div class='top-separation'>
                <div class='notes__label'>Notes: <span class='subheader__value'>Pavco is not an independent testing laboratory. These results are for your information only and should be verified by an independent testing laboratory. </span></div>
            </div>
            @if ( count($photos) > 0 )
            <div>
                <table cellspacing='2' class="page-break p-2" style="margin: 2rem 3rem; overflow: hidden;">

                    <tbody>
                        @for($i = 0; $i < count($photos); ++$i) <tr>
                            @if (($i+($i*2)) < count($photos))
                            <td class="td-styles">
                                <img src='{{ asset($photos[$i+($i*2)]['image']) }}' alt='{{$photos[$i]['description']}}' style='max-height: 250px; max-width: 320px;'>
                                <span class="reset-p">{{$photos[$i+($i*2)]['description']}}</span>
                            </td>
                                @else
                                @break
                                @endif

                                @if ( ($i+($i*2)+1) < count($photos) )
                                <td class="td-styles">
                                    <img src='{{ asset($photos[$i+($i*2)+1]['image']) }}' alt='{{$photos[$i]['description']}}' style='max-height: 250px; max-width: 320px;'>
                                    <span class="reset-p">{{$photos[$i+($i*2)+1]['description']}}</span>
                                </td>
                                    @else
                                    @break
                                    @endif

                                    @if ( ($i+($i*2)+2) < count($photos) )
                                    <td class="td-styles">
                                        <img src='{{ asset($photos[$i+($i*2)+2]['image']) }}' alt='{{$photos[$i]['description']}}' style='max-height: 250px; max-width: 320px;'>
                                        <span class="reset-p">{{$photos[$i+($i*2)+2]['description']}}</span>
                                    </td>
                                        @else
                                        @break
                                        @endif

                                        </tr>
                                        @endfor
                    </tbody>
                </table>
            </div>
            @endif

        </div>
    </div>
</body>

</html>

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
 
        @page{margin: 0px;} .body__first{margin: 0;font-family: Helvetica}
        
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
            font-weight: bold;
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
            font-weight: bold;
            color: #34689c;
        }
        .subheader__content{
            /* height: 22px; */
            margin-left: 54px;
            margin-right: 70px;
            font-size: 16px;
            font-weight: bold;
            color: #34689c;
        }
        .subheader__label {
            margin-right: 70px;
            font-size: 16px;
            font-weight: bold;
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
            font-weight: bold;
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
            font-weight: bold;
            margin-left: 34px;
            margin-right: 34px;
        }
    </style>
    <div class='body__first'>
        <div class='content'>
            <span class='rectangle'>
                <img src='https://pavcoprod.sfo3.digitaloceanspaces.com/images/assets/pavco@3x.png'  class='PavcoWhite'>
                <!-- srcset='pavco@2x.png 2x, pavco@3x.png 3x' -->
            </span>
            <span class='title'>Salt Spray Report Results</span>
        </div>
        <div class='header'>
            <div class='subheader__content'>
                <span class='subheader__label'>Run: <span class='subheader__value'>{{$id_run}}</span></span>
                <span class='subheader'>StartDate: <span class='subheader__value'>{{$start_date}}</span></span>
                <span class='subheader'>Customer: <span class='subheader__value'>{{$customer}}</span></span>
                <span class='subheader'>Status: <span class='subheader__value'>{{$status}}</span></span>
                <span class='subheader'>Hours: <span class='subheader__value'>{{$hours}}</span></span>
            </div>
            <div class='top-separation'>
                <div class='subheader__content'>Description: <span class='subheader__value'>{{$description}}</span></div>
            </div>
        </div>
        <div class='body'>
            <div class='table__container'>
                <table cellspacing='10' style='width:100%;' >
                    <thead>
                        <tr>
                            <th>Desc</th>
                            <th>Plate</th>
                            <th>Chromate</th>
                            <th>Topcoat</th>
                            <th>Secondary Topcoat</th>
                            <th>White Salt</th>
                            <th>Red Rust</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allParts as $part)
                        @if (
                                    $part->topCoatPer != null || $part->topCoatTemp != null || $part->topCoatPH != null || $part->topCoatDiptime != null ||
                                    $part->primaryPer != null || $part->primaryTemp != null || $part->primaryPH != null || $part->primaryDiptime != null ||
                                    $part->coatPer != null || $part->coatTemp != null || $part->coatPH != null || $part->coatDiptime != null
                                    )
                        <tr style="text-align: center;">
                            <td style="text-align: center;">{{$part->description}}</td>
                            <td style="text-align: center;">{{$part->plateType->name}}</td>
                            <td style="text-align: center;">
                                {{$part->chromate->name}} /
                                {{$part->primaryPer}}% /
                                {{$part->primaryTemp}}° /
                                {{$part->primaryPH}}pH /
                                {{$part->primaryDiptime}}sec
                            </td>
                            <td style="text-align: center;">
                                {{$part->topCoat->name}} /
                                {{$part->topCoatPer}}% /
                                {{$part->topCoatTemp}}° /
                                {{$part->topCoatPH}}pH /
                                {{$part->topCoatDiptime}}sec
                            </td>
                            <td style="text-align: center;">
                                {{$part->coat->name}} /
                                {{$part->coatPer}}% /
                                {{$part->coatTemp}}° /
                                {{$part->coatPH}}pH /
                                {{$part->coatDiptime}}sec
                            </td>
                            <td style="text-align: center;">
                                @if ( $part->isWs != null)  
                                    {{$part->hoursWs}} hrs
                                @else
                                    @if ($run_status == 1)
                                        Removet at {{$hours}} hrs
                                    @else
                                        Active
                                    @endif
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if ( $part->isRs != null)  
                                    {{$part->hoursRs}} hrs
                                @else
                                    @if ($run_status == 1)
                                        Removet at {{$hours}} hrs
                                    @else
                                        Active
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endif
                        @endforeach
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
</body>

</html>
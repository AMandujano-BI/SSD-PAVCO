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

        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap');

        body {
            margin: 0;
            padding: 0;
            font-family: 'Open Sans', sans-serif;
            background-color: #f8fafc;
        }

        body__first {
            margin: 0;
            /* font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif */
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
            font-weight: 600;
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
            font-weight: 600;
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
            font-weight: 600;
            color: #34689c;
        }

        .subheader__value {
            font-weight: normal;
            color: #3b4559;
        }


        .table__container {
            margin: 34px 34px 34px 34px;

        }

        table {
            background-color: white;
            border: none;
            border-collapse: collapse;
        }

        th {
            color: #3b4559;
            font-size: 16px;
            font-weight: 600;
            height: 72px;
            border: none;
        }

        td {
            border-top: 1px solid #979797 !important;
            color: #3b4559;
            font-size: 16px;
            font-weight: normal;
            padding-top: 13px;
            padding-right: 13px;
            padding-bottom: 13px;
            padding-left: 13px;
        }

        .notes__label {
            color: #34689c;
            font-size: 16px;
            font-weight: 600;
            margin-left: 34px;
            margin-right: 34px;
        }

        .image {
            margin: 34px;
        }
    </style>
    <div class='body__first'>
        <div class='content'>
            <span class='rectangle'>
                <img src='https://pavco1.sfo3.digitaloceanspaces.com/assets/assets/pavco.png' class='PavcoWhite'>
                <!-- srcset='pavco@2x.png 2x, pavco@3x.png 3x' -->
            </span>
            <span class='title'>Salt Spray Report Results</span>
        </div>
        <div class='header'>
            <div class='subheader__content'>
                <span class='subheader__label'>Customer: <span class='subheader__value'>{{$customerName}}</span></span>
                <span class='subheader'>StartDate: <span class='subheader__value'>{{$start_date}}</span></span>
                <span class='subheader'>EndDate: <span class='subheader__value'>{{$endDate}} </span></span>
                <span class='subheader'>Chromate: <span class='subheader__value'>{{$chromateName}} </span></span>
            </div>
            <div class='subheader__content' style="padding-top: 10px;">
                <span class='subheader__label'>Plate: <span class='subheader__value'>{{$plate_typeName}}</span></span>
                <span class='subheader'>TopCoat: <span class='subheader__value'>{{$top_coatName}} </span></span>
                <span class='subheader'>Coat: <span class='subheader__value'>{{$coatName }}</span></span>
            </div>
        </div>
        <div class='body'>
            <div class='table__container'>
                <table cellspacing='10' style="width: 100%;">
                    <thead>
                        <tr style="text-align: center;">
                            <th style="text-align: center;">Run #</th>
                            <th style="text-align: center;">Customer</th>
                            <th style="text-align: center;">Description</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($parts as $part)
                        @if (
                                    $part->topCoatPer != null || $part->topCoatTemp != null || $part->topCoatPH != null || $part->topCoatDiptime != null ||
                                    $part->primaryPer != null || $part->primaryTemp != null || $part->primaryPH != null || $part->primaryDiptime != null ||
                                    $part->coatPer != null || $part->coatTemp != null || $part->coatPH != null || $part->coatDiptime != null
                                    )
                        <tr style="text-align: center;">
                            <td style="text-align: center;">{{$part->run_id}}</td>
                            <td style="text-align: center;">{{$part->company}}</td>
                            <td style="text-align: center;">{{$part->description}}</td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- <div class='top-separation'>
                <div class='notes__label'>Notes: <span class='subheader__value'>Parts were salts sprayed using the guidelines of the ASTM B-117.</span></div>
            </div> -->
            <div class='top-separation'>
                <div class='notes__label'>Notes: <span class='subheader__value'>Pavco is not an independent testing laboratory. These results are for your information only and should be verified by an independent testing laboratory. </span></div>
            </div>
        </div>
    </div>
</body>

</html>
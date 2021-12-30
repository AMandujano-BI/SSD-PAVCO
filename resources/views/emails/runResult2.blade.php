<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pavco</title>
    <style>
         *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .title__header {
            color: #34689c;
            font-size: 16px;
        }
        .title__main{
            color: #3b4559;
            font-size: 20px;
            font-weight: 600px;
            text-align: center;

        }
        .flex__header{
            display: flex;
            align-items: center;
            
        }
        .icon__pavco {
            background-color: #0271c5;
            width: 235px;
            height: 122px;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="flex__header">
        <div class="icon__pavco">
        <img src="{{URL::asset('/pavco-white.png')}}" alt="Pavco Icon " height="200" width="200">
        </div>
        <h1 class="title__main">Run Result for Run # {{$run->number}}</h1>
    </div>
    <div>
        <span class="title__header">Run : </span> <span>#{{$run->number}}</span>
        <span class="title__header">Start Date: </span><span>{{$run->startDate}}</span>
        <span class="title__header">Customer : </span> <span>{{$run->company->name}}</span>
        <span class="title__header">Description : </span> <span>{{$run->description}} </span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Part Description</th>
                <th>Plate Type</th>
                <th>Primary Coat</th>
                <th>Coat</th>
                <th>Topcoat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($run->parts as $part)
            <tr>
                <td>{{$part->id}}</td>
                <td>{{$part->plateType->name}} </td>
                <td>{{$part->chromate->name}} - {{$part->primaryPer}} %</td>
                <td>{{$part->coat->name}} - {{$part->coatPer}} %</td>
                <td>{{$part->topCoat->name}} - {{$part->topCoatPer}} %</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
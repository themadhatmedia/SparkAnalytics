@extends('mail.common')
@section('report-type')
   {{$report->report_type}}
@endsection
@section('site')
   {{$report->site->site_name}}
@endsection
@section('title')
   {{$report->title}}
@endsection
@section('content')
<table style="width: 100%;">
    <thead style="text-align:left;" class="text-mute">
        <tr>
            <th scope="col" class="" data-sort="name">Metrics</th>
            <th scope="col">Current <br> Period   </th>
            <th scope="col">Previous <br> Period</th>
            <th scope="col" class="sort" data-sort="completion">Change</th>
           
        </tr>
    </thead>
    <tbody>
       @php
       $json=json_decode($report->data);
       foreach ($json as $key => $value) {
       @endphp
        <tr>
            <td class="text-mute">{{$key}}</td>
            <td class="text-mute">{{$value->current}}</td>
            <td class="text-mute">{{$value->previous}}</td>
            @if($value->previous<$value->current || $value->previous==0)
            <td style="color: green"><?= abs($value->change)?>%</td>
            @else
            <td style="color: red"><?= abs($value->change)?>%</td>
            @endif 
        </tr>
       @php
       }
       @endphp
    </tbody>
</table>
@endsection
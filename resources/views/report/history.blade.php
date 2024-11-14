@extends('layouts.admin')
@push('scripts')
@endpush
@section('page-title')
    {{__('Report History')}}
@endsection
@section('title')
    {{__('Report History')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Home')}}</a></li>
    <li class="breadcrumb-item">{{__('Report History')}}</li>
@endsection
@section('action-button')

@endsection
@section('content')
@if(count($data)>0)
@foreach($data as $val)
<div class=" col-xxl-4">
    <div class="card">workdo.io
        <div class="card-header border-0 pb-0">
            <div class="d-flex  align-items-center">
                <div class="bg-primary theme-avtar me-2 " ><h3 class="mb-0 badge" style="font-family: cursive;font-size: xx-large;"> <?=substr($val->site->site_name,0,1);?></h3></div>
                <div class="gap-4">
                     <h5 class="mb-0 "><a class="text-dark" href="https://demo.workdo.io/erpgo-saas/projects/7">{{$val->site->site_name}}</a></h5>
                </div>

            </div>

            <div class="card-header-right">
                <div class="btn-group card-option">
                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="#!" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#show_report" data-size="lg"  onclick="show_report(<?=$val->id?>)">
                            <i class="ti ti-eye"></i><span> Show</span>
                        </a>
                        {!! Form::open(['method' => 'DELETE', 'route' => ['delete-report-history', [$val->id]]]) !!}
                            <a href="#!" class="dropdown-item bs-pass-para show_confirm">
                                <i class="ti ti-archive"></i><span> Delete</span>
                            </a>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <h4><?=ucfirst($val->report_type)?> </h4>

            <h6 class="text-center text-muted mt-3">{{$val->title}}</h6>


        </div>
    </div>
</div>


@endforeach
@endif
<div class="modal fade " id="show_report" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="report_type"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">

                    <p id="report_title"></p>
                    <div  style="padding: 20px 40px">
                        <table class="table datatable"style="width: 100%;">
                            <thead style="text-align:left;" >
                                <tr>
                                    <th scope="col" class="text-muted" data-sort="name">{{__('Metrics')}}</th>
                                    <th scope="col" class="text-muted">{{__('Current')}} <br> {{__('Period')}}   </th>
                                    <th scope="col" class="text-muted">{{__('Previous')}} <br> {{__('Period')}}</th>
                                    <th scope="col" class="text-muted" data-sort="completion">{{__('Change')}}</th>

                                </tr>
                            </thead>
                            <tbody id="report_data">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        </div>
<script type="text/javascript">
    function show_report(id) {
    var token= $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: $("#path_admin").val()+"/show/report/"+id ,
        method:"POST",
        data: {"_token": token},
        success: function(data) {

            $('#report_type').html(data.report_type);
            $('#report_title').html(data.title);
            var parsed = JSON.parse(data.data);
            var html = '';
            $.each(parsed, function (i, item) {

                html += '<tr>';
                html += '<td scope="row" >' + (i + 1) + '</td>';
                html += '<td>' + item.current + '</td>';
                html += '<td>' + item.previous + '</td>';
                if(item.current<item.previous || item.previous==0)
                {
                    html += '<td style="color:green"><i class="ti ti-arrow-narrow-up"></i>' + Math.abs(item.change) + '%</td>';
                }
                else
                {
                    html += '<td style="color:red"><i class="ti ti-arrow-narrow-down"></i>' + Math.abs(item.change) + '%</td>';
                }

                html += '</tr>';
            });
           $("#report_data").html(html);
        }
    });
  }
</script>
@endsection


@extends('layouts.share-link')

@section('action-button')

@endsection
@section('breadcrumb')
    <li class="breadcrumb-item" id="current_site" data-siteid="{{$data->id}}">{{$data->site_name}}</li> 
  
@endsection
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <h5>{{__('Custom Chart')}}</h5>
                </div>
                <div class="col-md-2">
                    <div class="mb-2 text-end">
                        <div class="input-group mr-sm-2">
                          <input type="text" name="date_duration" onchange="get_custom_share_chart()" class="form-control date_duration w-100" id="date_duration" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="custom_chart">
                <div class="col-12 pt-5 text-center">
                    <h5>Please Select Metrics & Dimension For Custom Chart</h5>
                </div>
            </div>
        </div>
    </div>
</div>  

<script type="text/javascript">
  $(function (){  
      get_custom_share_chart();
    });
  
</script>
@endsection
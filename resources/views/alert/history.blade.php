@extends('layouts.admin')
@push('scripts')
@endpush
@section('page-title')
    {{__('Alert History')}}
@endsection
@section('title')
    {{__('Alert History')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Home')}}</a></li>
    <li class="breadcrumb-item">{{__('Alert History')}}</li>
@endsection
@section('action-button')

@endsection
@section('content')
@if(count($data)>0)
@foreach($data as $val)
<div class=" col-xxl-4">
    <div class="card price-card price-1 wow animate__fadeInUp">
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

                        {!! Form::open(['method' => 'DELETE', 'route' => ['delete-alert-history', [$val->id]]]) !!}
                            <a href="#!" class="dropdown-item bs-pass-para show_confirm">
                                <i class="ti ti-archive" data-bs-toggle="tootip" data-bs-original-title="{{__('Delete Alert')}}"></i><span> Delete</span>
                            </a>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <span class="price-badge bg-primary"><?=ucfirst($val->detail->duration)?> {{__('Alert')}}</span>
            <div class="text-center">
                <div ><span class="badge bg-light-primary" style="padding: 25px 25px;border-radius: 50%;"> <i data-feather="alert-triangle"></i></span>
                </div>
            </div>
            <h6 class="text-center text-dark mt-3">{{$val->title}}</h6>
            <h6 class="text-center text-muted text-sm mt-3">{{$val->description}}</h6>

        </div>
    </div>
</div>
@endforeach
@endif
@endsection


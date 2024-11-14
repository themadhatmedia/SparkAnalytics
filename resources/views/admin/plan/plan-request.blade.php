@extends('layouts.admin')
@section('page-title')
    {{ __('Plan Request') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page">{{ __('Plan Request') }}</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Company Name') }}</th>
                                <th>{{ __('Plan Name') }}</th>
                                <th>{{ __('Maximum Site') }}</th>
                                <th>{{ __('Maximum Widget') }}</th>
                                <th>{{ __('Custom') }}</th>
                                <th>{{ __('Analytics') }}</th>
                                <th>{{ __('Duration') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($plan_requests->count() > 0)
                                @foreach ($plan_requests as $prequest)
                                    <tr>
                                        <td>
                                            <div class="font-style font-weight-bold">{{ $prequest->user->name }}</div>
                                        </td>
                                        <td>
                                            <div class="font-style font-weight-bold">{{ $prequest->plan->name }}</div>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold">{{ $prequest->plan->max_site }}</div>

                                        </td>
                                        <td>
                                            <div class="font-weight-bold">{{ $prequest->plan->max_widget }}</div>

                                        </td>
                                        <td>
                                            <div class="font-weight-bold">{{ $prequest->plan->custom }}</div>

                                        </td>
                                        <td>
                                            <div class="font-weight-bold">{{ $prequest->plan->analytics }}</div>

                                        </td>
                                        <td>
                                            <div class="font-style font-weight-bold">
                                                {{ $prequest->duration == 'monthly' ? __('One Month') : __('One Year') }}
                                            </div>
                                        </td>
                                        <td>{{ \App\Models\Utility::getDateFormated($prequest->created_at, true) }}
                                        </td>
                                        <td>
                                            <div>
                                             
                                                <a href="{{ route('response.request', [$prequest->id, 1]) }}"
                                                    class="btn btn-success btn-sm">
                                                    <i class="ti ti-check"></i>
                                                </a>
                                                <a href="{{ route('response.request', [$prequest->id, 0]) }}"
                                                    class="btn btn-danger btn-sm">
                                                    <i class="ti ti-x"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <th scope="col" colspan="10">
                                        <h6 class="text-center">{{ __('No Manually Plan Request Found.') }}</h6>
                                    </th>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="modal fade " id="create_plan" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="myLargeModalLabel">{{ __('Create New Plan') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="px-3" method="post" action="save-plan" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="plan_id" id="plan_id" value="0">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="name" class="col-form-label">{{ __('Name') }}</label><x-required></x-required>
                                <input type="text" class="form-control" id="name" name="name" required />
                            </div>
                            <div class="form-group col-md-4">
                                <label for="monthly_price" class="col-form-label">{{ __('Monthly Price') }}</label><x-required></x-required>
                                <div class="form-icon-user">
                                    {{-- <span class="currency-icon">{{ (env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$') }}</span> --}}
                                    <input class="form-control" type="number" min="0" id="monthly_price"
                                        name="monthly_price" placeholder="{{ __('Monthly Price') }}" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="annual_price" class="col-form-label">{{ __('Annual Price') }}</label><x-required></x-required>
                                <div class="form-icon-user">
                                    {{-- <span class="currency-icon">{{ (env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$') }}</span> --}}
                                    <input class="form-control" type="number" min="0" id="annual_price"
                                        name="annual_price" placeholder="{{ __('Annual Price') }}" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="duration" class="col-form-label">{{ __('Trial Days') }} </label><x-required></x-required>
                                <input type="number" class="form-control mb-0" id="trial_days" name="trial_days"
                                    required />
                                <span><small>{{ __("Note: '-1' for Unlimited") }}</small></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="max_site" class="col-form-label">{{ __('Maximum Site') }} </label><x-required></x-required>
                                <input type="number" class="form-control mb-0" id="max_site" name="max_site"
                                    min="-1" required />
                                <span><small>{{ __("Note: '-1' for Unlimited") }}</small></span>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="form-group">
                                    <label for="max_widget" class="col-form-label">{{ __('Maximum Widget Per Site') }}
                                        </label><x-required></x-required>
                                    <input type="number" class="form-control mb-0" id="max_widget" min="-1"
                                        name="max_widget" required />
                                    <span><small>{{ __("Note: '-1' for Unlimited") }}</small></span>
                                </div>
                            </div>

                            <div class="form-group col-md-12 mb-0">
                                <div class="form-group">
                                    <label for="description" class="col-form-label">{{ __('Description') }}</label>
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="name" class="col-form-label"> {{ __('Status') }} </label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" value="1" class="form-check-input input-primary"
                                        name="status" id="status" checked>
                                    <label class="form-check-label" for="status"></label>
                                </div>
                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="name" class="col-form-label"> {{ __('Custom') }} </label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input input-primary" value="1"
                                        name="custom" id="custom">
                                    <label class="form-check-label" for="custom"></label>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="name" class="col-form-label"> {{ __('Analytics') }} </label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input input-primary" value="1"
                                        name="analytics" id="analytics">
                                    <label class="form-check-label" for="analytics"></label>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="duration" class="col-form-label"> {{ __('Duration') }} </label>
                                <select class="form-control select" required="required" id="duration" name="duration">
                                    <option selected="" disabled="">Selete Duration</option>
                                    <option value="unlimited">unlimited</option>
                                    <option value="month">Per Month</option>
                                    <option value="year">Per Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light"
                                data-bs-dismiss="modal">
                            <input type="submit" value="{{ __('Create') }}" class="btn btn-primary ms-2">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade " id="edit_plan" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="myLargeModalLabel">{{ __('Edit Plan') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="px-3" method="post" action="save-plan" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="plan_id" id="edit_plan_id">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_name" class="col-form-label">{{ __('Name') }}</label><x-required></x-required>
                                <input type="text" class="form-control" id="edit_name" name="name" required />
                            </div>
                            <div class="form-group col-md-4">
                                <label for="edit_monthly_price" class="col-form-label">{{ __('Monthly Price') }}</label><x-required></x-required>
                                <div class="form-icon-user">
                                    {{-- <span class="currency-icon">{{ (env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$') }}</span> --}}
                                    <input class="form-control" type="number" min="0" id="edit_monthly_price"
                                        name="monthly_price" placeholder="{{ __('Monthly Price') }}" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="edit_annual_price" class="col-form-label">{{ __('Annual Price') }}</label><x-required></x-required>
                                <div class="form-icon-user">
                                    {{-- <span class="currency-icon">{{ (env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$') }}</span> --}}
                                    <input class="form-control" type="number" min="0" id="edit_annual_price"
                                        name="annual_price" placeholder="{{ __('Annual Price') }}" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="edit_trial_days" class="col-form-label">{{ __('Trial Days') }} </label><x-required></x-required>
                                <input type="number" class="form-control mb-0" id="edit_trial_days" name="trial_days"
                                    required />
                                <span><small>{{ __("Note: '-1' for Unlimited") }}</small></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="edit_max_site" class="col-form-label">{{ __('Maximum Site') }} </label><x-required></x-required>
                                <input type="number" class="form-control mb-0" id="edit_max_site" name="max_site"
                                    min="-1" required />
                                <span><small>{{ __("Note: '-1' for Unlimited") }}</small></span>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="form-group">
                                    <label for="edit_max_widget" class="col-form-label">{{ __('Maximum Widget Per Site') }}
                                        </label><x-required></x-required>
                                    <input type="number" class="form-control mb-0" id="edit_max_widget" min="-1"
                                        name="max_widget" required />
                                    <span><small>{{ __("Note: '-1' for Unlimited") }}</small></span>
                                </div>
                            </div>

                            <div class="form-group col-md-12 mb-0">
                                <div class="form-group">
                                    <label for="description" class="col-form-label">{{ __('Description') }}</label>
                                    <textarea class="form-control" id="edit_description" name="description"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="name" class="col-form-label"> {{ __('Status') }} </label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" value="1" class="form-check-input input-primary"
                                        name="status" id="edit_status" >
                                    <label class="form-check-label" for="edit_status"></label>
                                </div>
                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="name" class="col-form-label"> {{ __('Custom') }} </label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input input-primary" value="1"
                                        name="custom" id="edit_custom">
                                    <label class="form-check-label" for="edit_custom"></label>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="name" class="col-form-label"> {{ __('Analytics') }} </label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input input-primary" value="1"
                                        name="analytics" id="edit_analytics">
                                    <label class="form-check-label" for="edit_analytics"></label>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="edit_duration" class="col-form-label"> {{ __('Duration') }} </label>
                                <select class="form-control select" required="required" id="edit_duration" name="duration">
                                    <option selected="" disabled="">Selete Duration</option>
                                    <option value="unlimited">unlimited</option>
                                    <option value="month">Per Month</option>
                                    <option value="year">Per Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light"
                                data-bs-dismiss="modal">
                            <input type="submit" value="{{ __('Create') }}" class="btn btn-primary ms-2">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
    function edit_plan_data(id) {
    var token= $('meta[name="csrf-token"]').attr('content');
    $.ajax({
            url: $("#path_admin").val()+"/edit-plan/"+id ,
            method:"POST",
            data: {"_token": token},
            success: function(data) {
               
                $("#edit_plan_id").val(data.id);
                $("#edit_name").val(data.name);
                $("#edit_monthly_price").val(data.monthly_price);
                $("#edit_annual_price").val(data.annual_price);
                $("#edit_trial_days").val(data.trial_days);
                $("#edit_max_site").val(data.max_site);
                $("#edit_max_widget").val(data.max_widget);
                $('#edit_description').val(data.description);;
                $('#edit_analytics').prop('checked', data.analytics);
                $('#edit_status').prop('checked', data.status);
                $('#edit_custom').prop('checked', data.custom);
                $('#edit_duration option[value="'+data.duration+'"]').prop('selected', true);

               

            }
        });

     
}
</script>
@endsection

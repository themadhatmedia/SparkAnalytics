<div class="table-responsive">
    <table class="table">
        <tbody>
        @foreach($plans as $plan)
            <tr>
                <td>
                    <div class="font-style font-weight-bold">{{$plan->name}}</div>
                </td>
                <td>
                    <div class="font-weight-bold">{{__('Site:')}}{{$plan->max_site}}</div>
                    
                </td>
                <td>
                    <div class="font-weight-bold">{{__('Users:')}}{{$plan->max_user}}</div>
                    
                </td>
                <td>
                    <div class="font-weight-bold">{{__('Widget:')}}{{$plan->max_widget}}</div>
                    
                </td>
                <td>
                    @if($user->plan == $plan->id)
                        <button type="button" class="btn btn-xs btn-soft-success btn-icon">
                            <span class="btn-inner--icon"><i class="fas fa-check"></i></span>
                            <span class="btn-inner--text">{{__('Active')}}</span>
                        </button>
                    @else
                        <div>
                            <a href="{{route('manually.activate.plan',[$user->id,$plan->id, 'monthly'])}}" class="badge rounded p-2 px-3 bg-primary text-white" title="{{ __('Click to Upgrade Plan') }}"> {{ __('One Month') }}</a>
                            <a href="{{route('manually.activate.plan',[$user->id,$plan->id, 'annual'])}}" class="badge rounded p-2 px-3 bg-primary text-white" title="{{ __('Click to Upgrade Plan') }}"> {{ __('One Year') }}</a>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

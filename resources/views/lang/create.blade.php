
        {{ Form::open(array('route' => array('store.language'))) }}
        <div class="form-group">
            {{ Form::label('code', __('Language Code'),['class' => 'col-form-label']) }}
            {{ Form::text('code', '', array('class' => 'form-control','required'=>'required')) }}
            @error('code')
            <span class="invalid-code" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            {{ Form::label('full_name', __('Full Name'),['class' => 'col-form-label']) }}
            {{ Form::text('full_name', '', array('class' => 'form-control','required'=>'required')) }}
            <span><small>{{__("Note: put full name of language")}}</small></span>
        </div>
       <div class="modal-footer">
                <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
                {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
            </div>
        {{ Form::close() }}



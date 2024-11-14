{{Form::model(null, array('route' => array('feature_update', $key), 'method' => 'POST','enctype' => "multipart/form-data",'class'=>'needs-validation', 'novalidate')) }}
<div class="body">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('feature_heading',$feature['feature_heading'], ['class' => 'form-control ','required'=>'required', 'placeholder' => __('Enter Heading')]) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                {{ Form::textarea('feature_description', $feature['feature_description'], ['class' => 'form-control summernote-simple', 'placeholder' => __('Enter Description'), 'id'=>'']) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('Logo', __('Logo'), ['class' => 'form-label']) }}<x-required></x-required>
                <input type="file" name="feature_logo" class="form-control" required>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}

<script type="text/javascript">
   summernote()
</script>

{{Form::model(null, array('route' => array('testimonials_update', $key), 'method' => 'POST','enctype' => "multipart/form-data", 'class'=>'needs-validation','novalidate' )) }}

    <div class="body">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('Title', __('Title'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('testimonials_title',$testimonial['testimonials_title'], ['class' => 'form-control ','required'=>'required', 'placeholder' => __('Enter Title')]) }}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('Star', __('Star'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::number('testimonials_star',$testimonial['testimonials_star'], ['class' => 'form-control ', 'min'=>'1', 'max'=>'5','required'=>'required','required'=>'required', 'placeholder' => __('Enter Star')]) }}
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                    {{ Form::textarea('testimonials_description', $testimonial['testimonials_description'], ['class' => 'form-control summernote-simple','placeholder' => __('Enter Description'), 'id'=>'']) }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('User', __('User'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('testimonials_user',$testimonial['testimonials_user'], ['class' => 'form-control ','required'=>'required', 'placeholder' => __('Enter User Name')]) }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('Designation', __('Designation'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('testimonials_designation',$testimonial['testimonials_designation'], ['class' => 'form-control','required'=>'required', 'placeholder' => __('Enter Designation')]) }}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('User Avtar', __('User Avtar'), ['class' => 'form-label']) }}<x-required></x-required>
                    <input type="file" name="testimonials_user_avtar" class="form-control" required>
                </div>
            </div>


        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Edit')}}" class="btn  btn-primary">
    </div>

{{ Form::close() }}

<script type="text/javascript">
    summernote()
</script>
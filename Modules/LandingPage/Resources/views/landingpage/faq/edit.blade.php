{{Form::model(null, array('route' => array('faq_update', $key), 'method' => 'POST','enctype' => "multipart/form-data",'class'=>'needs-validation', 'novalidate')) }}
<div class="body">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('questions', __('Questions'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('faq_questions',$faq['faq_questions'], ['class' => 'form-control ','required'=>'required', 'placeholder' => __('Enter Questions')]) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('answer', __('Answer'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::textarea('faq_answer', $faq['faq_answer'], ['class' => 'form-control summernote-simple','required'=>'required', 'placeholder' => __('Enter Answer'), 'id'=>'']) }}
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

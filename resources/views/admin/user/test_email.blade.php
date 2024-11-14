<form class="px-3" method="post" action="{{ route('company.test.email.send') }}" id="test_email" class="needs-validation" novalidate>
        
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <input type="hidden" name="mail_driver" value="{{$data['mail_driver']}}" />
    <input type="hidden" name="mail_host" value="{{$data['mail_host']}}" />
    <input type="hidden" name="mail_port" value="{{$data['mail_port']}}" />
    <input type="hidden" name="mail_username" value="{{$data['mail_username']}}" />
    <input type="hidden" name="mail_password" value="{{$data['mail_password']}}" />
    <input type="hidden" name="mail_encryption" value="{{$data['mail_encryption']}}" />
    <input type="hidden" name="mail_from_address" value="{{$data['mail_from_address']}}" />
    <input type="hidden" name="mail_from_name" value="{{$data['mail_from_name']}}" />
    <div class="row">
        <div class="col-md-12">
           
            <label for="email" class="form-control-label">{{ __('E-Mail Address')}}</label><x-required></x-required>
            <input type="text" class="form-control" id="email" name="email" required/>
        </div>
        <div class="modal-footer">
            <label id="email_sending" style="display: none"><i class="fas fa-clock"></i></label>
            <button type="button" class="btn  btn-light " data-bs-dismiss="modal">{{ __('Close') }}</button>
            {{Form::submit(__('Send Test Mail'),array('class'=>'btn  btn-primary btn-create'))}}
        </div>
   </div>
    
</form>
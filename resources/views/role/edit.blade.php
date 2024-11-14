
    {{Form::model($role,array('route' => array('roles.update', $role->id), 'method' => 'PUT','class'=>'needs-validation', 'novalidate')) }}
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('name',__('Name'),['class' => 'col-form-label'])}}<x-required></x-required>
                {{Form::text('name',null,array('class'=>'form-control','required'=>'required','placeholder'=>__('Enter Role Name')))}}
                @error('name')
                <span class="invalid-name text-danger text-xs" role="alert">{{ $message }}</span>
                @enderror
            </div>
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                @if(!empty($permissions))
                    <label for="permissions" class="form-control-label">{{__('Assign Permission to Roles')}}</label>
                    <table class="table">
                        <tr>
                            <th>
                                <input type="checkbox" class="align-middle checkbox_middle form-check-input"
                                    name="checkall" id="checkall">
                            </th>
                            <th class="text-dark">{{__('Module')}} </th>
                            <th class="text-dark">{{__('Permissions')}} </th>
                        </tr>
                        @php
                       $modules=['user','site','widget','dashboard','share report settings','share report','quick view','analytic','channel analytic','pages analytic','audience analytic','seo analytic','custom analytic'];
                            if(Auth::user()->type == 'super admin'){
                                $modules[] = 'language';
                                $modules[] = 'permission';
                                $modules[] = 'system settings';
                            }
                        @endphp
                        @foreach($modules as $module)
                            <tr>
                                <td><input type="checkbox" class="align-middle ischeck  form-check-input"
                                    name="checkall" data-id="{{ str_replace(' ', '', $module) }}"></td>
                                <td>{{ ucfirst(__($module)) }}</td>
                                <td>
                                    <div class="row">
                                        @if(in_array('manage '.$module,(array) $permissions))
                                            @if($key = array_search('manage '.$module,$permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' => 'permission' . $key]) }}
                                                    {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if(in_array('create '.$module,(array) $permissions))
                                            @if($key = array_search('create '.$module,$permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' => 'permission' . $key]) }}
                                                    {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if(in_array('edit '.$module,(array) $permissions))
                                            @if($key = array_search('edit '.$module,$permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' => 'permission' . $key]) }}
                                                    {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if(in_array('delete '.$module,(array) $permissions))
                                            @if($key = array_search('delete '.$module,$permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' => 'permission' . $key]) }}
                                                    {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if(in_array('associate '.$module,(array) $permissions))
                                            @if($key = array_search('associate '.$module,$permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' => 'permission' . $key]) }}
                                                    {{Form::label('permission'.$key,'Associate',['class'=>'custom-control-label '])}}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if(in_array('show '.$module,(array) $permissions))
                                            @if($key = array_search('show '.$module,$permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' => 'permission' . $key]) }}
                                                    {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if(in_array('move '.$module,(array) $permissions))
                                            @if($key = array_search('move '.$module,$permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' => 'permission' . $key]) }}
                                                    {{Form::label('permission'.$key,'Move',['class'=>'custom-control-label'])}}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if(in_array('client permission '.$module,(array) $permissions))
                                            @if($key = array_search('client permission '.$module,$permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' => 'permission' . $key]) }}
                                                    {{Form::label('permission'.$key,'Client Permission',['class'=>'custom-control-label'])}}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if(in_array('invite user '.$module,(array) $permissions))
                                            @if($key = array_search('invite user '.$module,$permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' => 'permission' . $key]) }}
                                                    {{Form::label('permission'.$key,'Invite User ',['class'=>'custom-control-label'])}}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if(in_array('change password '.$module,(array) $permissions))
                                            @if($key = array_search('change password '.$module,$permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' => 'permission' . $key]) }}
                                                    {{Form::label('permission'.$key,'Change Password ',['class'=>'custom-control-label'])}}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if(in_array('buy '.$module,(array) $permissions))
                                            @if($key = array_search('buy '.$module,$permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' => 'permission' . $key]) }}
                                                    {{Form::label('permission'.$key,'Buy',['class'=>'custom-control-label'])}}<br>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            {{Form::submit(__('Update'),array('class'=>'btn  btn-primary'))}}
        </div>
    </div>
    {{Form::close()}}
    <script>
        $(document).ready(function() {
            $("#checkall").click(function() {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });
            $(".ischeck").click(function() {
                var ischeck = $(this).data('id');
                $('.isscheck_' + ischeck).prop('checked', this.checked);
            });
        });
    </script>

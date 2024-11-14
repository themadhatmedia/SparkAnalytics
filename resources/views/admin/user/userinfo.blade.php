<?php
$avatar_path = \App\Models\Utility::get_file('avatars/');
$avatar=url($avatar_path).'/';
?>
<div class="modal-body">
    <div class="row">
        <div class="col-12 col-sm-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row owner" data-owner-id ={{ $id }}>
                        <div class="col-4 text-center">
                            <h6 >{{ __('Total User') }}</h6>
                            <p class=" text-sm mb-0">
                                <i
                                    class="ti ti-users text-warning card-icon-text-space fs-5 mx-1"></i><span class="total_users fs-5">{{ $users_data['total_users'] }}</span>
                            </p>
                        </div>
                        <div class="col-4 text-center">
                            <h6 >{{ __('Active User') }}</h6>
                            <p class=" text-sm mb-0">
                                <i
                                    class="ti ti-users text-primary card-icon-text-space fs-5 mx-1"></i><span class="active_users fs-5">{{ $users_data['active_users'] }}</span>
                            </p>
                        </div>
                        <div class="col-4 text-center">
                            <h6 >{{ __('Disable User') }}</h6>
                            <p class=" text-sm mb-0">
                                <i
                                    class="ti ti-users text-danger card-icon-text-space fs-5 mx-1"></i><span class="disable_users fs-5">{{ $users_data['disable_users'] }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-xxl-12 col-md-12">
                <div class="px-0 card-body">
                    <div class="tab-content" id="pills-tabContent">
                        @php
                            $users = \App\Models\User::where('created_by', $id)
                                ->get();
                            $owner = \App\Models\User::find($id);
                        @endphp
                        {{-- <div class="row">
                            <div class="col-lg-11 col-md-10 col-sm-10 text-end">
                            <small class="text-danger my-3">{{__('* Please ensure that if you disable the owner, all users within this owner are also disabled.')}}</small>

                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 text-end">
                                <div class="text-end">
                                    <div class="form-check form-switch custom-switch-v1">
                                        <input type="checkbox" name="owner_disable"
                                            class="form-check-input input-primary is_disable" value="1"
                                            data-id="{{ $id }}" data-company="{{ $id }}"
                                            data-name="{{ __('owner') }}"
                                            {{ $owner->user_status == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="owner_disable"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr> --}}
                        {{-- <div class="row workspace">
                                <div class="col-4 text-center">
                                    <p class="text-sm mb-0" data-toggle="tooltip"
                                        data-bs-original-title="{{ __('Total Users') }}" title="{{ __('Total Users') }}"><i
                                            class="ti ti-users text-warning card-icon-text-space fs-5 mx-1"></i><span class="total_users fs-5">{{ $users_data['total_users'] }}</span>

                                    </p>
                                </div>
                                <div class="col-4 text-center">
                                    <p class="text-sm mb-0" data-toggle="tooltip"
                                        data-bs-original-title="{{ __('Active Users') }}" title="{{ __('Active Users') }}"><i
                                            class="ti ti-users text-primary card-icon-text-space fs-5 mx-1"></i><span class="active_users fs-5">{{ $users_data['active_users'] }}</span>
                                    </p>
                                </div>
                                <div class="col-4 text-center">
                                    <p class="text-sm mb-0" data-toggle="tooltip"
                                        data-bs-original-title="{{ __('Disable Users') }}" title="{{ __('Disable Users') }}"><i
                                            class="ti ti-users text-danger card-icon-text-space fs-5 mx-1"></i><span class="disable_users fs-5">{{ $users_data['disable_users'] }}</span>
                                    </p>
                                </div>
                        </div> --}}
                        <div class="row my-2 " id="user_section">
                            @if(!$users->isEmpty())
                                @foreach ($users as $user)
                                    <div class="col-md-6 my-2 ">
                                        <div
                                            class="d-flex align-items-center justify-content-between list_colume_notifi pb-2">
                                            <div class="mb-3 mb-sm-0">
                                                <h6>
                                                    <img src="{{ isset($user->avatar)&&!empty($user->avatar) ? $avatar.$user->avatar : $avatar.'avatar.png'}}"
                                                        class=" wid-30 rounded-circle mx-2" alt="image"
                                                        height="30">
                                                    <label for="user"
                                                        class="form-label">{{ $user->name }}</label>
                                                </h6>
                                            </div>
                                            <div class="text-end ">
                                                <div class="form-check form-switch custom-switch-v1 mb-2">
                                                    <input type="checkbox" name="user_disable"
                                                        class="form-check-input input-primary is_disable"
                                                        value="1" data-id='{{ $user->id }}' data-company="{{ $id }}"
                                                        data-name="{{ __('user') }}"
                                                        {{ $user->user_status == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="user_disable"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-md-12 my-2 text-center">{{__('User Not Found.')}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on("click", ".is_disable", function() {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        var company_id = $(this).attr('data-company');
        var is_disable = ($(this).is(':checked')) ? $(this).val() : 0;

        $.ajax({
            url: '{{ route('user.unable') }}',
            type: 'POST',
            data: {
                "is_disable": is_disable,
                "id": id,
                "name": name,
                "company_id": company_id,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                if(data.success)
                {
                    if (name == 'owner')
                    {
                        var container = document.getElementById('user_section');
                        var checkboxes = container.querySelectorAll('input[type="checkbox"]');
                        checkboxes.forEach(function(checkbox) {
                            if(is_disable == 0){
                                checkbox.disabled = true;
                                checkbox.checked = false;
                            }else{
                                checkbox.disabled = false;
                            }
                        });

                    }
                    $('.active_users').text(data.users_data.active_users);
                    $('.disable_users').text(data.users_data.disable_users);
                    $('.total_users').text(data.users_data.total_users);
                    // $.each(data.users_data, function(userData) {
                    //     var $usersElements = $('.owner[data-owner-id="' +
                    //         userData.user_id + '"]');
                    //     // Update total_users, active_users, and disable_users for each workspace
                    //     $usersElements.find('.total_users').text(userData.total_users);
                    //     $usersElements.find('.active_users').text(userData
                    //         .active_users);
                    //     $usersElements.find('.disable_users').text(userData
                    //         .disable_users);
                    // });

                    toastrs('{{ __('success') }}', data.success, 'success');
                }else{
                    toastrs('{{ __('error') }}', data.error, 'error');

                }

            }
        });
    });
</script>

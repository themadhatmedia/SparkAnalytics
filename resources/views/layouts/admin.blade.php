@php
    $logos = \App\Models\Utility::get_file('logo/');
    $setting = \App\Models\Utility::settings();
    $color = !empty($setting['color']) ? $setting['color'] : 'theme-3';

    if (isset($setting['color_flag']) && $setting['color_flag'] == 'true') {
        $themeColor = 'custom-color';
    } else {
        $themeColor = $color;
    }

    $SITE_RTL = 'off';
    if (!empty($setting['SITE_RTL'])) {
        $SITE_RTL = $setting['SITE_RTL'];
    }
    $header_text = $setting['header_text'];
    $users = \Auth::user();
    $currantLang = $users->currentLanguage();
    $languages = \App\Models\Utility::languages();
    $year = date('Y');
    $footer_text = isset($settings['footer_text']) ? $settings['footer_text'] : '© ' . $year . ' AnalyticsGo SaaS';
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $SITE_RTL == 'on' ? 'rtl' : '' }}">
<meta name="csrf-token" content="{{ csrf_token() }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<title> {{$header_text ? $header_text : config('app.name', 'AnalyticsGo SaaS')}}
    - @yield('page-title')</title>
    @include('partials.head')
<div class="loader-bg"></div>
<body class="{{ $themeColor }}">
    <input type="hidden" id="path_admin" value="{{ url('/') }}">
    @include('partials.menu')
    <style>
        [dir="rtl"] .dash-sidebar {
            left: auto !important;
        }

        [dir="rtl"] .dash-header {
            left: 0;
            right: 280px;
        }

        [dir="rtl"] .dash-header:not(.transprent-bg) .header-wrapper {
            padding: 0 0 0 30px;
        }

        [dir="rtl"] .dash-header:not(.transprent-bg):not(.dash-mob-header)~.dash-container {
            margin-left: 0px;
        }

        [dir="rtl"] .me-auto.dash-mob-drp {
            margin-right: 10px !important;
        }

        [dir="rtl"] .me-auto {
            margin-left: 10px !important;
        }

        [dir="rtl"] .header-wrapper .ms-auto {
            margin-left: 0 !important;
        }

        [dir="rtl"] .dash-header {
            left: 0 !important;
            right: 280px !important;
        }

        [dir="rtl"] .list-group-flush>.list-group-item .float-end {
            float: left !important;
        }
    </style>
    <div class="main-content position-relative">
        @include('partials.header')
        @include('partials.content')
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleoverModal" tabindex="-1" role="dialog" aria-labelledby="exampleoverModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleoverModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    @include('partials.footer')
    <script>
        var toster_pos = "{{ $SITE_RTL == 'on' ? 'left' : 'right' }}";
    </script>
       <script src="{{ asset('js/admin.js') }}"></script>


    <!-- custom JS -->
    <script>
        function copyToClipboard(element) {
            var copyText = element.id;
            console.log(copyText)
            document.addEventListener('copy', function(e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            toastrs('success', 'Url copied to clipboard', 'success');
        }
    </script>

    @stack('script-page')

    @if (Session::has('success'))
        <script>
            toastrs('{{ __('Success') }}', "{!! session('success') !!}", 'success');
        </script>
        {{ Session::forget('success') }}
    @endif
    @if (Session::has('error'))
        <script>
            toastrs('{{ __('Error') }}', "{!! session('error') !!}", 'error');
        </script>
        {{ Session::forget('error') }}
    @endif
    <script>
        var date_picker_locale = {
            format: 'YYYY-MM-DD',
            daysOfWeek: [
                "{{ __('Sun') }}",
                "{{ __('Mon') }}",
                "{{ __('Tue') }}",
                "{{ __('Wed') }}",
                "{{ __('Thu') }}",
                "{{ __('Fri') }}",
                "{{ __('Sat') }}"
            ],
            monthNames: [
                "{{ __('January') }}",
                "{{ __('February') }}",
                "{{ __('March') }}",
                "{{ __('April') }}",
                "{{ __('May') }}",
                "{{ __('June') }}",
                "{{ __('July') }}",
                "{{ __('August') }}",
                "{{ __('September') }}",
                "{{ __('October') }}",
                "{{ __('November') }}",
                "{{ __('December') }}"
            ],
        };
        var calender_header = {
            today: "{{ __('today') }}",
            month: '{{ __('month') }}',
            week: '{{ __('week') }}',
            day: '{{ __('day') }}',
            list: '{{ __('list') }}'
        };
    </script>

    <script>
        var exampleModal = document.getElementById('exampleModal')

        exampleModal.addEventListener('show.bs.modal', function(event) {

            // Button that triggered the modal
            var button = event.relatedTarget
            // Extract info from data-bs-* attributes
            var recipient = button.getAttribute('data-bs-whatever')
            var url = button.getAttribute('data-url')
            var size = button.getAttribute('data-size');
            var modalTitle = exampleModal.querySelector('.modal-title')

            var modalBodyInput = exampleModal.querySelector('.modal-body input')
            // modalTitle.textContent = recipient
            $("#exampleModal .modal-title").html(recipient);
            $("#exampleModal .modal-dialog").addClass('modal-' + size);
            $.ajax({
                url: url,
                success: function(data) {
                    $('#exampleModal .modal-body').html(data);
                    $("#exampleModal").modal('show');
                    validation();
                },
                error: function(data) {
                    data = data.responseJSON;
                    toastrs('Error', data.error, 'error')
                }
            });
        })


        var exampleModal = document.getElementById('exampleoverModal')

        exampleModal.addEventListener('show.bs.modal', function(event) {
            // Button that triggered the modal
            var button = event.relatedTarget
            // Extract info from data-bs-* attributes
            var recipient = button.getAttribute('data-bs-whatever')
            var url = button.getAttribute('data-url')
            var size = button.getAttribute('data-size');
            var modalTitle = exampleModal.querySelector('.modal-title')
            var modalBodyInput = exampleModal.querySelector('.modal-body input')
            //   modalTitle.textContent = recipient
            $("#exampleoverModal .modal-title").html(recipient);
            $("#exampleoverModal .modal-dialog").addClass('modal-' + size);
            $.ajax({
                url: url,
                success: function(data) {
                    $('#exampleoverModal .modal-body').html(data);
                    $("#exampleoverModal").modal('show');
                    validation();
                },
                error: function(data) {
                    data = data.responseJSON;
                    toastrs('Error', data.error, 'error')
                }
            });
        })

        function arrayToJson(form) {
            var data = $(form).serializeArray();
            var indexed_array = {};

            $.map(data, function(n, i) {
                indexed_array[n['name']] = n['value'];
            });

            return indexed_array;
        }
    </script>



    <script>
        $(document).ready(function() {

            var highestBox = 0;
            $('.main .card').each(function() {
                if ($(this).height() > highestBox) {
                    highestBox = $(this).height();
                }
            });
            $('.main .card').height(highestBox);

        });
    </script>

</body>
@if (isset($setting['enable_cookie']) && $setting['enable_cookie'] == 'on')
    @include('layouts.cookie_consent')
@endif

</html>

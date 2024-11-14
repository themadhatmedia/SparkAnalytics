@php
    $users = \Auth::user();
@endphp
@php
\App::setLocale(Auth::user()->lang);
@endphp
<footer class="dash-footer">
    <div class="footer-wrapper">
        <div class="py-1">
            <span class="text-muted">{{ '© '}}
                {{ $setting['footer_text'] ? $setting['footer_text'] : __('AnalyticsGo SaaS ') }}{{ date(' Y')}}</span>
        </div>
    </div>
</footer>
<input type="hidden" id="path_admin" value="{{ url('/') }}">
<script src="{{ asset('js/jquery-1.11.0.min.js') }}"></script>
<script src="{{ asset('assets/vendor/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer="">
</script>
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/dash.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>

{{-- <script src="{{ asset('js/letter.avatar.js')}}"></script> --}}
@stack('pre-purpose-script-page')
{{-- FullCalendar --}}
<script src="{{ asset('assets/js/plugins/main.min.js') }}"></script>

<!-- sweet alert Js -->
<script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>


<!--Botstrap switch-->
<script src="{{ asset('assets/js/plugins/bootstrap-switch-button.min.js') }}"></script>

{{-- DataTable --}}
<script src="{{ asset('assets/js/plugins/simple-datatables.js') }}"></script>
<script src="{{ asset('js/jquery.form.js') }}"></script>
<script>
    const translations = @json(__('datatables'));
</script>
<script>
    const datatableConfig = {
        sortable: true,
        searchable: true,
        labels: {
            placeholder: translations.placeholder,
            perPage: translations.perPage,
            noRows: translations.noRows,
            noResults: translations.noResults,
            info: translations.info,
        },
    };
    const dataTable =  new simpleDatatables.DataTable('#pc-dt-simple', datatableConfig);
</script>
<script>
    var timer = '';
    var timzone = '{{ env('TIMEZONE') }}';



    function minTwoDigits(n) {
        return (n < 10 ? '0' : '') + n;
    }

    function changeTimezone(date, ianatz) {

        var invdate = new Date(date.toLocaleString('en-US', {
            timeZone: ianatz
        }));
        var diff = date.getTime() - invdate.getTime();
        return new Date(date.getTime() - diff);

    }

    function toastrs(title, message, type) {
        var o, i;
        var icon = '';
        var cls = '';
        if (type == 'success') {
            icon = 'fas fa-check-circle';
            // cls = 'success';
            cls = 'primary';
        } else {
            icon = 'fas fa-times-circle';
            cls = 'danger';
        }

        $.notify({
            icon: icon,
            title: " " + title,
            message: message,
            url: ""
        }, {
            element: "body",
            type: cls,
            allow_dismiss: !0,
            placement: {
                from: 'top',
                align: 'right'
            },
            offset: {
                x: 15,
                y: 15
            },
            spacing: 10,
            z_index: 1080,
            delay: 2500,
            timer: 2000,
            url_target: "_blank",
            mouse_over: !1,
            animate: {
                enter: o,
                exit: i
            },
            // danger
            template: '<div class="toast text-white bg-' + cls +
                ' fade show" role="alert" aria-live="assertive" aria-atomic="true">' +
                '<div class="d-flex">' +
                '<div class="toast-body"> ' + message + ' </div>' +
                '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
                '</div>' +
                '</div>'
            });
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.show_confirm').click(function(event) {
            var form = $(this).closest("form");
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "This action can not be undone. Do you want to continue?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        });
    });
</script>

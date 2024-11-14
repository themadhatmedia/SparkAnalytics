

<!-- [ Main Content ] start -->
<div class="dash-container" >
    <div class="dash-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3 mb-sm-0">
                            <div class="">
                                <h4 class="m-b-10"> @yield('page-title')</h4>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"> </li>
                                        <a href="{{ route('dashboard') }}"></a>
                                             @yield('breadcrumb')
                                </ul>
                            </div>
                            
                                @yield('action-button')
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <div class="row" id="printableArea">
        <!-- [ Main ContentHJMH ] start -->
            @yield('content')
        <!-- [ Main Content ] end -->
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->

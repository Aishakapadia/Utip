@extends( admin_layout('master') )

@section('title')
Dashboard
@stop

@section('head_page_level_plugins')
<link href="{{ admin_asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}"
    rel="stylesheet" type="text/css" />
<link href="{{ admin_asset('assets/global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ admin_asset('assets/global/plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet"
    type="text/css" />
<link href="{{ admin_asset('assets/global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ admin_asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" rel="stylesheet"
    type="text/css" />
<link href="{{ admin_asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ admin_asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet"
    type="text/css" />
@stop

@section('head_resources')

@stop

@section('content')
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="{{ url('/') }}">Home</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Dashboard</span>
                </li>
            </ul>
            <div class="page-toolbar">

            </div>
        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> Dashboard
            <small>statistics</small>
        </h1>
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <!-- BEGIN DASHBOARD STATS 1-->
        <div class="row">
            @if(PermissionHelper::isAllowed('user/manage'))
            {{--<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">--}}
            {{--<a class="dashboard-stat dashboard-stat-v2 blue" href="#">--}}
            {{--<div class="visual">--}}
            {{--<i class="fa fa-users"></i>--}}
            {{--</div>--}}
            {{--<div class="details">--}}
            {{--<div class="number">--}}
            {{--<span data-counter="counterup" data-value="{{ $total['users'] }}">{{ $total['users'] }}</span>--}}
            {{--</div>--}}
            {{--<div class="desc"> Total Users </div>--}}
            {{--</div>--}}
            {{--</a>--}}
            {{--</div>--}}
            @endif

            @if(PermissionHelper::isAllowed('ticket/manage'))

            @if(!PermissionHelper::isTransporter())
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 red"
                    href="{{route("ticket-manage", ['statusFilter' => Config::get('constants.OPEN_BY_SUPPLIER')])}}">
                    <div class="visual">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup"
                                data-value="{{ $total['requests_pending_for_admin_approval'] }}">{{ $total['requests_pending_for_admin_approval'] }}</span>
                        </div>
                        <div class="desc"> Pending for Admin Approval </div>
                    </div>
                </a>
            </div>
            @endif

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 blue" href="{{ route('ticket-manage')}}">
                    <div class="visual">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup"
                                data-value="{{ $total['requests'] }}">{{ $total['requests'] }}</span>
                        </div>
                        <div class="desc"> Total Requests </div>
                    </div>
                </a>
            </div>

            @if(!PermissionHelper::isTransporter())
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 green"
                    href="{{route("ticket-manage", ['statusFilter' => Config::get('constants.VEHICLE_OFFLOADED_BY_SITE_TEAM')])}}">
                    <div class="visual">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup"
                                data-value="{{ $total['requests_completed'] }}">{{ $total['requests_completed'] }}</span>
                        </div>
                        <div class="desc"> Requests Completed </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 purple"
                    href="{{route("ticket-manage", ['statusFilter' => Config::get('constants.CANCEL_BY_ADMIN')])}}">
                    <div class="visual">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup"
                                data-value="{{ $total['requests_rejected_by_admin'] }}">{{ $total['requests_rejected_by_admin'] }}</span>
                        </div>
                        <div class="desc"> Requests Rejected By Admin </div>
                    </div>
                </a>
            </div>
            @endif
            @endif
            @if(!PermissionHelper::isTransporter() && !PermissionHelper::isSupplier())
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 yellow"
                    href="{{route("ticket-manage", ['statusFilter' => Config::get('constants.VEHICLE_LOADED_BY_SUPPLIER')])}}">
                    <div class="visual">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup"
                                data-value="{{ $total['requests_vehicle_dispatched'] }}">{{ $total['requests_vehicle_dispatched'] }}</span>
                        </div>
                        <div class="desc"> Vehicle Dispatched </div>
                    </div>
                </a>
            </div>
            @endif
            @if(!PermissionHelper::isTransporter() && !PermissionHelper::isSupplier())
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 green-jungle"
                    href="{{route("ticket-manage", ['statusFilter' => Config::get('constants.VEHICLE_REACHED_AT_DESTINATION_BY_SITE_TEAM')])}}">
                    <div class="visual">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup"
                                data-value="{{ $total['requests_vehicle_reached'] }}">{{ $total['requests_vehicle_reached'] }}</span>
                        </div>
                        <div class="desc"> Vehicle Reached Destination </div>
                    </div>
                </a>
            </div>
            @endif
            @if(!PermissionHelper::isTransporter() && !PermissionHelper::isSupplier())
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 blue-chambray"
                    href="{{route("ticket-manage", ['statusFilter' => Config::get('constants.APPROVE_BY_ADMIN')])}}">
                    <div class="visual">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup"
                                data-value="{{ $total['requests_transporter_submission'] }}">{{ $total['requests_transporter_submission'] }}</span>
                        </div>
                        <div class="desc"> Transporter Submission Pending </div>
                    </div>
                </a>
            </div>
            @endif
            @if(!PermissionHelper::isTransporter() && !PermissionHelper::isSupplier())
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 red-mint"
                    href="{{route("ticket-manage", ['statusFilter' => Config::get('constants.ACCEPT_BY_TRANSPORTER')])}}">
                    <div class="visual">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup"
                                data-value="{{ $total['requests_transporter_selection'] }}">{{ $total['requests_transporter_selection'] }}</span>
                        </div>
                        <div class="desc"> Transporter Selection Pending </div>
                    </div>
                </a>
            </div>
            @endif
            @if(!PermissionHelper::isTransporter())
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 grey-gallery"
                    href="{{route("checklist-detail")}}">
                    <div class="visual">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="details">
                        <div class="number"></div>
                        <div class="desc">Logistics Safety System</div>
                    </div>
                </a>
            </div>
            @endif
        </div>
        <div class="clearfix"></div>

        <h1 class="page-title"> User Scorecard
        </h1>
        <div class="row" style="margin:16px;">
            <div class="col-md-4">
                <div class="dashboard-stat blue-sharp" style="color:white;">
                    <div style="padding-left:16px;">
                        <h3>{{$user->name}} <small style="color:white">({{$user->role->title}})</small></h3>
                        <ul style="list-style-type: none;margin-left: -24px; margin-top:16px;">
                            @php
                            {{$stars = 0;}}
                            @endphp
                            @forelse ($scorecard as $key=>$value)
                            @php
                            {{$stars += (double)($value)/100;}}
                            @endphp
                            <li>
                                <div class="row">
                                    <div class="col-md-9 font-weight-bold">{{$key}}
                                    </div>
                                    <div class="col-md-3">
                                        {{$value}}
                                    </div>
                                </div>
                            </li>
                            @empty
                            <li> No KPI specified</li>
                            @endforelse
                            @if (count($scorecard))
                            <br>
                            <div class="row" style="font-size: 1.1em;">
                                <div class="col-md-9">
                                    @php
                                    {{echo '<b>Overall Score</b>  ';}}
                                    @endphp
                                </div>
                                <div class="col-md-3">
                                    @php
                                    {{echo '<span class="glyphicon glyphicon-star"></span>'.' '.number_format($stars*5/count($scorecard), 2);}}
                                    @endphp
                                </div>
                            </div>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        @if ($user->isAdmin() || $user->isSuper())
        <h1 class="page-title"> Admin Scorecard Panel
        </h1>
        <form id="scorecard-request" action="{{route('request-scorecard')}}" method="get">
            <div class="row" style="margin:16px;">
                <div class="col-md-4">
                    <div class="inbox-form-group mail-to">
                        <label class="control-label">Username</label>
                        <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                            @php
                            $attributes = [];
                            $attributes['id'] = 'site_id_to';
                            $attributes['class'] = 'form-control select2';
                            @endphp
                            {{ Form::select('username', $usernames, null, $attributes) }}
                        </div>
                        <label class="control-label">Start Date</label>
                        <div class="controls controls-to">
                            <div class="input-group date form_datetime bs-datetime"
                                data-date="{{ date('Y-m-d').'T'.date('H:i:s').'Z' }}"
                                data-date-format="yyyy-mm-dd H:i:s">
                                {{--<input name="eta" class="form-control" size="16" type="text" value="" readonly>--}}
                                {{ Form::text('start', null, ['id' => 'start', 'class' => 'form-control', 'size' => 16, 'readonly' => 'readonly']) }}
                                <span class="input-group-addon">
                                    <button class="btn default date-set" type="button"><i
                                            class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                        <label class="control-label">End Date</label>
                        <div class="controls controls-to">
                            <div class="input-group date form_datetime bs-datetime"
                                data-date="{{ date('Y-m-d').'T'.date('H:i:s').'Z' }}"
                                data-date-format="yyyy-mm-dd H:i:s">
                                {{--<input name="eta" class="form-control" size="16" type="text" value="" readonly>--}}
                                {{ Form::text('end', null, ['id' => 'end', 'class' => 'form-control', 'size' => 16, 'readonly' => 'readonly']) }}
                                <span class="input-group-addon">
                                    <button class="btn default date-set" type="button"><i
                                            class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="controls controls-to" style="padding-top:16px;">
                            <button type="submit" class="btn btn-default mt-4">Search</button>
                        </div>
                    </div>

                </div>



        </form>
        <div class="col-md-4">
            <div class="dashboard-stat red-mint scorecard-display" style="color:white;">

            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    @endif
    <!-- END DASHBOARD STATS 1-->


</div>
<!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->
@stop

@section('foot_page_level_plugins')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="{{ admin_asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"
    type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/morris/morris.min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/morris/raphael-min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/counterup/jquery.waypoints.min.js') }}" type="text/javascript">
</script>
<script src="{{ admin_asset('assets/global/plugins/counterup/jquery.counterup.min.js') }}" type="text/javascript">
</script>
<script src="{{ admin_asset('assets/global/plugins/amcharts/amcharts/amcharts.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/amcharts/amcharts/serial.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/amcharts/amcharts/pie.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/amcharts/amcharts/radar.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/amcharts/amcharts/themes/light.js') }}" type="text/javascript">
</script>
<script src="{{ admin_asset('assets/global/plugins/amcharts/amcharts/themes/patterns.js') }}" type="text/javascript">
</script>
<script src="{{ admin_asset('assets/global/plugins/amcharts/amcharts/themes/chalk.js') }}" type="text/javascript">
</script>
<script src="{{ admin_asset('assets/global/plugins/amcharts/ammap/ammap.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/amcharts/ammap/maps/js/worldLow.js') }}" type="text/javascript">
</script>
<script src="{{ admin_asset('assets/global/plugins/amcharts/amstockcharts/amstock.js') }}" type="text/javascript">
</script>
<script src="{{ admin_asset('assets/global/plugins/fullcalendar/fullcalendar.min.js') }}" type="text/javascript">
</script>
<script src="{{ admin_asset('assets/global/plugins/horizontal-timeline/horizontal-timeline.js') }}"
    type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/flot/jquery.flot.min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/flot/jquery.flot.resize.min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/flot/jquery.flot.categories.min.js') }}" type="text/javascript">
</script>
<script src="{{ admin_asset('assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js') }}"
    type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/jquery.sparkline.min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js') }}"
    type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js') }}" type="text/javascript">
</script>
<script src="{{ admin_asset('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js') }}"
    type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js') }}"
    type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js') }}" type="text/javascript">
</script>
<script src="{{ admin_asset('assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js') }}"
    type="text/javascript"></script>
@stop

@section('foot_page_level_scripts')
<script src="{{ admin_asset('assets/pages/scripts/dashboard.min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"
    type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"
    type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"
    type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
    type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/clockface/js/clockface.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/pages/scripts/components-editors.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
@stop

@section('foot_resources')
<script>
    $(".form_datetime").datepicker({
        autoclose: true,
        isRTL: App.isRTL(),
        format: "dd-mm-yyyy",
        fontAwesome: true,
        pickerPosition: (App.isRTL() ? "top-right" : "top-left")
     });

     $(".select2").select2();
    
    var placeholder = "Select";
    $(".select2-multiple").select2({
    //placeholder: placeholder,
    width: null
    });

    $("#scorecard-request").submit(function(e) {
    
        e.preventDefault(); // avoid to execute the actual submit of the form.
        
        var form = $(this);
        var url = form.attr('action');
        $.ajax({
        type: "GET",
        url: url,
        data: form.serialize(), // serializes the form's elements.
        success: function(data)
        {
            $('.scorecard-display').empty();
            $('.scorecard-display').append(data);
        }
        });
    });
</script>
@stop
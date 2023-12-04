@extends( admin_layout('master') )

@section('title')
Safety Checklist
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
                    <span>Safety Checklist</span>
                </li>
            </ul>
            <div class="page-toolbar">

            </div>
        </div>
        <h1 class="page-title"> Safety Checklist Panel
        </h1>
        <a href="{{route('checklist-manage')}}" class="btn btn-primary" style="float: right;margin-right:40px;">Manage
            Checklists</a><br>
        <div class="row" style="margin:16px;">
        @if ($user->isAdmin() || $user->isSuper())
        <!-- Safety Checklist -->

            <div class="col-md-5">
                <div class="row" style="height:450px;">
                    <h4>Vehicle Accepted vs Rejected</h4>
                    <!--Div that will hold the pie chart-->
                    <div class="col-sm-12">
                        <div id="chart_total">Loading...</div>
                    </div>
                </div>
                <div class="row" style="height:450px;">
                    <h4>Vehicle Inspected <small>(Inbound)</small></h4>
                    <!--Div that will hold the pie chart-->
                    <div class="col-sm-12">
                        <div id="chart_div">Loading...</div>
                    </div>
                </div>
                <div class="row" style="height:450px;">
                    <h4>Vehicle Inspected <small>(Outbound - Primary)</small></h4>
                    <!--Div that will hold the pie chart-->
                    <div class="col-sm-12">
                        <div id="chart_div_p">Loading...</div>
                    </div>
                </div>
                <div class="row" style="height:450px;">
                    <h4>Vehicle Inspected <small>(Outbound - Secondary)</small></h4>
                    <!--Div that will hold the pie chart-->
                    <div class="col-sm-12">
                        <div id="chart_div_s">Loading...</div>
                    </div>
                </div>
               
            </div>
            <div class="col-sm-6">
                <div>
                    <h4 style="font-weight: 700;">Daily Safety Summary &nbsp;&nbsp;<small
                            style="font-size:0.6em">(Vehicles inspected: {{$inspected["Total"]}})</small></h4>

                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>
                                    Questions
                                </th>
                                <th>
                                    Responses
                                </th>
                            </tr>
                        </thead>
                        <tbody class="table-striped">
                            @foreach ($checklist[0] as $k=>$v)
                            <tr>
                                <td
                                    style='font-weight: {{\App\Question::where('question','=',$k)->first()->important ? "bold" : "" }}'>
                                    {{$k}}</td>
                                <td>
                                    @foreach ($v as $key=>$response)
                                    <span
                                        class='{{$key=='Pass'?"text-primary":($key=="Fail"?"text-danger":"text-secondary")}}'>{{$key.": ".$response}}</span><br>
                                    @endforeach
                                <td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div>
            </div>

        </div>

        <div class="clearfix"></div>

        @else
        @foreach ( ['danger', 'warning', 'success', 'info'] as $msg )
        @if(Session::has('alert-' . $msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close"
                data-dismiss="alert" aria-label="close">&times;</a></p>
        @endif
        @endforeach
        @php
        $sites = \App\Site::getDropDown()->prepend('Select', '');
        $transporter = \App\Transporter::getDropDown()->prepend('Select', '');
        @endphp

        <form id="scorecard-request" action="{{route('checklist-submit')}}" method="post" enctype="multipart/form-data">
            @method('post')
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <h3 class="caption">Safety Checklist</h3>
                    @if($errors->has('question')) <span class="help-block " style="color: red">
                        {{ $errors->first('question') }} </span> @endif
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('site_id_from') ? 'has-error' : '' }}">
                        <label class="control-label col-md-3">Site From: <span class="required"
                                aria-required="true">*</span></label>
                        <div class="col-md-9">
                            @php
                            $attributes = [];
                            $attributes['id'] = 'site_id_from';
                            $attributes['class'] = 'form-control select2';
                            @endphp
                            {{ Form::select('site_id_from', $sites, null, $attributes) }}
                            @if($errors->has('site_id_from')) <span class="help-block">
                                {{ $errors->first('site_id_from') }} </span>
                            @endif
                        </div>
                    </div>
                </div>
                <!--/span-->

                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('site_id_to') ? 'has-error' : '' }}">
                        <label class="control-label col-md-3">Site To: <span class="required"
                                aria-required="true">*</span></label>
                        <div class="col-md-9">
                            @php
                            $attributes = [];
                            $attributes['id'] = 'site_id_to';
                            $attributes['class'] = 'form-control select2';
                            @endphp
                            {{ Form::select('site_id_to', $sites, null, $attributes) }}
                            @if($errors->has('site_id_to')) <span class="help-block"> {{ $errors->first('site_id_to') }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <!--/span-->
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="col-md-3">
                            <label>Vehicle Number<span class="required" aria-required="true">*</span></label>
                        </div>
                        <div class="col-md-9">
                            {{ Form::text('vehicle_number', null, ['id' => 'vehicle_number', 'class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('site_id_from') ? 'has-error' : '' }}">
                        <label class="control-label col-md-3">Vehicle Type: <span class="required"
                                aria-required="true">*</span></label>
                        <div class="col-md-9">
                            @php
                            $attributes = [];
                            $attributes['id'] = 'vehicle_type';
                            $attributes['class'] = 'form-control select2';
                            @endphp
                            {{ Form::select('vehicle_type', $vehicle_types, null, $attributes) }}
                            @if($errors->has('vehicle_type')) <span class="help-block">
                                {{ $errors->first('vehicle_type') }} </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="col-md-3">
                            <label>Driver Name<span class="required" aria-required="true">*</span></label>
                        </div>
                        <div class="col-md-9">
                            {{ Form::text('driver_name', null, ['id' => 'driver_name', 'class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="col-md-3">
                            <label>Driver NIC<span class="required" aria-required="true">*</span></label>
                        </div>
                        <div class="col-md-9">
                            {{ Form::text('driver_nic', null, ['id' => 'driver_nic', 'class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('inspection_site') ? 'has-error' : '' }}">
                        <label class="control-label col-md-3">Inspection Site: <span class="required"
                                aria-required="true">*</span></label>
                        <div class="col-md-9">
                            @php
                            $attributes = [];
                            $attributes['id'] = 'inspection_site';
                            $attributes['class'] = 'form-control select2';
                            @endphp
                            {{ Form::select('inspection_site', $sites, null, $attributes) }}
                            @if($errors->has('inspection_site')) <span class="help-block">
                                {{ $errors->first('inspection_site') }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('site_id_from') ? 'has-error' : '' }}">
                        <label class="control-label col-md-3">Transporter: <span class="required" aria-required="true">*</span></label>
                        <div class="col-md-9">
                            @php
                            $attributes = [];
                            $attributes['id'] = 'transporter';
                            $attributes['class'] = 'form-control select2';
                            @endphp
                            {{ Form::select('transporter', $transporter, null, $attributes) }}
                            @if($errors->has('transporter')) <span class="help-block">
                                {{ $errors->first('transporter') }} </span>
                            @endif
                        </div>
                    </div>
                </div>
                <!--/span-->
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                        <label class="control-label col-md-3">Type <span class="required" aria-required="true">*</span></label>
                        <div class="col-md-9">
                            @php
                            $attributes = [];
                            $attributes['id'] = 'type';
                            $attributes['class'] = 'form-control select2';
                            @endphp
                            {{ Form::select('type', $types, null, $attributes) }}
                            @if($errors->has('type')) <span class="help-block">
                                {{ $errors->first('type') }} </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <br>

            {!!$checklist!!}

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
                        <label class="control-label col-md-3">Comments </label>
                        <div class="col-md-9">
                            {{ Form::textarea('comments', "", ['id' => 'comments', 'class' => 'form-control', 'rows' => 4]) }}
                            @if($errors->has('comments')) <span class="help-block">{{ $errors->first('comments') }}
                            </span> @endif
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Upload file</label>
                        <div class="col-md-9">
                            <label class="btn btn-primary" for="file_upload">
                                <input name="file_upload" id="file_upload" type="file" style="display:none">
                                Browse
                            </label>
                            <span class='label label-primary' id="upload-file-info"></span>
                        </div>
                    </div>
                </div>
                <!--/span-->
            </div>
            <!--/row-->

            <div class="controls controls-to" style="padding-top:16px;">
                <button type="submit" class="btn btn-primary mt-4">Submit</button>
            </div>
        </form>

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

    $("#scorecard-request").submit(function(e) {
        $("button[type='submit']", this)
        .text("Please Wait...")
        .attr('disabled', 'disabled');
    });

    $('#file_upload').bind('change', function() {

            //this.files[0].size gets the size of your file.
            var fileExtension = ['exe','mp4','avi','mov','mkv'];
            if (this.files[0].size < 5242880 &&
            $.inArray(this.files[0].name.split('.').pop().toLowerCase(), fileExtension) == -1 ){
                $('#upload-file-info').html(this.files[0].name);
            }else{
                $('#upload-file-info').html("Error: Please upload file of supported format (< 5MB)");
                $('#file_upload').wrap('<form>').closest('form').get(0).reset();
                e.unwrap();
            }
        });
</script>
<script>
    // $(".form_datetime").datepicker({
    //     autoclose: true,
    //     isRTL: App.isRTL(),
    //     format: "dd-mm-yyyy",
    //     fontAwesome: true,
    //     pickerPosition: (App.isRTL() ? "top-right" : "top-left")
    //  });

    //  $(".select2").select2();
    
    // var placeholder = "Select";
    // $(".select2-multiple").select2({
    // //placeholder: placeholder,
    // width: null
    // });

    // $("#scorecard-request").submit(function(e) {
    
    //     e.preventDefault(); // avoid to execute the actual submit of the form.
        
    //     var form = $(this);
    //     var url = form.attr('action');
    //     $.ajax({
    //     type: "GET",
    //     url: url,
    //     data: form.serialize(), // serializes the form's elements.
    //     success: function(data)
    //     {
    //         $('.scorecard-display').empty();
    //         $('.scorecard-display').append(data);
    //     }
    //     });
    // });

    @if($user->isAdmin() || $user->isSuper())
    // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        @php
            $total = 0;
            $cancelled=0;
        @endphp
       
        // Set chart options
        var options = {
                       'height':400,
                        'legend':{position: 'top', maxLines: 10},
                       'is3D': true};
        options['pieSliceText'] = "value";

        // Create the data table Inbound
        var dataI = new google.visualization.DataTable();
        dataI.addColumn('string', 'Submitted By');
        dataI.addColumn('number', 'Cancelled');
        dataI.addColumn({type: 'string', role: 'tooltip'});
        dataI.addRows([
            @foreach ($submitters[0] as $key=>$value)
            @php
                $total += $value->total;
                $cancelled += $value->cancelled;
            @endphp
            ['{{$value->site}}', {{$value->total}},"{{$value->site."\t\tTotal: ".$value->total."\tCancelled: ".$value->cancelled}}"],
            @endforeach
         
        ]);

        // Instantiate and draw our chart, passing in some options.
        var chartI = new google.visualization.PieChart(document.getElementById('chart_div'));
        chartI.draw(dataI, options);

        function selectHandlerI() {
            var selectedItem = chartI.getSelection()[0];
            var url = '/panel/checklist/manage?siteFilter='+dataI.getValue(selectedItem.row, 0)+"&typeFilter={{Config::get('constants.TYPE_INBOUND')}}";
            window.open(url);
            }        
        google.visualization.events.addListener(chartI, 'select', selectHandlerI);

        // Create the data table Outbound Primary
        var dataP = new google.visualization.DataTable();
        dataP.addColumn('string', 'Submitted By');
        dataP.addColumn('number', 'Cancelled');
        dataP.addColumn({type: 'string', role: 'tooltip'});
        dataP.addRows([
        @foreach ($submitters[1] as $key=>$value)
        @php
        $total += $value->total;
        $cancelled += $value->cancelled;
        @endphp
        ['{{$value->site}}',
        {{$value->total}},"{{$value->site."\t\tTotal: ".$value->total."\tCancelled: ".$value->cancelled}}"],
        @endforeach
        
        ]);
        
        // Instantiate and draw our chart, passing in some options.
        var chartP = new google.visualization.PieChart(document.getElementById('chart_div_p'));
        chartP.draw(dataP, options);
        
        function selectHandlerP() {
        var selectedItem = chartP.getSelection()[0];
        var url = '/panel/checklist/manage?siteFilter='+dataP.getValue(selectedItem.row, 0)+"&typeFilter={{Config::get('constants.TYPE_OUTB_PRI')}}";
        window.open(url);
        }
        google.visualization.events.addListener(chartP, 'select', selectHandlerP);

        // Create the data table Outbound Secondary
        var dataS = new google.visualization.DataTable();
        dataS.addColumn('string', 'Submitted By');
        dataS.addColumn('number', 'Cancelled');
        dataS.addColumn({type: 'string', role: 'tooltip'});
        dataS.addRows([
        @foreach ($submitters[2] as $key=>$value)
        @php
        $total += $value->total;
        $cancelled += $value->cancelled;
        @endphp
        ['{{$value->site}}',
        {{$value->total}},"{{$value->site."\t\tTotal: ".$value->total."\tCancelled: ".$value->cancelled}}"],
        @endforeach
        
        ]);
        
        // Instantiate and draw our chart, passing in some options.
        var chartS = new google.visualization.PieChart(document.getElementById('chart_div_s'));
        chartS.draw(dataS, options);
        
        function selectHandlerS() {
        var selectedItem = chartS.getSelection()[0];
        var url = '/panel/checklist/manage?siteFilter='+dataS.getValue(selectedItem.row,0)+"&typeFilter={{Config::get('constants.TYPE_OUTB_SEC')}}";
        window.open(url);
        }
        google.visualization.events.addListener(chartS, 'select', selectHandlerS);

        // Create the data table.
        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Label');
        data2.addColumn('number', 'Count');
        data2.addColumn({type: 'string', role: 'tooltip'});
        data2.addRows([
            ["Accepted",{{$total - $cancelled}},"Accepted: "+{{$total - $cancelled}}+" out of "+{{$total}}],
            ["Rejected",{{$cancelled}},"Rejected: "+{{$cancelled}}+" out of "+{{$total}}]
         
        ]);
        var chart2 = new google.visualization.PieChart(document.getElementById('chart_total'));
        chart2.draw(data2, options);

        function selectHandler2() {
            var selectedItem = chart2.getSelection()[0];
            var url = '/panel/checklist/manage?statusFilter='+data2.getValue(selectedItem.row, 0);
            window.open(url);
        }
            
        google.visualization.events.addListener(chart2, 'select', selectHandler2);
        
        @endif
      }
</script>
@stop
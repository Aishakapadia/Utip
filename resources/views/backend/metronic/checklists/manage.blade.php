@extends( admin_layout('master') )

@section('title')
Manage Checklists
@stop

@section('head_page_level_plugins')
<link href="{{ admin_asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet"
    type="text/css" />
<link href="{{ admin_asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}"
    rel="stylesheet" type="text/css" />
<link href="{{ admin_asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}"
    rel="stylesheet" type="text/css" />
<link href="{{ admin_asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet"
    type="text/css" />
@stop

@section('head_resources')

@stop

@section('content')
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->

        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="{{ route('checklist-detail') }}">Checklist</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="{{ route('checklist-manage') }}">Manage</a>
                    <i class="fa fa-circle"></i>
                </li>
            </ul>
        </div>
        <!-- END PAGE BAR -->

        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> Checklists
            <small>manage</small>
        </h1>

        <div class="row">
            <div class="col-md-12">
            <table border="0" class="display" id="checklists">
                <thead>
                    <tr>
                        <th width="10%">Id</th>
                        <th width="15%">Inspection Site</th>
                        <th width="15%">Transporter</th>
                        <th width="15%">Vehicle Number</th>
                        <th width="15%">Vehicle Status</th>
                        <th width="15%">Type</th>
                        <th width="15%">Created At</th>
                        <th width="15%">Submitted By</th>
                        <th width="15%">Action</th>
                    </tr>
                    <tr>
                        <th width="10%"></th>
                        <th class="search" width="15%">Inspection Site</th>
                        <th class="search" width="15%">Transporter</th>
                        <th class="search" width="15%">Vehicle Number</th>
                        <th class="search" width="15%">Vehicle Status</th>
                        <th class="search" width="15%">Type</th>
                        <th class="search" width="15%">Created At</th>
                        <th class="search" width="15%">Submitted By</th>
                        <th width="15%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>loading...</td>
                    </tr>
                </tbody>
            </table>
            </div>
            <br>
            <div class="col-md-4">
                <form id="checklist-request" action="{{route('request-checklist')}}" method="get">
                    <div class="inbox-form-group mail-to">
                        <h4>Export Checklist</h4>
                        <label class="control-label">Start Date</label>
                        <div class="controls controls-to">
                            <div class="input-group date form_datetime bs-datetime"
                                data-date="{{ date('Y-m-d').'T'.date('H:i:s').'Z' }}" data-date-format="yyyy-mm-dd H:i:s">
                                {{--<input name="eta" class="form-control" size="16" type="text" value="" readonly>--}}
                                {{ Form::text('from', null, ['id' => 'from', 'class' => 'form-control', 'size' => 16, 'readonly' => 'readonly']) }}
                                <span class="input-group-addon">
                                    <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                        <label class="control-label">End Date</label>
                        <div class="controls controls-to">
                            <div class="input-group date form_datetime bs-datetime"
                                data-date="{{ date('Y-m-d').'T'.date('H:i:s').'Z' }}" data-date-format="yyyy-mm-dd H:i:s">
                                {{--<input name="eta" class="form-control" size="16" type="text" value="" readonly>--}}
                                {{ Form::text('to', null, ['id' => 'to', 'class' => 'form-control', 'size' => 16, 'readonly' => 'readonly']) }}
                                <span class="input-group-addon">
                                    <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="controls controls-to" style="padding-top:16px;">
                            <button type="submit" class="btn btn-default mt-4">Export</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END CONTENT BODY -->
</div>


@stop

@section('foot_page_level_plugins')
<script src="{{ admin_asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}"
    type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"
    type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js') }}"
    type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript">
</script>
{{--<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-confirmation/1.0.7/bootstrap-confirmation.min.js"></script>--}}
@stop

@section('foot_page_level_scripts')
{{--<script src="{{ admin_asset('assets/pages/scripts/ui-confirmations.min.js') }}" type="text/javascript"></script>--}}
<script src="{{ admin_asset('assets/custom/scripts/data-table-helper.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/pages/scripts/ui-confirmations.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/pages/scripts/ui-sweetalert.min.js') }}" type="text/javascript"></script>
@stop

@section('foot_resources')
<script>
    $(document).ready(function () {

        $(".form_datetime").datepicker({
        autoclose: true,
        isRTL: App.isRTL(),
        format: "dd-mm-yyyy",
        fontAwesome: true,
        pickerPosition: (App.isRTL() ? "top-right" : "top-left")
        });

           $('#checklists thead .search').each( function () {
            var title = $(this).text();
                $(this).html( '<input type="text" class="input is-small"  />' );
            } );

            var table = $('#checklists').DataTable({
                "serverSide": true,
                "aoSearchCols": [
                null,
               { "sSearch": '{{$siteFilter}}'},
                null,
                null,
                { "sSearch": '{{$statusFilter}}' },
                { "sSearch": '{{$typeFilter}}' },
                null
                ],
                "order": [[ 6, "desc" ]],
                "ajax": {
                    "url":"{{ route('search-checklists')}}",
                    "data":{
                        "filter":{{$siteFilter== "" && $statusFilter=="" ? 0 : 1}}
                    }
                },
                "columnDefs": [
                { "searchable": false, "targets": 0 }
                ]
            });

            // Apply the filter
            $("#checklists thead input").on( 'keyup change', function () {
            table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
            } );
        });
</script>
@stop
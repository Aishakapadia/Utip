@extends( admin_layout('master') )

@section('title')
Manage {{ $module->father->title }}
@stop

@section('head_page_level_plugins')
<link href="{{ admin_asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" rel="stylesheet"
    type="text/css" />
<link href="{{ admin_asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ admin_asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet"
    type="text/css" />

<link href="{{ admin_asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}"
    rel="stylesheet" type="text/css" />
<link href="{{ admin_asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}"
    rel="stylesheet" type="text/css" />
<link href="{{ admin_asset('assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}"
    rel="stylesheet" type="text/css" />
<link href="{{ admin_asset('assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}"
    rel="stylesheet" type="text/css" />
<link href="{{ admin_asset('assets/global/plugins/clockface/css/clockface.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('head_resources')

@stop

@section('content')
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN PAGE BAR -->
        <div class="page-bar" data-html2canvas-ignore="true">
            <ul class="page-breadcrumb">
                <li>
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="{{ route('ticket-manage') }}">{{ $module->father->title }}</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="{{ route('ticket-manage') }}">Manage</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Details</span>
                </li>
            </ul>
        </div>
        <!-- END PAGE BAR -->

        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> {{ $module->father->title }}
            <small>information</small>
        </h1>
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->

        <div class="row">
            <div class="col-md-12">

                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="{{ $module->father->icon }} font-green"></i>
                            <span class="caption-subject font-green sbold uppercase"> {{ $module->father->title }}
                                Details </span>
                        </div>
                        <div class="actions">

                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="tabbable-line">

                            @foreach ( ['danger', 'warning', 'success', 'info'] as $msg )
                            @if(Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#"
                                    class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                            @endif
                            @endforeach
                            @if(count($errors)>0)
                            <p class="alert alert-{{ "danger" }}">{{ Session::get('alert-' . "danger") }} <a href="#"
                                    class="close" data-dismiss="alert" aria-label="close">&times;</a>Failed to update
                                ticket.</p>
                            @endif

                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="portlet blue-hoki box">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-info"></i>Ticket Information
                                            </div>
                                            <div class="actions">
                                                <a href="{{ route('ticket-manage') }}" class="btn btn-default btn-sm">
                                                    <i class="fa fa-backward"></i> Go Back
                                                </a>
                                                {{--<a href="{{ route('ticket-edit.ticket', $ticket->id) }}" class="btn
                                                btn-default btn-sm">--}}
                                                {{--<i class="fa fa-pencil"></i> Edit--}}
                                                {{--</a>--}}
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="row static-info">
                                                <div class="col-md-3 name"> Ticket Number: </div>
                                                <div class="col-md-9 value"> {{ $ticket->ticket_number }} </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-3 name"> Vehicle Type: </div>
                                                <div class="col-md-9 value"> {{ $ticket->relationVehicleType->title }}
                                                </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-3 name"> Vehicle Required At: </div>
                                                <div class="col-md-9 value">
                                                    {{ date('M d, Y h:i A', strtotime($ticket->vehicle_required_at)) }}
                                                </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-3 name"> From Site: </div>
                                                <div class="col-md-9 value"> {{ $ticket->relationFromSite->title }}
                                                </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-3 name"> To Site: </div>
                                                <div class="col-md-9 value"> {{ $ticket->relationToSite->title }} </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-3 name"> Drop Off Sites: </div>
                                                <div class="col-md-9 value">
                                                    @if($ticket->relationDropOffSites)
                                                    <ul>
                                                        @foreach($ticket->relationDropOffSites as $site)
                                                        <li>{{ $site->title }}</li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-3 name"> Ticket Current Status: </div>
                                                <div class="col-md-9 value"> <span
                                                        class="label label-success">{{ $ticket->relationActiveStatus()->visible }}</span>
                                                </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-3 name"> Review: </div>
                                                <div class="col-md-9 value"> {!! $ticket->remarks !!} </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-3 name"> Delivery Challan Number: </div>
                                                <div class="col-md-9 value"> {!! $ticket->delivery_challan_number ?
                                                    $ticket->delivery_challan_number : '<span
                                                        class="help-block">NA</span>' !!} </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-3 name"> Created At: </div>
                                                <div class="col-md-9 value">
                                                    {{ date('M d, Y h:i:s A', strtotime($ticket->created_at)) }} </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-3 name"> Updated At: </div>
                                                <div class="col-md-9 value">
                                                    {{ date('M d, Y h:i:s A', strtotime($ticket->updated_at)) }} </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- .row -->
                            <!-- TICKET INFORMATION -->

                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="portlet blue-hoki box">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-info"></i>Description
                                            </div>
                                            <div class="actions">

                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="table-scrollable table-scrollable-borderless">
                                                {!! Form::model($ticket,['method' => 'PUT', 'route' =>
                                                ['ticket-ibd-update',$ticket->id], 'class' => 'form-horizontal
                                                form-bordered']) !!}
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr class="uppercase">
                                                            <th>Material Type</th>
                                                            <th>Material Code</th>
                                                            <th>Material Name</th>
                                                            <th>Quantity</th>
                                                            <th>Unit</th>
                                                            <th>Weight (KG)</th>
                                                            <th>Volume(m<sup>3</sup>)/Filled(%)</th>
                                                            <th>PO Number</th>
                                                            <th>IBD Number</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if($ticket->details)
                                                        <?php $totalWeight = 0; $totalVolume = 0; $readonly = !PermissionHelper::isTransporter() && !PermissionHelper::isSupplier() ? null : 'readonly'?>
                                                        @foreach($ticket->details as $detail)
                                                        <tr>
                                                            <td>{{ $detail->material_type }}</td>
                                                            <td>{{ $detail->material_code }}</td>
                                                            <td>{{ $detail->material }}</td>
                                                            <td>{{ $detail->quantity }}</td>
                                                            <td>{{ $detail->unit }}</td>
                                                            <td>{{ $detail->weight }}</td>
                                                            <td>{{ $detail->volume * $detail->quantity }}</td>
                                                            <td>{{ Form::text('po_num[]', is_null($detail->po_number)?"N/A":$detail->po_number, ['id' => 'po_num[]', 'class' => 'form-control',$readonly]) }}</td>
                                                            <td>{{ Form::text('ibd_num[]', is_null($detail->ibd_number)?"N/A":$detail->ibd_number, ['id' => 'ibd_num[]', 'class' => 'form-control',$readonly]) }}</td>
                                                        </tr>
                                                        <?php $totalWeight += $detail->weight;
                                                              $totalVolume += ($detail->volume * $detail->quantity); ?>
                                                        @endforeach
                                                        <tr>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td><strong>{{ $totalWeight }} KG</strong></td>
                                                            <td><strong>{{$ticket->relationVehicleType->volume < 1 ? "N/A" : round($totalVolume * 100 /$ticket->relationVehicleType->volume,3)." %" }}
                                                            </td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                                @if (!$readonly)
                                                    <div class="col-md-2">
                                                        <label class="btn btn-primary" for="ticket_ibd_update">
                                                            <input name="ticket_ibd_update" id="ticket_ibd_update" type="submit" style="display:none">Update IBD/PO Number
                                                        </label>
                                                    </div>
                                                @endif  
                                                {!! Form::close() !!}
                                            </div>
                                            @if($ticket->relationActiveStatus()->id >=
                                            Config::get('constants.OPEN_BY_SUPPLIER') &&
                                            $ticket->relationActiveStatus()->id <
                                                Config::get('constants.VEHICLE_LOADED_BY_SUPPLIER') && ($user->role_id
                                                == Config::get('constants.ROLE_ID_ADMIN')||$user->role_id ==
                                                Config::get('constants.ROLE_ID_SUPPLIER')))
                                                {{-- MATERIAL CUSTOMIZATION COMES HERE--}}
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-12 h4 text-primary">
                                                        Update Materials
                                                    </div>
                                                </div>
                                                <div>
                                                    {!! Form::model($ticket,['method' => 'PUT', 'route' =>
                                                    ['ticket-material-update',$ticket->id], 'class' => 'form-horizontal
                                                    form-bordered']) !!}
                                                    @if($ticket->details)

                                                    <div class="jq_material_section">
                                                        @foreach($ticket->details as $key => $detail)
                                                        <div class="jq_material_box">
                                                            <div class="row jq_close">
                                                                <a href="javascript:;" data-repeater-delete=""
                                                                    class="btn btn-danger jq_remove_material_section"
                                                                    style="float:right; margin-right: 30px; margin-top: 15px;">
                                                                    <i class="fa fa-close"></i>
                                                                </a>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div
                                                                        class="form-group {{ $errors->has('material_id.0') ? 'has-error' : '' }}">
                                                                        <label class="control-label col-md-3">Material
                                                                            Code
                                                                            <span class="required"
                                                                                aria-required="true">*</span></label>
                                                                        <div class="col-md-9">
                                                                            {{ Form::select('material_id[]', $materials, $detail->material_id, ['id' => 'material_id', 'class' => 'form-control select2 jq_material_id field_material_id']) }}
                                                                            @if($errors->has('material_id.0')) <span
                                                                                class="help-block">
                                                                                {{ $errors->first('material_id.0') }}
                                                                            </span> @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-6">
                                                                    <div
                                                                        class="form-group {{ $errors->has('material_type.0') ? 'has-error' : '' }}">
                                                                        <label class="control-label col-md-3">Material
                                                                            Type
                                                                            <span class="required"
                                                                                aria-required="true">*</span></label>
                                                                        <div class="col-md-9">
                                                                            {{--{{ Form::select('material_type[]', ['' => 'Select', 'RM' => 'RM', 'PM' => 'PM'], $detail->material_type, ['id' => 'material_type', 'class' => 'form-control field_material_type']) }}--}}
                                                                            {{ Form::text('material_type[]', $detail->material_type, ['id' => 'material_type', 'class' => 'form-control field_material_type jq_material_type', 'readonly' => 'readonly']) }}
                                                                            @if($errors->has('material_type.0')) <span
                                                                                class="help-block">
                                                                                {{ $errors->first('material_type.0') }}
                                                                            </span> @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row-->

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div
                                                                        class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                                                        <label class="control-label col-md-3">Material
                                                                            Description</label>
                                                                        <div class="col-md-9">
                                                                            {{ Form::text('description', $detail->material, ['id' => 'description', 'class' => 'form-control jq_description', 'readonly' => 'readonly']) }}
                                                                            @if($errors->has('description')) <span
                                                                                class="help-block">
                                                                                {{ $errors->first('description') }}
                                                                            </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-6">
                                                                    <div
                                                                        class="form-group {{ $errors->has('unit_id.0') ? 'has-error' : '' }}">
                                                                        <label class="control-label col-md-3">Unit <span
                                                                                class="required"
                                                                                aria-required="true">*</span></label>
                                                                        <div class="col-md-9">
                                                                            {{ Form::select('unit_id[]', $units, $detail->unit_id, ['id' => 'unit_id', 'class' => 'form-control select2 field_unit_id']) }}
                                                                            @if($errors->has('unit_id.0')) <span
                                                                                class="help-block">
                                                                                {{ $errors->first('unit_id.0') }}
                                                                            </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row-->

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div
                                                                        class="form-group {{ $errors->has('quantity.0') ? 'has-error' : '' }}">
                                                                        <label class="control-label col-md-3">Quantity
                                                                            <span class="required"
                                                                                aria-required="true">*</span></label>
                                                                        <div class="col-md-9">
                                                                            {{ Form::number('quantity[]', $detail->quantity, ['id' => 'quantity', 'class' => 'form-control field_quantity', 'min' => '1']) }}
                                                                            @if($errors->has('quantity.0')) <span
                                                                                class="help-block">
                                                                                {{ $errors->first('quantity.0') }}
                                                                            </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-6">
                                                                    <div
                                                                        class="form-group {{ $errors->has('weight.0') ? 'has-error' : '' }}">
                                                                        <label class="control-label col-md-3">Weight
                                                                            (KG)
                                                                            <span class="required"
                                                                                aria-required="true">*</span></label>
                                                                        <div class="col-md-9">
                                                                            {{ Form::number('weight[]', $detail->weight, ['id' => 'weight', 'class' => 'form-control field_weight', 'min' => '1']) }}
                                                                            @if($errors->has('weight.0')) <span
                                                                                class="help-block">
                                                                                {{ $errors->first('weight.0') }} </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row-->

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div
                                                                        class="form-group {{ $errors->has('volume') ? 'has-error' : '' }}">
                                                                        <label
                                                                            class="control-label col-md-3">Volume</label>
                                                                        <div class="col-md-9">
                                                                            {{ Form::text('volume[]', $detail->volume, ['id' => 'volume', 'class' => 'form-control jq_volume', 'readonly' => 'readonly']) }}
                                                                            @if($errors->has('volume')) <span
                                                                                class="help-block">
                                                                                {{ $errors->first('volume') }} </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div
                                                                        class="form-group {{ $errors->has('po_number.0') ? 'has-error' : '' }}">
                                                                        <label class="control-label col-md-3">PO Number
                                                                            <span class="required"
                                                                                aria-required="true">*</span></label>
                                                                        <div class="col-md-9">
                                                                            {{ Form::text('po_number[]', $detail->po_number, ['id' => 'po_number', 'class' => 'form-control field_po_number']) }}
                                                                            @if($errors->has('po_number.0')) <span
                                                                                class="help-block">
                                                                                {{ $errors->first('po_number.0') }}
                                                                            </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-6">
                                                                    <div
                                                                        class="form-group {{ $errors->has('ibd_number.0') ? 'has-error' : '' }}">
                                                                        <label class="control-label col-md-3">IBD Number
                                                                        </label>
                                                                        <div class="col-md-9">
                                                                            {{ Form::text('ibd_number[]', $detail->ibd_number, ['id' => 'ibd_number', 'class' => 'form-control field_ibd_number']) }}
                                                                            @if($errors->has('ibd_number.0')) <span
                                                                                class="help-block">
                                                                                {{ $errors->first('ibd_number.0') }}
                                                                            </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row-->

                                                        </div><!-- .jq_material_box -->

                                                        <hr>

                                                        @endforeach

                                                    </div><!-- .jq_material_section -->
                                                    @endif
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <a href="javascript:;" data-repeater-create=""
                                                            class="btn btn-info jq-repeater-add">
                                                            <i class="fa fa-plus"></i> Add More Material
                                                        </a>
                                                    </div>
                                                </div>
                                                <!--/row-->
                                                <div class="row">
                                                    <div class="col-md-10">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="btn btn-primary" for="material_update">
                                                            <input name="material_update" id="material_update"
                                                                type="submit" style="display:none">Update Material
                                                        </label>
                                                    </div>
                                                </div>
                                                <!--/row-->
                                                {!! Form::close() !!}
                                                @endif
                                        </div>
                                    </div>
                                </div>
                            </div><!-- .row -->
                            <!-- DESCRIPTION -->

                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="portlet blue-hoki box">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-info"></i>Transporter Information
                                            </div>
                                            <div class="actions">

                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="table-scrollable table-scrollable-borderless">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr class="uppercase">
                                                            <th>Transporter</th>
                                                            <th>Vehicle Number</th>
                                                            <th>Driver Contact #</th>
                                                            <th>ETA</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if($ticket->relationTransporters()->count())
                                                        @foreach($transporterInformation as $transporter)
                                                        <tr
                                                            style="background: {{ \App\TransporterStatus::find($transporter->pivot->transporter_status_id)->color_code }};">
                                                            <td>{{ $transporter->title ? $transporter->title : 'NA' }}
                                                            </td>
                                                            <td>{{ $transporter->pivot->vehicle_number ? $transporter->pivot->vehicle_number : 'NA' }}
                                                            </td>
                                                            <td>{{ $transporter->pivot->driver_contact ? $transporter->pivot->driver_contact : 'NA' }}
                                                            </td>
                                                            <td>{{ isset($transporter->pivot->eta) ? date(\App\Setting::getStandardDateTimeFormat(), strtotime($transporter->pivot->eta)) : 'NA' }}
                                                            </td>
                                                            <td>{{ \App\TransporterStatus::find($transporter->pivot->transporter_status_id)->title }}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                            {{-- VEHICLE NUMBER CHANGE STARTS HERE--}}
                                            @if($ticket->relationActiveStatus()->id >=
                                            Config::get('constants.CONFIRM_TRANSPORTER_BY_ADMIN')
                                            && $ticket->relationActiveStatus()->id <=
                                                Config::get('constants.VEHICLE_APPROVED_BY_SUPPLIER') && ($user->role_id
                                                == Config::get('constants.ROLE_ID_ADMIN')||$user->role_id ==
                                                Config::get('constants.ROLE_ID_TRANSPORTER')))
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-12 h4 text-primary">
                                                        Update Transporter Details
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        {!! Form::model($ticket,['method' => 'PUT', 'route' =>
                                                        ['vehicle-number-update',$ticket->id], 'class' =>
                                                        'form-horizontal
                                                        form-bordered']) !!}
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4">New Vehicle
                                                                Number</label>
                                                            <div class="col-md-7">
                                                                <input id="new_vehicle_number" name="new_vehicle_number"
                                                                    class="form-control" type="text"
                                                                    value="{{$transporterInformation[0]->pivot->vehicle_number}}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4">New Mobile
                                                                Number</label>
                                                            <div class="col-md-7">
                                                                <input id="new_mobile_number" name="new_mobile_number"
                                                                    class="form-control" type="text"
                                                                    pattern="^((\+92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$"
                                                                    value="{{$transporterInformation[0]->pivot->driver_contact}}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4">Vehicle Type</label>
                                                            <div class="col-md-8">
                                                                @php
                                                                $attributes = [];
                                                                $attributes['id'] = 'vehicle_type_id';
                                                                $attributes['class'] = 'form-control select2';
                                                                @endphp
                                                                {{ Form::select('vehicle_type_id', $vehicle_types, null, $attributes) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <label class="btn btn-primary" for="vehicle_update">
                                                            <input name="vehicle_update" id="vehicle_update"
                                                                type="submit" style="display:none">
                                                            Update
                                                        </label>
                                                    </div>
                                                    <!--/span-->
                                                    {!! Form::close() !!}
                                                </div>
                                                <!--/row-->
                                                @endif
                                                {{-- VEHICLE NUMBER CHANGE END HERE--}}
                                        </div>
                                    </div>
                                </div>
                            </div><!-- .row -->

                            <!-- UPDATE DESTINATION INFORMATION -->
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="portlet blue-hoki box">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-info"></i>Update Destination
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                                {{-- TO SITE CHANGE STARTS HERE--}}
                                                @if($ticket->relationActiveStatus()->id <
                                                    Config::get('constants.VEHICLE_LOADED_BY_SUPPLIER') && $user->
                                                    role_id == Config::get('constants.ROLE_ID_ADMIN'))
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            {!! Form::model($ticket,['method' => 'PUT',
                                                            'route' => ['to-site-update',$ticket->id],
                                                            'class' => 'form-horizontal form-bordered']) !!}
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    {{ Form::hidden('site_id_from', $ticket->site_id_from) }}
                                                                    <label class="control-label col-md-4">To Site</label>
                                                                    <div class="col-md-8">
                                                                        @php
                                                                        $attributes = [];
                                                                        $attributes['id'] = 'site_id_to';
                                                                        $attributes['class'] = 'form-control select2';
                                                                        @endphp
                                                                        {{ Form::select('site_id_to', $sites, null, $attributes) }}
                                                                        @if($errors->has('site_id_to')) <span class="help-block"> {{ $errors->first('site_id_to') }} </span> @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="btn btn-primary" for="to_site_update">
                                                                <input name="to_site_update" id="to_site_update"
                                                                    type="submit" style="display:none">
                                                                Update
                                                            </label>
                                                        </div>
                                                        <!--/span-->
                                                        {!! Form::close() !!}

                                                    <!--/span-->

                                            </div>
                                            <!--/row-->
                                            @endif
                                            {{-- TO SITE CHANGE END HERE--}}
                                        </div>
                                    </div>
                                </div>
                            </div><!-- .row -->
                            <!-- UPDATE TO SITE INFORMATION -->

                            {{-- CHANGE TRANSPORTER STARTS HERE--}}
                            @if(PermissionHelper::isAdmin() && 
                            $ticket->relationActiveStatus()->id > \Config::get('constants.ACCEPT_BY_TRANSPORTER') &&
                            $ticket->relationActiveStatus()->id < \Config::get('constants.VEHICLE_LOADED_BY_SUPPLIER'))
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="portlet blue-hoki box">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-info"></i>Change Transporter
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            @if($ticket->relationActiveStatus()->id < Config::get('constants.VEHICLE_LOADED_BY_SUPPLIER') && $user->
                                                role_id == Config::get('constants.ROLE_ID_ADMIN'))
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        {!! Form::model($ticket,['method' => 'PUT',
                                                        'route' => ['transporter-update',$ticket->id],
                                                        'class' => 'form-horizontal form-bordered']) !!}
                                                        <div class="row">
                                                            <div class="form-group">
                                                                @if($transporters)
                                                                            <label class="control-label col-md-4">Transporters <span class="required"
                                                                                    aria-required="true">*</span></label>
                                                                            <div class="col-md-8">
                                                                                {{ Form::select('transporter_id', $transporters_update, null, ['id' => 'transporter_id', 'class' => 'form-control']) }}
                                                                                @if($errors->has('transporter_id')) <span class="help-block">
                                                                                    {{ $errors->first('transporter_id') }} </span>
                                                                                @endif
                                                                            </div>
                                                                <!--/row-->
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="btn btn-primary" for="transporter_update">
                                                            <input name="transporter_update" id="transporter_update" type="submit" style="display:none">
                                                            Change
                                                        </label>
                                                    </div>
                                                    <!--/span-->
                                                    {!! Form::close() !!}
                            
                                                    <!--/span-->
                            
                                                </div>
                                                <!--/row-->
                                                @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            {{-- CHANGE TRANSPORTER ENDS HERE --}}


                            @if($ticket->relationActiveStatus()->id <
                                \Config::get('constants.VEHICLE_OFFLOADED_BY_SITE_TEAM')) <div class="row">
                                <div class="col-md-12 col-sm-12" data-html2canvas-ignore="true">
                                    <div class="portlet blue-hoki box">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-info"></i>Update
                                            </div>
                                            <div class="actions">

                                            </div>
                                        </div>
                                        <div class="portlet-body">

                                            <div class="portlet-body form">

                                                {!! Form::model($ticket, ['method' => 'PUT', 'route' =>
                                                ['ticket-status.ticket', $ticket->id], 'class' => 'form-horizontal
                                                form-bordered','files'=> true]) !!}

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="form-group {{ $errors->has('status_id') ? 'has-error' : '' }}">
                                                            <label class="control-label col-md-3">Status <span
                                                                    class="required"
                                                                    aria-required="true">*</span></label>
                                                            <div class="col-md-9">
                                                                {{ Form::select('status_id', $statuses, null, ['id' => 'status_id', 'class' => 'form-control']) }}
                                                                @if($errors->has('status_id')) <span class="help-block">
                                                                    {{ $errors->first('status_id') }} </span> @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Upload file</label>
                                                            <div class="col-md-9">
                                                                <label class="btn btn-primary" for="file_upload">
                                                                    <input name="file_upload[]" id="file_upload"
                                                                        type="file" style="display:none" multiple>
                                                                    Browse
                                                                </label>
                                                                <span class='label label-primary'
                                                                    id="upload-file-info"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <!--/row-->

                                                {{--@if(PermissionHelper::isTransporter() && $ticket->relationActiveStatus()->id < 7)--}}
                                                @if(PermissionHelper::isTransporter())
                                                @if($ticket->relationActiveStatus()->id ==
                                                \Config::get('constants.APPROVE_BY_ADMIN') ||
                                                $ticket->relationActiveStatus()->id ==
                                                \Config::get('constants.CANCELLED_BY_SUPPLIER'))
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="form-group {{ $errors->has('vehicle_number') ? 'has-error' : '' }}">
                                                            <label class="control-label col-md-3">Vehicle Number <span
                                                                    class="required"
                                                                    aria-required="true">*</span></label>
                                                            <div class="col-md-9">
                                                                {{ Form::text('vehicle_number', null, ['id' => 'vehicle_number', 'class' => 'form-control']) }}
                                                                @if($errors->has('vehicle_number')) <span
                                                                    class="help-block">
                                                                    {{ $errors->first('vehicle_number') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <!--/row-->

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="form-group {{ $errors->has('driver_contact') ? 'has-error' : '' }}">
                                                            <label class="control-label col-md-3">Driver Contact Number
                                                                <span class="required"
                                                                    aria-required="true">*</span></label>
                                                            <div class="col-md-9">
                                                                {{ Form::text('driver_contact', null, ['id' => 'driver_contact', 'class' => 'form-control',
                                                                                'pattern' => '^((\+92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$',
                                                                                'title'=>'Valid phone number']) }}
                                                                @if($errors->has('driver_contact')) <span
                                                                    class="help-block">
                                                                    {{ $errors->first('driver_contact') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <!--/row-->

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="form-group {{ $errors->has('eta') ? 'has-error' : '' }}">
                                                            <label class="control-label col-md-3">ETA <span
                                                                    class="required"
                                                                    aria-required="true">*</span></label>
                                                            <div class="col-md-9">

                                                                <div class="input-group date form_datetime bs-datetime"
                                                                    data-date="{{ date('Y-m-d').'T'.date('H:i:s').'Z' }}"
                                                                    data-date-format="yyyy-mm-dd H:i:s">
                                                                    {{--<input name="eta" class="form-control" size="16" type="text" value="" readonly>--}}
                                                                    {{ Form::text('eta', null, ['id' => 'eta', 'class' => 'form-control', 'size' => 16, 'readonly' => 'readonly']) }}
                                                                    <span class="input-group-addon">
                                                                        <button class="btn default date-reset"
                                                                            type="button"><i
                                                                                class="fa fa-times"></i></button>
                                                                    </span>
                                                                    <span class="input-group-addon">
                                                                        <button class="btn default date-set"
                                                                            type="button"><i
                                                                                class="fa fa-calendar"></i></button>
                                                                    </span>
                                                                </div>
                                                                @if($errors->has('eta')) <span class="help-block">
                                                                    {{ $errors->first('eta') }} </span> @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <!--/row-->
                                                @endif
                                                @endif

                                                @if(PermissionHelper::isAdmin() && $ticket->relationActiveStatus()->id
                                                == \Config::get('constants.ACCEPT_BY_TRANSPORTER'))
                                                @if($transporters)
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="form-group {{ $errors->has('transporter_id') ? 'has-error' : '' }}">
                                                            <label class="control-label col-md-3">Transporters <span
                                                                    class="required"
                                                                    aria-required="true">*</span></label>
                                                            <div class="col-md-9">
                                                                {{ Form::select('transporter_id', $transporters, null, ['id' => 'transporter_id', 'class' => 'form-control']) }}
                                                                @if($errors->has('transporter_id')) <span
                                                                    class="help-block">
                                                                    {{ $errors->first('transporter_id') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <!--/row-->
                                                @endif
                                                @endif

                                                @if(PermissionHelper::isSupplier() &&
                                                $ticket->relationActiveStatus()->id ==
                                                \Config::get('constants.VEHICLE_APPROVED_BY_SUPPLIER'))
                                                @if(!$ticket->delivery_challan_number)
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="form-group {{ $errors->has('delivery_challan_number') ? 'has-error' : '' }}">
                                                            <label class="control-label col-md-3">DC Number <span
                                                                    class="required"
                                                                    aria-required="true">*</span></label>
                                                            <div class="col-md-9">
                                                                {{ Form::text('delivery_challan_number', null, ['id' => 'delivery_challan_number', 'class' => 'form-control']) }}
                                                                @if($errors->has('delivery_challan_number')) <span
                                                                    class="help-block">
                                                                    {{ $errors->first('delivery_challan_number') }}
                                                                </span> @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <!--/row-->
                                                @endif
                                                @endif

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
                                                            <label class="control-label col-md-3">Comments </label>
                                                            <div class="col-md-9">
                                                                {{ Form::textarea('comments', $comment, ['id' => 'comments', 'class' => 'form-control', 'rows' => 2]) }}
                                                                @if($errors->has('comments')) <span class="help-block">
                                                                    {{ $errors->first('comments') }} </span> @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">

                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <!--/row-->

                                                @if ($ticket->relationActiveStatus()->id >=
                                                Config::get('constants.CONFIRM_TRANSPORTER_BY_ADMIN') &&
                                                $ticket->relationActiveStatus()->id <
                                                    Config::get('constants.VEHICLE_LOADED_BY_SUPPLIER') && ($user->
                                                    agent))

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div
                                                                class="form-group {{ $errors->has('eta') ? 'has-error' : '' }}">
                                                                <label class="control-label col-md-3">Updated At</label>
                                                                <div class="col-md-9">

                                                                    <div class="input-group date form_datetime bs-datetime"
                                                                        data-date="{{ date('Y-m-d').'T'.date('H:i:s').'Z' }}"
                                                                        data-date-format="yyyy-mm-dd H:i:s">
                                                                        {{ Form::text('updated_at', null, ['id' => 'updated_at', 'class' => 'form-control', 'size' => 16, 'readonly' => 'readonly']) }}
                                                                        <span class="input-group-addon">
                                                                            <button class="btn default date-reset"
                                                                                type="button"><i
                                                                                    class="fa fa-times"></i></button>
                                                                        </span>
                                                                        <span class="input-group-addon">
                                                                            <button class="btn default date-set"
                                                                                type="button"><i
                                                                                    class="fa fa-calendar"></i></button>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                        </div>
                                                        <!--/span-->
                                                    </div>
                                                    @endif

                                                    <!-- Checklist start here -->
                                                    @if((PermissionHelper::isSupplier() &&
                                                    ($ticket->relationActiveStatus()->id ==
                                                    \Config::get('constants.VEHICLE_ARRIVED_BY_SUPPLIER') ||
                                                    $ticket->relationActiveStatus()->id ==
                                                    \Config::get('constants.UPDATED_BY_TRANSPORTER'))) ||
                                                    ($user->isSiteTeam() &&
                                                    $ticket->relationActiveStatus()->id ==
                                                    \Config::get('constants.VEHICLE_LOADED_BY_SUPPLIER')))
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h3 class="caption">Safety Checklist</h3>
                                                            @if($errors->has('question')) <span class="help-block "
                                                                style="color: red">
                                                                {{ $errors->first('question') }} </span> @endif
                                                        </div>
                                                    </div>
                                                    {!!$checklist!!}
                                                    @endif
                                                    <!-- Checklist ends here -->

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-3"></label>
                                                                <div class="col-md-9">
                                                                    {{ Form::hidden('current_status', $ticket->relationActiveStatus()->id) }}
                                                                    {{ Form::hidden('summary', "") }}
                                                                    <button type="submit"
                                                                        class="btn green">SAVE</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                        </div>
                                                        <!--/span-->
                                                    </div>
                                                    <!--/row-->
                                                    {!! Form::close() !!}

                                            </div>

                                        </div>
                                    </div>
                                </div>
                        </div><!-- UPDATE TICKET STATUS FORM -->
                        @endif

                    </div><!-- .tabbable-line -->
                </div><!-- .portlet-body -->
            </div>

        </div>
    </div>

    <div class="row">
        <!--Uploaded Files START-->
        <div class="col-md-12 col-sm-12">
            <div class="portlet blue-hoki box">
                <div class="portlet-title">
                    <div class="caption">
                        </i>Uploaded Files
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-scrollable table-scrollable-borderless">
                        <table class="table table-hover">
                            <thead>
                                <tr class="uppercase">
                                    <th>Filename</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($files))
                                @foreach($files as $f)
                                <tr>
                                    <td> <a href="\uploads\files\{{ $f->file }}" target="_blank"
                                            download>{{ $f->file }}</a>
                                    </td>
                                    <td>{{ date(\App\Setting::getStandardDateTimeFormat(), strtotime($f->created_at)) }}
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td>No files uploaded for this ticket</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Uploaded Files END -->

    @if(!PermissionHelper::isTransporter() )
    <div class="portlet light portlet-fit bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-clock font-green"></i>
                <span class="caption-subject bold font-green uppercase">Ticket History</span>
                <span class="caption-helper">Request's statuses</span>
            </div>
            <div class="actions hide">
                <div class="btn-group btn-group-devided" data-toggle="buttons">
                    <label class="btn red btn-outline btn-circle btn-sm active">
                        <input type="radio" name="options" class="toggle" id="option1">Settings</label>
                    <label class="btn  red btn-outline btn-circle btn-sm">
                        <input type="radio" name="options" class="toggle" id="option2">Tools</label>
                </div>
            </div>
        </div>
        <div class="portlet-body">
            @if($ticket->relationStatusesWithDetail->count() > 0)
            <div class="mt-timeline-2">

                <div class="mt-timeline-line border-grey-steel"></div>
                <ul class="mt-container">

                    @foreach($ticket->relationStatusesWithDetail as $status)
                    {{--{{ dump($status) }}--}}
                    <li class="mt-item">
                        <div class="mt-timeline-icon bg-blue bg-font-blue border-grey-steel">
                            <i class="{{ $status->icon }}"></i>
                        </div>
                        <div class="mt-timeline-content">
                            <div class="mt-content-container bg-white border-grey-steel">
                                <div class="mt-title">
                                    <h3 class="mt-content-title">{{ $status->title }}</h3>
                                </div>
                                <div class="mt-author">
                                    <div class="mt-avatar">
                                        <img src="{{ admin_asset('assets/pages/media/users/avatar80_3.jpg') }}" />
                                    </div>
                                    <div class="mt-author-name">
                                        <a href="javascript:;" class="font-blue-madison">{{ $status->name }}
                                            ({{ $status->role }})</a>
                                    </div>
                                    {{--<div class="mt-author-notes font-grey-mint">{{ \Carbon\Carbon::parse($status->created_at)->format('M d, Y - h:m A')}}
                                </div>--}}
                                <div class="mt-author-notes font-grey-mint">
                                    {{ date(\App\Setting::getStandardDateTimeFormat(), strtotime($status->created_at)) }}
                                </div>
                            </div>
                            <div class="mt-content border-grey-steel">
                                <p>{{ $status->comments ? $status->comments : 'N/A' }}</p>
                            </div>
                        </div>
            </div>
            </li>
            @endforeach

            </ul>
        </div>
        @endif
    </div>
    @endif
</div>

</div>
<!-- END CONTENT BODY -->
</div>
@stop

@section('foot_page_level_plugins')
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
@stop

@section('foot_page_level_scripts')
<script src="{{ admin_asset('assets/pages/scripts/components-editors.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/html2canvas/html2canvas.min.js') }}" type="text/javascript"></script>
{{--<script src="{{ admin_asset('assets/pages/scripts/components-date-time-pickers.js') }}" type="text/javascript">
</script>--}}
@stop

@section('foot_resources')
<script>
    $('input#title').on('keyup', function() {
        $('#slug').val( main.convertToSlug( $(this).val() ) );
    });

    $(".select2").select2();

    var placeholder = "Select";
    $(".select2-multiple").select2({
        //placeholder: placeholder,
        width: null
    });


    var box = $('.jq_material_box').clone();

    $(".form_datetime").datetimepicker({
            autoclose: true,
            isRTL: App.isRTL(),
            format: "dd MM yyyy hh:ii",
            fontAwesome: true,
            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
            minuteStep: 15
        });

        $("#updated_at").val("");

        $('#file_upload').bind('change', function() {

            //this.files[0].size gets the size of your file.
            var fileExtension = ['exe','mp4','avi','mov','mkv'];
            var msg = this.files.length + " files uploaded";

            for (let index = 0; index < this.files.length; index++) {
                const element = this.files[index];
                if (element.size < 5242880 && $.inArray(element.name.split('.').pop().toLowerCase(), fileExtension)==-1 ){

                }else{
                    msg = "Error: Please upload files of supported format (< 5MB)";
                    $('#file_upload').wrap('<form>').closest('form').get(0).reset();
                    $('#file_upload').unwrap();
                    break;
                }
            }
            $('#upload-file-info').html(msg);            
        });

        $(window).on('load',function () {
            if($('.jq_close').length == 1){
                $('.jq_close').remove();
            }
            //Client-side Summary Snapshot
            if({{ $completed }}){
                if (document.documentElement.clientWidth < 720) { 
                    document.querySelector("meta[name=viewport]").setAttribute(
                        'content', 
                        'width=device-width, initial-scale=0.35, maximum-scale=1.0, user-scalable=0');
                }
            
            html2canvas(document.querySelector(".page-content"),{
                scrollY:window.pageYOffset*-1,
                scale:0.85
            }).then(canvas => {
                //document.body.appendChild(canvas)
                // Get a base64 data string
                var imageType = 'image/jpeg';
                var imageData = canvas.toDataURL(imageType);
                var summary = $('input[name="summary"]');
                summary.val(imageData);
                // Open the data string in the current window
                //document.location.href = imageData.replace(imageType, 'image/octet-stream');
            });
        }
        }); 
        
        $('.jq_remove_material_section').on('click', function (e) {
            e.preventDefault();
            $(this).parents('.jq_material_box').remove();
            if($('.jq_close').length == 1){
                $('.jq_close').remove();
            }
        });


        $('.jq-repeater-add').on('click', function () {
            var el = $(this);
            var id = el.val();
            main.ajax(main.adminUrl('ticket/material-form'), 'GET', {}, function (data) {
                $('.jq_material_section').append(data);
            });
        });

        $('.jq_material_id').on('change', function () {
        var el = $(this);
        var id = el.val();
        main.ajax(main.adminUrl('material/detail'), 'GET', {id: id}, function (data) {
            el.parents('.jq_material_box').find('.jq_description').val(data.title);
            el.parents('.jq_material_box').find('.jq_material_type').val(data.type);
            quantity = el.parents('.jq_material_box').find('#quantity').val() === '' ? 0 : el.parents('.jq_material_box').find('#quantity').val(); 
            el.parents('.jq_material_box').find('#volume').val(data.volume * quantity);
        });

        $('.field_quantity').on('change', function () {
               var el = $(this).parents('.jq_material_box').find('#material_id');
               var id = el.val();
               if (id !== ''){
               main.ajax(main.adminUrl('material/detail'), 'GET', {id: id}, function (data) {
                    quantity = el.parents('.jq_material_box').find('#quantity').val() === '' ? 0 : el.parents('.jq_material_box').find('#quantity').val(); 
                    el.parents('.jq_material_box').find('#volume').val(data.volume * quantity);
               });

               }
            });
    });


</script>
@stop
@extends( admin_layout('master') )


@section('title')
    {{ $pageMode }} {{ $module->title }}
@stop


@section('head_page_level_plugins')
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ admin_asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
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
            <div class="page-bar">
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
                        <span>{{ $pageMode }}</span>
                    </li>
                </ul>
            </div>
            <!-- END PAGE BAR -->

            <!-- BEGIN PAGE TITLE-->
            <h1 class="page-title"> {{ $module->father->title }}
                <small>{{ strtolower($pageMode) }}</small>
            </h1>
            <!-- END PAGE TITLE-->

            <!-- END PAGE HEADER-->
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered form-fit">

                        <div class="portlet-title">
                            <div class="caption">
                                <i class="{{ $module->father->icon }} font-blue-hoki"></i>
                                <span class="caption-subject font-blue-hoki sbold uppercase"> {{ $module->father->title }} Create </span>
                            </div>
                            <div class="actions hide">
                                <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                                    <i class="icon-cloud-upload"></i>
                                </a>
                                <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                                    <i class="icon-wrench"></i>
                                </a>
                                <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                                    <i class="icon-trash"></i>
                                </a>
                            </div>
                        </div>

                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
	                        @if(strtolower($pageMode) != 'edit')
	                            {!! Form::open(['route' => 'ticket-create', 'class' => 'form-horizontal form-bordered', 'id' => 'ticket_form']) !!}
	                        @else
	                            {!! Form::model($ticket, ['method' => 'PUT', 'route' => ['ticket-update.ticket', $ticket->id], 'class' => 'form-horizontal form-bordered', 'id' => 'ticket_form']) !!}
	                        @endif
	                            @include(admin_view('tickets.form'))
	                        {!! Form::close() !!}
                            <!-- END FORM-->
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- END CONTENT BODY -->
    </div>
@stop


@section('foot_page_level_plugins')
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js') }}" type="text/javascript"></script>
    
    <script src="{{ admin_asset('assets/global/plugins/jquery-csv/jquery.csv.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/clockface/js/clockface.js') }}" type="text/javascript"></script>
@stop


@section('foot_page_level_scripts')
    <script src="{{ admin_asset('assets/pages/scripts/components-editors.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
@stop


@section('foot_resources')

	{{--@if(env('ADMIN_CLIENT_VALIDATIONS', 1))--}}
		{{--<!-- Laravel Javascript Validation -->--}}
		{{--<script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js') }}"></script>--}}
		{{--@if($pageMode != 'Edit')--}}
			{{--{!! JsValidator::formRequest('App\Http\Requests\Backend\TicketStoreRequest') !!}--}}
		{{--@else--}}
			{{--{!! JsValidator::formRequest('App\Http\Requests\Backend\TicketUpdateRequest') !!}--}}
		{{--@endif--}}
	{{--@endif--}}

    <script type="text/javascript">

        //disable manual input for copies field
        $("#copy").keypress(function (evt) {
            evt.preventDefault();
        });
        
        $(function(){

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

//            $('.jq-repeater-add').on('click', function(e) {
//                e.preventDefault();
//
//                $('.jq_material_section .jq_close').fadeIn();
//
//                $('.jq_material_section').append(box);
//
//            });

            $('.jq_material_id').on('change', function () {
               var el = $(this);
               var id = el.val();
               main.ajax(main.adminUrl('material/detail'), 'GET', {id: id}, function (data) {
                   //console.log(data);
                    $('#description').val(data.title);
                    $('#material_type').val(data.type);
                    quantity = $('#quantity').val() === '' ? 0 : $('#quantity').val(); 
                    $('#volume').val(data.volume * quantity);
               });
            });

            $('.field_quantity').on('change', function () {
               var el = $('#material_id');
               var id = el.val();
               if (id !== ''){
               main.ajax(main.adminUrl('material/detail'), 'GET', {id: id}, function (data) {
                    quantity = $('#quantity').val() === '' ? 0 : $('#quantity').val(); 
                    $('#volume').val(data.volume * quantity);
               });

               }
            });


            $('.jq-repeater-add').on('click', function () {
                var el = $(this);
                var id = el.val();
                main.ajax(main.adminUrl('ticket/material-form'), 'GET', {}, function (data) {
                    $('.jq_material_section').append(data);
                });
            });

            //Adds material via CSV
            $('#batch_add').on('change', function () {
                //remove all previous except first
                $('.jq_material_box').not(':first').remove();
                var file = this.files[0]
                var fileExtension = ['csv'];
                if ($.inArray(this.files[0].name.split('.').pop().toLowerCase(), fileExtension) != -1 ) {
                    var reader = new FileReader();
                    reader.readAsText(file, "UTF-8");
                    reader.onload = function (evt) {
                        var rows = $.csv.toObjects(evt.target.result);
                        var materials = document.getElementById('material_id').options;
                        var units = document.getElementById('unit_id').options;

                        //Fill first row
                        let value = getMaterialValue(materials,rows[0]['Material Code']);
                        var el = $('.jq_material_section').find('.jq_material_id');
                        el.val(value);
                        $('.jq_material_section').find('#quantity').last().val(rows[0]['Quantity']);
                        $('.jq_material_section').find('#weight').last().val(rows[0]['Weight']);
                        $('.jq_material_section').find('#unit_id').last().val(getUnitValue(units,rows[0]['Unit']));
                        $('.jq_material_section').find('#po_number').last().val(rows[0]['PO Number']);
                        $('.jq_material_section').find('#ibd_number').last().val(rows[0]['IBD Number']);
                        populateMaterialDetails(el,value);

                        //Fill rest of the rows
                        $.get(main.adminUrl('ticket/material-form'), function(result) {
                            for (let index = 1; index < rows.length; index++) {
                                data = $(result);
                                let value = getMaterialValue(materials,rows[index]['Material Code']);
                                var el = data.find('.jq_material_id');
                                el.val(value);
                                data.find('#quantity').last().val(rows[index]['Quantity']);
                                data.find('#weight').last().val(rows[index]['Weight']);
                                data.find('#unit_id').last().val(getUnitValue(units,rows[index]['Unit']));
                                data.find('#po_number').last().val(rows[index]['PO Number']);
                                data.find('#ibd_number').last().val(rows[index]['IBD Number']);
                                populateMaterialDetails(el,value);
                                $('.jq_material_section').append(data);
                                 
                            }
                        });

                        $('#batch_add').wrap('<form>').closest('form').get(0).reset();
                        $('#batch_add').unwrap();
                    }
                    reader.onerror = function (evt) {
                        alert("Failed to load file");
                    }
                }
            });
            
            function getMaterialValue(materials,text) {
                let value = "";
                for (let i = 0;i<materials.length;i++) {
                    if(materials[i].text === text){
                        value = materials[i].value;
                        break;
                    }
                }
                return value
            }

            function getUnitValue(units,text) {
                let value = "";
                for (let i = 0;i<units.length;i++) {
                    if(units[i].text === text){
                        value = units[i].value;
                        break;
                    }
                }
                return value
            }

            function populateMaterialDetails(el,id){
                main.ajax(main.adminUrl('material/detail'), 'GET', {id: id}, function (data) {
                    el.parents('.jq_material_box').find('.jq_description').val(data.title);
                    el.parents('.jq_material_box').find('.jq_material_type').val(data.type);
                    quantity = el.parents('.jq_material_box').find('.field_quantity').val() === '' ? 0 : el.parents('.jq_material_box').find('.field_quantity').val(); 
                    el.parents('.jq_material_box').find('.jq_volume').val(data.volume * quantity);
                });
            }

            $('#submit, #save').on('click', function (e) {
                e.preventDefault();

                var el = $(this);
                var data = $('#ticket_form').serialize();
                var uri = 'ticket/create';
                var method = 'POST';
                var type = 'json';

                //el.prop('disabled', true);
                $('#save, #submit').prop('disabled', true);

                if($('#update').val() !== undefined) {
                    uri = 'ticket/update/'+$('#ticket_id').val();
                    type = 'PUT';
                }

                $.ajax({
                    url: main.adminUrl(uri),
                    method: method,
                    data: $('#ticket_form').serialize() + '&action=' + this.id,
                    type: type,
                    success: function (data) {
                        //console.log(data);
                        if (data.success == true) {
                            return window.location = main.adminUrl('ticket/manage');
                        }
                    },
                    error: function (xhr, status, error) {
                        var errors = xhr.responseJSON;

                        $('.form-group').removeClass('has-error');
                        $('span.help-block').hide();

                        $.each(errors.errors, function (k, v) {
                            var e = k.split('.');
                            var field = e[0];
                            if (e[1] !== undefined) {
                                $('.field_' + field + ':eq(' + e[1] + ')').focus().parent().append('<span class="help-block">' + v + '</span>');
                                $('.field_' + field + ':eq(' + e[1] + ')').parents('.form-group').addClass('has-error');
                            } else {
                                $('#' + field).focus().parent().append('<span class="help-block">' + v + '</span>');
                                $('#' + field).parents('.form-group').addClass('has-error');
                            }
                        });

//                        el.prop('disabled', false);
                        $('#save, #submit').prop('disabled', false);
                    }
                });
            });

        });

        $(".form_datetime").datetimepicker({
            autoclose: true,
            isRTL: App.isRTL(),
            format: "dd MM yyyy hh:ii",
            fontAwesome: true,
            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
            minuteStep: 15
        });
    </script>
@stop

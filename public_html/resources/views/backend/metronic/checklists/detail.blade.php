@extends( admin_layout('master') )

@section('title')
    Manage Checklists
@stop

@section('head_page_level_plugins')

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
                        <a href="{{ route('checklist-detail') }}">Checklists</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ route('checklist-manage') }}">Manage</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span>Details</span>
                    </li>
                </ul>
            </div>
            <!-- END PAGE BAR -->

            <!-- BEGIN PAGE TITLE-->
            <h1 class="page-title"> Checklist
                <small>information</small>
            </h1>
            <!-- END PAGE TITLE-->

            <!-- END PAGE HEADER-->
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin: life time stats -->
                    <div class="portlet light portlet-fit portlet-datatable bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="font-green"></i>
                                <span class="caption-subject font-green sbold uppercase"> Checklist Details </span>
                            </div>
                            <div class="actions">

                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="tabbable-line">

                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="portlet blue-hoki box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-info"></i>Details
                                                </div>
                                                <div class="actions">
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Site To: </div>
                                                    <div class="col-md-9 value"> {{$checklist->site_to}}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Site From: </div>
                                                    <div class="col-md-9 value"> {{$checklist->site_from}}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Inspection Site: </div>
                                                    <div class="col-md-9 value"> {{$checklist->inspection_site}}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Transporter: </div>
                                                    <div class="col-md-9 value">
                                                        {{$checklist->transporter}}
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Vehicle Number: </div>
                                                    <div class="col-md-9 value">
                                                        {{$checklist->vehicle_number}}
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Driver Name: </div>
                                                    <div class="col-md-9 value">
                                                        {{$checklist->driver_name}}
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Driver NIC: </div>
                                                    <div class="col-md-9 value">
                                                        {{$checklist->driver_nic}}
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Vehicle Status: </div>
                                                    <div class="col-md-9 value">
                                                            {{$checklist->selected ? "Selected" : "Rejected"}}
                                                </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Type: </div>
                                                    <div class="col-md-9 value">
                                                        {{$checklist->type}}
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Inspected At: </div>
                                                    <div class="col-md-9 value"> {{ date('M d, Y h:i:s A', strtotime($checklist->created_at)) }} </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Comments: </div>
                                                    <div class="col-md-9 value">
                                                        {{$checklist->comments}}
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .row -->

                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="portlet blue-hoki box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-info"></i>Checklist Response
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row static-info">
                                                    <div class="col-md-12">  
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
                                                                @php
                                                                    $response = json_decode($checklist->responses);  
                                                                    $question = json_decode($checklist->questions);
                                                                @endphp
                                                                @foreach ($question as $k=>$v)
                                                                <tr>
                                                                    <td style='font-weight: {{\App\Question::where('question','=',$v)->first()->important ? "bold" : "" }}'>
                                                                        {{$v}}</td>
                                                                    <td>
                                                                        
                                                                        <span
                                                                            class='{{$response[$k]=='Pass'?"text-primary":($response[$k]=="Fail"?"text-danger":"text-secondary")}}'>{{$response[$k]}}</span><br>
                                                                        
                                                                    <td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .row -->

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
                                                                <td> <a href="\uploads\files\{{ $f->file }}" target="_blank" download>{{ $f->file }}</a>
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

                            </div>
                        </div>
                    </div>
                    <!-- End: life time stats -->
                </div>
            </div>
        </div>
        <!-- END CONTENT BODY -->
    </div>
@stop

@section('foot_page_level_plugins')

@stop

@section('foot_page_level_scripts')

@stop

@section('foot_resources')
    <script>

    </script>
@stop

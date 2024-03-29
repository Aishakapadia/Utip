@extends( admin_layout('master') )

@section('title')
    Manage {{ $module->father->title }}
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
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ route('user-manage') }}">{{ $module->father->title }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ route('user-manage') }}">Manage</a>
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
                    <!-- Begin: life time stats -->
                    <div class="portlet light portlet-fit portlet-datatable bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="{{ $module->father->icon }} font-green"></i>
                                <span class="caption-subject font-green sbold uppercase"> {{ $module->father->title }} Details </span>
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
                                                    <a href="javascript:history.back()" class="btn btn-default btn-sm">
                                                        <i class="fa fa-backward"></i> Go Back
                                                    </a>
                                                    <a href="{{ route('user-edit.user', $user->id) }}" class="btn btn-default btn-sm">
                                                        <i class="fa fa-pencil"></i> Edit 
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Role: </div>
                                                    <div class="col-md-9 value"> {{ $user->role->title }} </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Agent: </div>
                                                    <div class="col-md-9 value"> {{ $user->agent ? "True" :  "False" }} </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Name: </div>
                                                    <div class="col-md-9 value"> {{ $user->name }} </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Email: </div>
                                                    <div class="col-md-9 value"> {{ $user->email }} </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Date of Birth: </div>
                                                    <div class="col-md-9 value"> {{ $user->date_of_birth }} </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Status: </div>
                                                    <div class="col-md-9 value">
                                                        @if($user->active == '0')
                                                            <span class="label label-danger"> Inactive </span>
                                                        @else
                                                            <span class="label label-success"> Active </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Created At: </div>
                                                    <div class="col-md-9 value"> {{ date('M d, Y h:i:s A', strtotime($user->created_at)) }} </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Updated At: </div>
                                                    <div class="col-md-9 value"> {{ date('M d, Y h:i:s A', strtotime($user->updated_at)) }} </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
@extends('layouts.default.master')
@section('data_count')
<!-- BEGIN CONTENT BODY -->
<div class="page-content">

    <!-- BEGIN PORTLET-->
    @include('layouts.flash')
    <!-- END PORTLET-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-rss-square"></i>{{ trans('english.E_RESOURCE_JCSC')}}
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">
                    <div class="portlet light">

                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 red-thunderbird tooltips" href="{{URL::to('public/uploads/eresources/employment_of_air_power.pdf')}}" target="_blank" title="Employment of Air Power" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Employment of Air Power</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-hoki tooltips" href="{{URL::to('public/uploads/eresources/Fin_Mgt.pdf')}}" target="_blank" title="Financial Management" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Financial Management</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-steel tooltips" href="{{URL::to('public/uploads/eresources/HAP.pdf')}}" target="_blank" title="History of Air Power" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-inbox"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">History of Air Power</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-dark tooltips" href="{{URL::to('public/uploads/eresources/hrm.pdf')}}" target="_blank" title="Human Resource Management" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">HRM</div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-oleo tooltips" href="{{URL::to('public/uploads/eresources/Information_Management.pdf')}}" target="_blank" title="Information Management" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Information Management</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 green tooltips" href="{{URL::to('public/uploads/eresources/JOINT_WARFARE.pdf')}}" target="_blank" title="Joint Warfare" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Joint Warfare</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 green-meadow tooltips" href="{{URL::to('public/uploads/eresources/Mang_Ldrship.pdf')}}" target="_blank" title="Managementt & Leadership" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-inbox"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Managementt & Leadership</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 green-turquoise tooltips" href="{{URL::to('public/uploads/eresources/MOOTW.pdf')}}" target="_blank" title="MOOTW" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">MOOTW</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 yellow-crusta tooltips" href="{{URL::to('public/uploads/eresources/Office_Management.pdf')}}" target="_blank" title="Office Management" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Office Management</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 red-sunglo tooltips" href="{{URL::to('public/uploads/eresources/ORGANISATION_AND_ADMINISTRATION.pdf')}}" target="_blank" title="Organization and Administration" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc"><small>Organization and Administration</small></div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 yellow-casablanca tooltips" href="{{URL::to('public/uploads/eresources/PA_Module.pdf')}}" target="_blank" title="Personnel Administration" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-inbox"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Personnel Administration</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 purple-studio tooltips" href="{{URL::to('public/uploads/eresources/REINFORCEMENT.pdf')}}" target="_blank" title="Reinforcement" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span><i class="fa fa-book" ></i></span>
                                        </div>
                                        <div class="desc">Reinforcement </div>
                                    </div>
                                </a>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

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
                        <i class="fa fa-Book"></i> Online Library 
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">
                    <div class="portlet light">
                        
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-dark" target="_blank" href="{{URL::to('library/1')}}">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            BAF CSTI
                                        </div>
                                        <div class="desc">Library</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-sharp" target="_blank" href="{{URL::to('library/2')}}">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            BAF Central
                                        </div>
                                        <div class="desc">Library</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="dashboard-stat dashboard-stat-v2 blue-hoki">
                                    <div class="visual">
                                        <i class="fa fa-inbox"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            BAF BSR
                                        </div>
                                        <div class="desc">Library </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="dashboard-stat dashboard-stat-v2 grey-cascade">
                                    <div class="visual">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            BAF MTR
                                        </div>
                                        <div class="desc">Library </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <br />
                        
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="dashboard-stat dashboard-stat-v2 red">
                                    <div class="visual">
                                        <i class="fa fa-bar-chart-o"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number text-center">
                                            BAF ZHR
                                        </div>
                                        <div class="desc">Library</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="dashboard-stat dashboard-stat-v2 green">
                                    <div class="visual">
                                        <i class="fa fa-shopping-cart"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            BAF BBD
                                        </div>
                                        <div class="desc">Library</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="dashboard-stat dashboard-stat-v2 blue">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            BAF PKP
                                        </div>
                                        <div class="desc">Library</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="dashboard-stat dashboard-stat-v2 purple">
                                    <div class="visual">
                                        <i class="fa fa-globe"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            NDC
                                        </div>
                                        <div class="desc">Library</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <br />
                        
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="dashboard-stat dashboard-stat-v2 blue-sharp">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            DSCSC
                                        </div>
                                        <div class="desc">Library</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="dashboard-stat dashboard-stat-v2 blue-hoki">
                                    <div class="visual">
                                        <i class="fa fa-inbox"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            BA Central
                                        </div>
                                        <div class="desc">Library </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="dashboard-stat dashboard-stat-v2 grey-cascade">
                                    <div class="visual">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            BN Central
                                        </div>
                                        <div class="desc">Library </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

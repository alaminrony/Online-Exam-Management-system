@extends('layouts.default.master')
@section('data_count')
<!-- BEGIN CONTENT BODY -->
<div class="page-content">

    <!-- BEGIN PORTLET-->
    @include('layouts.flash')

    @if($studentHasExam)

    <div class="note note-warning">
        <h3>{{ trans('english.THERE_IS_NO_ERESOURCES_AVAILABLE_AT_THIS_MOMENT') }}</h3>
    </div>                        

    @else

    <!-- END PORTLET-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-rss-square"></i>{{ trans('english.E_RESOURCE_PART_1')}}
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">
                    <div class="portlet light">

                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 red-thunderbird tooltips" href="{{URL::to('public/uploads/eresources/p1/Effective-English-Writing.pdf')}}" target="_blank" title="Employment of Air Power" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Effective English Writing</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-hoki tooltips" href="{{URL::to('public/uploads/eresources/p1/Office-Management.pdf')}}" target="_blank" title="Financial Management" style="margin-bottom:25px;">
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
                                <a class="dashboard-stat dashboard-stat-v2 blue-steel tooltips" href="{{URL::to('public/uploads/eresources/p1/Admin-Staff-Paper.pdf')}}" target="_blank" title="History of Air Power" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-inbox"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Admin Staff Paper</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-dark tooltips" href="{{URL::to('public/uploads/eresources/p1/Employment-of-Air-Power.pdf')}}" target="_blank" title="Human Resource Management" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-book"></i>
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
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-oleo tooltips" href="{{URL::to('public/uploads/eresources/p1/National-Affairs.pdf')}}" target="_blank" title="Information Management" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">National Affairs</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 green tooltips" href="{{URL::to('public/uploads/eresources/p1/Org-&-Admin-1.pdf')}}" target="_blank" title="Joint Warfare" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Org &amp; Admin - I</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 green-meadow tooltips" href="{{URL::to('public/uploads/eresources/p1/Legal-Studies-1.pdf')}}" target="_blank" title="Managementt & Leadership" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-inbox"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Legal Studies - I</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 green-turquoise tooltips" href="{{URL::to('public/uploads/eresources/p1/Engineering.pdf')}}" target="_blank" title="MOOTW" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Engineering</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 yellow-crusta tooltips" href="{{URL::to('public/uploads/eresources/p1/Air-Defence-Weapon-Control.pdf')}}" target="_blank" title="Office Management" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Air Defence Weapon Control</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 red-sunglo tooltips" href="{{URL::to('public/uploads/eresources/p1/Administration.pdf')}}" target="_blank" title="Organization and Administration" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Administration</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 yellow-casablanca tooltips" href="{{URL::to('public/uploads/eresources/p1/Air-Force-Law.pdf')}}" target="_blank" title="Personnel Administration" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-inbox"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Air Force Law</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 purple-studio tooltips" href="{{URL::to('public/uploads/eresources/p1/Air-Traffic-Control.pdf')}}" target="_blank" title="Reinforcement" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Air Traffic Control</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 red-thunderbird tooltips" href="{{URL::to('public/uploads/eresources/p1/Education.pdf')}}" target="_blank" title="Office Management" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Education</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-hoki tooltips" href="{{URL::to('public/uploads/eresources/p1/Finance.pdf')}}" target="_blank" title="Organization and Administration" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Finance</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-steel tooltips" href="{{URL::to('public/uploads/eresources/p1/Flg-and-Airmanship-for-Navigators.pdf')}}" target="_blank" title="Personnel Administration" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-inbox"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Flg &nbsp; Airmentship<br /> for Navigators</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-dark tooltips" href="{{URL::to('public/uploads/eresources/p1/Flying-&-Airmenship-for-Pilot.pdf')}}" target="_blank" title="Reinforcement" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Flg &nbsp; Airmanship for Pilot</div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-oleo tooltips" href="{{URL::to('public/uploads/eresources/p1/Logistics.pdf')}}" target="_blank" title="Office Management" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Logistic</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 green tooltips" href="{{URL::to('public/uploads/eresources/p1/Meteorology.pdf')}}" target="_blank" title="Organization and Administration" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Meterology</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-rss-square"></i>{{ trans('english.E_RESOURCE_PART_2')}}
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">
                    <div class="portlet light">

                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 red-thunderbird tooltips" href="{{URL::to('public/uploads/eresources/p2/Defence-Communication.pdf')}}" target="_blank" title="Employment of Air Power" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Defence Communication</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-hoki tooltips" href="{{URL::to('public/uploads/eresources/p2/Management-&-Leadership.pdf')}}" target="_blank" title="Financial Management" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Management &amp; Leadership</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-steel tooltips" href="{{URL::to('public/uploads/eresources/p2/Operational-Staff-Paper.pdf')}}" target="_blank" title="History of Air Power" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-inbox"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Operational Staff Paper</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-dark tooltips" href="{{URL::to('public/uploads/eresources/p2/History-of-Air-Power.pdf')}}" target="_blank" title="Human Resource Management" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-book"></i>
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
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-oleo tooltips" href="{{URL::to('public/uploads/eresources/p2/International-Affairs.pdf')}}" target="_blank" title="Information Management" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">International Affairs</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 green tooltips" href="{{URL::to('public/uploads/eresources/p2/Organization-&-Administration-2.pdf')}}" target="_blank" title="Joint Warfare" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Organization &nbsp; Administration</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 green-meadow tooltips" href="{{URL::to('public/uploads/eresources/p2/Legal-Studies-2.pdf')}}" target="_blank" title="Managementt & Leadership" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-inbox"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Legal Studies - II</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 green-turquoise tooltips" href="{{URL::to('public/uploads/eresources/p2/Air-Force-Law.pdf')}}" target="_blank" title="MOOTW" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Air Force Law</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 yellow-crusta tooltips" href="{{URL::to('public/uploads/eresources/p2/Air-Traffic-Control.pdf')}}" target="_blank" title="Office Management" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Air Traffic Control</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 red-sunglo tooltips" href="{{URL::to('public/uploads/eresources/p2/Administration.pdf')}}" target="_blank" title="Organization and Administration" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc"><small>Administration</small></div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 yellow-casablanca tooltips" href="{{URL::to('public/uploads/eresources/p2/Air-Defence-Weapon-Control.pdf')}}" target="_blank" title="Personnel Administration" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-inbox"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Air Defense Weapon Control</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 purple-studio tooltips" href="{{URL::to('public/uploads/eresources/p2/Education.pdf')}}" target="_blank" title="Reinforcement" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Education</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 red-thunderbird tooltips" href="{{URL::to('public/uploads/eresources/p2/Engineering.pdf')}}" target="_blank" title="Office Management" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Engineering</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-hoki tooltips" href="{{URL::to('public/uploads/eresources/p2/Finnance.pdf')}}" target="_blank" title="Organization and Administration" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Finance</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-steel tooltips" href="{{URL::to('public/uploads/eresources/p2/Flg-&-Airmentship-for-Pilot.pdf')}}" target="_blank" title="Personnel Administration" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-inbox"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Flg &nbsp; Airmentship for Pilot</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-dark tooltips" href="{{URL::to('public/uploads/eresources/p2/Flg-and-Airmanship-for-Navigators.pdf')}}" target="_blank" title="Reinforcement" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Flg &nbsp; Airmanship<br /> for Navigators</div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue-oleo tooltips" href="{{URL::to('public/uploads/eresources/p2/Logistic.pdf')}}" target="_blank" title="Office Management" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Logistic</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 green tooltips" href="{{URL::to('public/uploads/eresources/p2/Meterology.pdf')}}" target="_blank" title="Organization and Administration" style="margin-bottom:25px;">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span>
                                                <i class="fa fa-book" ></i>
                                            </span>
                                        </div>
                                        <div class="desc">Meterology</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @endif
</div>
@stop

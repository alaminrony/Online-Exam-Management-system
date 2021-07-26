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
                        <i class="fa fa-Book"></i> CSTI Library 
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">
                    <div class="portlet light">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{URL::to('library')}}" class="btn blue" title="Go Back">
                                    <i class="fa fa-sign-in fa-rotate-180"></i> {{ trans('english.GO_BACK') }}
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="text-center"><strong>LIST OF BOOKS AT CSTI LIBRARY</strong></h3>
                                <div class="table-scrollable">
                                    <table class="table table-bordered table-hover">
                                        <thead>

                                            <tr>
                                                <th> Ser </th>
                                                <th> Name of Book </th>
                                                <th> Author </th>
                                                <th> Publisher </th>
                                                <th> Availability </th>
                                                <th> Price (approx) </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td> 1 </td>
                                                <td> Airpower in Small Wars: Fighting Insurgents and Terrorists (Modern War Studies) </td>
                                                <td> James S. Corum and Wray Johnson </td>
                                                <td> University Press of Kansas </td>
                                                <td>
                                                    Amazon.com
                                                </td>
                                                <td>
                                                    <span class="label label-sm label-warning">$15.27</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td> 2 </td>
                                                <td> Air Warfare </td>
                                                <td> Wiliam C Sherman and Col Wray R Johnson </td>
                                                <td> CreateSpace Independent Publishing Platform </td>
                                                <td>
                                                    Amazon.com
                                                </td>
                                                <td>
                                                    <span class="label label-sm label-warning">$18.55</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td> 3 </td>
                                                <td> The Command of The Air (USAF Warrior Studies) </td>
                                                <td> Giulio Douhet and Dino Ferrari </td>
                                                <td> CreateSpace Independent Publishing Platform </td>
                                                <td>
                                                    Amazon.com
                                                </td>
                                                <td>
                                                    <span class="label label-sm label-warning">$27.99</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td> 4 </td>
                                                <td> Winged Defense: The Development and Possibilities of Modern Air Power--Economic and Military (Alabama Fire Ant) </td>
                                                <td> William Mitchell and Robert S. Ehlers Jr. </td>
                                                <td> Fire Ant Books </td>
                                                <td>
                                                    Amazon.com
                                                </td>
                                                <td>
                                                    <span class="label label-sm label-warning">$34.95</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td> 5 </td>
                                                <td> Rhetoric and Reality in Air Warfare: The Evolution of British and American Ideas about Strategic Bombing, 1914-1945 </td>
                                                <td> Tami Davis Biddle </td>
                                                <td> Princeton Studies in International History and Politics </td>
                                                <td>
                                                    Amazon.com
                                                </td>
                                                <td>
                                                    <span class="label label-sm label-warning">$33.70</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td> 6 </td>
                                                <td> The Future of Air Power in the Aftermath of the Gulf War </td>
                                                <td> Richard H. Shultz (Editor), Robert L. Pfaltzgraff (Editor) </td>
                                                <td> University Press of the Pacific </td>
                                                <td>
                                                    Amazon.com
                                                </td>
                                                <td>
                                                    <span class="label label-sm label-warning">$12.58</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td> 7 </td>
                                                <td> Airpower, Afghanistan, and the Future of Warfare: An Alternative View: A CADRE Paper </td>
                                                <td> Lieutenant Colonel, USAF, Craig D. Wills </td>
                                                <td> CreateSpace Independent Publishing Platform </td>
                                                <td>
                                                    Amazon.com
                                                </td>
                                                <td>
                                                    <span class="label label-sm label-warning">$11.89</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td> 8 </td>
                                                <td> Airpower Reborn: The Strategic Concepts of John Warden and John Boyd (History of Military Aviation) </td>
                                                <td> John Andreas Olsen (Editor) </td>
                                                <td> Naval Institute Press </td>
                                                <td> Amazon.com </td>
                                                <td>
                                                    <span class="label label-sm label-warning"> $35.36 </span>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{URL::to('library')}}" class="btn blue" title="Go Back">
                                    <i class="fa fa-sign-in fa-rotate-180"></i> {{ trans('english.GO_BACK') }}
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

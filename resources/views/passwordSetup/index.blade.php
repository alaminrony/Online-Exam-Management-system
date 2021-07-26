@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.VIEW_PASSWORD_SETUP')
            </div>
            <div class="actions">
                <a href="{{ URL::to('passwordSetup/'.$target->id.'/edit') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-plus"></i> @lang('label.EDIT_PASSWORD_SETUP') </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-responsive">
                <table class="table  table-bordered table-hover">
                    <tr>
                        <th>@lang('label.MAX_LENGTH')</th>
                        <td>{{ $target->maximum_length}} @lang('label.CHARACTERS')</td>
                    <tr/>
                    <tr>
                        <th>@lang('label.MIN_LENGTH')</th>
                        <td>{{ $target->minimum_length}} @lang('label.CHARACTERS')</td>
                    </tr>
                    <tr>
                        <th>@lang('label.SPECIAL_CHARECTER')</th>
                        <td>
                            @if($target->special_character =='1')
                            <span class="label label-success">{{ __('label.YES') }}</span>
                            @else
                            <span class="label label-danger">{{   __('label.NO')}}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>@lang('label.LOWER_CASE')</th>
                        <td>
                            @if($target->lower_case =='1')
                            <span class="label label-success">{{ __('label.YES') }}</span>
                            @else
                            <span class="label label-danger">{{   __('label.NO')}}</span>
                            @endif
                    </tr>
                    <tr>
                        <th>@lang('label.UPPER_CASE')</th>
                        <td>
                            @if($target->upper_case =='1')
                            <span class="label label-success">{{ __('label.YES') }}</span>
                            @else
                            <span class="label label-danger">{{   __('label.NO')}}</span>
                            @endif
                    </tr>
                    <tr>
                        <th>@lang('label.EXPEIRED_OF_PASSWORD')</th>
                        <td>{{ $target->expeired_of_password}} @lang('label.DAYS')</td>
                    </tr>
                    <tr>
                        <th>@lang('label.SPACE_NOT_ALLOWED')</th>
                        <td>{{ $target->space_not_allowed  == '1' ? __('label.NOT_ALLOWED') :''}}</td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
</div>
@stop

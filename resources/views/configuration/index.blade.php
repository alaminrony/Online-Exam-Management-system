@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.CONFIGURATION')
            </div>
            <div class="actions">
                <a href="{{ URL::to('configuration/1/edit') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-plus"></i> @lang('label.UPDATE_CONFIGURATION') </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>@lang('label.ADMIN_EMAIL')</th>
                        <td>{!! $targetArr->admin_email !!}</td>
                    </tr>
                    
                    <tr>
                        <th>@lang('label.ABOUT_US')</th>
                        <td>{!! $targetArr->about_us!!}</td>
                    </tr>
                    <tr>
                        <th>@lang('label.HISTORY')</th>
                        <td>{!! $targetArr->history!!}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

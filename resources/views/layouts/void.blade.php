@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-ban"></i>{{$void['header']}}
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                        <p><i class="fa fa-bell-o fa-fw"></i> {{$void['body']}}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <a href="javascript:history.back()" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('label.GO_BACK')</a>
                </div>

            </div>
        </div>
    </div>
</div>
@stop
@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.SCROLL_MESSAGE_LIST')
            </div>
            <div class="actions">
                <a href="{{ URL::to('scrollmessage/create') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-plus"></i> @lang('label.CREATE_SCROLL_MESSAGE') </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>@lang('label.SL_NO')</th>
                            <th>@lang('label.MESSAGE')</th>
                            <th>@lang('label.SCOPE')</th>
                            <th>@lang('label.PUBLISH')</th>
                            <th>@lang('label.STATUS')</th>
                            <th>@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $item)
                        <tr class="contain-center">
                            <td>{{ ++$sl}}</td>
                            <td>{{ $item->message}}</td>
                            <td>
                                @if(!empty($item->messagescope))
                                @foreach($item->messagescope as $scope)
                                @if($scope->scope_id == 3)
                                @lang('label.HOME_PAGE') </br>
                                @elseif($scope->scope_id == 1)
                                @lang('label.ISSP_DASHBOARD') </br>
                                @elseif($scope->scope_id == 2)
                                @lang('label.JCSC_DASHBOARD') </br>
                                @endif
                                @endforeach
                                @endif
                            </td>
                            <td>
                                {{$item->from_date. ' To '.$item->to_date}}
                            </td>
                            <td>
                                @if ($item->status == '1')
                                <span class="label label-success">@lang('label.ACTIVE')</span>
                                @elseif($item->status == '2')
                                <span class="label label-info">@lang('label.COMMON')</span>
                                @else
                                <span class="label label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td class="action-center">
                                <div class='text-center'>
                                    {{ Form::open(array('url' => 'scrollmessage/' . $item->id.'/'.Helper::queryPageStr($qpArr), 'id' => 'delete')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <a class='btn btn-primary btn-xs' href="{{ URL::to('scrollmessage/' . $item->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class='fa fa-edit'></i>
                                    </a>
                                    <button class="btn btn-danger btn-xs delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class='fa fa-trash'></i>
                                    </button>
                                    {{ Form::close() }}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6">@lang('label.EMPTY_DATA')</td>
                        </tr>
                        @endif 
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>
    </div>
</div>
@stop

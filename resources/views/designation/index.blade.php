@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-suitcase"></i>@lang('label.VIEW_APPOINTMENT_LIST')
            </div>
            <div class="actions">
                <a href="{{ URL::to('designation/create') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-plus"></i> @lang('label.CREATE_NEW_APPOINTMENT') </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>@lang('label.SL_NO')</th>
                            <th>@lang('label.TITLE')</th>
                            <th>@lang('label.SHORT_NAME')</th>
                            <th class="text-center">@lang('label.ORDER')</th>
                            <th class='text-center'>@lang('label.STATUS')</th>
                            <th class='text-center'>@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $value)

                        <tr class="contain-center">
                            <td>{{++$sl}}</td>
                            <td>{{ $value->title}}</td>
                            <td>{{ $value->short_name}}</td>
                            <td class="text-center">{{ $value->order }}</td>
                            <td class="text-center">
                                @if ($value->status == '1')
                                <span class="label label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>

                            <td class="action-center">
                                <div class='text-center'>
                                    {{ Form::open(array('url' => 'designation/' . $value->id.'/'.Helper::queryPageStr($qpArr), 'id' => 'delete')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <a class='btn btn-primary btn-xs' href="{{ URL::to('designation/' . $value->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
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
                            <td colspan="5">@lang('label.EMPTY_DATA')</td>
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

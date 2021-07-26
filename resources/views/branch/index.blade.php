@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.VIEW_BRANCH_LIST')
            </div>
            <div class="actions">
                <a href="{{ URL::to('branch/create') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-plus"></i> @lang('label.CREATE_NEW_BRANCH') </a>
            </div>
        </div>
        <div class="portlet-body">
            {{ Form::open(array('role' => 'form', 'url' => 'branch/filter', 'class' => '', 'id' => 'branchFilter')) }}
            {{ Form::hidden('page', Helper::queryPageStr($qpArr))}}
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="searchText">@lang('label.SEARCH_TEXT')</label>
                        <div class="col-md-8">
                            {{ Form::text('search_text', Request::get('search_text'), array('id'=> 'searchText', 'class' => 'form-control', 'placeholder' => __('label.SERACH_TEXT'))) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                        <i class="fa fa-search"></i> @lang('label.FILTER')
                    </button>
                </div>
            </div>
            {{Form::close()}}
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>@lang('label.ID_HASH')</th>
                            <th>@lang('label.NAME')</th>
                            <th>@lang('label.SOL_ID')</th>
                            <th>@lang('label.REGION')</th>
                            <th>@lang('label.CLUSTER')</th>
                            <th>@lang('label.LOCATION')</th>
                            <th class="text-center">@lang('label.ORDER')</th>
                            <th class="text-center">@lang('label.STATUS')</th>
                            <th class="text-center">@lang('label.ACTION')</th>
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
                            <td>{{ ++$sl}}</td>
                            <td>{{ $value->name}}</td>
                            <td>{{ $value->sol_id }}</td>
                            <td>{{ $value->region}}</td>
                            <td>{{ $value->cluster}}</td>
                            <td>{{ $value->location}}</td>
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
                                    {{ Form::open(array('url' => 'branch/' . $value->id.'/'.Helper::queryPageStr($qpArr), 'id' => 'delete')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('branch/' . $value->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="btn btn-danger btn-xs delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {{ Form::close() }}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="9">@lang('label.EMPTY_DATA')</td>
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

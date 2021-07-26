@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.VIEW_GRADING_SYSTEM')
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>@lang('label.SL')</th>
                            <th>@lang('label.MARKS') (%)</th>
                            <th>@lang('label.GRADE')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($grades->isNotEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        <?php $i = 1; ?>
                        @foreach($grades as $grade)
                        <tr class="contain-center">
                            <td>{{ $i++ }}</td>
                            @if($grade->to_mark < '100')
                            <td>{{ $grade->from_mark}} - < {{ $grade->to_mark}}</td>
                            @else
                            <td>{{ $grade->from_mark}} - {{ $grade->to_mark}}</td>
                            @endif
                            <td>{{ $grade->grade}}</td>
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
        </div>
    </div>
</div>
@stop

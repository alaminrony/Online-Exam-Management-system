@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-tag"></i>@lang('label.VIEW_QUESTION_BANK')
            </div>
            <div class="actions">
                <a href="{{ URL::to('question/create') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-plus"></i> @lang('label.CREATE_NEW_QUESTION') </a>
            </div>
        </div>
        <div class="portlet-body">
            {{ Form::open(array('role' => 'form', 'url' => 'question/filter', 'class' => '')) }}
            {{ Form::hidden('page', Helper::queryPageStr($qpArr))}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="subjectId">@lang('label.SELECT_SUBJECT')</label>
                        <div class="col-md-8">
                            {{ Form::select('subject_id', $subjectList, Request::get('fill_subject_id'), array('id'=> '', 'class' => 'form-control js-source-states')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="typeId">@lang('label.SELECT_QUESTION_TYPE')</label>
                        <div class="col-md-6">
                            {{ Form::select('type_id', $typeList, Request::get('fill_type_id'), array('id'=> '', 'class' => 'form-control js-source-states')) }}
                        </div>
                    </div>
                </div>                        
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="searchText">@lang('label.SEARCH_TEXT')</label>
                        <div class="col-md-8">
                            {{ Form::text('search_text', Request::get('search_text'), array('id'=> 'searchText', 'class' => 'form-control', 'placeholder' => __('label.SERACH_TEXT'))) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="status">@lang('label.STATUS')</label>
                        <div class="col-md-9">
                            {{ Form::select('status',$statusList,  Request::get('fill_status'), array('id'=> 'status', 'class' => 'form-control')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                        <i class="fa fa-search"></i> @lang('label.FILTER')
                    </button>
                    <a class="btn btn-md red  filter-submit margin-bottom-20" href="{{URL::to('question?generate=true&search_text='.Request::get('search_text').'&type_id='.Request::get('type_id').'&subject_id='.Request::get('subject_id').'&status='.Request::get('status').'&view=excel')}}"  title="Download Excel" target="_blank" class="btn btn-danger tooltips rounded-0"><i class="fa fa-file-excel-o"></i></a>
                </div>
            </div>
            {{Form::close()}}
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">@lang('label.SL_NO')</th>
                            <th>@lang('label.SUBJECT')</th>
                            <th>@lang('label.QUESTION_TYPE')</th>
                            <th>@lang('label.QUESTION')</th>
                            <th>@lang('label.CONTENT_TYPE')</th>
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
                            <td class="text-center">{{ ++$sl }}</td>
                            <td>{{ $value->Subject->title}}</td>
                            <td>{{ $value->QuestionType->name}}</td>
                            <td>{{ $value->question}}</td>
                            <td class="text-center">
                                @if (!empty($value->content_type_id) && $value->content_type_id =='1')
                                <span class="label label-success tooltips" title="Image"><i class="fa fa-image"></i></span>
                                @elseif(!empty($value->content_type_id) && $value->content_type_id =='2')
                                <span class="label label-warning tooltips" title="Audio File"><i class="fa fa-file-audio-o"></i></span>
                                @elseif(!empty($value->content_type_id) && $value->content_type_id =='3')
                                <span class="label label-success tooltips" title="Video File"><i class="fa fa-video-camera"></i></span>
                                @elseif(!empty($value->content_type_id) && $value->content_type_id =='4')
                                <span class="label label-warning tooltips" title="Pdf File"><i class="fa fa-file-pdf-o"></i></span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($value->status == '1')
                                <span class="label label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>

                            <td class="action-center">
                                <div class='text-center'>
                                    {{ Form::open(array('url' => 'question/' . $value->id.'/'.Helper::queryPageStr($qpArr), 'id' => 'delete')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <a class="btn btn-primary btn-xs tooltips" title="Edit" href="{{ URL::to('question/' . $value->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="btn btn-danger btn-xs delete tooltips" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {{ Form::close() }}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7">@lang('label.EMPTY_DATA')</td>
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

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
                        <i class="fa fa-list"></i>{{trans('english.VIEW_NOTICE_LIST')}}
                    </div>
                    @if(in_array(Auth::user()->group_id, [1,2,3,6] ))
                    <div class="actions">
                        <a href="{{ URL::to('notice/create') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-plus"></i> {{trans('english.CREATE_NEW_NOTICE')}} </a>
                    </div>
                    @endif
                </div>
                <div class="portlet-body">

                    @if(in_array(Auth::user()->group_id, [1,2,3,6] ))
                    {{ Form::open(array('role' => 'form', 'url' => 'notice/filter', 'class' => '', 'id' => 'noticeFilter')) }}

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-md-3 control-label">{{trans('english.SELECT_COURSE')}}</label>
                                <div class="col-md-9">
                                    {{Form::select('course_id', $courseArr, Request::get('course_id'), array('class' => 'form-control js-source-states', 'id' => 'courseId'))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-md-3 control-label">{{trans('english.SEARCH_TEXT')}}</label>
                                <div class="col-md-9">
                                    {{ Form::text('search_text', Request::get('search_text'), array('id'=> 'studentSearchText', 'class' => 'form-control', 'placeholder' => 'Search by title/short description/description')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                                <i class="fa fa-search"></i> {{trans('english.FILTER')}}
                            </button>
                        </div>
                    </div>
                    {{Form::close()}}
                    @endif

                    @if(in_array(Auth::user()->group_id, [4,5] ))
                    @if (!$noticeArr->isEmpty())

                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet-body">
                                <div class="tab-content">
                                    <div class="mt-comments">
                                        @foreach($noticeArr as $value)

                                        <div class="mt-comment comment-custom">
                                            <div class="mt-comment-img ">
                                                <div class="label label-sm label-success">
                                                    <i class="fa fa-bell-o"></i>
                                                </div>
                                            </div>
                                            <div class="mt-comment-body comment-body-custom">
                                                <div class="mt-comment-info">
                                                    <span class="mt-comment-author">{{ $value->title }}</span>
                                                    <span class="mt-comment-date">{{ trans('english.PUBLISH_DATE') .' : '.$value->published_date }}</span>
                                                </div>
                                                <div class="mt-comment-text">{{ $value->short_info }} </div>
                                                <div class="mt-comment-details">
                                                    <button type="button" class="btn btn-outline green btn-sm" data-toggle="modal" data-target="#view-details" data-id="{{$value->id}}" href="#view-details" id="view_details" data-container="body" data-trigger="hover" data-placement="top">{{ trans('english.VIEW_DETAILS') }}</button>
                                                    @if(!empty($value->fileInfo))
                                                    <a class="btn btn-outline blue btn-sm" href="{{ URL::to('public/uploads/notice',$value->fileInfo) }}" download id="downloadAttachment">{{ trans('english.DOWNLOAD_ATTACHMENT') }}</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                    @if(in_array(Auth::user()->group_id, [1,2,3,6] ))
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{{trans('english.SL_NO')}}</th>
                                    @if(Auth::user()->group_id != '5')
                                    <th>{{trans('english.COURSE')}}</th>
                                    @endif
                                    <th>{{trans('english.TITLE')}}</th>
                                    <th class="text-center">{{trans('english.SHORT_INFO')}}</th>
                                    <th class='text-center'>{{trans('english.PUBLISH_DATE')}}</th>
                                    <th class='text-center'>{{trans('english.CLOSING_DATE_ONLY')}}</th>
                                    <th class='text-center'>{{trans('english.STATUS')}}</th>
                                    <th class='text-center'>{{trans('english.ACTION')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$noticeArr->isEmpty())
                                <?php
                                $page = Request::get('page');
                                $page = empty($page) ? 1 : $page;
                                $sl = ($page - 1) * trans('english.PAGINATION_COUNT');
                                ?>
                                @foreach($noticeArr as $value)

                                <tr class="contain-center">
                                    <td>{{++$sl}}</td>
                                    @if(Auth::user()->group_id != '5')
                                    <td>{{ $value->course_name}}</td>
                                    @endif
                                    <td>{{ $value->title}}</td>
                                    <td class="text-center">{{ $value->short_info }}</td>
                                    <td class="text-center">{{ $value->published_date }}</td>
                                    <td class="text-center">{{ $value->closing_date }}</td>
                                    <td class="text-center">
                                        @if ($value->status == '1')
                                        <span class="label label-success">{{ trans('english.ACTIVE') }}</span>
                                        @else
                                        <span class="label label-warning">{{ trans('english.INACTIVE') }}</span>
                                        @endif
                                    </td>
                                    <td class="action-center">
                                        <div class='text-center'>
                                            {{ Form::open(array('url' => 'notice/' . $value->id, 'id' => 'delete')) }}
                                            {{ Form::hidden('_method', 'DELETE') }}
                                            <a class="tooltips" data-toggle="modal" data-target="#view-modal" data-id="{{$value->id}}" href="#view-modal" id="getNotice" title="Details Notice" data-container="body" data-trigger="hover" data-placement="top">
                                                <span class="btn btn-success btn-xs"> 
                                                    &nbsp;<i class='fa fa-info'></i>&nbsp;
                                                </span>
                                            </a>
                                            @if (!empty($value->fileInfo))
                                            <a class="btn btn-warning btn-xs tooltips" href="{{ URL::to('public/uploads/notice',$value->fileInfo) }}"  data-placement="top" data-rel="tooltip" download title="Download Attachments">
                                                <i class='fa fa-file'></i>
                                            </a>
                                            @endif

                                            @if(Auth::user()->group_id <= '3')
                                            <a class='btn btn-primary btn-xs tooltips' href="{{ URL::to('notice/' . $value->id . '/edit') }}" title="Edit Notice">
                                                <i class='fa fa-edit'></i>
                                            </a>
                                            <button class="btn btn-danger btn-xs tooltips" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete Notice">
                                                <i class='fa fa-trash'></i>
                                            </button>
                                            @endif
                                            {{ Form::close() }}
                                        </div>
                                    </td>

                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="7">{{trans('english.EMPTY_DATA')}}</td>
                                </tr>
                                @endif 
                            </tbody>
                        </table>

                    </div>
                    <div class="row">
                        <div class="col-md-5 col-sm-5">
                            <div class="dataTables_info" role="status" aria-live="polite">
                                Showing {{($noticeArr->getCurrentPage()-1)*$noticeArr->getPerPage()+1}} to {{$noticeArr->getCurrentPage()*$noticeArr->getPerPage()}} of  {{$noticeArr->getTotal()}} Notice
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-7">
                            {{ $noticeArr->appends(Input::all())->links()}}
                        </div>
                    </div>
                    @endif 
                    
                    @if(in_array(Auth::user()->group_id, [4,5] ) && ($noticeArr->isEmpty()))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="note note-warning">
                                        <h3>{{ trans('english.THERE_IS_NO_NOTICE_AVAILABLE_AT_THIS_MOMENT') }}</h3>
                                    </div>
                                </div>

                            </div>                                
                        </div>
                    </div>
                    
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
<!-- END CONTENT BODY -->
<!-- END CONTENT BODY -->
<!--This module use for student other information edit-->
<div id="view-modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="dynamic-content"><!-- mysql data will load in table -->
        </div>
    </div>
</div>

<div id="view-details" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content" id="dynamic-details"><!-- mysql data will load in table -->

        </div>
    </div>
</div>
<script>
    // ****************** Ajax Code for children edit *****************
    $(document).on('click', '#getNotice', function (e) {
        e.preventDefault();
        var noticeId = $(this).data('id'); // get id of clicked row

        $('#dynamic-content').html(''); // leave this div blank
        $.ajax({
            url: "{{ URL::to('ajaxresponse/notice-details') }}",
            type: "GET",
            data: {
                notice_id: noticeId
            },
            cache: false,
            contentType: false,
            success: function (response) {
                $('#dynamic-content').html(''); // blank before load.
                $('#dynamic-content').html(response.html); // load here
                $('.date-picker').datepicker({autoclose: true});
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                $('#dynamic-content').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
            }
        });
    });

    $(document).on('click', '#view_details', function (e) {
        e.preventDefault();
        var noticeId = $(this).data('id'); // get id of clicked row

        $('#dynamic-details').html(''); // leave this div blank
        $.ajax({
            url: "{{ URL::to('ajaxresponse/student-notice-details') }}",
            type: "GET",
            data: {
                notice_id: noticeId
            },
            cache: false,
            contentType: false,
            success: function (response) {
                $('#dynamic-details').html(''); // blank before load.
                $('#dynamic-details').html(response.html); // load here

            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                $('#dynamic-details').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
            }
        });
    });

    $(document).on("submit", '#delete', function (e) {
        //This function use for sweetalert confirm message
        e.preventDefault();
        var form = this;
        swal({
            title: 'Are you sure you want to Delete?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete",
            closeOnConfirm: false
        },
        function (isConfirm) {
            if (isConfirm) {
                toastr.info("Loading...", "Please Wait.", {"closeButton": true});
                form.submit();
            } else {
                //swal(sa_popupTitleCancel, sa_popupMessageCancel, "error");

            }
        });
    });

</script>
@stop

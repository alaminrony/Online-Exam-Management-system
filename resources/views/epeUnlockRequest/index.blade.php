@extends('layouts.default.master')
@section('data_count')

<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-unlock"></i>{{__('label.EPE_UNLOCK_REQUEST')}}
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">{{__('label.SL_NO')}}</th>
                            <th class="text-center">{{__('label.TYPE')}}</th>
                            <th class="text-center">{{__('label.PHOTO')}}</th>
                            <th class='text-center'>{{__('label.UNLOCK_REQUEST_BY')}}</th>
                            <th class="text-center">{{__('label.SUBJECT')}}</th>
                            <th class="text-center">{{__('label.TITLE')}}</th>
                            <th class="text-center">{{__('label.REMARKS')}}</th>
                            <th class='text-center'>{{__('label.UNLOCK_REQUEST_AT')}}</th>
                            <th class='text-center'>{{__('label.ACTION')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$epeMarking->isEmpty())
                        <?php
                        $sl = 0;
                        ?>
                        @foreach($epeMarking as $item)
                        <tr class="contain-center">
                            <td class="text-center">{{++$sl}}</td>
                            <td class="text-center">
                                @if($item->type == '1')
                                <span class="label label-success"> {{__('label.REGULAR')}} </span>
                                @elseif($item->type == '2')
                                <span class="label label-warning"> {{__('label.IRREGULAR')}} </span>
                                @elseif($item->type == '3')
                                <span class="label label-info"> {{__('label.RESCHEDULE')}} </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($item->photo))
                                <img width="100" height="100" src="{{URL::to('/')}}/public/uploads/thumbnail/{{$item->photo}}" alt="{{ $item->name }}">
                                @else
                                <img width="100" height="100" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $item->name }}">
                                @endif
                            </td>
                            <td class="text-left">
                                <a class="tooltips zoom-in details-btn" data-tooltip="tooltip" data-toggle="modal" data-target="#view-modal" data-id="{{$item->user_id}}" href="#view-modal"  title="View User Details" data-container="body" data-trigger="hover" data-placement="top">
                                    {{$item->name}}
                                </a>
                            </td>

                            <td class="text-center">{{ $item->subject_name }}</td>
                            <td class="text-left">{{ $item->epe_title }}</td>
                            <td class="text-center">{{ $item->remarks }}</td>
                            <td class="text-center">{{ Helper::printDateTime($item->unlock_request_at) }}</td>
                            <td class="action-center">
                                @if($item->unlock_request == '1')
                                <div class='text-center'>
                                    <a data-tooltip="tooltip" class='unlock btn btn-primary btn-sm' data-id="{{$item->id}}" title="{{__('label.UNLOCKED')}}" href="#">
                                        <i class='fa fa-unlock'></i>
                                    </a>
                                    <a data-tooltip="tooltip" class='deny btn btn-danger btn-sm tooltips' data-id="{{$item->id}}" title="{{__('label.DENY')}}" href="#">
                                        <i class='fa fa-close'></i>
                                    </a>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12">{{__('label.EMPTY_DATA')}}</td>
                        </tr>
                        @endif
                    </tbody>

                </table>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="view-modal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showDetails"></div> 
    </div>
</div>
<!-- END CONTENT BODY -->
<script type="text/javascript">
    //student info
    $(document).ready(function () {
        $("body").tooltip({selector: '[data-tooltip=tooltip]'});



    });

    $(document).on('click', '.details-btn', function (e) {
        e.preventDefault();
        var userId = $(this).attr('data-id');
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null
        };
        $.ajax({
            url: "{!! URL::to('user/details') !!}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                user_id: userId
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#showDetails').html(res.html);
                $('.tooltips').tooltip();
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
            }
        });
    });

    //deny
    $(document).on('click', '.deny', function (e) {
        e.preventDefault();
        var epeMarkId = $(this).data('id');
        swal({
            title: 'Are you sure you want to Deny?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, deny",
            closeOnConfirm: false
        },
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: "{{url('unlockrequest/deny')}}",
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                epe_mark_id: epeMarkId
                            },
                            dataType: 'json',
                            success: function (response) {
                                toastr.success(response.message, "Success", {"closeButton": true});
                                setTimeout(location.reload.bind(location), 1000);
                            },
                            error: function (jqXhr, ajaxOptions, thrownError) {
                                var errorsHtml = '';
                                if (jqXhr.status == 400) {
                                    var errors = jqXhr.responseJSON.message;
                                    $.each(errors, function (key, value) {
                                        errorsHtml += '<li>' + value[0] + '</li>';
                                    });
                                    toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true});
                                } else if (jqXhr.status == 500) {
                                    toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true});
                                } else if (jqXhr.status == 401) {
                                    toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                                } else {
                                    toastr.error("Error", "Something went wrong", {"closeButton": true});
                                }
                            }
                        });
                    } else {
                        //swal(sa_popupTitleCancel, sa_popupMessageCancel, "error");

                    }
                });
    });

    //unlock
    $(document).on('click', '.unlock', function (e) {
        e.preventDefault();
        var epeMarkId = $(this).data('id');
        swal({
            title: 'Are you sure you want to Unlock?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, unlock",
            closeOnConfirm: false
        },
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: "{{url('unlockrequest/unlock')}}",
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                epe_mark_id: epeMarkId
                            },
                            dataType: 'json',
                            success: function (response) {
                                toastr.success(response.message, "Success", {"closeButton": true});
                                setTimeout(location.reload.bind(location), 400);
                            },
                            error: function (jqXhr, ajaxOptions, thrownError) {
                                var errorsHtml = '';
                                if (jqXhr.status == 400) {
                                    var errors = jqXhr.responseJSON.message;
                                    $.each(errors, function (key, value) {
                                        errorsHtml += '<li>' + value[0] + '</li>';
                                    });
                                    toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true});
                                } else if (jqXhr.status == 500) {
                                    toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true});
                                } else if (jqXhr.status == 401) {
                                    toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                                } else {
                                    toastr.error("Error", "Something went wrong", {"closeButton": true});
                                }
                            }
                        });
                    } else {
                        //swal(sa_popupTitleCancel, sa_popupMessageCancel, "error");

                    }
                });
    });
</script>
@stop
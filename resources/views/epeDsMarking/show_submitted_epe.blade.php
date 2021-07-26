<div class="bg-blue-hoki bg-font-blue-hoki">
    <h5 style="padding: 10px;">
        {{__('label.SUBJECT')}} : <strong>{{$epeInfo->Subject->title }} |</strong> 
        {{__('label.TOTAL_SUBMISSION')}} : <strong>{{count($finalArr) }} |</strong> 
        {{__('label.WAITING_FOR_ASSESSMENT')}} : <strong>{{ $dsStatusArr[0]['total'] }} |</strong>
        {{__('label.ASSESSED')}} : <strong>{{ $dsStatusArr[1]['total'] }} |</strong>
        {{__('label.LOCKED')}} : <strong>{{ $dsStatusArr[2]['total'] }} |</strong>
        {{__('label.SUBMISSION_DATELINE')}} : <strong>{{Helper::formatDateTime($epeInfo->submission_deadline) }} |</strong>
        {{__('label.RESULT_PUBLISHED_DATE')}} : <strong>{{ Helper::formatDateTime($epeInfo->result_publish) }}</strong>
    </h5>
</div>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th class="text-center">{{__('label.SL_NO')}}</th>
            <th class="text-center">{{__('label.SUBMISSION_ID')}}</th>
            <th class="text-center">{{__('label.OBJECTIVE')}}</th>
            <th class="text-center">{{__('label.SUBJECTIVE')}}</th>
            <th class="text-center">{{__('label.ACHIEVED_MARK')}}</th>
            <th class="text-center">{{__('label.ACHIEVED_MARK'). '(%)'}}</th>
            <th class="text-center">{{__('label.ASSESSMENT')}} {{__('label.STATUS')}}</th>
            <th class="text-center">{{__('label.RESULT')}} {{__('label.STATUS')}}</th>
            @if(Auth::user()->group_id =='2')
            <th class="text-center">{{__('label.ACTION')}}</th>
            @endif
        </tr>
    </thead>
    <tbody>
        <?php $pendingLock = 0; ?>
        @if (!empty($finalArr))
        <?php
        $sl = 0;
        ?>
        @foreach($finalArr as $item)
        <?php
        if ($item['ds_status'] != '2') {
            $pendingLock++;
        }
        $totalPercent = $item['achieved_mark_per'];
        ?>

        <tr class="contain-center">
            <td class="text-center">{{++$sl}}</td>
            <td class="text-center">{{$item['id']}}</td>
            <td class="text-center">{{ ($item['objective_mark'] == null) ? __('label.BLANK') :  Helper::numberformat($item['objective_mark'])}}</td>
            <td class="text-center">{{ ($item['subjective_mark'] == null) ? __('label.BLANK') :  Helper::numberformat($item['subjective_mark'])}}</td>
            <td class="text-center">{{ Helper::numberformat($item['achieved_mark'])}}</td>
            <td class="text-center">{{ Helper::numberformat($item['achieved_mark_per']).'%' }}</td>
            <td class="text-center"><span class="label label-{{$dsStatusArr[$item['ds_status']]['label']}}"> {{ $dsStatusArr[$item['ds_status']]['text'] }} </span></td>
            <td class="text-center">
                {{ Helper::findGrade($item['achieved_mark_per'])}}
            </td>
            @if(Auth::user()->group_id =='2')
            <td class="action-center">
                @if($item['ds_status'] == '2')
                <div class="text-center user-action">
                    <a href="{{URL::to('subjectiveMarking/'.$item['id'])}}" class="btn yellow-crusta btn-sm tooltips" data-tooltip="tooltip" title="{{__('label.ASSESS_EPE_SUBJECTIVE_SCRIPT')}}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                </div>
                @else
                <div class="text-center user-action">
                    <a href="{{URL::to('subjectiveMarking/'.$item['id'])}}" class="btn yellow-crusta btn-sm tooltips" data-tooltip="tooltip" title="{{__('label.ASSESS_EPE_SUBJECTIVE_SCRIPT')}}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                </div>
                @endif
                <!--unlocke-->
                @if($item['ds_status'] == '2')
                @if($item['unlock_request'] == '0')
                <div class="text-center user-action">
                    <a data-tooltip="tooltip" class="btn grey-cascade btn-sm tooltips" data-toggle="modal" data-target="#view-modal" data-id="{{$item['id']}}" href="#view-modal" id="remark" title="{{__('label.REQUEST_TO_UNLOCK')}}" data-container="body" data-trigger="hover" data-placement="top">
                        <i class="fa fa-unlock" aria-hidden="true"></i>
                    </a>
                </div>
                @else
                <div class="text-center text-danger">
                    {{__('label.REQUESTED_TO_UNLOCK').' '.__('label.AT').' '.Helper::printDateTime($item['unlock_request_at'])}}
                </div>
                @endif
                @endif
            </td>
            @endif
        </tr>
        @endforeach

        @else
        <tr>
            <td colspan="9">{{__('label.EMPTY_DATA')}}</td>

        </tr>

        @endif 

    </tbody>
</table>
<!--unlock request modal-->
<div id="view-modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="dynamic-info">
            <!--mysql data will load in table--> 
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("body").tooltip({selector: '[data-tooltip=tooltip]'});
    });

    //get unlock request
    $(document).on('click', '#remark', function (e) {
        e.preventDefault();
        var epeMarkId = $(this).data('id'); // get id of clicked row

        $('#dynamic-info').html(''); // leave this div blank
        $.ajax({
            url: "{{ URL::to('epedsmarking/unlockRequest') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                epe_mark_id: epeMarkId
            },
//            cache: false,
//            contentType: false,
            success: function (response) {
                $('#dynamic-info').html(''); // blank before load.
                $('#dynamic-info').html(response.html); // load here

            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                $('#dynamic-info').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
            }
        });
    });

//unlock request save
    $(document).on('click', '#saveRequest', function (e) {
        var formData = new FormData($('#formData')[0]);
        e.preventDefault();
        $.ajax({
            url: "{{ URL::to('epedsmarking/unlockRequestSave') }}",
            type: "POST",
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            async: true,
            success: function (response) {
                toastr.success('Unlock request has been sent to Admin', "Success", {"closeButton": true});
                setTimeout(function () {
                    window.location.href = "{{URL::to('/')}}" + "/epedsmarking?epe_id=" + response.data.epe_id;
                }, 3000);
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
                $('#attachment-modal').modal('show');
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
    }
    );
</script>


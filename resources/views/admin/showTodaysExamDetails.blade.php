<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title bold text-center">
            @lang('label.TODAYS_EXAM_DETAILS')
        </h4>
    </div>
    <div class="modal-body">
        <!--start :: todays exam details-->
        <div class="row div-box-default margin-top-10">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="info">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.SUBJECT')</th>
                                <th class="vcenter">@lang('label.TITLE')</th>
                                <th class="text-center vcenter">@lang('label.EXAM_TYPE')</th>
                                <th class=" text-center vcenter">@lang('label.QUESTIONNAIRE_FORMAT')</th>
                                <th class="text-center vcenter">@lang('label.DURATION')</th>
                                <th class="text-center vcenter">@lang('label.RESULT_SUBMISSION_DEADLINE')</th>
                                <th class="text-center vcenter">@lang('label.RESULT_PUBLISH_DATE_TIME')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$todaysExamDetails->isEmpty())
                            <?php
                            $sl = 0;
                            ?>
                            @foreach($todaysExamDetails as $exam)
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="vcenter">{!! $exam->subject ?? __('label.N_A') !!}</td>
                                <td class="vcenter">{!! $exam->title ?? __('label.N_A') !!}</td>
                                <td class="text-center vcenter">
                                    @if($exam->type == '1')
                                    <span class="label label-green-seagreen">@lang('label.REGULAR')</span>
                                    @elseif($exam->type == '2')
                                    <span class="label label-red-soft">@lang('label.RETAKE')</span>
                                    @endif
                                </td>
                                <td class="text-center vcenter">
                                    @if($exam->questionnaire_format == '1')
                                    <span class="label label-purple-sharp">@lang('label.ONE_TO_ONE')</span>
                                    @elseif($exam->questionnaire_format == '2')
                                    <span class="label label-grey-mint">@lang('label.ONE_TO_MANY')</span>
                                    @endif
                                </td>
                                <td class="text-center vcenter">
                                    @if(empty($exam->obj_duration_hours) && empty($exam->obj_duration_minutes))
                                    @lang('label.N_A')
                                    @else
                                    {!! !empty($exam->obj_duration_hours) ? $exam->obj_duration_hours. '&nbsp;'.__('label.HOURS').'&nbsp;' : '' !!}
                                    {!! !empty($exam->obj_duration_minutes) ? $exam->obj_duration_minutes. '&nbsp;'.__('label.MINUTES').'&nbsp;' : '' !!}
                                    @endif
                                </td>
                                <td class="text-center vcenter">
                                    {!! !empty($exam->submission_deadline) ? $exam->submission_deadline : __('label.N_A') !!}
                                </td>
                                <td class="text-center vcenter">
                                    {!! !empty($exam->result_publish) ? $exam->result_publish : __('label.N_A') !!}
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="14" class="vcenter">@lang('label.NO_EXAM_SCHEDULED_FOR_TODAY')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <!--end :: todays exam details-->
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
$(".tooltips").tooltip();
});
</script>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr class="contain-center">
            <th class="text-center">{{trans('english.SL_NO')}}</th>
            <th class="text-center">{{trans('english.TYPE')}}</th>
            <th>{{trans('english.SUBJECT_INFO')}}</th>
            <th>{{trans('english.TITLE')}}</th>
            <th class="text-center">{{trans('english.RESULT_PUBLISH_DATE_TIME')}}</th>
            <th class="text-center">{{trans('english.RESULT')}}</th>
            <th class='text-center'>{{trans('english.ACTION')}}</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($allTaeArr))
        <?php
        $sl = 0;
        ?>
        @foreach($allTaeArr as $value)
        <?php
        $resultPublishDateTime = $value['result_publish'];
        $tadayDateTime = date("Y-m-d H:i:s");
        ?>
        @if($resultPublishDateTime < $tadayDateTime)
        <tr class=" contain-center">
            <td class="text-center">{{++$sl}}</td>
            <td class="text-center">
                @if($value['type'] == '1')
                <label class="label label-primary label-sm">{{trans('english.REGULAR')}}</label>
                @elseif($value['type'] == '2')
                <label class="label label-danger label-sm">{{trans('english.IRREGULAR')}}</label>
                @elseif($value['type'] == '3')
                <label class="label label-warning label-sm">{{trans('english.RESCHEDULE')}}</label>
                @endif
            </td>
            <td>
                <label>{{trans('english.PHASE')}} :
                    @if(!empty($value['phase_id']))
                    {{$phaseList[$value['phase_id']]}}
                    @endif
                </label><br/>
                <label>{{trans('english.SUBJECT')}} : {{$subjectList[$value['subject_id']]}}</label><br/>
            </td>
            <td>{{$value['title'] }}</td>
            <td class="text-center">{{Custom::printDateTime($value['result_publish']) }}</td>
            <td class="text-center">
                @if($value['result_status'] == '1')
                <label class="label label-success label-sm">{{trans('english.PASSED')}}</label>
                @elseif($value['result_status'] == '2')
                <label class="label label-danger label-sm">{{trans('english.FAILED')}}</label>
                @endif
            </td>
            <td class="action-center">
                <div class="text-center user-action">
                    @if(!empty($value['ebook_file']))
                    <a data-tooltip="tooltip" href="{{URL::to('public/uploads/ebook',$value['ebook_file'])}}" class="btn yellow-crusta btn-sm tooltips" title="{{trans('english.E_BOOK_VIEW')}}" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                    <a data-tooltip="tooltip" href="{{ URL::to('tae/download?id='.$value['tae_id'].'&type=1') }}" class="btn green btn-sm tooltips" title="{{trans('english.E_BOOK_DOWNLOAD')}}" data-container="body" data-trigger="hover" data-placement="top"> <i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                    @endif
                    @if(!empty($value['question_file']))
                    <a data-tooltip="tooltip" href="{{URL::to('public/uploads/question',$value['question_file'])}}" class="btn yellow-crusta btn-sm tooltips" title="{{trans('english.QUESTION_VIEW')}}" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                    <a data-tooltip="tooltip" href="{{ URL::to('tae/download?id='.$value['tae_id'].'&type=2') }}" class="btn green btn-sm tooltips" title="{{trans('english.QUESTION_DOWNLOAD')}}" data-container="body" data-trigger="hover" data-placement="left"> <i class="fa fa-cloud-download" aria-hidden="true" ></i></a>
                    @endif
                </div>
            </td>
        </tr>
        @endif
        @endforeach
        @else
        <tr>
            <td colspan="7">{{trans('english.EMPTY_DATA')}}</td>
        </tr>
        @endif 
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function () {
        $(".tooltips").tooltip({html: true});
    });
</script>
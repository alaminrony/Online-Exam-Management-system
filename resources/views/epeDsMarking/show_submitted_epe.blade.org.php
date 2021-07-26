<h5>
    {{trans('english.TOTAL_SUBMISSION')}} : <strong>{{$targetArr->count() }} |</strong> 
    {{trans('english.WAITING_FOR_ASSESSMENT')}} : <strong>{{ $dsStatusArr[0]['total'] }} |</strong>
    {{trans('english.ASSESSED')}} : <strong>{{ $dsStatusArr[1]['total'] }} |</strong>
    {{trans('english.LOCKED')}} : <strong>{{ $dsStatusArr[2]['total'] }}</strong>
</h5>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th class="text-center">{{trans('english.SL_NO')}}</th>
            <th class="text-center">{{trans('english.SUBMISSION_ID')}}</th>
            <th class="text-center">{{trans('english.ACHIEVED_MARK')}}</th>
            <th class="text-center">{{trans('english.ACHIEVED_MARK'). '(%)'}}</th>
            <th class="text-center">{{trans('english.STATUS')}}</th>
            <th class="text-center">{{trans('english.ACTION')}}</th>
        </tr>
    </thead>
    <tbody>
        <?php $pendingLock = 0; ?>
        @if (!$targetArr->isEmpty())
        <?php
        $sl = 0;
        ?>
        @foreach($targetArr as $item)
        <?php
        if ($item->ds_status != '2') {
            $pendingLock++;
        }
        ?>

        <tr class="contain-center">
            <td class="text-center">{{++$sl}}</td>
            <td class="text-center">{{$item->id}}</td>
            <td class="text-center">{{$item->subjective_earned_mark}}</td>
            <td class="text-center">{{ Custom::numberFormat((($item->subjective_earned_mark*100)/$markDistribution->subjective))}}</td>
            <td class="text-center"><span class="label label-{{$dsStatusArr[$item->ds_status]['label']}}"> {{ $dsStatusArr[$item->ds_status]['text'] }} </span></td>

            <td class="action-center">
                <div class="text-center user-action">
                    <a href="{{URL::to('subjectiveMarking/'.$item->id)}}" class="btn yellow-crusta btn-sm tooltips" title="{{trans('english.ASSESS_EPE_SUBJECTIVE_SCRIPT')}}"><i class="icon-note" aria-hidden="true"></i></a>
                </div>
            </td>
        </tr>
        @endforeach
        <tr>
            @if(empty($epeInfo->ds_status))
            <td colspan="6" id="tdsubmit">
                <button type="button" id="submitResult" class="btn btn-primary">{{ trans('english.SUBMIT_RESULT') }}</button>
            </td>
            @endif
        </tr>
        @else
        <tr>
            <td colspan="8">{{trans('english.EMPTY_DATA')}}</td>

        </tr>

        @endif 

    </tbody>
</table>
@if(!empty($epeInfo->ds_status))
<div class="row">
    <div class="col-md-12 text-center">
        <div class="well text-danger">{{ trans('english.THIS_EPE_HAS_BEEN_SUBMITTED_BY').' '.$lockerInfo->Rank->short_name.' '.$lockerInfo->first_name.' '.$lockerInfo->last_name.' ('.$lockerInfo->username.') at '.$epeInfo->ds_lock_at }}</div>
    </div>
</div>
@endif
<div id="lockedMsg">

</div>
<input id="pendingLock" type="hidden" value="{{ $pendingLock }}" />

<script type="text/javascript">
    $(document).ready(function () {
        $("body").tooltip({selector: '[data-tooltip=tooltip]'});
    });
</script>


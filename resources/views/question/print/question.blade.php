<table>
    <tr>
        <td>
            @if(!empty($request->subject_id))
            <label>@lang('label.SUBJECT'): {{!empty($subjectList[$request->subject_id]) ? $subjectList[$request->subject_id] : 'N/A'}}</label>
            @endif
            @if(!empty($request->type_id))
            <label>@lang('label.QUESTION_TYPE'): {{!empty($typeList[$request->type_id])? $typeList[$request->type_id] : 'N/A'}}</label>
            @endif  
            @if(!empty($request->status)) 
            <label>@lang('label.STATUS'): {{!empty($statusList[$request->status]) ? $statusList[$request->status] : 'N/A'}}</label>
            @endif
        </td>
    </tr>
</table>
<!--Laravel Excel not supported body & other tags, only Table tag accepted-->
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th class="text-center">@lang('label.SL_NO')</th>
            <th>@lang('label.SUBJECT')</th>
            <th>@lang('label.QUESTION_TYPE')</th>
            <th>@lang('label.QUESTION')</th>
        </tr>
    </thead>
    <tbody>
        @if (!$targetArr->isEmpty())
        <?php
        $page = Request::get('page');
        $page = empty($page) ? 1 : $page;
        $sl = ($page - 1) * __('label.PAGINATION_COUNT_50');
        ?>
        @foreach($targetArr as $value)
        <tr class="contain-center">
            <td class="text-center">{{ ++$sl }}</td>
            <td>{{ $value->Subject->title}}</td>
            <td>{{ $value->QuestionType->name}}</td>
            <td>{{ $value->question}}</td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="7">@lang('label.EMPTY_DATA')</td>
        </tr>
        @endif 
    </tbody>
</table>










<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{trans('english.NOTICE_DETAILS')}}</h4>
</div>
<div class="modal-body">
    <table class="table table-striped table-bordered table-hover">
        @if(Auth::user()->group_id != '5')
        <tr>
            <th>{{trans('english.PUBLISH_DATE')}}</th>
            <td>{{$noticeDetails->published_date}}</td>
        </tr>
        <tr>
            <th>{{trans('english.CLOSING_DATE_ONLY')}}</th>
            <td>{{$noticeDetails->closing_date}}</td>
        </tr>
        @endif
        <tr>
            <th>{{trans('english.TITLE')}}</th>
            <td>{{$noticeDetails->title}}</td>
        </tr>
        <tr>
            <th>{{trans('english.DESCRIPTION')}}</th>
            <td>{{$noticeDetails->description}}</td>
        </tr>        
        @if(!empty($noticeDetails->fileInfo))
        <tr>            
            <td colspan="2">
                <a class="btn btn-outline blue btn-sm" href="{{ URL::to('public/uploads/notice',$noticeDetails->fileInfo) }}" download id="downloadAttachment">{{ trans('english.DOWNLOAD_ATTACHMENT') }}</a>
            </td>
        </tr>
        @endif
        
    </table>
</div>

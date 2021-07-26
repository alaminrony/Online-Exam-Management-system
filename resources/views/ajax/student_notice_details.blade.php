<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{$noticeDetails->title}}</h4>
</div>

<div class="modal-body">
    {{$noticeDetails->description}}
    @if(!empty($noticeDetails->fileInfo))
    <div>
        <a class="btn btn-outline blue btn-sm" href="{{ URL::to('public/uploads/notice',$noticeDetails->fileInfo) }}" download id="downloadAttachment">{{ trans('english.DOWNLOAD_ATTACHMENT') }}</a>
    </div>
    @endif
</div>


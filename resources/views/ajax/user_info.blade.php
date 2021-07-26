<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{trans('english.USER_DETAILS_INFO')}}</h4>
</div>
<div class="modal-body">
    <table class="table table-striped table-bordered table-hover">
        
        <tr>
            <th>{{trans('english.SERVICE_NO')}}</th>
            <td>{{$userInfo->service_no}}</td>
        </tr>
        <tr>
            <th>{{trans('english.USER_GROUP')}}</th>
            <td>{{$userInfo->group_name}}</td>
        </tr>
        <tr>
            <th>{{trans('english.RANK')}}</th>
            <td>{{$userInfo->rank_title}}</td>
        </tr>
        <tr>
            <th>{{trans('english.APPOINTMENT')}}</th>
            <td>{{(!empty($userInfo->appointment_title)) ? $userInfo->appointment_title : '---'}}</td>
        </tr>
        <tr>
            <th>{{trans('english.BRANCH')}}</th>
            <td>{{(!empty($userInfo->branch_name)) ? $userInfo->branch_name : '---'}}</td>
        </tr>
        <tr>
            <th>{{trans('english.FIRST_NAME')}}</th>
            <td>{{$userInfo->first_name}}</td>
        </tr>
        <tr>
            <th>{{trans('english.LAST_NAME')}}</th>
            <td>{{$userInfo->last_name}}</td>
        </tr>
        <tr>
            <th>{{trans('english.USERNAME')}}</th>
            <td>{{$userInfo->username}}</td>
        </tr>
        <tr>
            <th>{{trans('english.OFFICIAL_NAME')}}</th>
            <td>{{$userInfo->official_name}}</td>
        </tr>
        <tr>
            <th>{{trans('english.STATUS')}}</th>
            <td>
                @if ($userInfo->status == 'active')
                <span class="label label-success">{{ $userInfo->status }}</span>
                @else
                <span class="label label-warning">{{ $userInfo->status }}</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>{{trans('english.EMAIL')}}</th>
            <td>{{$userInfo->email}}</td>
        </tr>
        <tr>
            <th>{{trans('english.PHONE_NO')}}</th>
            <td>{{$userInfo->phone_no}}</td>
        </tr>
    </table>
</div>

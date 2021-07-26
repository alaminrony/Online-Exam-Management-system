<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" class="btn white pull-right tooltips" data-dismiss="modal" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            <strong>@lang('label.USER_DETAILS')</strong>
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-offset-1 col-md-8">
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <td>@lang('label.USER_GROUP')</td>
                        <td>{{$target->group_name}} </td>
                    </tr>
                    <tr>
                        <td>@lang('label.DEPARTMENT')</td>
                        <td>{{(!empty($target->department_name)) ? $target->department_name : '---'}} </td>
                    </tr>
                    <tr>
                        <td>@lang('label.RANK')</td>
                        <td> {{$target->rank_title??'--'}}</td>
                    </tr>
                    <tr>
                        <td>@lang('label.APPOINTMENT')</td>
                        <td>{{(!empty($target->designation_title)) ? $target->designation_title : '---'}} </td>
                    </tr>
                    
                    <tr>
                        <td>@lang('label.BRANCH')</td>
                        <td> {{(!empty($target->branch_name)) ? $target->branch_name : '---'}}</td>
                    </tr>
                    <tr>
                        <td>@lang('label.REGION')</td>
                        <td> {{(!empty($target->region_name)) ? $target->region_name : '---'}}</td>
                    </tr>
                    <tr>
                        <td>@lang('label.CLUSTER')</td>
                        <td> {{(!empty($target->cluster_name)) ? $target->cluster_name : '---'}}</td>
                    </tr>
                    <tr>
                        <td>@lang('label.FIRST_NAME')</td>
                        <td>{{$target->first_name}} </td>
                    </tr>
                    <tr>
                        <td>@lang('label.LAST_NAME')</td>
                        <td> {{$target->last_name}}</td>
                    </tr>
                    <tr>
                        <td>@lang('label.USERNAME')</td>
                        <td>{{$target->username}} </td>
                    </tr>
                   
                    
                    <tr>
                        <td>@lang('label.EMAIL')</td>
                        <td> {{$target->email}}</td>
                    </tr>
                    <tr>
                        <td>@lang('label.PHONE_NO')</td>
                        <td> {{$target->phone_no}}</td>
                    </tr>
                    
                    <tr>
                        <td>@lang('label.STATUS')</td>
                        <td> 
                            @if ($target->status == 'active')
                            <span class="label label-success">{{ $target->status }}</span>
                            @else
                            <span class="label label-warning">{{ $target->status }}</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-3">
                @if(!empty($target->photo))
                <img height="150" width="150" src="{{URL::to('/')}}/public/uploads/user/{{$target->photo}}" alt="{{$target->full_name}}"/>
                @else
                <img height="150" width="150" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{$target->full_name}}"/>
                @endif
                <br />
                <strong>{{ $target->full_name }}</strong>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>  
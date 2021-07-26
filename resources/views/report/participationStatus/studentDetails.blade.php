<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-phone-square"></i> {!!__('label.STUDENT_DETAILS')!!}</h4>
    </div>
    <div class="modal-body">
        <table class="table table-bordered">
            <tr>
                <th class="vcenter">@lang('label.SL_NO')</th>
                <th class='text-center vcenter'>@lang('label.PHOTO')</th>
                <th class="text-center vcenter">@lang('label.NAME')</th>
                <th class="text-center vcenter">@lang('label.DEPARTMENT')</th>
                <th class="text-center vcenter">@lang('label.RANK')</th>
                <th class="text-center vcenter">@lang('label.APPOINTMENT')</th>
                <th class="text-center vcenter">@lang('label.BRANCH')</th>
                <th class="text-center vcenter">@lang('label.REGION')</th>
                <th class="text-center vcenter">@lang('label.CLUSTER')</th>
            </tr>
            <?php $i=1;
            ?>
            @foreach($studentDetails as $result)
            <tr>
                <td>{{$i++}}</td>
                <td class="text-center vcenter">
                    @if(!empty($result->photo))
                    <img width="40" height="40" src="{{URL::to('/')}}/public/uploads/user/{{$result->photo}}" alt="{{ $result->employee_name}}">
                    @else
                    <img width="40" height="40" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $result->employee_name}}">
                    @endif
                </td>
                <td>{{$result->employee_name}} ({{$result->username}})</td>
                <td>{{$result->department_name}}</td>
                <td>{{$result->grade}}</td>
                <td>{{$result->designation_title}}</td>
                <td>{{$result->branch_name}}</td>
                <td>{{$result->region_name}}</td>
                <td>{{$result->cluster_name}}</td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>



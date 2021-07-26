<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-eye"></i> {!!__('label.VIEW_ASSIGNED_STUDENT')!!}</h4>
    </div>
    <div class="modal-body">
        <table class="table table-striped table-bordered table-hover table-checkable order-column dataTables_wrapper" id="dataTable">
            <thead>
                <tr>
                    <th width="20%"> {{__('label.NAME')}} </th>
                    <th> {{__('label.REGION')}} </th>
                    <th> {{__('label.CLUSTER')}} </th>
                    <th> {{__('label.BRANCH')}} </th>
                    <th> {{__('label.DEPARTMENT')}} </th>
                </tr>
            </thead>
            <tbody>
                @if(!$studentArr->isEmpty())
                <?php
                $class = 'noStd';
                ?>
                @foreach($studentArr as $student)
                <tr class="odd gradeX">
                    <td >{{!empty($student->rank->short_name) ? $student->rank->short_name : ''}} {{$student->first_name??''}} {{$student->last_name??''}} ({{$student->username??''}})</td>
                    <td>{{!empty($student->region_name) ? $student->region_name : ''}}</td>
                    <td>{{!empty($student->cluster_name) ? $student->cluster_name : ''}}</td>
                    <td>{{!empty($student->branch_name) ? $student->branch_name: ''}}</td>
                    <td>{{!empty($student->department_name) ? $student->department_name: ''}}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>




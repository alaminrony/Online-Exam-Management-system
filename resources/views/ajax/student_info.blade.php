<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{trans('english.STUDENT_DETAILS_INFO')}}</h4>
</div>
<div class="modal-body">
		<h4 class="text-center"><strong>{{$studentInfo->course2_title}}</strong></h4>
	
		<table class="table table-striped table-hover">
		<tr>
			<td>
				<table class="table table-hover">
				<tr>
					<th>{{trans('english.NAME')}}</th>
					<th> : </th>
					<td> {{$studentInfo->first_name .' '. $studentInfo->last_name}} </td>
				</tr>
				<tr>
					<th>{{trans('english.REGISTRATION_NO')}}</th>
					<th> : </th>
					<td> {{$studentInfo->registration_no}} </td>
				</tr>
				<tr>
					<th>@if($studentInfo -> program_id == 1)
							{{trans('english.ISSP')}}
						@else
							{{trans('english.JCSC').' '.'Index'}}
						@endif
					</th>
					<th> : </th>
					<td>
						@if($studentInfo -> program_id == 1)
							{{$studentInfo->iss_no}}
						@else
							{{$studentInfo->jc_sc_index}}
						@endif
					</td>
				</tr>
				
				
				<tr>
					<th>{{trans('english.SERVICE_NO')}}</th>
					<th> : </th>
					<td>{{$studentInfo->service_no}}</td>
				</tr>
				
				</table>
			</td>
			<td>
				<div  class="pull-right"> 
				@if(isset($studentInfo->photo))
				<img width="150" height="150" src="{{URL::to('/')}}/public/uploads/user/{{$studentInfo->photo}}" alt="{{ $studentInfo->first_name.' '.$studentInfo->last_name }}">
				@else
				<img width="150" height="150" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $studentInfo->first_name.' '.$studentInfo->last_name }}">
				@endif
			</td>
		</div>
		</tr>
       </table>
	   <table class="table table-striped table-bordered table-hover">
		<tr>
			<th>{{trans('english.USERNAME')}} </th>
			<td> {{$studentInfo->username}} </td>
		</tr>
        <tr>
            <th>{{trans('english.USER_GROUP')}}</th>
            <td>{{$studentInfo->group_name}}</td>
        </tr>
        <tr>
            <th>{{trans('english.RANK')}}</th>
            <td>{{$studentInfo->rank_title}}</td>
        </tr>
        <tr>
            <th>{{trans('english.APPOINTMENT')}}</th>
            <td>{{(!empty($studentInfo->appointment_title)) ? $studentInfo->appointment_title : '---'}}</td>
        </tr>
        <tr>
            <th>{{trans('english.BRANCH')}}</th>
            <td>{{(!empty($studentInfo->branch_name)) ? $studentInfo->branch_name : '---'}}</td>
        </tr>
       
        <tr>
            <th>{{trans('english.OFFICIAL_NAME')}}</th>
            <td>{{$studentInfo->official_name}}</td>
        </tr>
        <tr>
            <th>{{trans('english.STATUS')}}</th>
            <td>
                @if ($studentInfo->status == 'active')
                <span class="label label-success">{{ $studentInfo->status }}</span>
                @else
                <span class="label label-warning">{{ $studentInfo->status }}</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>{{trans('english.EMAIL')}}</th>
            <td>{{$studentInfo->email}}</td>
        </tr>
        <tr>
            <th>{{trans('english.PHONE_NO')}}</th>
            <td>{{$studentInfo->phone_no}}</td>
        </tr>
    </table>
</div>

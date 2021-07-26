@extends('layouts.default.master')
@section('data_count')
<!-- BEGIN CONTENT BODY -->
<div class="page-content">

    <!-- BEGIN PORTLET-->
    @include('layouts.flash')
    <!-- END PORTLET-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-list"></i>{{trans('english.MANUAL_LIST')}}
                    </div>
                </div>
                <div class="portlet-body">
				
					<div class="row">
                        <div class="col-md-12">
							<div class="portlet-body">
								<div class="tab-content">
								<div class="mt-comments">
									@if (!$userGroup->isEmpty())
										@foreach($userGroup as $user)
									
											<div class="mt-comment comment-custom">
												<div class="mt-comment-img ">
													<div class="label label-sm label-success">
														<i class="fa fa-newspaper-o"></i>
													</div>
												</div>
												<div class="mt-comment-body comment-body-custom">
													<div class="mt-comment-info">
														<span class="mt-comment-author">{{$user->name .' '.trans('english.MANUAL')}} </span>
													</div>
													<div class="mt-comment-text"> </div>
													<div class="mt-comment-details">
														<a  class="btn blue-hoki btn-outline sbold"  href="{{URL::to('public/manual/'.$viewManual[$user->id].'.pdf')}}" target="_blank">{{trans('english.VIEW_MANUAL')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<a  class="btn green-haze btn-outline sbold" href="{{ URL::to('manual/download/'.$user->id) }}" >{{trans('english.DOWNLOAD')}}</a>
													</div>
												</div>
											</div>
										@endforeach
									@endif
									</div>
								</div>
							</div>
						</div>
					</div>
					
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END CONTENT BODY -->
<!-- END CONTENT BODY -->
<script>
  
	
</script>
@stop

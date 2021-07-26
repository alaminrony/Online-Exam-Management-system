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
                        <i class="fa fa-Book"></i> Message
                    </div>
                    <div class="actions">
                        
                    </div>
                </div>
                <div class="portlet-body">
					<div class="portlet light">
						<div class="row">
							<div class="col-md-12">
								@if (!$scrollmessageList->isEmpty())
									@foreach($scrollmessageList as $message)
									<div class="note note-success text-center">
										 <h4 class="block"> {{$message->message}}</h4>   
									</div>
									@endforeach
								@endif
							</div>
							
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

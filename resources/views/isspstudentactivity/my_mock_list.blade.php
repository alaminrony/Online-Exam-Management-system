<div class="portlet-body">
    <div class="mt-element-list">
        <div class="mt-list-head list-default ext-1 green-haze">
            <div class="row">
                <div class="col-xs-8">
                    <div class="list-head-title-container">
                        <h3 class="list-title uppercase sbold">{{$epe->Subject->title}}</h3>
                        <!-- <div class="list-date">{{$epe->start_at}} - {{$epe->end_at}}</div> -->
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="list-head-summary-container">
                        <div class="list-pending">
                            <div class="list-count badge badge-default ">{{$epe->no_of_mock}}</div>
                            <div class="list-label">{{ __('label.MOCK_REQUIRED')}}</div>
                        </div>
                        <div class="list-done">
                            <div class="list-count badge badge-default last">{{$completedMock}}</div>
                            <div class="list-label">{{ __('label.MOCK_COMPLETED')}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-list-container list-default ext-1">
            @if(!$mockListArr->isEmpty())
                <ul>
                    @foreach($mockListArr as $mock)
                        <?php 
                            if($mock->pass == '1'){
                                $statusClass = 'done';
                                $icon = '<i class="icon-check"></i>';
                                $exam = '<a onclick="return false;">'.$mock->title.'</a>';
                                $title = 'Successfully Completed the Mock Test';
                            }else if($mock->pass == '2'){
                                $statusClass = '';
                                $icon = '<a href="#stack2" class="mockplay" data-toggle="modal" data-id="'.$mock->id.'"><i class="icon-close"></i></a>';
                                $exam = '<a href="#stack2" class="mockplay" data-toggle="modal" data-id="'.$mock->id.'">'.$mock->title.'</a>';
                                $title = 'Attempt Taken But Failed!';
                            }else{
                                $statusClass = '';
                                $icon = '<a href="#stack2" class="mockplay" data-toggle="modal" data-id="'.$mock->id.'"><i class="icon-target"></i></a>';
                                $exam = '<a href="#stack2" class="mockplay" data-toggle="modal" data-id="'.$mock->id.'">'.$mock->title.'</a>';
                                $title = 'No Attempt Taken Yet';
                            }
                        ?>
                    <li class="mt-list-item {!! $statusClass !!} tooltips" title="{!! $title !!}">
                            <div class="list-icon-container">
                                {!! $icon !!}
                            </div>
                            <div class="list-datetime">
                                <span class="badge badge-default">{{(strlen($mock->duration_hours) === 1) ? '0'.$mock->duration_hours : $mock->duration_hours }}:{{(strlen($mock->duration_minutes) === 1) ? '0'.$mock->duration_minutes : $mock->duration_minutes }}</span>
                            </div>
                            <div class="list-item-content">
                                <h3 class="uppercase">
                                    {!! $exam !!}
                                </h3>
                                <p>{{ __('label.TOTAL_NO_OF_QUESTION')}}: {{$mock->obj_no_question}}</p>
                                <p class="text-warning"><small>({{$mock->start_at}} {{__('label.TO')}} {{$mock->end_at}})</small></p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="uppercase bold text-center">{{ __('label.NO_ACTIVE_MOCK_TEST_FOR')}} {{$epe->Subject->title}}</p>
            @endif
        </div>
    </div>
</div>



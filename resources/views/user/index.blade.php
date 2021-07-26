@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.USER_LIST')
            </div>
            <div class="actions">
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('user/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_USER')
                    <i class="fa fa-plus create-new"></i>
                </a>
            </div>
        </div>
        <div class="portlet-body">
            {{ Form::open(array('role' => 'form', 'url' => 'user/filter', 'class' => '')) }}
            {{ Form::hidden('page', Helper::queryPageStr($qpArr))}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="userGroup">@lang('label.USER_GROUP')</label>
                        <div class="col-md-8">
                            {!! Form::select('fil_group_id',  $groupList, Request::get('fil_group_id'), ['class' => 'form-control js-source-states','autocomplete'=>'off','id'=>'userGroup']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="rankId">@lang('label.SELECT_RANK')</label>
                        <div class="col-md-6">
                            {!! Form::select('fil_rank_id',  $rankList, Request::get('fil_rank_id'), ['class' => 'form-control js-source-states','autocomplete'=>'off','id'=>'rankId']) !!}
                        </div>
                    </div>
                </div>                        
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="searchText">@lang('label.SEARCH_TEXT')</label>
                        <div class="col-md-8">
                            {{ Form::text('search_text',Request::get('search'), array('id'=> 'searchText', 'class' => 'form-control', 'placeholder' => __('label.SERACH_TEXT'))) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="appointmentId">@lang('label.APPROINTMENT')</label>
                        <div class="col-md-8">
                            {!! Form::select('fil_designation_id',  $appointmentList, Request::get('fil_designation_id'), ['class' => 'form-control js-source-states','autocomplete'=>'off','id'=>'appointmentId']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                        <i class="fa fa-search"></i> @lang('label.FILTER')
                    </button>
                </div>
            </div>
            {{Form::close()}}

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="text-center info">
                            <th class="vcenter">@lang('label.SL_NO')</th>
                            <th class="text-center vcenter">@lang('label.USER_GROUP')</th>
                            <th class="text-center vcenter">@lang('label.DEPARTMENT')</th>
                            <th class="text-center vcenter">@lang('label.RANK')</th>
                            <th class="text-center vcenter">@lang('label.APPOINTMENT')</th>
                            <th class="text-center vcenter">@lang('label.BRANCH')</th>
                            <th class="text-center vcenter">@lang('label.REGION')</th>
                            <th class="text-center vcenter">@lang('label.CLUSTER')</th>
                            <th class="text-center vcenter">@lang('label.NAME')</th>
                            <th class="text-center vcenter">@lang('label.USERNAME')</th>
                            <th class='text-center vcenter'>@lang('label.PHOTO')</th>
                            <th class="text-center vcenter">@lang('label.ACCOUNT_CONFIRMED')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
                            <th class='text-center vcenter'>@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td class="vcenter">{{++$sl}}</td>
                            <td class="text-center vcenter">{{$target->UserGroup->name}}</td>
                            <td class="text-center vcenter">{{$target->department->name}}</td>
                            <td class="text-center vcenter">{{$target->rank->title??''}}</td>
                            <td class="text-center vcenter">{{$target->designation->title??''}}</td>
                            <td class="text-center vcenter">{{!empty($target->branch_name) ? $target->branch_name: ''}}</td>
                            <td class="text-center vcenter">{{!empty($target->region_name) ? $target->region_name: ''}}</td>
                            <td class="text-center vcenter">{{!empty($target->cluster_name) ? $target->cluster_name: ''}}</td>
                            <td class="text-center vcenter">{{ $target->first_name .' '. $target->last_name }}</td>
                            <td class="text-center vcenter">{{ $target->username }}</td>
                            <td class="text-center vcenter">
                                @if(isset($target->photo))
                                <img width="40" height="40" src="{{URL::to('/')}}/public/uploads/user/{{$target->photo}}" alt="{{ $target->first_name.' '.$target->last_name }}">
                                @else
                                <img width="40" height="40" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $target->first_name.' '.$target->last_name }}">
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                @if ($target->first_login == '1')
                                <span class="label label-success">@lang('label.YES')</span>
                                @else
                                <span class="label label-warning">@lang('label.NO')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                @if ($target->status == 'active')
                                <span class="label label-success">{{ $target->status }}</span>
                                @else
                                <span class="label label-warning">{{ $target->status }}</span>
                                @endif
                            </td>
                            <td class="action-center vcenter">
                                <div class="text-center user-action">
                                    {{ Form::open(array('url' => 'user/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'id' => 'delete')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}

                                    <?php
                                    $dd = Request::query();

                                    if (!empty($dd)) {
                                        $param = '';
                                        $sn = 1;

                                        foreach ($dd as $key => $item) {
                                            if ($sn === 1) {
                                                $param .= $key . '=' . $item;
                                            } else {
                                                $param .= '&' . $key . '=' . $item;
                                            }
                                            $sn++;
                                        }//foreach
                                    }
                                    ?>
                                    @if((Auth::user()->group_id == 1) || (Auth::user()->group_id != $target->group_id))
                                    <a class='btn btn-info btn-xs tooltips' href="{{ URL::to('user/activeUser/' . $target->id ) }}@if(isset($param)){{'/'.$param }} @endif" data-rel="tooltip" title="@if($target->status == 'active') Inactivate @else Activate @endif" data-container="body" data-trigger="hover" data-placement="top">
                                        @if($target->status == 'active')
                                        <i class='fa fa-remove'></i>
                                        @else
                                        <i class='fa fa-check-circle'></i>
                                        @endif
                                    </a>
                                    @endif
                                    <a class='btn btn-primary btn-xs tooltips' href="{{ URL::to('user/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}" title="@lang('label.EDIT_USER')" data-container="body" data-trigger="hover" data-placement="top">
                                        <i class='fa fa-edit'></i>
                                    </a>
                                    <a class="tooltips" href="{{ URL::to('changePassword/' . $target->id) }}@if(isset($param)){{'/'.$param }} @endif" data-original-title="@lang('label.CHANGE_PASSWORD')">
                                        <span class="btn btn-success btn-xs"> 
                                            <i class="fa fa-key"></i>
                                        </span>
                                    </a>
                                    <a class="tooltips details-btn tooltips" data-toggle="modal"  data-id="{{$target->id}}" data-target="#details" data-id="{{$target->id}}" href="#view-modal" id="detailsBtn-{{$target->id}}" title="@lang('label.USER_DETAILS')" data-placement="top">
                                        <span class="btn btn-success btn-xs"> 
                                            &nbsp;<i class='fa fa-info'></i>&nbsp;
                                        </span>
                                    </a>
                                    @if((Auth::user()->group_id == 1) || (Auth::user()->group_id != $target->group_id))
                                    <button class="btn btn-danger btn-xs tooltips delete" type="submit" title="@lang('label.DELETE')" data-placement="top" data-rel="tooltip" data-original-title="@lang('label.DELETE')">
                                        <i class='fa fa-trash'></i>
                                    </button>
                                    @endif
                                    {{ Form::close() }}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12" class="vcenter">@lang('label.NO_USER_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>
<!--User modal -->
<div class="modal fade" id="details" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showDetails"></div> 
    </div>
</div>
<!--End user modal -->
<script type="text/javascript">
    $(function () {
        $(function () {
            $(document).on('click', '.details-btn', function (e) {
                e.preventDefault();
                var userId = $(this).attr('data-id');
                var options = {
                    closeButton: true,
                    debug: false,
                    positionClass: "toast-bottom-right",
                    onclick: null
                };
                $.ajax({
                    url: "{!! URL::to('user/details') !!}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        user_id: userId
                    },
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        $('#showDetails').html(res.html);
                        $('.tooltips').tooltip();
                        App.unblockUI();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                    }
                });
            });
        });
    });
</script>
@stop
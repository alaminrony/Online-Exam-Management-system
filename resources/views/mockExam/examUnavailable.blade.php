@extends('layouts.default.master')
@section('data_count')

<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-graduation-cap"></i>{{ __('label.MOCK_TEST_EXAM') }} </div>
            <div class="tools">
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="note note-warning">
                        <h3>{{ $message['short'] }}</h3>
                        <p>{{ $message['long'] }}</p>
                    </div>
                </div>
                <div class="col-md-12">
                    <a href="{{URL::to('isspstudentactivity/mymocktest')}}">
                        <button type="button" class="btn btn-success mt-ladda-btn ladda-button btn-circle" data-style="expand-left" data-spinner-color="#333">
                            <span class="ladda-label">
                                <i class="icon-arrow-left"></i> {{__('label.BACK')}}</span>
                            <span class="ladda-spinner"></span>
                        </button>
                        <a/>
                </div>
            </div>
        </div>
    </div>
</div>

@stop


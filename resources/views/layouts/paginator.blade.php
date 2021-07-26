<div class="row">
    <div class="col-md-10">
        {{ $targetArr->appends(Request::all())->links() }}
        <?php
        $start = empty($targetArr->total()) ? 0 : (($targetArr->currentPage() - 1) * $targetArr->perPage() + 1);
        $end = ($targetArr->currentPage() * $targetArr->perPage() > $targetArr->total()) ? $targetArr->total() : ($targetArr->currentPage() * $targetArr->perPage());
        ?> <br />
        @lang('label.SHOWING') {{ $start }} @lang('label.TO') {{$end}} @lang('label.OF')  {{$targetArr->total()}} @lang('label.RECORDS')
    </div>
    <div class="col-md-2" id="recordPerPageHolder">					
        {!! Form::open(array('group' => 'form', 'url' => 'setRecordPerPage', 'class' => '')) !!}
        <div class="input-group">
            <div class="input-icon">
                <i class="fa fa-list fa-fw"></i>
                {!! Form::text('record_per_page', Session::get('paginatorCount'), ['class' => 'form-control integer-only tooltips'
                , 'title' => __('label.RECORDS_PER_PAGE'), 'placeholder' => __('label.RECORDS_PER_PAGE'), 'id' => 'recordPerPage',
                'maxlength' => 3]) !!}
            </div>
            <span class="input-group-btn">
                <button id="" class="btn btn-success" type="submit">
                    <i class="fa fa-arrow-right fa-fw"></i></button>
            </span>
        </div>
        {!! Form::close() !!}
    </div>
</div>
 <div class="col-md-offset-4 col-md-8">
    <h4 class="block">Choose Parts </h4>
    @if(!$partList->isEmpty())
        @foreach ($partList as $part)
        <div class="form-group">
            <div class="margin-bottom-10">
                <label class="col-md-6">{{ $part->title }}</label>
                <div class="col-md-6">
                    <input name="part_id[]" type="checkbox" class="make-switch" <?php echo in_array($part->id, $relatedPartArr) ? 'checked' : '';?> {{(in_array($part->id,$hasRelationPhaseArr)) ? 'readonly="readonly"' : ''}} data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" value="{{ $part->id }}">
                </div>
            </div>
        </div>
        @endforeach
    @else
       Data not found!
    @endif
</div>


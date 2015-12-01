<!-- Barcode Id Field -->
<div class="form-group">
    {!! Form::label('barcode_id', 'Barcode:') !!}
    {{-- Form::text('barcode_id', null, ['class' => 'form-control']) --}}
    <select ng-options="" class="form-control" required name="barcode_id">
        <option value=""></option>
    </select>
</div>

<!-- Shelf Id Field -->
<div class="form-group">
    {!! Form::label('shelf_id', 'Shelf Id:') !!}
    {!! Form::text('shelf_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Came Date Field -->
<div class="form-group">
    {!! Form::label('came_date', 'Came Date:') !!}
    {!! Form::text('came_date', null, ['class' => 'form-control']) !!}
</div>

<!-- Exit Date Field -->
<div class="form-group">
    {!! Form::label('exit_date', 'Exit Date:') !!}
    {!! Form::text('exit_date', null, ['class' => 'form-control']) !!}
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::text('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::text('status', null, ['class' => 'form-control']) !!}
</div>


<!-- Submit Field -->
<div class="form-group">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
</div>

<!-- Barcode Id Field -->
<div class="form-group">
    {!! Form::label('barcode_id', 'Barcode Id:') !!}
    <p>{!! $barcodeProcess->barcode_id !!}</p>
</div>

<!-- Shelf Id Field -->
<div class="form-group">
    {!! Form::label('shelf_id', 'Shelf Id:') !!}
    <p>{!! $barcodeProcess->shelf_id !!}</p>
</div>

<!-- Came Date Field -->
<div class="form-group">
    {!! Form::label('came_date', 'Came Date:') !!}
    <p>{!! $barcodeProcess->came_date !!}</p>
</div>

<!-- Exit Date Field -->
<div class="form-group">
    {!! Form::label('exit_date', 'Exit Date:') !!}
    <p>{!! $barcodeProcess->exit_date !!}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{!! $barcodeProcess->description !!}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>{!! $barcodeProcess->status !!}</p>
</div>


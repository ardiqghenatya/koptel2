<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::text('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Model Id Field -->
<div class="form-group">
    {!! Form::label('model_id', 'Model Id:') !!}
    {!! Form::text('model_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Data Id Field -->
<div class="form-group">
    {!! Form::label('data_id', 'Data Id:') !!}
    {!! Form::text('data_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Text Field -->
<div class="form-group">
    {!! Form::label('text', 'Text:') !!}
    {!! Form::text('text', null, ['class' => 'form-control']) !!}
</div>

<!-- Ip Address Field -->
<div class="form-group">
    {!! Form::label('ip_address', 'Ip Address:') !!}
    {!! Form::text('ip_address', null, ['class' => 'form-control']) !!}
</div>


<!-- Submit Field -->
<div class="form-group">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
</div>

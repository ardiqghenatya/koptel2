<!-- City Name Field -->
<div class="form-group">
    {!! Form::label('city_name', 'City Name:') !!}
    {!! Form::text('city_name', null, ['class' => 'form-control']) !!}
</div>

<!-- City Code Field -->
<div class="form-group">
    {!! Form::label('city_code', 'City Code:') !!}
    {!! Form::text('city_code', null, ['class' => 'form-control']) !!}
</div>

<!-- Country Id Field -->
<div class="form-group">
    {!! Form::label('country_id', 'Country Id:') !!}
    {!! Form::text('country_id', null, ['class' => 'form-control']) !!}
</div>


<!-- Submit Field -->
<div class="form-group">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
</div>

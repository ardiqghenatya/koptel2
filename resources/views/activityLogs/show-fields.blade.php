<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{!! $activityLog->user_id !!}</p>
</div>

<!-- Model Id Field -->
<div class="form-group">
    {!! Form::label('model_id', 'Model Id:') !!}
    <p>{!! $activityLog->model_id !!}</p>
</div>

<!-- Data Id Field -->
<div class="form-group">
    {!! Form::label('data_id', 'Data Id:') !!}
    <p>{!! $activityLog->data_id !!}</p>
</div>

<!-- Text Field -->
<div class="form-group">
    {!! Form::label('text', 'Text:') !!}
    <p>{!! $activityLog->text !!}</p>
</div>

<!-- Ip Address Field -->
<div class="form-group">
    {!! Form::label('ip_address', 'Ip Address:') !!}
    <p>{!! $activityLog->ip_address !!}</p>
</div>


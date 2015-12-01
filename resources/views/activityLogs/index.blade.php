@extends('app')

@section('title')
    {{ Config::get('app.app_name') }} | Manage ActivityLogs
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        ActivityLogs
        <small>Index</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url() }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">ActivityLogs</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">            
            @include('flash::message')
        </div>
    </div>

    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <div class="box box-solid box-primary collapsed-box">
                <div class="box-header">
                    <h3 class="box-title">Search</h3>

                    <div class="box-tools pull-right">
                        <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div><!-- /.box-header -->

                <div style="display: none;" class="box-body">
                    <tr>
    {!! Form::open(['route' => 'activityLogs.index', 'method' => 'get', 'class'=>"", 'id'=>"search_form"]) !!}

        

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



        <div class="form-group">
        	<button type="submit" class="btn">
        		<i class="fa fa-search"></i> Search
        	</button>
        </div>

    {!! Form::close() !!}
</tr>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-body">

                <div class="form-group">
                    <a href="{!! route('activityLogs.create') !!}" class="btn btn-success">Add New</a>                    
                </div>


                @if($activityLogs->isEmpty())
                    <div class="well text-center">No ActivityLogs found.</div>
                @else
                    <table id="data-index" class="table table-bordered table-striped">
                        <thead>
                        <th>User Id</th>
			<th>Model Id</th>
			<th>Data Id</th>
			<th>Text</th>
			<th>Ip Address</th>
                        <th width="50px">Action</th>
                        </thead>
                        <tbody>
                        @foreach($activityLogs as $activityLog)
                            <tr>
                                <td>{!! $activityLog->user_id !!}</td>
					<td>{!! $activityLog->model_id !!}</td>
					<td>{!! $activityLog->data_id !!}</td>
					<td>{!! $activityLog->text !!}</td>
					<td>{!! $activityLog->ip_address !!}</td>
                                <td>
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>
                                        <ul class="dropdown-menu">
                                            <li><a href="{!! route('activityLogs.show', [$activityLog->id]) !!}"><i class="fa fa-search"></i> View</a></li>
                                            <li><a href="{!! route('activityLogs.edit', [$activityLog->id]) !!}"><i class="fa fa-edit"></i> Edit</a></li>
                                            <li><a href="{!! route('activityLogs.delete', [$activityLog->id]) !!}"><i class="fa fa-times"></i> Delete</a></li>
                                        </ul>
                                    </div><!-- /btn-group -->
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
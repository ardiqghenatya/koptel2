@extends('app')

@section('title')
    {{ Config::get('app.app_name') }} | Edit Cities
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Cities
        <small>Edit</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url() }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{!! route('cities.index') !!}">Cities</a></li>
        <li class="active">Edit</li>
    </ol>
</section>

<section class="content">
    <div class="row">
	 	<!-- left column -->
	   	<div class="col-md-7">
	   		<!-- general form elements -->
            <div class="box box-primary">
            	<div class="box-header">
                    <h3 class="box-title">Create Form</h3>
                </div><!-- /.box-header -->

            	<div class="box-body">
				    @include('common.errors')

				    {!! Form::model($city, ['route' => ['cities.update', $city->id], 'method' => 'patch']) !!}

				        @include('cities.fields')

				    {!! Form::close() !!}
			    </div>
		    </div>
	    </div>
	</div>
</section>
@endsection

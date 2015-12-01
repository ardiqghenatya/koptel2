@extends('app')

@section('title')
    {{ Config::get('app.app_name') }} | Create BarcodeProcesses
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        BarcodeProcesses
        <small>Create</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url() }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{!! route('barcodeProcesses.index') !!}">BarcodeProcesses</a></li>
        <li class="active">Create</li>
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

				    {!! Form::open(['route' => 'barcodeProcesses.store', 'role'=>'form']) !!}

				        @include('barcodeProcesses.fields')

				    {!! Form::close() !!}
			    </div>
		    </div>
	    </div>
	</div>
</section>
@endsection

@extends('app')

@section('title')
    {{ Config::get('app.app_name') }} | Show Cities
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Cities
        <small>Show</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url() }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{!! route('cities.index') !!}">Cities</a></li>
        <li class="active">Show</li>
    </ol>
</section>

<section class="content">
    <div class="row">
	 	<!-- left column -->
	   	<div class="col-md-7">
	   		<!-- general form elements -->
            <div class="box box-primary">
            	<div class="box-header">
                    <h3 class="box-title">Show #{{ $city->id }}</h3>
                </div><!-- /.box-header -->

            	<div class="box-body">
					 @include('cities.show-fields')
			    </div>
		    </div>
	    </div>
	</div>
</section>
@endsection

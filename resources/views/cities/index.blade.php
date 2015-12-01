@extends('app')

@section('title')
    {{ Config::get('app.app_name') }} | Manage Cities
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Cities
        <small>Index</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url() }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Cities</li>
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
    {!! Form::open(['route' => 'cities.index', 'method' => 'get', 'class'=>"", 'id'=>"search_form"]) !!}

        

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
                    <a href="{!! route('cities.create') !!}" class="btn btn-success">Add New</a>                    
                </div>


                @if($cities->isEmpty())
                    <div class="well text-center">No Cities found.</div>
                @else
                    <table id="data-index" class="table table-bordered table-striped">
                        <thead>
                        <th>City Name</th>
			<th>City Code</th>
			<th>Country Id</th>
                        <th width="50px">Action</th>
                        </thead>
                        <tbody>
                        @foreach($cities as $city)
                            <tr>
                                <td>{!! $city->city_name !!}</td>
					<td>{!! $city->city_code !!}</td>
					<td>{!! $city->country_id !!}</td>
                                <td>
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>
                                        <ul class="dropdown-menu">
                                            <li><a href="{!! route('cities.show', [$city->id]) !!}"><i class="fa fa-search"></i> View</a></li>
                                            <li><a href="{!! route('cities.edit', [$city->id]) !!}"><i class="fa fa-edit"></i> Edit</a></li>
                                            <li><a href="{!! route('cities.delete', [$city->id]) !!}"><i class="fa fa-times"></i> Delete</a></li>
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
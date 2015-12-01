@extends('app')

@section('title')
    {{ Config::get('app.app_name') }} | Manage Barcodes
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Barcodes
        <small>Index</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url() }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Barcodes</li>
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
                    <a href="{!! route('barcodes.create') !!}" class="btn btn-success">Add New</a>                    
                </div>


                @if($barcodes->isEmpty())
                    <div class="well text-center">No Barcodes found.</div>
                @else
                    <table id="data-index" class="table table-bordered table-striped">
                        <thead>
                        <th>Barcode</th>
			<th>Description</th>
                        <th width="50px">Action</th>
                        </thead>
                        <tbody>
                        @foreach($barcodes as $barcode)
                            <tr>
                                <td>{!! $barcode->barcode !!}</td>
					<td>{!! $barcode->description !!}</td>
                                <td>
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>
                                        <ul class="dropdown-menu">
                                            <li><a href="{!! route('barcodes.show', [$barcode->id]) !!}"><i class="fa fa-search"></i> View</a></li>
                                            <li><a href="{!! route('barcodes.edit', [$barcode->id]) !!}"><i class="fa fa-edit"></i> Edit</a></li>
                                            <li><a href="{!! route('barcodes.delete', [$barcode->id]) !!}"><i class="fa fa-times"></i> Delete</a></li>
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
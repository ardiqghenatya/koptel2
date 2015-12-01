@extends('app_login')

@section('title')
    {{ Config::get('app.app_name') }} | Register
@endsection

@section('content')
<div class="form-box" id="login-box">
    <div class="header">Register New Membership</div>
    <form action="{{ url('auth/register') }}" method="post">
        <div class="body bg-gray">
        	@if (count($errors) > 0)
				<div class="alert alert-danger">
					<i class="fa fa-ban"></i>
					<strong>Whoops!</strong> There were some problems with your input.<br><br>
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

            <div class="form-group">
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Full Name">
            </div>
            <div class="form-group">
            	<input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
            </div>
            <div class="form-group">
            	<input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group">
            	<input type="password" class="form-control" name="password_confirmation" placeholder="Retype password">
            </div>
        </div>
        <div class="footer">                    

            <button type="submit" class="btn bg-olive btn-block">Sign me up</button>

            <a href="{{ url('auth/login') }}" class="text-center">I already have a membership</a>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</div>
@endsection

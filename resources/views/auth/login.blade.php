@extends('app_login')

@section('title')
    {{ Config::get('app.app_name') }} | Login
@endsection

@section('content')
<div class="form-box" id="login-box">
    <div class="header">Sign In</div>
    <form action="{{ url('auth/login') }}" method="post">
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
            	<input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email"/>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>          
            <div class="form-group">
                <input type="checkbox" name="remember"> Remember Me
            </div>
        </div>

        <div class="footer">                                                               
            <button type="submit" class="btn bg-olive btn-block">Sign me in</button>    
            <p><a href="/password/email">I forgot my password</a></p>
        </div>

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</div>
@endsection

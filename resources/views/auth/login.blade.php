@extends('layouts.app')

@section('content')
<div id="login" class="main">

    <h1>Login</h1>

    <div class="login-credentials">
        <h2>Login Details</h2>
        <ul>
            <li>Email: jennyswiftcreations@gmail.com</li>
            <li>Password: abcdefg</li>
        </ul>
    </div>

    <form role="form" method="POST" action="{{ url('/login') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="control-label">E-Mail Address</label>

            <div>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                @if ($errors->has('email'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="control-label">Password</label>

            <div>
                <input id="password" type="password" class="form-control" name="password">

                @if ($errors->has('password'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div>
                <div class="checkbox-container">
                    <label for="remember-me">Remember Me</label>
                    <input id="remember-me" type="checkbox" name="remember">
                </div>
            </div>
        </div>

        <div class="form-group">
            <div>
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </div>

    </form>
</div>
@include('shared.footer.footer')

@endsection

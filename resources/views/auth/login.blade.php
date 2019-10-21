@extends('layouts.auth')

@section('content')
<div class="h-100 d-flex justify-content-center align-items-center">
    <div class="wrap-auth">
        <img src="/img/logo.svg" class="app-logo">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- E-mail -->
            <div class="form-group row">
                <div class="col-sm-12">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="icon material-icons">account_circle</i>
                            </div>
                        </div>
                        <input id="email" name="email" type="email" placeholder="E-mail" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>            
            
            <!-- Password -->
            <div class="form-group row">
                <div class="col-sm-12">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="icon material-icons">lock_open</i>
                            </div>
                        </div>
                        
                        <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    <div class="mt-1 text-right">
                        @if (Route::has('password.request'))
                            <a class="text-muted" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                    
                    
                </div>                
            </div>

            <!-- Login button & remember me -->
            <div class="form-row align-items-center mt-4">
                <div class="col-md-6">
                    <div class="custom-control custom-checkbox">    
                        <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
    
                        <label class="custom-control-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                    <div class="form-check">
                        
                    </div>
                </div>

                <div class="col-md-6 w-100">
                    <button type="submit" class="btn btn-primary w-100">
                        {{ __('Login') }}
                    </button>
                </div>
            </div> 
        </form>
    </div>
</div>
@endsection

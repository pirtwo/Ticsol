@extends('layouts.auth')

@section('content')
<div class="h-100 d-flex justify-content-center align-items-center">
    <div class="wrap-auth">
        <img src="/img/logo.svg" class="app-logo">
        <form method="POST" action="{{ route('changePassword') }}">
            @csrf

            <!-- Password -->
            <div class="form-group row">
                <div class="col-sm-12">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="icon material-icons">lock_open</i>
                            </div>
                        </div>
                        <input id="password" name="password" type="password" placeholder="Enter current password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" required autofocus>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>            
            
            <!-- NewPassword -->
            <div class="form-group row">
                <div class="col-sm-12">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="icon material-icons">lock</i>
                            </div>
                        </div>
                        
                        <input id="newPassword" name="newPassword" type="password" placeholder="Enter new password" class="form-control @error('newPassword') is-invalid @enderror" required>

                        @error('newPassword')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div> 
                </div>                
            </div>

            <!-- Confirm Password -->
            <div class="form-group row">
                <div class="col-sm-12">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="icon material-icons">check_circle</i>
                            </div>
                        </div>
                        
                        <input id="confirmPassword" name="confirmPassword" type="password" placeholder="Confirm new password" class="form-control @error('confirmPassword') is-invalid @enderror" required>

                        @error('confirmPassword')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div> 
                </div>                
            </div>

            <!-- Login button & remember me -->
            <div class="form-row align-items-center mt-4">
                
                <div class="col-md-4">
                    <a href="/home" class="btn btn-light w-100">
                      Cancel
                    </a>
                </div>

                <div class="col-md-8 w-100">
                    <button type="submit" class="btn btn-primary w-100">
                        Change Password
                    </button>
                </div> 

            </div> 
        </form>
    </div>
</div>
@endsection

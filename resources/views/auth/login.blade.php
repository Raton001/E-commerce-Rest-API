@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button> 
    <strong>{{ $message }}</strong>
</div>
@endif
            <div class="card border-0 bg-authentication mb-0">
                                <div class="row m-0">
                                    <!-- left section-login -->
                                    <div class="col-md-6 col-12 px-0">
                                        <div class="card border-0 mb-0 p-2 h-100 d-flex justify-content-center shadow-lg">
                                            <div class="card-header pb-1 bg-white">
                                                <div class="card-title">
                                                    <h4 class="text-center mb-2">Welcome Back</h4>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <form  method="POST" action="{{ route('login') }}">
                                                    @csrf
                                                    <div class="form-group mb-50">
                                                        <label class="text-bold-600" for="exampleInputEmail1">Email address</label>
                                                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" placeholder="Email address">
                                                        @error('email')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-bold-600" for="exampleInputPassword1">Password</label>
                                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                                                        @error('password')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group d-flex flex-md-row flex-column justify-content-between align-items-center">
                                                        <div class="form-check text-left">
                                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        
                                                            <label class="form-check-label" for="remember">
                                                                {{ __('Remember Me') }}
                                                            </label>
                                                        </div>
                                                        {{-- <div class="text-left">
                                                            <div class="checkbox checkbox-sm">
                                                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                                <label class="checkboxsmall" for="exampleCheck1"><small>Keep me logged in</small></label>
                                                            </div>
                                                        </div> --}}
                                                        @if (Route::has('password.request'))
                                                            <div class="text-right"><a href="{{ route('password.request') }}" class="card-link"><small>Forgot Password?</small></a></div>
                                                        @endif
                                                    </div>
                                                    <button type="submit" class="btn btn-primary glow w-100 position-relative">Login<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
                                                </form>
                                                <hr>
                                                <div class="text-center"><small class="mr-25">Don't have an account?</small><a href="{{ route('register') }}"><small>Sign up</small></a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- right section image -->
                                    <div class="col-md-6 d-md-block d-none text-center align-self-center p-3" style="background-color : #f0faf3">
                                        <img class="img-fluid" src="{{url('assets/images/pages/login.png')}}" alt="branding logo">
                                    </div>
                                </div>
                            </div> 
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
             <div class="card border-0 bg-authentication mb-0">
                                <div class="row m-0">
                                    <!-- left section-login -->
                                    <div class="col-md-6 col-12 px-0">
                                        <div class="card border-0 disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center shadow-lg">
                                            <div class="card-header pb-1 bg-white">
                                                <div class="card-title">
                                                    <h4 class="text-center mb-2">New Registration</h4>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <form  method="POST" action="{{ route('register') }}">
                                                    @csrf
                                                    <div class="form-group mb-50">
                                                        <label class="text-bold-600" for="exampleInputEmail1">Name</label>
                                                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" required autocomplete="name" placeholder="Enter Name">
                                                        @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group mb-50">
                                                        <label class="text-bold-600" for="exampleInputEmail1">Email address</label>
                                                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter Email address">
                                                        @error('email')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-bold-600" for="exampleInputPassword1">Password</label>
                                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="password" placeholder="Enter Password">
                                                        @error('password')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-bold-600" for="exampleInputPassword1">Confirm Password</label>
                                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Re-enter Password">
                                                        @error('password')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="text-bold-600" for="membership">membership</label>
                                                        <select id="membership" name="membership" class="form-control">
                                                            <?php
                                                            if (isset($memberships)) {

                                                            
                                                            foreach ($memberships as $key => $value) {
                                                               ?>
                                                               <option class="form-control" value="<?php echo $value->id;?>"><?php echo $value->title;?></option>
                                                               <?php
                                                            }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <button type="submit" class="btn btn-primary glow w-100 position-relative mt-5">Sign Up<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
                                                </form>
                                                <hr>
                                                <div class="text-center"><small class="mr-25">Already have an account?</small><a href="#" onclick="history.back()"><small>Sign in</small> </a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- right section image -->
                                    <div class="col-md-6 d-md-block d-none text-center align-self-center p-3" style="background-color : #f0faf3">
                                        <img class="img-fluid" src="{{url('assets/images/pages/register.png')}}" alt="branding logo">
                                    </div>
                                </div>
                            </div> 
        </div>
    </div>
</div>

@endsection

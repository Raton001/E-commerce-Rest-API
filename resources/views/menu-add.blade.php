@extends('layouts.app')

@section('content')
  
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Menu') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                @include('flash-message')
                    <!-- Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Add Menu</h4>
                                </div>
                                <div class="card-body">
                                     <form action="{{ url('menu/save') }}" method="post" id="listingForm">
                                        @csrf 
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-label-group">
                                                       <fieldset class="form-group">
                                                        <h6>Name</h6>
                                                           
                                                            <input type="text" name="name" class="form-control" placeholder="Menu Name" value="{{old('name') ?? ''}}">
                                                        </fieldset>

                                                     
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-label-group">
                                                        
                                                       <fieldset class="form-group">
                                                        <h6>URL</h6>
                                                            
                                                            <input type="text" name="url" class="form-control" placeholder="Menu URL" value="{{old('url') ?? ''}}">
                                                        </fieldset>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-label-group">
                                                    <h6>Parent</h6>
                                                      

                                                    <div class="form-group">
                                                        <select class="select2-data-array form-control" name="parent_menu" id="select2-array">
                                                            <option value="0">{{ "Select parent" }}</option>
                                                            @foreach ($parent_down as $pd)
                                                                <option value="{{ $pd->id }}">{{ $pd->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-label-group">
                                                        <fieldset>
                                                            <div class="checkbox">
                                                                <input type="checkbox" name="user_role[]" id="checkboxdefault" value="reseller">
                                                                <label for="checkboxdefault">Reseller</label>
                                                            </div>
                                                        </fieldset>
                                                        <fieldset>
                                                            <div class="checkbox">
                                                                <input type="checkbox" name="user_role[]" id="checkboxdefault2" value="me">
                                                                <label for="checkboxdefault2">Marketting Executive (ME)</label>
                                                            </div>
                                                        </fieldset>
                                                        <fieldset>
                                                            <div class="checkbox">
                                                                <input type="checkbox" name="user_role[]" id="checkboxdefault3" value="admin">
                                                                <label for="checkboxdefault3">Admin</label>
                                                            </div>
                                                        </fieldset>
                                                      

                                                        <label for="country-floating">User Role</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-label-group">
                                                    <h6>User ID</h6>

                                                    <div class="form-group">
                                                        <select class="select2-data-array form-control" name="user_id" id="select2-array">
                                                            <option value="">{{ "Select User" }}</option>
                                                            @foreach ($users as $users)
                                                                <option value="{{ $users->id }}">{{ $users->id }} - {{ $users->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    
                                                       
                                                    </div>
                                                </div>
                                                
                                                
                                                <div class="col-12 d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-primary mr-1">Submit</button>
                                                    <button type="reset" class="btn btn-light-secondary">Reset</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Basic multiple Column Form section end -->
                </div>
            </div>
        </div>
    </div>

@endsection



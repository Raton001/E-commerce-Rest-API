@extends('layouts.app')


@section('title')
    {{ $title }}
@endsection

@section('headertitle')
 <h3>Upload Content</h3>
@endsection

@section('content')

<div class="card" style="width:100%;">
    <div class="card-header text-left">

    <form id="myform">

       <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

        <p id="myElem" style="display: none" class="alert alert-secondary" role="alert" >Content has been Upload</p>

        <div class="col-4 col-sm-4 py-2  wow fadeInLeft">
               <label> Title </label>
               <input type="text" name="title" id="title"  class="form-control">
           </div>

           <div class="col-4 col-sm-4 py-2  wow fadeInLeft">
               <label> Author </label>
               <input type="text" name="author" id="author"  class="form-control">
           </div>

           <div class="col-4 col-sm-4 py-2  wow fadeInLeft">
               <label> Description </label>
               <input type="text" name="description" id="description"  class="form-control">
           </div>


           <div class="col-md-12">

            <div class="media">

            </a>
            <div class="media-body mt-25">
                <div class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">
                    <label for="select-files" class="btn btn-sm btn-light-primary ml-50 mb-50 mb-sm-0">
                        <span>Upload Content </span>
                        <input id="select-files" type="file" name="profile_pic" hidden>
                    </label>
                </div>
                <p class="text-muted ml-1 mt-50"><small>Allowed JPG, GIF or PNG. Max
                        size of
                        800kB</small></p>
            </div>
        </div>
    </div>


        <button type="submit" class="btn btn-success mt-3 ml-1 wow zoomIn" id="store" >Save</button>

   </form>


         <div class="mb-2 ml-1 d-flex"  >
               <a href="#">
                   <button type="submit" class="btn btn-primary mt-3 wow zoomIn">Back</button>
           </a>
               <button class="btn btn-warning mt-3 ml-1 wow zoomIn" id="reset">Reset</button>

           </div>
   </div>
   </div>

@endsection

@extends('layouts.app')


@section('title')
    {{ $title }}
@endsection

@section('headertitle')
 <h3>Academy</h3>
@endsection



@section('content')

<div class="my-3 mr-1">
    <a href="{{route('upload.content')}}">
       <button class="btn btn-primary float-right"> Upload Content</button>
    </a>
 </div>




@endsection

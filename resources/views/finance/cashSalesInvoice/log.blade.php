@extends('layouts.app')

@section('assets')

@endsection

@section('headertitle')
 <h3>  Cash Sale Invoice
    <a href="{{route('cash.sales.invoice')}}">
        <button  class="btn btn-primary mt-3 wow zoomIn float-right">Back to List</button>
    </a>
</h3>

@endsection


@section('content')


<div class="row">


@foreach($clog as $data)

<div class="timeline">
    <ul>
      <li>
        <span style="color: #fff;">{{date('d M Y', strtotime($data->action_datetime))}}</span>
        <div class="content">
          <h3>{{$data->title}}</h3>
          <p>
           It has been {{$data->action_type}} by {{ $data->name }} ({{$data->label}}) on {{date('d M Y, g:i A', strtotime($data->action_datetime))}}
          </p>
        </div>
        </li>
      </li>
    </ul>
  </div>
  @endforeach
</div>
@endsection

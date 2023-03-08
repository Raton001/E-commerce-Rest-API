@extends('layouts.app')


@section('title')
    {{ $title }}
@endsection

@section('headertitle')
 <h3> Delivery Order</h3>
@endsection

@section('content')

@if(session()->has('success'))

    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>{{session()->get('success')}}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif



<form id="myform" action="{{route('save-do', $model->uuid)}}" method="post" enctype="multipart/form-data">
  @csrf
    <label class="mb-2 ml-1"> Upload Signed DO </label>
    <input type="hidden" class="form-control" value="{{$model->uuid}}" name="uuid" id="uuid">
    <input type="file"  class="form-control" name="do" required>
    <button type="submit" class="btn btn-primary mt-3 ml-1 wow zoomIn">Save</button>
</form>

  <div class="mb-2 ml-1 d-flex"  >
    <a href="{{route('delivery.order')}}">
        <button type="submit" class="btn btn-primary mt-3 wow zoomIn">Back</button>
   </a>

    <button class="btn btn-warning mt-3 ml-1 wow zoomIn" id="reset">Reset</button>
</div>



<script>

@section('js')



//reset
$('#reset').on('click', function(){
 $('#myform').trigger("reset");
});

@endsection

</script>





@endsection

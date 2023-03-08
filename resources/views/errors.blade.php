@extends('layouts.app')

@section('assets')
    {{Html::style('assets/css/plugins/forms/form-quill-editor.css')}}
    {{Html::style('assets/js/vendors/css/editors/quill/quill.snow.css')}}
    {{Html::style('assets/css/vendors/css/pickers/pickadate/pickadate.css')}}
    {{Html::style('assets/css/vendors/css/pickers/daterange/daterangepicker.css')}}
    {{Html::style('assets/js/vendors/css/extensions/swiper.min.css')}}
    {{Html::style('assets/css/plugins/extensions/swiper.css')}}

@endsection



@section('title')

  Something Not Right 

@endsection



@section('content')
 @if ($message)
 <?php
 foreach ($message as $key => $value) {
  ?>
   <div class="col-12" style="border-left: 5px solid red;text-align: left;padding: 10px;margin-bottom: 15px;">
  {!! $value !!}
 </div>
  <?php
 }

 ?>

      
  @endif
   
@endsection

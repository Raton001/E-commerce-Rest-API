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
    Orders
@endsection

<?php
  if (isset($shopname)) {
  ?>
  @section('shopname')
      <?php echo $shopname;?>
  @endsection
  <?php
  }
?>


@section('title-content')




@endsection

@section('content')

@endsection

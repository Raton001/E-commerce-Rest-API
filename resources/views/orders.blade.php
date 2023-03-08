@extends('layouts.app')

@section('assets')
    {{Html::style('assets/css/plugins/forms/form-quill-editor.css')}}
    {{Html::style('assets/js/vendors/css/editors/quill/quill.snow.css')}}
    {{Html::style('assets/css/vendors/css/pickers/pickadate/pickadate.css')}}
    {{Html::style('assets/css/vendors/css/pickers/daterange/daterangepicker.css')}}
    {{Html::style('assets/js/vendors/css/extensions/swiper.min.css')}}
    {{Html::style('assets/css/plugins/extensions/swiper.css')}}

@endsection

<?php

if ($source == 'shopee') {

  $orderStatus = array_keys($data);
  $view = 'shopee.orders';
  $default = 'READY_TO_SHIP';

} else if ($source == 'lazada'){
  $orderStatus = ['new','completed', 'pending', 'canceled', 'inactive', 'shipped'];
  $view = 'lazada.orders';
  $default = 'completed';


} else {
  $orderStatus = ['Completed', 'Active', 'Cancelled', 'Inactive'];
  $view = 'ebay.orders';
  $default = 'Completed';
}
?>

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
<input type="hidden" id="marketplace" value="<?php echo $source;?>">
<input type="hidden" id="account" value="<?php echo $account;?>">

 <form method="post" action="/shopee/<?php echo $account;?>/orders">
           @csrf
<div class="row">
  <div class="col-md-4">
    <label for="order_ids">Start & End Date</label>

    <fieldset class="form-group position-relative has-icon-left">
        <input type="text" id="start-end-date" name="start-end-date" class="form-control dateranges" placeholder="Select Date">
        <div class="form-control-position">
            <i class='bx bx-calendar-check'></i>
        </div>
    </fieldset>
</div>

<div class="col-md-4">
  <fieldset class="form-group">
      <label for="order_ids">Order ID (s)</label>
      <input type="text" class="form-control" name="order_id" id="order_id" placeholder="Enter Order ID">
  </fieldset>
</div>

<div class="col-md-4">
  <fieldset class="form-group">
      <label for="order_ids"></label>
      <input type="submit" id="search" class="btn btn-secondary mr-1 mb-1 form-control" value="Search">
  </fieldset>
</div>

<div class="col-12"><a class="btn btn-secondary" target="_blank" href="/<?php echo $source;?>/<?php echo $account;?>/shipment-requests"> My Shipment Requests</a></div>
</div>
</form>



@endsection

@section('content')
<div class="card-header">
    <div class="row">
  <div class="col-12">
    <ul id="portfolio-flters" class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">
       <?php

        foreach ($orderStatus as $key => $value) {

          ?>
         <li data-filter=".filter-<?php echo $value;?>"  class="<?php echo ($value==$default ? 'filter-active' :'');?>"><?php echo (ucfirst(strtolower($value)));?></li>
              <?php
        }

       ?>
    </ul>
  </div>
 
  
</div>
</div>
   <div class="card-body">
  
        <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">

         <?php

          foreach ($orderStatus as $key => $value) {
 
            ?>
             <div class="hidden col-lg-12 col-md-12 portfolio-item filter-<?php echo $value;?> <?php echo ($value==$default ? 'filter-active' :'');?>">
              @include($view, ['data'=>(isset($data[$value])? $data[$value]: ''), 'title'=>$value, 'account'=>$account])

            </div>
            <?php
          }
          ?>

        </div>
</div>
@endsection
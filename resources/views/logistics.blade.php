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
    Shipment Requests
@endsection

@section('title-content')


<div class="row">
  <div class="col-md-4">
    <label for="order_ids">Start & End Date</label>

    <fieldset class="form-group position-relative has-icon-left">
        <input type="text" id="start-end-date" class="form-control dateranges" placeholder="Select Date">
        <div class="form-control-position">
            <i class='bx bx-calendar-check'></i>
        </div>
    </fieldset>
</div>

<div class="col-md-4">
  <fieldset class="form-group">
      <label for="order_ids">Order ID (s)</label>
      <input type="text" class="form-control" id="listing_ids" placeholder="Enter Order ID">
  </fieldset>
</div>

<div class="col-md-4">
  <fieldset class="form-group">
      <label for="order_ids"></label>
      <input type="button" id="search" class="btn btn-secondary mr-1 mb-1 form-control" value="Search">
  </fieldset>
</div>

<!-- <div class="col-md-4">
   <fieldset class="form-group">
      <label for=""></label>

   <button type="button" id="search" class="btn btn-secondary mr-1 mb-1">Search</button>
  </fieldset>
   

</div> -->

</div>



@endsection

@section('content')
<div class="card-header">
    <div class="row">
  <div class="col-8">
    <ul id="portfolio-flters" class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">
       <li data-filter=".filter-all"  class="filter-active">All</li>
    </ul>
  </div>
  
</div>
</div>
   <div class="card-body">
  
        <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">

             <div class="hidden col-lg-12 col-md-12 portfolio-item filter-all">
             <div class="table-responsive">
 <table class="table table-striped">
      <thead>
          <tr class="text-left">
            <th></th>
            
              <th>Order ID</th>
              <th>Invoice ID</th>

              <th>Created at</th>
              <th>Created by</th>

       
              <th>Status</th>
              <th>Marketplace</th>
              <th>Shop</th>

          </tr>
      </thead>
      <tbody>
         <form method="post" action="#">
           @csrf
          <?php

            if (!is_array($orders)) {
            ?>
            <tr>
              <td colspan="9">No Record</td>
            </tr>
            <?php
           
           } else {
               
            $count = 0;
            foreach ($orders as $k => $v) {
            $count++;
            ?>
            <tr>
            <td><?php echo $count;?></td>
            <td><?php echo $v->order_id;?></td>
            <td><?php echo $v->invoice_id;?></td>
            <td><?php echo $v->created_at;?></td>
            <td><?php echo $v->created_by;?></td>

            <td>
                <?php 
                if ($invoice->{$v->order_id}) {
                    if ($invoice->{$v->order_id}->invoice_id == $v->invoice_id) {
                        echo $invoice->{$v->order_id}->status;
                    }
                    
                }
                    
                ?>
                </td>
            <td><?php echo $v->marketplace;?></td>
            <td><?php echo $v->shop;?></td>

                </tr>
            <?php
                
           }

       }
           ?>
         
           
          </form>
      </tbody>
  </table>
</div>


            </div>
           

        </div>
</div>
@endsection

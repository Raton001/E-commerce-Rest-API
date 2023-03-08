@extends('layouts.app')

@section('assets')
    

@endsection

<?php
    if ($source == 'shopee') {
      $listingStatus = array_keys($selling);

      $view = 'shopee.listings';
      $default = 'NORMAL';

    } else if ($source == 'lazada'){

    $listingStatus = $data;
      
      $view = 'lazada.listings';
      $default = 'Active';



    } else {
    $listingStatus = ['ActiveList', 'SoldList', 'UnsoldList'];
     
      $view = 'ebay.listings';
      $default = 'Completed';


    }
?>

@section('title')
    Listings
@endsection

@section('title-content')

<input type="hidden" id="marketplace" value="<?php echo $source;?>">
<input type="hidden" id="account" value="<?php echo $account;?>">

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
      <label for="order_ids">Listing ID (s)</label>
      <input type="text" class="form-control" id="listing_ids" placeholder="Enter Listing ID">
  </fieldset>
</div>


<div class="col-md-4">
  <fieldset class="form-group">
      <label for="order_ids"></label>
      <input type="button" id="search" class="btn btn-secondary mr-1 mb-1 form-control" value="Search">
  </fieldset>
</div>

</div>




@endsection

@section('content')
<div class="card-header">
  <div class="row">
  <div class="col-md-12">
    <ul id="portfolio-flters" class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">
       <?php

        foreach ($listingStatus as $key => $value) {
       
          ?>
          <li data-filter=".filter-<?php echo $value;?>"  class="mx-2 <?php echo ($value==$default ? 'filter-active' :'');?>"><?php echo $value;?></li>
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

          foreach ($listingStatus as $key => $value) {
               if ($source == 'shopee') {
                $data = $selling[$value];
               } else if ($source == 'lazada') {
                $data = $selling[$value];
               
               } else {
      $data = (isset($selling[$account][$value]["ItemArray"]["Item"])? $selling[$account][$value]["ItemArray"]["Item"]: []);

               }

            ?>
           
                
            
             <div class="hidden col-lg-12 col-md-12 portfolio-item filter-<?php echo $value;?>" id="listings">
              @include($view, ['data'=>$data, 'title'=>$value, 'account'=>$account])
            </div>
         
            <?php
          }
          ?>
        
        </div>
</div>
@endsection

@section('assets')



@endsection

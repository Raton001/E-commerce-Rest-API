@extends('layouts.app')

@section('content')

<?php
    $listingStatus = ['ActiveList', 'SoldList', 'UnsoldList'];

?>
        <!-- ======= Portfolio Section ======= -->
    <section id="portfolio" class="portfolio">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Listings</h2>
        
          <div class="row">
              <div class="col-md-6">
                <fieldset class="form-group position-relative has-icon-left">
                    <input type="text" id="start-end-date" class="form-control daterange" placeholder="Select Date">
                    <div class="form-control-position">
                        <i class='bx bx-calendar-check'></i>
                    </div>
                </fieldset>
            </div>
          </div>
        </div>

        <ul id="portfolio-flters" class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">
           <?php

            foreach ($listingStatus as $key => $value) {
           
              ?>
              <li data-filter=".filter-<?php echo $value;?>"  class="mx-2 <?php echo ($value=='ActiveList' ? 'filter-active' :'');?>"><?php echo $value;?></li>
              <?php
            }

           ?>
        </ul>

        <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">

          <?php

          foreach ($listingStatus as $key => $value) {
            ?>
             <div class="hidden col-lg-12 col-md-12 portfolio-item filter-<?php echo $value;?>">
              @include('ebay.listings', ['data'=>(isset($selling[$account][$value]["ItemArray"]["Item"])? $selling[$account][$value]["ItemArray"]["Item"]: ''), 'title'=>$value, 'account'=>$account])
            </div>
            <?php
          }
          ?>
         

          

        </div>

      </div>
    </section><!-- End Portfolio Section -->
@endsection

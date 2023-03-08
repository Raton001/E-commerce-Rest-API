@extends('layouts.app')

@section('content')
<!-- <div class="app-content content">
  <div class="content-overlay"></div>
  <div class="content-wrapper">
      <div class="content-header row">
      </div>
      <div class="content-body"> -->
          <section id="">

            <table class="table-bordered">
              <tr>
                <th>Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Type</th>
                <th>Id</th>

              </tr>
              <?php
              if (isset($promotions)) {

              foreach ($promotions as $key => $promotion) {

              ?>
              <tr>
                <td><a href="/ebay/<?php echo $account;?>/promotion/<?php echo $promotion->promotionId;?>"><?php echo $promotion->name;?></a></td>
                <td>
                  <?php 
                  $date=date_create($promotion->startDate);
                  echo date_format($date,"Y/m/d H:i:s");
                  
                  ?>
                    
                </td>
                 <td>
                  <?php 
                  $date=date_create($promotion->endDate);
                  echo date_format($date,"Y/m/d H:i:s");
                  
                  ?>
                    
                </td>
                <td><?php echo $promotion->promotionStatus;?></td>
                <td><?php echo $promotion->promotionType;?></td>
                <td><?php echo $promotion->promotionId;?></td>
                <td>
                  <a href="">Edit</a>
                  <a href="/ebay/<?php echo $account;?>/promotion/delete/<?php echo $promotion->promotionId;?>">Delete</a>

                </td>

      
              </tr>
              <?php
               }
               }
              ?>
            </table>
          </section>
<!--       </div>
  </div>
</div> -->


@endsection

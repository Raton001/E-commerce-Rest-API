@extends('layouts.app')

@section('content')

                <!-- Dashboard Ecommerce Starts -->
                <section id="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Awaiting Shipment') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                      <!-- Basic tabs start -->
                <section id="basic-tabs-components">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h4><?php echo $account;?></h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <table>
                                    <tr>
                                      <th>Image</th>
                                      <th>Title</th>
                                      <th>Qty</th>
                                      <th>Price</th>
                                      <th>Type</th>
                                      <th>Payment</th>
                                      <th>Shipment</th>

                                    </tr>
                                     <?php
                echo "<pre>";
                var_dump($orders);exit;
                                   $entries = $selling['SoldList']['PaginationResult']['TotalNumberOfEntries'];
                                   if ($entries > 0) {

                                    $sold = $selling['SoldList'];

                                   
                                    foreach ($sold as $key => $value) {

                                      if (isset($value['OrderTransaction'])) {
                                 
                                      if (is_array($value['OrderTransaction'])) {
                                        foreach ($value['OrderTransaction'] as $k => $transac) {
                                         
                                    // if (isset($transac['Transaction']['ShippedTime'])) {
                                      ?>
                                      <tr>
                                                <td>
                                                  <?php
                                                  if (sizeof($transac['Transaction']['Item']['PictureDetails'])) {
                                                    foreach ($transac['Transaction']['Item']['PictureDetails'] as $k => $v) {
                                                      
                                                    ?>
                                                   
                                                    <img src="<?php echo $v;?>" style="width: 100px;height: 100px;">
                                                
                                                    <?php
                                                    }
                                                    ?>
                                                    
                                                    <?php
                                                   }
                                                  ?>
                                                </td>
                                                <td>
                                                   <a href="#" class="btn btn-outline-warning" data-toggle="modal" data-target="#full-scrn">
                                          
                                                  <?php echo $transac['Transaction']['Item']['Title'];?>
                                                   </a>
                                                </td>

                                                <td>
                                                  <?php echo (isset($transac['Transaction']['QuantityPurchased'])? $transac['Transaction']['QuantityPurchased'] : '');?>
                                                </td>
                                                <td>
                                                   <?php echo (isset($transac['Transaction']['TotalPrice'])? $transac['Transaction']['TotalPrice'] : '');?>
                                              
                                                </td>
                                                <td>
                                                  <?php echo $transac['Transaction']['Item']['ListingType'];?>
                                                </td>

                                                

                                                <td>
                                                  <?php

                                                  if (isset($transac['Transaction']['PaidTime'])) {
                                                   echo "Paid";
                                                  } else {
                                                    echo "Pending";
                                                  }
                                                  ?>
                                                </td>

                                                <td>
                                                  <?php
                                                  if (isset($transac['Transaction']['ShippedTime'])) {
                                                   echo "Shipped";
                                                  } else {
                                                    echo "Pending";
                                                  }
                                                  ?>
                                                </td>
                                                <td>
                                                   <a href="#" class="btn btn-outline-warning" data-toggle="modal" data-target="#full-scrn">view</a>
                                                </td>
                                                <td>
                                                  <a href="/ebay/<?php echo $account;?>/order/ship/<?php echo $transac['Transaction']['OrderLineItemID'];?>">ship now</a>
                                                </td>
                                              </tr>
                                       <tr>
                                        
                                        <!-- full size modal-->
                                        <div class="modal fade text-left w-100" id="full-scrn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel20" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myModalLabel20">Full Screen Modal</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <i class="bx bx-x"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php
                                                        echo '<pre>';
                                                        var_dump($transac['Transaction']);
                                                        echo "</pre>";
                                                        
                                                        echo '<pre>';
                                                        var_dump($transac['Transaction']['Item']);
                                                        echo "</pre>";

                                                        echo '<pre>';
                                                        var_dump($transac['Transaction']['Buyer']);
                                                        echo "</pre>";

                                                        echo '<pre>';
                                                        if (isset($transac['Transaction']['ShippingDetails'])) {
                                                        var_dump($transac['Transaction']['ShippingDetails']);

                                                        }
                                                        echo "</pre>";

                                                        
                                                        ?>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                                            <i class="bx bx-x d-block d-sm-none"></i>
                                                            <span class="d-none d-sm-block">Close</span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary ml-1" data-dismiss="modal">
                                                            <i class="bx bx-check d-block d-sm-none"></i>
                                                            <span class="d-none d-sm-block">Accept</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                       
                                      </tr>
                                      <?php
                                    // }
                                      }
                                      }
                                    }

                                    }
                                    ?>
                                   
                                    <?php
                                   } else {
                                    echo "one entry";
                                   }
                                    ?>
                                  </table>
                        </div>
                      </div>


                </section>
                <!-- Basic Tag Input end -->
                </div>
            </div>
        </div>
    </div>


   

</div>
</section>



@endsection

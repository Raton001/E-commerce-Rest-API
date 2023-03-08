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
                                <input type="hidden" id ="account" value="<?php echo $account;?>">
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table-bordered">
                                    <tr>
                                      <!-- <th>Image</th> -->
                                      <th>Title</th>
                                      <th>Condition</th>
                                      <th>Qty</th>
                                      <th>Price</th>
                                     <!--  <th>Type</th> -->
                                      <th>Payment</th>
                                      <th>Shipment</th>
                                      <th>In house Status</th>

                                    </tr>
                                     <?php

                                    if ($orders['PaginationResult']['TotalNumberOfEntries'] == 1) {
                                      echo "one";
                                    } else {
                                  
                                  // echo "<pre>";


                                      foreach ($orders['OrderArray']['Order'] as $key => $order) {
                                        // var_dump($order);
                                       
                                      if (!isset($order['ShippedTime'])) {
                                        ?>
                                        <tr>
                                          <!-- <td></td> -->
                                          <td>
                                            
                                            <a target="_blank" href="https://www.ebay.com/itm/<?php echo $order['TransactionArray']['Transaction']['Item']['ItemID'];?>">
                                            <?php
                                           echo $order['TransactionArray']['Transaction']['Item']['Title'];
                                           echo "<br/>";
                                           echo $order['OrderID'];
                                           echo "<br/>";
                                           echo $order['TransactionArray']['Transaction']['OrderLineItemID'];
                                           
                                            ?>
                                          </a>
                                          </td>
                                          <td>
                                            <?php echo $order['TransactionArray']['Transaction']['Item']['ConditionDisplayName'];?>
                                          </td>
                                          <td>
                                             <?php echo $order['TransactionArray']['Transaction']['QuantityPurchased'];?>
                                            
                                          </td>
                                          <td>
                                            <?php
                                            echo "$".$order['AmountPaid'];
                                            ?>
                                          </td>
                                          <!-- <td></td> -->
                                          <td>
                                            
                                            <?php echo $order['CheckoutStatus']['PaymentMethod'];?>
                                          </td>
                                          <td>
                                            <a href="/ebay/<?php echo $account;?>/order/ship/<?php echo $order['OrderID'];?>">ship now</a>
                                          </td>
                                          <td>
                                            <?php

                                            if (in_array($order['OrderID'], array_column($tracking, 'order_id'))) {

                                              $arrKey = array_search($order['OrderID'], array_column($tracking, 'order_id'));
                                              
                                              //completed
                                       

                                              $shipmentStatus = $tracking[$arrKey]->shipment_status;
                                              $invoiceID = $tracking[$arrKey]->id;
                                              $trackingCode = $tracking[$arrKey]->tracking_code;
                                              $orderID = $tracking[$arrKey]->order_id;

                                              $itemID = $order['TransactionArray']['Transaction']['Item']['ItemID'];

                                              if ($shipmentStatus != 'completed') {
                                                echo $shipmentStatus;
                                              } else {
                                                 echo $shipmentStatus;

                                                 $oLine = $order['TransactionArray']['Transaction']['OrderLineItemID'];
                                                ?>
                                                <br/>
                                                <a href="#" data-update-tracking>
                                                  <span data-tracking="<?php echo $trackingCode;?>" data-invoice="<?php echo $invoiceID;?>" data-order="<?php echo $orderID;?>" data-oline = "<?php echo $oLine;?>" data-item="<?php echo $itemID;?>">Update Tracking code</span></a>
                                                <?php
                                              }
                                              
                                            }
                                            ?>
                                          </td>

                                        </tr>
                                        <?php
                                      }
                                      }
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

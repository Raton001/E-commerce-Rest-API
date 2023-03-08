@extends('layouts.app')

@section('content')

    <!-- DISPLAY ACTIVE UNSOLD LISTING -->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <section id="active-unsold-table">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h4>Awaiting Shipment</h4>
                                <h6>{{ $account }}</h6>
                            </div>
                        </div>
                        <div class="card-body card-dashboard">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <input type="hidden" id="account"
                                value="<?php echo $account; ?>">

                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item  ml-3">
                                    <a class="nav-link active" id="allorder-tab" data-toggle="tab" href="#allorder"
                                        aria-controls="allorder" role="tab" aria-selected="true">
                                        <i class="bx bx-home align-middle"></i>
                                        <span class="align-middle">All Order</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending"
                                        aria-controls="pending" role="tab" aria-selected="false">
                                        <i class="bx bx-user align-middle"></i>
                                        <span class="align-middle">Pending Shipment</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="allorder" aria-labelledby="allorder-tab" role="tabpanel">
                                     <div class="table-responsive">
                                        <table
                                                                class="table mb-4 table-bordered table-striped table-hover">
                                                                <thead class="thead-light">
                                                                    <tr>
                                                                        <th class="text-center" width="10%">Order ID</th>
                                                                        <th class="text-center" width="20%">Title</th>
                                                                        <th class="text-center" width="15%">Order Line ID
                                                                        </th>
                                                                        <th width="7%">Condition</th>
                                                                        <th width="4%">Quatity</th>
                                                                        <th width="9%">Price</th>
                                                                        <th width="8%">Payment</th>
                                                                        <th width="10%">Shipment</th>
                                                                        <th class="text-center" width="17%">In House Status
                                                                        </th>
                                                                    </tr>
                                                                </thead>

                                            @if (!isset($selling['SoldList']['PaginationResult']['TotalNumberOfEntries']))

                                                <div class="mt-5 text-center">
                                                    <h3>NO ORDER</h3>
                                                </div>

                                            @else
                                                @if ($selling['SoldList']['PaginationResult']['TotalNumberOfEntries'])
                                                @php
                                                 $orders = $selling['SoldList']['OrderTransactionArray']['OrderTransaction']
                                                
                                              
                                                 @endphp

                                                      

                                                    @foreach ($orders as $key => $value)
                                                     @if (isset($value['Order']))
                                                     @php
                                                      $order = $value['Order'];
                                                    
                                                      @endphp
                                                    


                                                        
                                                          
                                                                <tbody>

                                                                  @foreach ($order['TransactionArray'] as $k => $v)
                                                                  
                                                                   
                                                                  @php
                                                       
                                                                  for ($i = 0; $i < sizeof($v); $i++) {
                                                                 
                                                                  @endphp
                                                                   @if (isset($v[$i]['ShippedTime']))
                                                                  <tr>
                                                                  <td class="text-center">{{ $order['OrderID'] }}</td>
                                                                  <td>{{ $v[$i]['Item']['Title'] }}</td>
                                                                   <td class="text-center">
                                                                        {{ $v[$i]['OrderLineItemID'] }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ $v[$i]['QuantityPurchased'] }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                      $ {{ $v[$i]['Item']['BuyItNowPrice'] }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                     {{ $v[$i]['SellerPaidStatus'] }}
                                                                    </td>
                                                                    <td class="text-center">

                                                                      <a href="/ebay/{{ $account }}/order/ship/{{ $order['OrderID'] }}/{{ $v[$i]['Item']['ItemID'] }}" data-item-id="<?php echo $v[$i]['Item']['ItemID'];?>">Ship Now</a></td>
                                                                    <td>

                                                                        <?php if (in_array($order['OrderID'],
                                                                        array_column($tracking, 'order_id'))) {

                                                                        $arrKey = array_search($order['OrderID'],
                                                                        array_column($tracking, 'order_id'));
                                                                        $shipmentStatus = $tracking[$arrKey]->shipment_status;
                                                                        ?>
                                                                        <span class='font-weight-bold text-uppercase'>
                                                                          <?php
                                                                          echo $shipmentStatus;
                                                                          ?>
                                                                        </span><br>
                                                                        <?php 
                                                                       $oLine = $v[$i]['OrderLineItemID'];
                                                                        ?>
                                                                        <a href="#" data-update-tracking>
                                                                            <span
                                                                                data-tracking="<?php echo $trackingCode; ?>"
                                                                                data-invoice="<?php echo $invoiceID; ?>"
                                                                                data-order="<?php echo $orderID; ?>"
                                                                                data-oline="<?php echo $oLine; ?>"
                                                                                data-item="<?php echo $itemID; ?>">Update
                                                                                Tracking code</span>
                                                                        </a>
                                                                    </td>
                                                                     <?php
                                                                    } ?>
                                                                  </tr>
                                                                  @endif
                                                                  @php
                                                                  }
                                                                  @endphp
                                                               
                                                                   
                                                                  
                                                                
                                                                  @endforeach


                                                                  
                                                                </tbody>
                                                            </table>
                                                        @elseif (isset($value))
                                                          <table
                                                                class="table mb-4 table-bordered table-striped table-hover">
                                                                <thead class="thead-light">
                                                                    <tr>
                                                                        <th class="text-center" width="10%">Order ID</th>
                                                                        <th class="text-center" width="20%">Title</th>
                                                                        <th class="text-center" width="15%">Order Line ID
                                                                        </th>
                                                                        <th width="7%">Condition</th>
                                                                        <th width="4%">Quatity</th>
                                                                        <th width="9%">Price</th>
                                                                        <th width="8%">Payment</th>
                                                                        <th width="10%">Shipment</th>
                                                                        <th class="text-center" width="17%">In House Status
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                 
                                                                   @if (isset($value['Transaction']['ShippedTime']))
                                                                  <tr>
                                                                   <td class="text-center">
                                                                     <?php
                                                                     if (isset($value['OrderID'])) {
                                                                      echo $value['OrderID'];
                                                                     } else {
                                                                      echo "no order id";
                                                                     }
                                                                     ?>
                                                                   </td>

                                                                  <td>{{ $value['Transaction']['Item']['Title'] }}</td>
                                                                   <td class="text-center">
                                                                        {{ $value['Transaction']['OrderLineItemID'] }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ $value['Transaction']['QuantityPurchased'] }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                      $ {{ $value['Transaction']['Item']['BuyItNowPrice'] }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                     {{ $value['Transaction']['SellerPaidStatus'] }}
                                                                    </td>
                                                                    <td class="text-center">

                                                                      <a href="/ebay/{{ $account }}/order/ship/{{ $value['Transaction']['OrderLineItemID'] }}/{{ $value['Transaction']['Item']['ItemID'] }}" data-item-id="<?php echo $value['Transaction']['Item']['ItemID'];?>">Ship Now</a></td>
                                                                    <td>

                                                                        <?php 
                                                                        if (isset($value['OrderID'])) {

                                                                        
                                                                        if (in_array($value['OrderID'],
                                                                        array_column($tracking, 'order_id'))) {

                                                                        $arrKey = array_search($value['OrderID'],
                                                                        array_column($tracking, 'order_id'));
                                                                        $shipmentStatus = $tracking[$arrKey]->shipment_status;
                                                                        ?>
                                                                        <span class='font-weight-bold text-uppercase'>
                                                                          <?php
                                                                          echo $shipmentStatus;
                                                                          ?>
                                                                        </span><br>
                                                                        <?php 
                                                                       $oLine = $value['Transaction']['OrderLineItemID'];
                                                                        ?>
                                                                        <a href="#" data-update-tracking>
                                                                            <span
                                                                                data-tracking="<?php echo $trackingCode; ?>"
                                                                                data-invoice="<?php echo $invoiceID; ?>"
                                                                                data-order="<?php echo $orderID; ?>"
                                                                                data-oline="<?php echo $oLine; ?>"
                                                                                data-item="<?php echo $itemID; ?>">Update
                                                                                Tracking code</span>
                                                                        </a>
                                                                    </td>
                                                                     <?php
                                                                    }
                                                                    } ?>
                                                                  </tr>
                                                                  @endif
                                                                  
                                                                   
                                                                  


                                                                  
                                                                </tbody>
                                                        @else
                                                            <div class="mt-5 text-center">
                                                                <h3>NO ORDER</h3>
                                                            </div>
                                                        @endif
                                                        

                                                    @endforeach
                                                @endif
                                            @endif
                                                            </table>

                                      </div>
                                </div>
                                <div class="tab-pane" id="pending" aria-labelledby="pending-tab" role="tabpanel">
                                    <div class="table-responsive">
                                        <div class="table-responsive">
                                           <table
                                                                class="table mb-4 table-bordered table-striped table-hover">
                                                                <thead class="thead-light">
                                                                    <tr>
                                                                        <th class="text-center" width="10%">Order ID</th>
                                                                        <th class="text-center" width="20%">Title</th>
                                                                        <th class="text-center" width="15%">Order Line ID
                                                                        </th>
                                                                        <th width="7%">Condition</th>
                                                                        <th width="4%">Quatity</th>
                                                                        <th width="9%">Price</th>
                                                                        <th width="8%">Payment</th>
                                                                        <th width="10%">Shipment</th>
                                                                        <th class="text-center" width="17%">In House Status
                                                                        </th>
                                                                    </tr>
                                                                </thead>

                                            @if (!isset($selling['SoldList']['PaginationResult']['TotalNumberOfEntries']))

                                                <div class="mt-5 text-center">
                                                    <h3>NO PENDING SHIPMENT</h3>
                                                </div>

                                            @else
                                                @if ($selling['SoldList']['PaginationResult']['TotalNumberOfEntries'])
                                                @php
                                                 $orders = $selling['SoldList']['OrderTransactionArray']['OrderTransaction']
                                                

                                                 @endphp

                                                      
                                              
                                                    @foreach ($orders as $key => $value)
                                                   
                                                     @if (isset($value['Order']))
                                                     @php
                                                      $order = $value['Order'];
                                                    
                                                      @endphp
                                                    
                                                           
                                                                <tbody>
                                                                  
                                                                  @foreach ($order['TransactionArray'] as $k => $v)
                                                  
                                                                    
                                                                  @php
                                                       
                                                                  for ($i = 0; $i < sizeof($v); $i++) {
                                                                 
                                                                  @endphp
                                                                  @if (!isset($v[$i]['ShippedTime']))
                                                                  <tr>
                                                                  <td class="text-center">{{ $order['OrderID'] }}</td>
                                                                  <td>{{ $v[$i]['Item']['Title'] }}</td>
                                                                   <td class="text-center">
                                                                        {{ $v[$i]['OrderLineItemID'] }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ $v[$i]['QuantityPurchased'] }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                      $ {{ $v[$i]['Item']['BuyItNowPrice'] }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                     {{ $v[$i]['SellerPaidStatus'] }}
                                                                    </td>
                                                                    <td class="text-center">

                                                                      <a href="/ebay/{{ $account }}/order/ship/{{ $order['OrderID'] }}/{{ $v[$i]['Item']['ItemID'] }}" data-item-id="<?php echo $v[$i]['Item']['ItemID'];?>">Ship Now</a></td>
                                                                    <td>

                                                                        <?php if (in_array($order['OrderID'],
                                                                        array_column($tracking, 'order_id'))) {

                                                                        $arrKey = array_search($order['OrderID'],
                                                                        array_column($tracking, 'order_id'));
                                                                        $shipmentStatus = $tracking[$arrKey]->shipment_status;
                                                                        ?>
                                                                        <span class='font-weight-bold text-uppercase'>
                                                                          <?php
                                                                          echo $shipmentStatus;
                                                                          ?>
                                                                        </span><br>
                                                                        <?php 
                                                                       $oLine = $v[$i]['OrderLineItemID'];
                                                                        ?>
                                                                        <a href="#" data-update-tracking>
                                                                            <span
                                                                                data-tracking="<?php echo $trackingCode; ?>"
                                                                                data-invoice="<?php echo $invoiceID; ?>"
                                                                                data-order="<?php echo $orderID; ?>"
                                                                                data-oline="<?php echo $oLine; ?>"
                                                                                data-item="<?php echo $itemID; ?>">Update
                                                                                Tracking code</span>
                                                                        </a>
                                                                    </td>
                                                                     <?php
                                                                    } ?>
                                                                  </tr>
                                                                   @endif
                                                                  @php
                                                                  }
                                                                  @endphp
                                                               
                                                                   
                                                                  
                                                               
                                                                  @endforeach


                                                                  
                                                                </tbody>
                                                            <!-- </table> -->
                                                        @elseif (isset($value))


                                                          
                                                                <tbody>

                                                                 
                                                                   @if (!isset($value['Transaction']['ShippedTime']))
                                                                  <tr>
                                                                   <td class="text-center">
                                                                     <?php
                                                                     if (isset($value['OrderID'])) {
                                                                      echo $value['OrderID'];
                                                                     } else {
                                                                      echo "no order id";
                                                                     }


                                                                     ?>
                                                                   </td>
                                                                    <?php
                                                                    if (isset($value['Transaction'])) {

                                                                    
                                                                    ?>
                                                                  <td>{{ $value['Transaction']['Item']['Title'] }}</td>
                                                                   <td class="text-center">
                                                                        {{ $value['Transaction']['OrderLineItemID'] }}
                                                                    </td>
                                                                    <td></td>
                                                                    <td class="text-center">
                                                                        {{ $value['Transaction']['QuantityPurchased'] }}
                                                                    </td>
                                                                    
                                                                    <td class="text-center">
                                                                      $ {{ $value['Transaction']['TotalPrice'] }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                     {{ $value['Transaction']['SellerPaidStatus'] }}
                                                                    </td>
                                                                    <td class="text-center">
<a href="/ebay/{{ $account }}/order/ship2/{{ $value['Transaction']['OrderLineItemID'] }}/{{ $value['Transaction']['Item']['ItemID'] }}" data-item-id="<?php echo $value['Transaction']['Item']['ItemID'];?>">Ship Now</a>
                                                                      </td>
                                                                    <td>

                                                                    <?php if (in_array($order['OrderID'],
                                                                    array_column($tracking, 'order_id'))) {

                                                                    $arrKey = array_search($order['OrderID'],
                                                                    array_column($tracking, 'order_id'));
                                                                    $shipmentStatus = $tracking[$arrKey]->shipment_status;
                                                                    ?>
                                                                    <span class='font-weight-bold text-uppercase'>
                                                                      <?php
                                                                      echo $shipmentStatus;
                                                                      ?>
                                                                    </span><br>
                                                                    <?php 
                                                                   $oLine = $value['Transaction']['OrderLineItemID'];
                                                                    ?>
                                                                    <a href="#" data-update-tracking>
                                                                        <span
                                                                            data-tracking="<?php echo $trackingCode; ?>"
                                                                            data-invoice="<?php echo $invoiceID; ?>"
                                                                            data-order="<?php echo $orderID; ?>"
                                                                            data-oline="<?php echo $oLine; ?>"
                                                                            data-item="<?php echo $itemID; ?>">Update
                                                                            Tracking code</span>
                                                                        </a>
                                                                    </td>
                                                                     <?php
                                                                    }} ?>
                                                                  </tr>
                                                                  @endif
                                                                  
                                                                   
                                                                  


                                                                  
                                                                </tbody>
                                                            
                                                        @else
                                                            <div class="mt-5 text-center">
                                                                <h3>NO PENDING SHIPMENT</h3>
                                                            </div>
                                                        @endif
                                                        

                                                    @endforeach
                                                @endif
                                            @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END DISPLAY ACTIVE UNSOLD LISTING -->

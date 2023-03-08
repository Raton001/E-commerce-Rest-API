
<?php 
 
$custDetails= [];
$salesDetails = [];

$custDetails = $v['ShippingAddress'];
$custId = $v['BuyerUserID'];
$salesDetails = $v;
$totalTax = $v['TransactionArray']['Transaction']['Taxes']['TotalTaxAmount'];
$shippingDetails = $v['TransactionArray']['Transaction']['ShippingDetails']['ShipmentTrackingDetails'];
$hand_ship = $v['TransactionArray']['Transaction']['Taxes']['TaxDetails'];

$address = $custDetails['Street1'].", ";
$postalCode = $custDetails['CityName'].", ".$custDetails['PostalCode'].", ";
$country = $custDetails['StateOrProvince'].", ".$custDetails['Country'];

// ===========================================================================================

$totalTaxes = 0.0;
$totalAllAmount = 0.0;

$order1 = $v['TransactionArray'];
$transaction = $order1['Transaction'];
$i = 1;


?>

<div class="modal fade" id="order_<?php echo $v['OrderID'];?>" tabindex="-1" role="dialog" aria-labelledby="order_<?php echo $v['OrderID'];?>Label" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="order_<?php echo $v['OrderID'];?>Label">Order #<?php echo $v['OrderID'];?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          {{-- CUSTOMER DETAILS --}}
          <div class="col-md-4 col-sm-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Customer Details </h4>
                <a class="heading-elements-toggle">
                  <i class="bx bx-dots-vertical font-medium-3"></i>
                </a>
                  {{-- Detail Start Here --}}
                  <div class="heading-elements">
                    <ul class="list-inline mb-0">
                      <li>
                        <a data-action="collapse">
                          <i class="bx bx-chevron-down"></i>
                        </a>
                      </li>
                    </ul>
                  </div>

              </div>
              <div class="card-content collapse show">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>First Name</label>
                        </div>
                        <div class="col-md-8 form-group" style>
                            {{ $custDetails['Name'] }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Address</label>
                        </div>
                        <div class="col-md-8 form-group">
                            {{ $address }}<br>{{ $postalCode }}<br>{{ $country }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Customer ID</label>
                        </div>
                        <div class="col-md-8 form-group">
                            {{ $custId }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Phone Contact</label>
                        </div>
                        <div class="col-md-8 form-group">
                            {{ $custDetails['Phone'] }}
                        </div>     
                    </div>
                </div>
            </div>
            </div>
          </div>
          {{-- SALE DETAILS --}}
          <div class="col-md-4 col-sm-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Sales Details </h4>
                <a class="heading-elements-toggle">
                  <i class="bx bx-dots-vertical font-medium-3"></i>
                </a>
                  {{-- Detail Start Here --}}
                  <div class="heading-elements">
                    <ul class="list-inline mb-0">
                      <li>
                        <a data-action="collapse">
                          <i class="bx bx-chevron-down"></i>
                        </a>
                      </li>
                    </ul>
                  </div>

              </div>
              <div class="card-content collapse show">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Order ID</label>
                        </div>
                        <div class="col-md-8 form-group" style>
                            {{ $salesDetails['OrderID'] }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Amount Paid</label>
                        </div>
                        <div class="col-md-8 form-group" style>
                            USD {{ number_format((double)$salesDetails['AmountPaid'], 2, '.', '') }}<br>
                            [Subtotal] USD {{ number_format((double)$salesDetails['Subtotal'], 2, '.', '') }}<br>
                            [Taxes] USD {{ number_format((double)$totalTax, 2, '.', '') }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Paid Date</label>
                        </div>
                        <div class="col-md-8 form-group" style>
                            {{ date('d-m-Y H:i:s', strtotime($salesDetails['PaidTime'])) }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Shipped Date</label>
                        </div>
                        <div class="col-md-8 form-group" style>
                            {{ date('d-m-Y H:i:s', strtotime($salesDetails['ShippedTime'])) ?? "Not Ship Yet" }}
                        </div>     
                    </div>
                </div>
            </div>
            </div>
          </div>
          {{-- SHIPPING DETAILS --}}
          <div class="col-md-4 col-sm-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Shipping Details </h4>
                <a class="heading-elements-toggle">
                  <i class="bx bx-dots-vertical font-medium-3"></i>
                </a>
                  {{-- Detail Start Here --}}
                  <div class="heading-elements">
                    <ul class="list-inline mb-0">
                      <li>
                        <a data-action="collapse">
                          <i class="bx bx-chevron-down"></i>
                        </a>
                      </li>
                    </ul>
                  </div>

              </div>
              <div class="card-content collapse show">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <label>Status</label>
                        </div>
                        <div class="col-md-7 form-group" style>
                        @if($salesDetails['ShippedTime'])
                            Shipped
                        @else
                            Item Not Ship Out
                        @endif
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label>Tracking Number</label>
                        </div>
                        <div class="col-md-7 form-group" style>
                            {{ $shippingDetails['ShipmentTrackingNumber'] }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label>Carrier Used</label>
                        </div>
                        <div class="col-md-7 form-group" style>
                            {{ $shippingDetails['ShippingCarrierUsed'] }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label>Payment</label>
                        </div>
                        <div class="col-md-7 form-group" style>
                        <?php
                            $extra_payment = $hand_ship['TaxOnShippingAmount'] + $hand_ship['TaxOnHandlingAmount'];
                        ?>  
                        @if($extra_payment <= 0)
                            FREE<br>
                        @else
                            USD {{ number_format((double)$extra_payment, 2, '.', '') }}<br>
                        @endif
                            
                            [Shipping] USD {{ number_format((double)$hand_ship['TaxOnShippingAmount'], 2, '.', '') }}<br>
                            [Handling] USD {{ number_format((double)$hand_ship['TaxOnHandlingAmount'], 2, '.', '') }}
                        </div>     
                    </div>
                </div>
            </div>
            </div>
          </div>

        </div>

        {{-- TABLE ORDER ITEM DETAILS --}}
        <div class="users-list-table">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="users-list-datatable">
                  <thead>
                    <tr class="text-center">
                      <th>#</th>
                      <th>SKU</th>
                      <th width="40%">Item Details</th>
                      <th>Quantity</th>
                      <th>Unit Price (USD)</th>
                      <th>Tax (USD)</th>
                      <th>Amount (USD)</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($order1 as $key1 => $value1)
                        @php
                          $item = $transaction['Item'];
                          $totalAmount = $transaction['QuantityPurchased'] * $transaction['TransactionPrice'];
                        @endphp

                        <tr>
                          <td class="text-center">{{ $i }}</td>
                          <td>{{ $item['ItemID'] }}</td>
                          <td>{{ $item['Title'] }}</td>
                          <td class="text-center">{{ $transaction['QuantityPurchased'] }}</td>
                          <td class="text-right pr-1">{{ number_format((double)$transaction['TransactionPrice'], 2, '.', '') }}</td>
                          <td class="text-right pr-1">{{ number_format((double)$transaction['Taxes']['TotalTaxAmount'], 2, '.', '') }}</td>
                          <td class="text-right pr-1">{{ number_format((double)$totalAmount, 2, '.', '') }}</td>
                        </tr>

                        @php
                          $totalTaxes += $transaction['Taxes']['TotalTaxAmount'];
                          $totalAllAmount += $totalAmount;
                          $i++;
                      @endphp

                    @endforeach

                    <tr>
                      <td colspan="5" class="table-active"></td>
                      <td class="text-right pr-1">Total Taxes : USD {{ number_format((double)$totalTaxes, 2, '.', '') }}</td>
                      <td class="text-right pr-1">Total Taxes : USD {{ number_format((double)$totalAllAmount, 2, '.', '') }}</td>
                    </tr>

                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>
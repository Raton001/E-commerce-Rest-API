<?php 

$customer_details = $v->recipient_address;
$currency = $v->currency;

$items = json_decode(json_encode($v->items), true);
$i = 0;
$totalAll = 0;
?>

<div class="modal fade" id="order_<?php echo $v->ordersn;?>" tabindex="-1" role="dialog" aria-labelledby="order_<?php echo $v->ordersn;?>Label" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="order_<?php echo $v->ordersn;?>Label">Order #<?php echo $v->ordersn;?></h5>
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
                            {{ $customer_details->name }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Address</label>
                        </div>
                        <div class="col-md-8 form-group">
                            {{ $customer_details->full_address }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Customer ID</label>
                        </div>
                        <div class="col-md-8 form-group">
                          {{ $v->buyer_username }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Phone Contact</label>
                        </div>
                        <div class="col-md-8 form-group">
                          +{{ $customer_details->phone }}
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
                            {{ $v->ordersn }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Amount Paid</label>
                        </div>
                        <div class="col-md-8 form-group" style>
                            {{ $currency. " " .number_format((double)$v->total_amount, 2, '.', '') }}<br>
                            {{ $currency. " " .number_format((double)$v->escrow_amount, 2, '.', '') }} [ Esrow ] <br>
                            {{ $currency. " " .number_format((double)$v->estimated_shipping_fee, 2, '.', '') }} [Ship. Fee]
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Paid Date</label>
                        </div>
                        <div class="col-md-8 form-group" style>
                            {{ date('d-m-Y H:i:s A', $v->pay_time) }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Shipped<br>Date</label>
                        </div>
                        <div class="col-md-8 form-group" style>
                            {{ date('d-m-Y H:i:s A', $v->ship_by_date) ?? "Not Ship Yet" }}
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
                        <div class="col-md-7 form-group text-capitalize" style>
                          {{ $v->order_status }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label>Tracking Number</label>
                        </div>
                        <div class="col-md-7 form-group" style>
                            {{ $v->tracking_no }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label>Carrier Used</label>
                        </div>
                        <div class="col-md-7 form-group" style>
                            {{ $v->shipping_carrier }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label>Payment</label>
                        </div>
                        <div class="col-md-7 form-group" style>
                          {{ $v->payment_method }}<br>
                          @if (isset($v->airway_bill))
                              <a href="{{ $v->airway_bill }}" class="btn btn-primary btn-sm btn-block" role="button" target="_blank">View Airway Bill</a>
                          @endif
                          
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
                        <th width="45%">Item Details</th>
                        <th>Quantity</th>
                        <th>Unit Price ({{ $currency }})</th>
                        {{-- <th>Tax (USD)</th> --}}
                        <th>Amount ({{ $currency }})</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($items as $item)
                          @php
                            $total_amount = $item['variation_quantity_purchased'] * $item['variation_original_price'];
                          @endphp

                          <tr>
                            <td class="text-center">{{ $i+1 }}</td>
                            <td class="text-center">{{ $item['item_sku'] }}</td>
                            <td>{{ $item['item_name'] }}</td>
                            <td class="text-center">{{ $item['variation_quantity_purchased'] }}</td>
                            <td class="text-right pr-1">{{ number_format((double)$item['variation_original_price'], 2, '.', '') }}</td>
                            <td class="text-right pr-1">{{ number_format((double)$total_amount, 2, '.', '') }}</td>
                            
                          </tr>

                          @php
                            $totalAll += $total_amount;
                            $i++;
                        @endphp

                      @endforeach

                      <tr>
                        <td colspan="5" class="table-active"></td>
                        <td class="text-right pr-1">Total : {{ $currency }} {{ number_format((double)$totalAll, 2, '.', '') }}</td>
                      </tr>
                      

                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>


        </div>
      	<?php
      	?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>
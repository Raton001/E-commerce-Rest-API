<?php 

  $customer_details = $v['address_shipping'];

  $address = $customer_details['address1'].", ".$customer_details['address2'];
  $post_code = $customer_details['post_code'].", ".$customer_details['city'];
  $state = $customer_details['address3'];


?>

<div class="modal fade" id="order_<?php echo $v['order_id'];?>" tabindex="-1" role="dialog" aria-labelledby="order_<?php echo $v['order_id'];?>Label" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="order_<?php echo $v['order_id'];?>Label">Order #<?php echo $v['order_id'];?></h5>
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
                            {{ $customer_details['first_name'] }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Address</label>
                        </div>
                        <div class="col-md-8 form-group">
                          {{ $address }}<br>{{ $post_code }}<br>{{ $state }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Customer ID</label>
                        </div>
                        <div class="col-md-8 form-group">
                          {{ $v['customer_first_name'] }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Phone Contact</label>
                        </div>
                        <div class="col-md-8 form-group">
                          +{{ $customer_details['phone'] }}
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
                            {{ $v['order_number'] }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Amount Paid</label>
                        </div>
                        <div class="col-md-8 form-group" style>
                          @php
                            $paid = $v['price'] - $v['voucher'];
                          @endphp
                            [PAID] MYR {{ number_format((double)$paid, 2, '.', '') }}<br>
                            [TOTAL] MYR {{ number_format((double)$v['price'], 2, '.', '') }}<br>
                            [DISCOUNT] MYR {{ number_format((double)$v['voucher'], 2, '.', '') }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Paid Date</label>
                        </div>
                        <div class="col-md-8 form-group" style>
                            {{ $v['created_at'] }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Shipped Date</label>
                        </div>
                        <div class="col-md-8 form-group" style>
                            {{ $v['updated_at'] ?? "Not Ship Yet" }}
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
                        <div class="col-md-7 form-group text-uppercase" style>
                          {{ $v['statuses'][0] }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label>Tracking Number</label>
                        </div>
                        <div class="col-md-7 form-group" style>
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label>Carrier Used</label>
                        </div>
                        <div class="col-md-7 form-group" style>
                          {{ $v['warehouse_code'] }}
                        </div>     
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label>Payment</label>
                        </div>
                        <div class="col-md-7 form-group" style>
                          {{ $v['payment_method'] }}
                        </div>     
                    </div>
                </div>
            </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
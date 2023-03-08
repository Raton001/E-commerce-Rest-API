@extends('layouts.app')

@section('content')
   
                <!-- Dashboard Ecommerce Starts -->
                <section id="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                
                <div class="row">
                  <div class="col-md-6">
                    {{ __('Configure Calculator') }}
                  </div>
                  <div class="col-md-6">
                   
                  </div>
                </div>
              </div>

                <div class="card-body">
                  <?php 
                  $url = 'ebay/config/calculator/'.$account;
                  ?>
                 <form action="{{ url($url) }}" method="post" id="listingForm">

                        @csrf 
                    <table>
                      

                       <tr>
                        <th>Fuel Surcharge</th>
                        <td>
                          <div class="form-group">
                            <input type="text" name="fuel_surcharge" class="form-control" value="<?php echo (isset($config['fuel_surcharge']) ? $config['fuel_surcharge'] : '');?>">
                          </div>
                        </td>
                      </tr>


                      <tr>
                        <th>Conversion Rate</th>
                        <td>
                          <div class="form-group">
                            <input type="text" name="conversion_rate" class="form-control" value="<?php echo (isset($config['conversion_rate']) ? $config['conversion_rate'] : '');?>">
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <th>Foreign Currency Rate</th>
                        <td>
                          <div class="form-group">
                            <input type="text" name="foreign_currency_rate" class="form-control" value="<?php echo (isset($config['foreign_currency_rate']) ? $config['foreign_currency_rate'] : '');?>">
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <th>Listing Fees</th>
                        <td>
                          <table>
                           
                            <tr>
                              <td>
                                ebay no store
                                <div class="form-group">
                                  <input type="text" name="ebay_no_store" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_no_store']) ? $config['ebay_no_store'] : '0.35');?>">
                                </div>
                              </td>
                            </tr>

                            <tr>
                              <td>
                                ebay starter
                                <div class="form-group">
                                  <input type="text" name="ebay_starter" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_starter']) ? $config['ebay_starter'] : '0.30');?>">
                                </div>
                              </td>
                            </tr>

                            <tr>
                              <td>
                                ebay basic auction fixed price
                                <div class="form-group">
                                  <input type="text" name="ebay_basic_auction_fixed_price" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_basic_auction_fixed_price']) ? $config['ebay_basic_auction_fixed_price'] : '0.25');?>">
                                </div>
                              </td>
                            </tr>

                            <tr>
                              <td>
                                ebay premium auction
                                <div class="form-group">
                                  <input type="text" name="ebay_premium_auction" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_premium_auction']) ? $config['ebay_premium_auction'] : '0.15');?>">
                                </div>
                              </td>
                            </tr>

                             <tr>
                              <td>
                                ebay fixed price
                                <div class="form-group">
                                  <input type="text" name="ebay_premium_fixed_price" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_premium_fixed_price']) ? $config['ebay_premium_fixed_price'] : '0.05');?>">
                                </div>
                              </td>
                            </tr>

                             <tr>
                              <td>
                                ebay anchor auction
                                <div class="form-group">
                                  <input type="text" name="ebay_anchor_auction" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_anchor_auction']) ? $config['ebay_anchor_auction'] : '0.10');?>">
                                </div>
                              </td>
                            </tr>

                            <tr>
                              <td>
                                ebay anchor fixed price
                                <div class="form-group">
                                  <input type="text" name="ebay_anchor_fixed_price" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_anchor_fixed_price']) ? $config['ebay_anchor_fixed_price'] : '0.05');?>">
                                </div>
                              </td>
                            </tr>
                            <tr>
                              
                              <td>
                                Paypal Off Ebay
                                <div class="form-group">
                                  <input type="text" name="paypal_off_ebay" class="form-control" class="form-control" value="<?php echo (isset($config['paypal_off_ebay']) ? $config['paypal_off_ebay'] : '0.00');?>">
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>



                       <tr>
                        <th>ebay_referal_no_store(%)</th>
                        <td>
                          <div class="form-group">
                            <input type="text" name="ebay_referal_no_store" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_referal_no_store']) ? $config['ebay_referal_no_store'] : '');?>">
                          </div>
                        </td>
                      </tr>

                       <tr>
                        <th>ebay_referal_any_store(%)</th>
                        <td>
                          <div class="form-group">
                            <input type="text" name="ebay_referal_any_store" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_referal_any_store']) ? $config['ebay_referal_any_store'] : '');?>">
                          </div>
                        </td>
                      </tr>

                      


                       <tr>
                        <th>SST</th>
                        <td>
                          <div class="form-group">
                            <input type="text" name="sst" class="form-control" class="form-control" value="<?php echo (isset($config['sst']) ? $config['sst'] : '');?>">
                          </div>
                        </td>
                      </tr>

                       <tr>
                        <th>Payment Gateway Fee</th>
                        <td>
                          <div class="form-group">
                            <input type="text" name="payment_gateway_fee" class="form-control" class="form-control" value="<?php echo (isset($config['payment_gateway_fee']) ? $config['payment_gateway_fee'] : '');?>">
                          </div>
                        </td>
                      </tr>

                       <tr>
                        <th>Handling Fee</th>
                        <td>
                          <div class="form-group">
                            <input type="text" name="handling_fee" class="form-control" class="form-control" value="<?php echo (isset($config['handling_fee']) ? $config['handling_fee'] : '');?>">
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <td colspan="2"><input type="submit" value="SUBMIT"></td>
                      </tr>
                    </table>
                  </form>

                </div>
          </div>
        </div>
  </div>
</div>
</section>
@endsection

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
                   <ul>
                     <li>
                       <a href="/ebay/config/calculator/<?php echo $account;?>/new">Add New Calculator configuration</a>
                    

                     </li>
                   </ul>
                  </div>
                </div>
              </div>

                <div class="card-body">
                  
                 
                 <?php
                 foreach ($calculatorConfig as $key => $config) {
             
               
                 ?>
                    <!-- Default switch -->
<div class="custom-control custom-switch">
  <input type="checkbox" class="custom-control-input"  data-calculator-id="<?php echo $config['id'];?>" id="customSwitches_<?php echo $config['id'];?>" <?php echo ($config['status'] == 1) ? 'checked': '';?>>
  <label class="custom-control-label" for="customSwitches_<?php echo $config['id'];?>">Activate</label>
</div>
                    <table>
                      <tr>
                        <td>
                          <ul>
                           <li>
                             <a href="/ebay/config/calculator/edit/<?php echo $config['id'];?>">Edit</a>
                          

                           </li>
                         </ul>
                        </td>
                      </tr>

                      <tr>
                        <th>Fuel Surcharge</th>
                        <td>
                          <div class="form-group">
                            
                            <?php echo $config['fuel_surcharge'];?>
                            
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th>Conversion Rate</th>
                        <td>
                          <div class="form-group">
                            
                            <?php echo $config['conversion_rate'];?>
                            
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <th>Foreign Currency Rate</th>
                        <td>
                          <div class="form-group">
                            <?php echo $config['foreign_currency_rate'];?>
                           
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <th>ebay_no_store</th>
                        <td>
                          <div class="form-group">
                            <?php echo $config['ebay_no_store'];?>

                           
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th>ebay_starter</th>
                        <td>
                          <div class="form-group">
                            <?php echo $config['ebay_starter'];?>

                           
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th>ebay_basic_auction_fixed_price</th>
                        <td>
                          <div class="form-group">
                            <?php echo $config['ebay_basic_auction_fixed_price'];?>

                           
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th>ebay_premium_auction</th>
                        <td>
                          <div class="form-group">
                            <?php echo $config['ebay_premium_auction'];?>

                           
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th>ebay_premium_fixed_price</th>
                        <td>
                          <div class="form-group">
                            <?php echo $config['ebay_premium_fixed_price'];?>

                           
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th>ebay_anchor_auction</th>
                        <td>
                          <div class="form-group">
                            <?php echo $config['ebay_anchor_auction'];?>

                           
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th>ebay_anchor_fixed_price</th>
                        <td>
                          <div class="form-group">
                            <?php echo $config['ebay_anchor_fixed_price'];?>

                           
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <th>paypal_off_ebay</th>
                        <td>
                          <div class="form-group">
                            <?php echo $config['paypal_off_ebay'];?>

                           
                          </div>
                        </td>
                      </tr>

                      

                       <tr>
                        <th>ebay_referal_no_store(%)</th>
                        <td>
                          <div class="form-group">
                            <?php echo $config['ebay_referal_no_store'];?>
                          
                          </div>
                        </td>
                      </tr>



                       <tr>
                        <th>ebay_referal_any_store(%)</th>
                        <td>
                          <div class="form-group">
                            <?php echo $config['ebay_referal_any_store'];?>
                          
                          </div>
                        </td>
                      </tr>

                      

                       <tr>
                        <th>SST</th>
                        <td>
                          <div class="form-group">
                            <?php echo $config['sst'];?>

                          
                          </div>
                        </td>
                      </tr>


                       <tr>
                        <th>Payment Gateway Fee</th>
                        <td>
                          <div class="form-group">
                            <?php echo $config['payment_gateway_fee'];?>

                          </div>
                        </td>
                      </tr>

                       <tr>
                        <th>Handling Fee</th>
                        <td>
                          <div class="form-group">
                            <?php echo $config['handling_fee'];?>

                         
                          </div>
                        </td>
                      </tr>

                      
                    </table>
                    <?php
                      }
                    ?>
                  

                </div>
          </div>
        </div>
  </div>
</div>

</section>

@endsection

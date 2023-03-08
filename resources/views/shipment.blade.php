@extends('layouts.app')

@section('content')

<input type="hidden" id="account" value="<?php echo $account;?>">
<input type="hidden" id="orderID" value="<?php echo $orderID;?>">

                <!-- Dashboard Ecommerce Starts -->
 <div class="card">
                                <div class="card-body pb-0 mx-25">
             
                   <form method="post" action="/ebay/<?php echo $account;?>/order/request/shipment">
                            @csrf
<section class="">
                    <div class="row">
                        <!-- invoice view page -->
                        <div class="col-xl-12 col-md-8 col-12">
                            <h4 class="text-secondary">Shipment Request</h4>
                          <?php
                          if (isset($data)) {
                           
                          ?>
                           
                                    <!-- header section -->
                                    <div class="row mx-0">
                                        <div class="col-xl-4 col-md-12 d-flex align-items-center pl-0">
                                            <h4 class="text-secondary">#<?php echo $data['order']['id'];?></h4>
                                            
                                            <!--data-->
                                            <input type="hidden" name="order_id" value="<?php echo $data['order']['id'];?>">
                                            <input type="hidden" name="order_status" value="<?php echo $data['order']['status'];?>"><input type="hidden" name="subtotal" value="<?php echo $data['order']['subtotal'];?>"><input type="hidden" name="total" value="<?php echo $data['order']['total'];?>">
                                        </div>
                                        <div class="col-xl-8 col-md-12 px-0 pt-xl-0 pt-1">
                                            <div class="invoice-date-picker d-flex align-items-center justify-content-xl-end flex-wrap">
                                                <div class="d-flex align-items-center">
                                                  
                                                    <fieldset class="d-flex ">
                                                        <?php
                                                        if (isset($data['shipment'][0]->marketplace_id)) {

                                                            switch ($data['shipment'][0]->marketplace_id) {
                                                                case '1':
                                                                   ?>
                                                                   <img src="{{ asset('theme/img/ebay.png') }}" alt="logo" height="150" width="150">
                                                                   <?php
                                                                    break;
                                                                case '2':
                                                                   ?>
                                                                   <img src="{{ asset('theme/img/shopee.png') }}" alt="logo" height="150" width="150">
                                                                   <?php
                                                                    break;
                                                                case '3':
                                                                   ?>
                                                                   <img src="{{ asset('theme/img/amazon.png') }}" alt="logo" height="150" width="150">
                                                                   <?php
                                                                    break;
                                                                case '1':
                                                                   ?>
                                                                   <img src="{{ asset('theme/img/lazada.png') }}" alt="logo" height="150" width="150">
                                                                   <?php
                                                                    break;
                                                                
                                                                default:
                                                                    # code...
                                                                    break;
                                                            }
                                                            ?>
                                                            
                                                            <?php
                                                        }
                                                        ?>
                                                       
                                                    </fieldset>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <hr>
                                    <!-- invoice address and contact -->
                                    <div class="row invoice-info">
                                        <div class="col-lg-6 col-md-12 mt-25">
                                            
                                            <h6 class="invoice-to">Ship To</h6>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <fieldset class="invoice-address form-group">
                                                        <label>Name</label><br/>
                                                        <?php echo $data['customer']['address']['Name'];?>
                                                        <input type="hidden" name="customer_name" class="form-control" placeholder="City" value="<?php echo $data['customer']['address']['Name'];?>">
                                                    </fieldset>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                   <fieldset class="invoice-address form-group">
                                                       
                                                        <label>Street 1</label><br/>
                                                         <?php echo $data['customer']['address']['Street1'];?>

                                                        <input type="hidden" name="street1" class="form-control" placeholder="City" value="<?php echo $data['customer']['address']['Street1'];?>">

                                                    </fieldset> 

                                                    <label>Street 2</label>

                                                    <fieldset class="invoice-address form-group">
                                                        <?php 
                                                        if (!is_array($data['customer']['address']['Street2'])) {
                                                           echo $data['customer']['address']['Street2'];
                                                            ?>
                                                             <input type="hidden" name="street2" class="form-control" placeholder="City" value="<?php echo $data['customer']['address']['Street2'];?>">
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <textarea name="street2">
                                                                <?php
                                                                echo json_encode($data['customer']['address']['Street2']);
                                                                ;?>
                                                            </textarea>
                                                            <?php
                                                        }
                                                        ?>
                                                    </fieldset>

                                                   <label>City Name</label>
                                                    <?php echo $data['customer']['address']['CityName'];?>
                                                    <fieldset class="invoice-address form-group">
                                                        <input type="hidden" name="city" class="form-control" placeholder="City" value="<?php echo $data['customer']['address']['CityName'];?>">
                                                    </fieldset>

                                                    <label>Postal Code</label>

                                                    <fieldset class="invoice-address form-group">
                                                        <?php echo $data['customer']['address']['PostalCode'];?>
                                                        <input type="hidden" name="postal"  class="form-control" placeholder="Pincode" value="<?php echo $data['customer']['address']['PostalCode'];?>">
                                                    </fieldset>

                                                     <label>State</label>

                                                    <fieldset class="invoice-address form-group">
                                                        <?php echo $data['customer']['address']['StateOrProvince'];?>
                                                        <input type="hidden" name="state"  class="form-control" placeholder="Pincode" value="<?php echo $data['customer']['address']['StateOrProvince'];?>">
                                                    </fieldset>
                                                </div>

                                                 <div class="col-md-6">

                                                     <label>Country</label>

                                                    <fieldset class="invoice-address form-group">
                                                        <?php echo $data['customer']['address']['CountryName'];?>
                                                        <input type="hidden" name="country"  class="form-control" placeholder="Pincode" value="<?php echo $data['customer']['address']['Country'];?>">
                                                    </fieldset>

                                                     <label>Phone</label>

                                                    <fieldset class="invoice-address form-group">
                                                        <?php echo $data['customer']['address']['Phone'];?>
                                                        <input type="hidden" name="phone" class="form-control" placeholder="Phone" value="<?php echo $data['customer']['address']['Phone'];?>">
                                                    </fieldset>

                                                    <label>User ID</label>

                                                     <fieldset class="invoice-address form-group">
                                                        <?php echo $data['customer']['userid'];?>
                                                        
                                                    </fieldset>

                                                     <label>Email</label>

                                                     <fieldset class="invoice-address form-group">
                                                        <?php echo $data['customer']['email'];?>
                                                        
                                                    </fieldset>
                                                    
                                                </div>
                                            </div>

                                            
                                        </div>
                                        <div class="col-lg-6 col-md-12 mt-25">
                                            <h6 class="invoice-to">Shipped By:</h6>

                                            <label>eBay Account</label>
                                             <input type="hidden" name="marketplace_id" value="<?php echo $data['shipment'][0]->marketplace_id;?>">

                                            <fieldset class="invoice-address form-group">
                                                <?php echo $data['shipment'][0]->account;?>
                                                 <input type="hidden" name="account" value="<?php echo $data['shipment'][0]->account;?>">
                                            </fieldset>

                                            <label>Axis Ship ID</label>
                                            <fieldset class="invoice-address form-group">
                                                <?php echo $data['shipment'][0]->axis_shop_id;?>
                                                 <input type="hidden" name="axis_shop_id" value="<?php echo $data['shipment'][0]->axis_shop_id;?>">
                                            </fieldset>

                                            <label>Axis Shop Name</label>
                                            <fieldset class="invoice-address form-group">
                                               <?php echo $data['shipment'][0]->axis_shop_name;?>
                                                <input type="hidden" name="axis_shop_name" value="<?php echo $data['shipment'][0]->axis_shop_name;?>">
                                            </fieldset>

                                            <label>Axis User Name</label>
                                            <fieldset class="invoice-address form-group">
                                               <?php echo $data['shipment'][0]->axis_username;?>
                                                <input type="hidden" name="axis_username" value="<?php echo $data['shipment'][0]->axis_username;?>">
                                            </fieldset>
                                        

                                            
                                            <input type="hidden" name="axis_user_id" value="<?php echo $data['shipment'][0]->axis_user_id;?>">

                                            
                                            
                                        </div>
                                    </div>
                                    <hr>
                                </div>


                                <div class="card-body pt-50">
                                   
                                <!-- product details table-->
                                <div class="invoice-product-details table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <thead>
                                            <tr class="border-0">
                                                <th scope="col">Item</th>
                                                <th scope="col">Qty</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">axis</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                  
                                            
                                             foreach ($data['listing'] as $key => $product) {
                                                
                                                ?>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="data[<?php echo $product['listing']['ItemID'];?>][listing][itemID]" value="<?php echo $product['listing']['ItemID'];?>">
                                                        <input type="hidden" name="data[<?php echo $product['listing']['ItemID'];?>][listing][title]" value="<?php echo $product['listing']['Title'];?>">
                                                        <?php echo $product['listing']['Title'];?>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="data[<?php echo $product['listing']['ItemID'];?>][listing][QuantityPurchased]" value="<?php echo $product['listing']['QuantityPurchased'];?>">
                                                        <?php echo $product['listing']['QuantityPurchased'];?></td>
                                                    <td>
                                                    <input type="hidden" name="data[<?php echo $product['listing']['ItemID'];?>][listing][TransactionPrice]" value="<?php echo $product['listing']['TransactionPrice'];?>">
                                                        <?php echo $product['listing']['TransactionPrice'];?>
                                                            
                                                        </td>
                                                    <td>
                                                       <?php
                                                         if (sizeof($product['product']) <= 0) {

                                                           ?>
                                                       
                                                               <div data-toggle="modal" data-target="#shipment_<?php echo $key;?>" class="livicon-evo" data-options=" name: search.svg; style: original; size: 30px; strokeStyle: original; strokeWidth: original; tryToSharpen: true; rotate: none; flipHorizontal: false; flipVertical: false; strokeColor: #22A7F0; strokeColorAction: #b3421b; strokeColorAlt: #F9B32F; strokeColorAltAction: #ab69c6; fillColor: #91e9ff; fillColorAction: #ff926b; solidColor: #6C7A89; solidColorAction: #4C5A69; solidColorBgAction: #ffffff; solidColorBg: #ffffff; colorsOnHover: none; colorsHoverTime: 0.3; colorsWhenMorph: none; brightness: 0.1; saturation: 0.07; morphState: start; morphImage: none; allowMorphImageTransform: false; strokeWidthFactorOnHover: none; strokeWidthOnHoverTime: 0.3; keepStrokeWidthOnResize: false; animated: true; eventType: hover; eventOn: self; autoPlay: false; delay: 0; duration: default; repeat: default; repeatDelay: default; drawOnViewport: false; viewportShift: oneHalf; drawDelay: 0; drawTime: 1; drawStagger: 0.1; drawStartPoint: middle; drawColor: same; drawColorTime: 1; drawReversed: false; drawEase: Power1.easeOut; eraseDelay: 0; eraseTime: 1; eraseStagger: 0.1; eraseStartPoint: middle; eraseReversed: true; eraseEase: Power1.easeOut; touchEvents: false "></div>
                                                      
                                                           <!-- Modal -->
                                                            @include('ebay.shipment-add-product', ['sme'=>$sme, 'key'=>$key, 'listing'=>$product['listing']['Title']])

                                                            
                                                           
                                                       <?php
                                                        } 
                                                        ?>  
                                                    </td>
                                                 </tr>
                                                <tr id="<?php echo $product['listing']['ItemID'];?>">
                                                    <td>
                                                       <?php
                                                    
                                                         if (sizeof($product['product']) <= 0) {
                                                           
                                                           ?>
                                                            <div id="hiddenInput"></div>
                                                            <table id="invoiceTemp"></table>
                                                           
                                                       <?php
                                                        } else {
                                                           ?>
                                                           <table>
                                                            <?php
                                                            $pro_pkg = (isset($product['product']['products']) ? $product['product']['products'] : $product['product']['packages']);

                                                          
                                                            foreach ($pro_pkg as $k2 => $v2) {
                                                                
                                                                foreach ($v2 as $k3 => $v3) {
                                                                    ?>
                                                                     <!--pre-selected products-->
                                                                     <input type="hidden" name="data[<?php echo $product['listing']['ItemID'];?>][product][<?php echo $k2;?>][<?php echo $k3;?>]" value="<?php echo $v3;?>">
                                                                    <?php
                                                                   
                                                                }
                                                              ?>
                                                             
                                                              <?php
                                                            }
                                                           
                                                           if(isset($product['product']['products'])) {
                                            
                                                           foreach ($product['product']['products'] as $k => $v) {
                                                           
                                                               ?>
                                                               <tr>
                                                                   <td style="padding: 0;">
                                                                    <span class="bullet bullet-primary bullet-sm"></span>
                                                                    <small class="text-muted"><?php echo $v->name;?></small>
                                                                    <span class="badge badge-light-success badge-pill"><?php echo (isset($v->quantity) ? $v->quantity : '1');?></span>
                                                                  </td>
                                                              </tr>
                                                              
                                                               
                                                               <?php
                                                           }
                                                           }

                                                           if(isset($product['product']['packages'])) {
                                            
                                                           foreach ($product['product']['packages'] as $k => $v) {
                                                           
                                                               ?>
                                                               <tr>
                                                                   <td style="padding: 0;">
                                                                    <span class="bullet bullet-primary bullet-sm"></span>
                                                                    <small class="text-muted"><?php echo $v->name;?></small>
                                                                    <span class="badge badge-light-success badge-pill"><?php echo (isset($v->quantity) ? $v->quantity : '1');?></span>
                                                                  </td>
                                                              </tr>
                                                              
                                                               
                                                               <?php
                                                           }
                                                           }

                                                           ?>
                                                       </table>
                                                           <?php

                                                        }
                                                        ?>  
                                                    </td>
                                                </tr>
                                                
                                                <?php
                                             }
                                            ?>
                                            
                                        </tbody>
                                    </table>
                                </div>


                                    <!-- invoice subtotal -->
                                    <hr>
                                    <div class="invoice-subtotal pt-50">
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                
                                                
                                            </div>
                                            <div class="col-lg-5 col-md-7 offset-lg-2 col-12">
                                                <ul class="list-group list-group-flush">
                                                   <!--  <li class="list-group-item d-flex justify-content-between border-0 pb-0">
                                                        <span class="invoice-subtotal-title">Subtotal</span>
                                                        <h6 class="invoice-subtotal-value mb-0">$00.00</h6>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between border-0 pb-0">
                                                        <span class="invoice-subtotal-title">Discount</span>
                                                        <h6 class="invoice-subtotal-value mb-0">$00.00</h6>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between border-0 pb-0">
                                                        <span class="invoice-subtotal-title">Tax</span>
                                                        <h6 class="invoice-subtotal-value mb-0">0.0%</h6>
                                                    </li>
                                                    <li class="list-group-item py-0 border-0 mt-25">
                                                        <hr>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between border-0 py-0">
                                                        <span class="invoice-subtotal-title">Invoice Total</span>
                                                        <h6 class="invoice-subtotal-value mb-0">$00.00</h6>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between border-0 pb-0">
                                                        <span class="invoice-subtotal-title">Paid to date</span>
                                                        <h6 class="invoice-subtotal-value mb-0">$00.00</h6>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between border-0 pb-0">
                                                        <span class="invoice-subtotal-title">Balance (USD)</span>
                                                        <h6 class="invoice-subtotal-value mb-0">$000</h6>
                                                    </li> -->
                                                    <li class="list-group-item border-0 pb-0">

                                            
                                                        <button type="submit" class="btn btn-secondary btn-block subtotal-preview-btn">Submit</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                          }
                   
                            ?>
                       
                </section>


                                             
                                                
                                             
                                          </form>

 </div>
                        <!-- invoice action  -->
                    </div>

@endsection

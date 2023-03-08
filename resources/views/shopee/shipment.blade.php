@extends('layouts.app')
@section('title')
    Create Shipment Request
@endsection

@section('title-content')
@endsection

@section('content')

<input type="hidden" id="account" value="<?php echo $account;?>">
<input type="hidden" id="orderID" value="<?php echo $orderID;?>">

                <!-- Dashboard Ecommerce Starts -->

             
                   <form method="post" action="/<?php echo $marketplace;?>/<?php echo $account;?>/orders">
                            @csrf

                    
                          <?php
                          if (isset($data)) {
                             
                          ?>
                           <div class="card">
                                
                                <div class="card-header">
                                      <div class="row">
                                       <div class="col-lg-12">
                                           <h4 class="text-secondary">#<?php echo $data['order']['id'];?></h4>
                                            
                                            <!--data-->
                                            <input type="hidden" name="orders" value="<?php echo $data['order']['id'];?>">
                                            <input type="hidden" name="order_status" value="<?php echo $data['order']['status'];?>"><input type="hidden" name="subtotal" value="<?php echo $data['order']['subtotal'];?>"><input type="hidden" name="total" value="<?php echo $data['order']['total'];?>">
                                       </div>
                                   </div>
                                </div>
                                <div class="card-body">
                                 
                                    <!-- invoice address and contact -->
                                    <div class="row invoice-info">
                                        <div class="col-lg-6 col-md-12 mt-25">
                                            
                                            <h6 class="invoice-to">Ship To</h6>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <fieldset class="invoice-address form-group">
                                                        <label>Name</label><br/>
                                                        <?php echo $data['customer']['address']->name;?>
                                                        <input type="hidden" name="customer_name" class="form-control" placeholder="City" value="<?php echo $data['customer']['address']->name;?>">
                                                    </fieldset>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                   <fieldset class="invoice-address form-group">
                                                       
                                                        <label>Full Address</label><br/>
                                                         <?php echo $data['customer']['address']->full_address;?>

                                                        <input type="hidden" name="street1" class="form-control" placeholder="City" value="<?php echo $data['customer']['address']->full_address;?>">

                                                    </fieldset> 


                                                   <label>City Name</label>
                                                    <?php echo $data['customer']['address']->city;?>
                                                    <fieldset class="invoice-address form-group">
                                                        <input type="hidden" name="city" class="form-control" placeholder="City" value="<?php echo $data['customer']['address']->city;?>">
                                                    </fieldset>

                                                    <label>Postal Code</label>

                                                    <fieldset class="invoice-address form-group">
                                                        <?php echo $data['customer']['address']->zipcode;?>
                                                        <input type="hidden" name="postal"  class="form-control" placeholder="Pincode" value="<?php echo $data['customer']['address']->zipcode;?>">
                                                    </fieldset>

                                                     <label>State</label>

                                                    <fieldset class="invoice-address form-group">
                                                        <?php echo $data['customer']['address']->state;?>
                                                        <input type="hidden" name="state"  class="form-control" placeholder="Pincode" value="<?php echo $data['customer']['address']->state;?>">
                                                    </fieldset>
                                                </div>

                                                 <div class="col-md-6">

                                                     <label>Country</label>

                                                    <fieldset class="invoice-address form-group">
                                                        <?php echo $data['customer']['address']->country;?>
                                                        <input type="hidden" name="country"  class="form-control" placeholder="Pincode" value="<?php echo $data['customer']['address']->country;?>">
                                                    </fieldset>

                                                     <label>Phone</label>

                                                    <fieldset class="invoice-address form-group">
                                                        <?php echo $data['customer']['address']->phone;?>
                                                        <input type="hidden" name="phone" class="form-control" placeholder="Phone" value="<?php echo $data['customer']['address']->phone;?>">
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
                                                <th scope="col">Unit Price</th>
                                                <th scope="col">Total Price</th>
                                                <th scope="col">Selling Price</th>

                                                <!-- <th scope="col">axis</th> -->

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            foreach ($data['listing'] as $key => $value) {
                                               
                                            ?>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="data[<?php echo $value['listing']->item_sku;?>][listing]->item_sku" value="<?php echo $value['listing']->item_sku;?>">
                                                        <input type="hidden" name="data[<?php echo $value['listing']->item_sku;?>][listing][title]" value="<?php echo $value['listing']->item_name;?>">
                                                        <?php echo $value['listing']->item_name;?>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="data[<?php echo $value['listing']->item_sku;?>][listing]->variation_quantity_purchased" value="<?php echo $value['listing']->variation_quantity_purchased;?>">
                                                        <?php echo $value['listing']->variation_quantity_purchased;?></td>
                                                    <td>
                                                    <input type="hidden" name="data[<?php echo $value['listing']->item_sku;?>][listing][TransactionPrice]" value="<?php echo $value['listing']->variation_original_price;?>">
                                                        <?php echo $value['listing']->variation_original_price;?>
                                                            
                                                        </td>

                                                        <td>
                                                    <input type="hidden" name="data[<?php echo $value['listing']->item_sku;?>][listing][TransactionPrice]" value="<?php echo $value['listing']->variation_original_price;?>">
                                                        <?php echo $value['listing']->variation_quantity_purchased * $value['listing']->variation_original_price;?>
                                                            
                                                        </td>

                                                         <td>
                                                  
                                                        <?php echo $value['listing']->total_amount;?>
                                                            
                                                        </td>

                                              
                                                       <?php

                                                         if (isset($value['products']) && sizeof($value['products']) <= 0) {

                                                           ?>
                                                       
                                                               <div data-toggle="modal" data-target="#shipment_<?php echo $value['listing']->item_sku;?>" class="livicon-evo" data-options=" name: search.svg; style: original; size: 30px; strokeStyle: original; strokeWidth: original; tryToSharpen: true; rotate: none; flipHorizontal: false; flipVertical: false; strokeColor: #22A7F0; strokeColorAction: #b3421b; strokeColorAlt: #F9B32F; strokeColorAltAction: #ab69c6; fillColor: #91e9ff; fillColorAction: #ff926b; solidColor: #6C7A89; solidColorAction: #4C5A69; solidColorBgAction: #ffffff; solidColorBg: #ffffff; colorsOnHover: none; colorsHoverTime: 0.3; colorsWhenMorph: none; brightness: 0.1; saturation: 0.07; morphState: start; morphImage: none; allowMorphImageTransform: false; strokeWidthFactorOnHover: none; strokeWidthOnHoverTime: 0.3; keepStrokeWidthOnResize: false; animated: true; eventType: hover; eventOn: self; autoPlay: false; delay: 0; duration: default; repeat: default; repeatDelay: default; drawOnViewport: false; viewportShift: oneHalf; drawDelay: 0; drawTime: 1; drawStagger: 0.1; drawStartPoint: middle; drawColor: same; drawColorTime: 1; drawReversed: false; drawEase: Power1.easeOut; eraseDelay: 0; eraseTime: 1; eraseStagger: 0.1; eraseStartPoint: middle; eraseReversed: true; eraseEase: Power1.easeOut; touchEvents: false "></div>
                                                      
                                                           <!-- Modal -->
                                                            @include('ebay.shipment-add-product', ['sme'=>$sme, 'key'=>$value['listing']->item_sku, 'listing'=>$value['listing']->item_name])

                                                            
                                                           </td>
                                                       <?php
                                                        } 
                                                        ?>  
                                                        
                                                        <?php

                                                         if (isset($value['package']) && sizeof($value['package']) <= 0) {

                                                           ?>
                                                       
                                                               <div data-toggle="modal" data-target="#shipment_<?php echo $value['listing']->item_sku;?>" class="livicon-evo" data-options=" name: search.svg; style: original; size: 30px; strokeStyle: original; strokeWidth: original; tryToSharpen: true; rotate: none; flipHorizontal: false; flipVertical: false; strokeColor: #22A7F0; strokeColorAction: #b3421b; strokeColorAlt: #F9B32F; strokeColorAltAction: #ab69c6; fillColor: #91e9ff; fillColorAction: #ff926b; solidColor: #6C7A89; solidColorAction: #4C5A69; solidColorBgAction: #ffffff; solidColorBg: #ffffff; colorsOnHover: none; colorsHoverTime: 0.3; colorsWhenMorph: none; brightness: 0.1; saturation: 0.07; morphState: start; morphImage: none; allowMorphImageTransform: false; strokeWidthFactorOnHover: none; strokeWidthOnHoverTime: 0.3; keepStrokeWidthOnResize: false; animated: true; eventType: hover; eventOn: self; autoPlay: false; delay: 0; duration: default; repeat: default; repeatDelay: default; drawOnViewport: false; viewportShift: oneHalf; drawDelay: 0; drawTime: 1; drawStagger: 0.1; drawStartPoint: middle; drawColor: same; drawColorTime: 1; drawReversed: false; drawEase: Power1.easeOut; eraseDelay: 0; eraseTime: 1; eraseStagger: 0.1; eraseStartPoint: middle; eraseReversed: true; eraseEase: Power1.easeOut; touchEvents: false "></div>
                                                      
                                                           <!-- Modal -->
                                                            @include('ebay.shipment-add-product', ['sme'=>$sme, 'key'=>$value['listing']->item_sku, 'listing'=>$value['listing']->item_name])

                                                            
                                                           </td>
                                                       <?php
                                                        } 
                                                        ?>  
                                                
                                                 </tr>

                                                 <?php
                                               
                                                  
                                                 if (isset($value['product'])) {
                                                    


                                                     if (isset($value['product'][0])) {
                                                        $pro = $value['product'][0];
                                                     } else {
                                                        $pro = $value['product'];
                                                     }
                                                     
                                                  
                                           
                                                        if(gettype($pro) == 'string') {
                                                            $pro = json_decode($pro);
                                                        }
                                                   
                                                    foreach ($pro as $k => $v) {
                                                        ?>
                                                         <tr id="<?php echo $value['listing']->item_sku;?>">
                                                    <td>
                                                       <?php
                                                    
                                                         if (!isset($v->package)) {
                                                   
                                                           ?>
                                                            <div id="hiddenInput"></div>
                                                            <table id="invoiceTemp"></table>
                                                           
                                                       <?php
                                                        } else {
                                                    
                                                           ?>
                                                           <!-- <table> -->
                                                            <?php
                                                            $pro_pkg = (isset($v->products) ? $v->products : $v->package);

                                                          
                                                            foreach ($pro_pkg as $k2 => $v2) {
                                                                    if (isset($v->products)) {
                                                                        ?>
                                                                        <!--pre-selected products-->
                                                                  
                                                                     <input type="text" name="data[<?php echo $value['listing']->item_sku;?>][product][<?php echo $k2;?>]" value="<?php echo $v2;?>">
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <!--pre-selected products-->
                                                                  
                                                                     <input type="text" name="data[<?php echo $value['listing']->item_sku;?>][package][<?php echo $k2;?>]" value="<?php echo $v2;?>">
                                                                        <?php
                                                                    }
                                                              
                                                                    ?>
                                                                     
                                                                  
                                                                    <?php
                                                                   
                                                               
                                                              ?>
                                                             
                                                              <?php
                                                            }
                                                           
                                                           if(isset($value['products']->products)) {
                                            
                                                           
                                                               ?>
                                                               <tr>
                                                                   <!--  <table>
                                                                    <tr> -->
                                                                        <td>
                                                                             <span class="bullet bullet-primary bullet-sm"></span>&nbsp;
                                                                    <small class="text-muted"><?php echo $v->variant[1];?></small>
                                                                        </td>
                                                                        <td>
                                                                            <small class="text-muted"><?php echo (isset($v->products->quantity) ? $v->products->quantity : '1');?></small>

                                                                        <!-- </td> -->
                                                                        <td>
                                                                          <!--   <span class="bullet bullet-primary bullet-sm"></span> -->
                                                                    <small class="text-muted"><?php echo $v->products->variant_price;?></small>
                                                                        </td>
                                                                        <td>
                                                                            <!--  <span class="bullet bullet-primary bullet-sm"></span> -->
                                                                    <small class="text-muted"><?php echo $v->products->variant_total_price;?></small>
                                                                        </td>
                                                                   <!--  </tr>
                                                                </table> -->
                                                              </tr>
                                                              
                                                               
                                                               <?php
                                                           
                                                           }
                                                           
                                                           if(isset($v->package)) {
                                                          
                                                               ?>
                                                               <tr>
                                                                <!-- <table> -->
                                                                    <!-- <tr> -->
                                                                        <td>
                                                                             <span class="bullet bullet-primary bullet-sm"></span>&nbsp;
                                                                    <small class="text-muted"><?php echo $v->variant[1];?></small>
                                                                        </td>
                                                                       <td>
                                                                           <small class="text-muted"><?php echo (isset($v->package->quantity) ? $v->package->quantity : '1');?></small>

                                                                        </td>
                                                                       <td>
                                                                        
                                                                    <!-- <small class="text-muted"><?php echo $v->package->variant_price;?></small> -->
                                                                        </td>
                                                                       <td>
                                                                        
                                                                   <!--  <small class="text-muted"><?php echo $v->package->variant_total_price;?></small> -->
                                                                        </td>
                                                                   <!--  </tr>
                                                                </table> -->
                                                                  
                                                              <!-- </tr> -->
                                                              
                                                               
                                                               <?php
                                                           
                                                           }

                                                           ?>
                                                       <!-- </table> -->
                                                           <?php

                                                        }
                                                        ?>  
                                                    </td>
                                                </tr>
                                                        <?php
                                                    
                                                    }
                                                 } else {
                                                     
                                                      if (isset($value['package'][0])) {
                                                        $pro = json_decode($value['package'][0]);
                                                     } else {
                                                        $pro = $value['package'];
                                                     }
                                                     
                                                  
                                           
                                                    foreach ($pro as $k => $v) {
                                                        ?>
                                                         <tr id="<?php echo $value['listing']->item_sku;?>">
                                                    <td>
                                                       <?php
                                                    
                                                         if (!isset($v->package)) {
                                                   
                                                           ?>
                                                            <div id="hiddenInput"></div>
                                                            <table id="invoiceTemp"></table>
                                                           
                                                       <?php
                                                        } else {
                                                    
                                                           ?>
                                                           <!-- <table> -->
                                                            <?php
                                                            $pro_pkg = (isset($v->products) ? $v->products : $v->package);

                                                          
                                                            foreach ($pro_pkg as $k2 => $v2) {
                                                                    if (isset($v->products)) {
                                                                        ?>
                                                                        <!--pre-selected products-->
                                                                  
                                                                     <input type="hidden" name="data[<?php echo $value['listing']->item_sku;?>][product][<?php echo $k2;?>]" value="<?php echo $v2;?>">
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <!--pre-selected products-->
                                                                  
                                                                     <input type="hidden" name="data[<?php echo $value['listing']->item_sku;?>][package][<?php echo $k2;?>]" value="<?php echo $v2;?>">
                                                                        <?php
                                                                    }
                                                              
                                                                    ?>
                                                                     
                                                                  
                                                                    <?php
                                                                   
                                                               
                                                              ?>
                                                             
                                                              <?php
                                                            }
                                                           
                                                           if(isset($value['products']->products)) {
                                            
                                                           
                                                               ?>
                                                               <tr>
                                                                   <!--  <table>
                                                                    <tr> -->
                                                                        <td>
                                                                             <span class="bullet bullet-primary bullet-sm"></span>&nbsp;
                                                                    <small class="text-muted"><?php echo $v->variant[1];?></small>
                                                                        </td>
                                                                        <td>
                                                                            <small class="text-muted"><?php echo (isset($v->products->quantity) ? $v->products->quantity : '1');?></small>

                                                                        <!-- </td> -->
                                                                        <td>
                                                                          <!--   <span class="bullet bullet-primary bullet-sm"></span> -->
                                                                    <small class="text-muted"><?php echo $v->products->variant_price;?></small>
                                                                        </td>
                                                                        <td>
                                                                            <!--  <span class="bullet bullet-primary bullet-sm"></span> -->
                                                                    <small class="text-muted"><?php echo $v->products->variant_total_price;?></small>
                                                                        </td>
                                                                   <!--  </tr>
                                                                </table> -->
                                                              </tr>
                                                              
                                                               
                                                               <?php
                                                           
                                                           }
                                                           
                                                           if(isset($v->package)) {
                                                          
                                                               ?>
                                                               <tr>
                                                                <!-- <table> -->
                                                                    <!-- <tr> -->
                                                                        <td>
                                                                             <span class="bullet bullet-primary bullet-sm"></span>&nbsp;
                                                                    <small class="text-muted"><?php echo $v->variant[1];?></small>
                                                                        </td>
                                                                       <td>
                                                                           <small class="text-muted"><?php echo (isset($v->package->quantity) ? $v->package->quantity : '1');?></small>

                                                                        </td>
                                                                       <td>
                                                                        
                                                                    <!-- <small class="text-muted"><?php echo $v->package->variant_price;?></small> -->
                                                                        </td>
                                                                       <td>
                                                                        
                                                                   <!--  <small class="text-muted"><?php echo $v->package->variant_total_price;?></small> -->
                                                                        </td>
                                                                   <!--  </tr>
                                                                </table> -->
                                                                  
                                                              <!-- </tr> -->
                                                              
                                                               
                                                               <?php
                                                           
                                                           }

                                                           ?>
                                                       <!-- </table> -->
                                                           <?php

                                                        }
                                                        ?>  
                                                    </td>
                                                </tr>
                                                        <?php
                                                    }
                                                    
                                                 }

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
                                    
                                             
                                          </form>



@endsection

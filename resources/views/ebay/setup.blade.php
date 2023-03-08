@extends('layouts.app')

@section('content')

<?php

	if ($firstTime) {
		?>
		  <!-- Form wizard with step validation section start -->
                <section id="validation">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4 class="card-title">Sync Ebay Account</h4>
                                </div>
                                <div class="card-body">
                                    <form action="#" class="wizard-validation">
                                        <!-- Step 1 -->
                                        <h6>
                                            <i class="step-icon"></i>
                                            <span>Step 1</span>
                                        </h6>
                                        <!-- Step 1 -->
                                        <!-- body content of step 1 -->
                                        <fieldset>
                                            <div class="row">
                                                <div class="col-md-12">
<button class="btn btn-outline-info"><a  href="/ebay/authenticate">Authorization</a></button>
                                          <!--          @if (gettype($consent) != 'string')-->
		                                        <!--    <button class="btn btn-outline-info"><a data-authnauth  href="/authenticate">Auth N Auth</a></button>-->
		                                        <!--@else-->
		                                        <!--    <button class="btn btn-outline-info"><a data-authnauth  href="<?php echo $consent;?>">Auth N Auth <i class="fas fa-check"></i></a></button>-->
		                                        <!--@endif-->
                                                </div>
                                                
                                            </div>
                                            
                                        </fieldset>
                                        <!-- body content of step 1 end -->
                                        <!-- Step 2 -->
                                        <h6>
                                            <i class="step-icon"></i>
                                            <span>Step 2</span>
                                        </h6>
                                        <!-- step 2 -->
                                        <!-- body content of step 2 end -->
                                        <fieldset>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button class="btn btn-outline-info"><a href="https://auth.ebay.com/oauth2/authorize?client_id=karennai-middlema-PRD-ec8ec878c-badd14db&response_type=code&redirect_uri=karen_nair-karennai-middle-chacvvjxx&scope=https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly https://api.ebay.com/oauth/api_scope/sell.finances https://api.ebay.com/oauth/api_scope/sell.payment.dispute https://api.ebay.com/oauth/api_scope/commerce.identity.readonly">Confirmation</a></button>
                                                </div>
                                                
                                            </div>
                                        </fieldset>
                                        <!-- body content of step 2 end -->

                                         <!-- Step 3 -->
                                        <h6>
                                            <i class="step-icon"></i>
                                            <span>Step 3</span>
                                        </h6>
                                        <!-- step 2 -->
                                        <!-- body content of step 2 end -->
                                        <fieldset>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button class="btn btn-outline-info"><a id="last-shipment-btn" href="https://auth.ebay.com/oauth2/authorize?client_id=karennai-middlema-PRD-ec8ec878c-badd14db&response_type=code&redirect_uri=karen_nair-karennai-middle-chacvvjxx&scope=https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly https://api.ebay.com/oauth/api_scope/sell.finances https://api.ebay.com/oauth/api_scope/sell.payment.dispute https://api.ebay.com/oauth/api_scope/commerce.identity.readonly">Shipment</a></button>
                                                </div>
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" id="memberv2-username">
                                                    <button class="btn btn-outline-info" id="getAxisShop">Get Axis Shop for Shipment</button>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="hidden" id="marketplace" value="<?php echo $marketplace;?>">
                                                    <select id="shops"></select>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <!-- body content of step 2 end -->
                                       
                                       
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Form wizard with step validation section end -->
		<?php
	} else {

		?>
		  <!-- vertical Wizard start-->
                <section id="vertical-wizard">
                    <div class="card">
                        <div class="card-header">
                            <div class="section-title">
                                  <h2>Setup</h2>
                                
                                </div>
                        </div>
                        <div class="card-body">
                            <form action="#" class="wizard-vertical">
                                <!-- step 1 -->
                                <h3>
                                    <span class="fonticon-wrap mr-1">
                                        <i class="livicon-evo" data-options="name:gear.svg; size: 50px; style:lines; strokeColor:#adb5bd;"></i>
                                    </span>
                                    <span class="icon-title">
                                        <span class="d-block">Accounts</span>
                                        <small class="text-muted">Synced</small>
                                        
                                </h3>


                                    </span>
                                <!-- step 1 end-->
                                <!-- step 1 content -->
                                <fieldset class="pt-0">
                                    <h6 class="pb-50">Synched account(s) with eBay</h6>
                                     <small class="text-muted">
                                            <a href="/ebay/authenticate">Add More Account</a>
                                     </small>
                                    <div class="row">
                                        
                                                 <?php

                           
					                            if (isset($consent['authnauth']) && is_array($consent['authnauth'])) {
                                                    $i = 0;
					                            foreach ($consent['authnauth'] as $key => $value) {
					                                   $i++;
					                                ?>
                                                    <div class="col-sm-4">
					                                <div class="card text-center">
					                                                <div class="card-body py-1">
					                                                    <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
					                                                        <i class="bx bx-user font-medium-5"><?php echo $i;?></i>
					                                                    </div>
					                                                    <h5 class="mb-0"><?php echo $value['account'];?></h5>
					                                                    <div class="text-muted line-ellipsis"><?php echo ($value['method'] == null ? 'Self-Signed' : $value['method']);?></div>
					                                                </div>
					                                            </div>
                                                    </div>
					                                
					                                <?php
					                            }
					                            }
					                           
					                            ?>
                                        
                                        
                                    </div>
                                
                                 
                                </fieldset>
                                <!-- step 1 content end-->
                                <!-- step 2 -->
                                <h3>
                                    <span class="fonticon-wrap mr-1">
                                        <i class="livicon-evo" data-options="name:location.svg; size: 50px; style:lines; strokeColor:#adb5bd;"></i>
                                    </span>
                                    <span class="icon-title">
                                        <span class="d-block">Business Policy</span>
                                        <small class="text-muted">Payment, Shipment and Return</small>
                                    </span>
                                </h3>
                                <!-- step 2 end-->
                                <!-- step 2 content -->
                                <fieldset class="pt-0">
                                    <div class="card-title">
                                <h4>Business Policy</h4>
                            </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <?php
                                           	if ($policies) {

                                           		
                                           		?>
                                           		  <!-- Nav Tabs End Aligned Starts -->
						                <section id="nav-tabs-end">
						                    <div class="row">
						                        <div class="col-sm-12">
						                           
						                                   
						                                    <ul class="nav nav-tabs justify-content-end" role="tablist">
						                                    	<?php
						                                    	$i = 0;
						                                    	foreach ($policies as $key => $value) {
						                                   			foreach ($value as $account => $policy) {
						                                   				$i++;
						                                   				?>
						                                   				 <li class="nav-item">
								                                            <a class="nav-link <?php echo ($i == 1 ? 'active' : '');?>" id="<?php echo $account;?>-tab-end" data-toggle="tab" href="#<?php echo $account;?>-align-end" aria-controls="<?php echo $account;?>-align-end" role="tab" aria-selected="true">
								                                                <?php echo $account;?>
								                                            </a>
								                                        </li>
						                                   				<?php
						                                   			}
						                                   		}
						                                    	?>
						                                       
						                                       
						                                    </ul>
						                                    <div class="tab-content">
						                                    	<?php
						                                    	$i=0;
                                                        
						                                    	foreach ($policies as $key => $value) {
						                                   			foreach ($value as $account => $policy) {

						                                   				$i++;
						                                   				?>
						                                   				 <div class="tab-pane <?php echo ($i == 1 ? 'active' : '');?>" id="<?php echo $account;?>-align-end" aria-labelledby="<?php echo $account;?>-tab-end" role="tabpanel">
								                                            <?php
								                                        
								                                            foreach ($policy as $k => $v) {
								                                            	
								                                            	?>
								                                            	<h6><?php echo ucfirst($k);?> Policy<?php echo $i;?></h6>
								                                            	<fieldset class="form-group">
									                                                <select class="form-control" id="basicSelect">
									                                                	<?php
									                                                	foreach ($v as $pkey => $pvalue) {
										                                            	
										                                            		?>
										                                            		<option <?php echo ($pvalue['default'] == true ? 'selected' : '')?>><?php echo $pvalue['name'];?> <?php echo ($pvalue['default'] == true ? '(default)' : '')?></option>
										                                            		<?php
										                                            	}
									                                                	?>
									                                                    
									                                                </select>
									                                            </fieldset>
								                                            	<?php
								                                            }
								                                            ?>
								                                        </div>
						                                   				<?php
						                                   			}
						                                   		}
						                                    	?>

						                                       
						                                        
						                                    </div>
						                               
						                        </div>
						                    </div>
						                </section>
                <!-- Nav Tabs End Aligned Ends -->
                                           		<?php
                                           	}
                                            ?>
                                        </div>
                                        
                                       
                                    </div>
                                </fieldset>
                                <!-- step 2 content end-->

                                <!-- step 4 -->
                                <h3>
                                    <span class="fonticon-wrap mr-1">
                                        <i class="livicon-evo" data-options="name:truck.svg; size: 50px; style:lines; strokeColor:#adb5bd;"></i>
                                    </span>
                                    <span class="icon-title">
                                        <span class="d-block">Shipment Request</span>
                                        <small class="text-muted">Axis</small>
                                    </span>
                                </h3>
                                <!-- step 4 end-->
                                <!-- step 4 content -->
                                 <!-- step 2 content -->
                    
                                     
                                            <fieldset class="pt-0">
                                   <div class="card-title">
                                <h4>Axis Shipment</h4>
                            </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <?php
                                            if ($shipment) {

                                                
                                                ?>
                               <section id="basic-tabs-components">
                   
                           
                            <ul class="nav nav-tabs" role="tablist">
                                <?php
                                $i = 0;
                                foreach ($shipment as $key => $value) {
                                    $i++;
                                    ?>
                                    <li class="nav-item">
                                    <a class="nav-link <?php echo ($i == 0 ? 'active': '');?>" id="<?php echo $value->account;?>-tab" data-toggle="tab" href="#<?php echo $value->account;?>" aria-controls="<?php echo $value->account;?>" role="tab" aria-selected="true">
                                        <i class="bx bx-home align-middle"></i>
                                        <span class="align-middle"><?php echo $value->account;?></span>
                                    </a>
                                    </li>
                                    <?php
                                }
                                ?>
                                
                                
                            </ul>
                            <div class="tab-content">
                               
                                <?php
                                foreach ($shipment as $key => $value) {
                                   ?>
                                    <div class="tab-pane <?php echo ($i == 0 ? 'active': '');?>" id="<?php echo $value->account;?>" aria-labelledby="<?php echo $value->account;?>-tab" role="tabpanel">
                                     <div class="col-12">


                                <div class="form-group">
                                    <label for="eventName13">Axis Shop ID</label>
                                    <?php echo ($value->axis_shop_id != 0? $value->axis_shop_id: 'Not Setup');?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="eventName13">Axis Shop Name</label>
                                  
                                     <?php echo ($value->axis_shop_name != null? $value->axis_shop_name: 'Not Setup');?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="eventName13">Marketplace ID</label>
                                    <?php echo $value->marketplace_id;?>
                                </div>
                            </div>
                                </div>
                                   <?php
                                }
                                ?>
                            </div>
                
                </section>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        
                                       
                                    </div>
                                </fieldset>
                                        
                                <!-- step 4 content end-->
                            </form>
                        </div>
                    </div>
                </section>
                <!-- vertical Wizard end-->
		<?php
	}
?>

@endsection


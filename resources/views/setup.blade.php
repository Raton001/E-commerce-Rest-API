@extends('layouts.app')

@section('assets')

{{Html::style('assets/css/plugins/forms/wizard.css')}}
@endsection

@section('title')
    Setup
@endsection

<?php
  if (isset($shopname)) { 
?>
@section('shopname')
    <?php echo $shopname;?>
@endsection
<?php
}
?>

@section('content')
<?php

if ($firstTime) {
?>
<div class="card-body">
 <form action="#" class="wizard-validation">
    <input type="hidden" id="edit-account" value="<?php echo $edit;?>">
        <input type="hidden" id="marketplacename" value="<?php echo $marketplace;?>">

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

            <button class="btn btn-outline-secondary"><a  href="/<?php echo $marketplace;?>/authenticate">Authorization</a></button>
            </div>
            
        </div>
        
    </fieldset>
    <!-- body content of step 1 end -->
     <?php  if ($marketplaceID == 1) {?>
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
<?php } ?>
     <!-- Step 3 -->
    <h6>
        <i class="step-icon"></i>
       <span>Step <?php echo ($marketplaceID ==1 ? '3': '2');?></span>
    </h6>
    <!-- step 2 -->
    <!-- body content of step 2 end -->
    <fieldset>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-outline-info"><a id="last-shipment-btn" href="https://auth.ebay.com/oauth2/authorize?client_id=karennai-middlema-PRD-ec8ec878c-badd14db&response_type=code&redirect_uri=karen_nair-karennai-middle-chacvvjxx&scope=https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly https://api.ebay.com/oauth/api_scope/sell.finances https://api.ebay.com/oauth/api_scope/sell.payment.dispute https://api.ebay.com/oauth/api_scope/commerce.identity.readonly">Shipment</a></button>
                
            </div>
            
        </div>
        <div class="row" style="margin: 15px 0;">

            <div class="col-md-6">
                <div class="card">
                    <div class="card-hader  text-left">
                         <small class="text-muted">Kindly type in your Axis username to fetch the corresponding shop in Axis for shipment request creation purpose.</small>
                    </div>
                    <div class="card-body text-left">
                        <fieldset>
                            <label>Axis Username</label>
                                <div class="input-group">
                                    <input type="text" id="memberv2-username" class="form-control" placeholder="" aria-describedby="getAxisShop" value="<?php echo (isset($shipment[0]->axis_username) ? $shipment[0]->axis_username : '')?>">
                                    <div class="input-group-append" id="getAxisShop">
                                        <button class="btn btn-secondary" type="button">Go</button>
                                    </div>
                                </div>
                            </fieldset>

                       
                            

                            <label>Shop</label>
                             <input type="hidden" id="marketplace" value="<?php echo $marketplaceID;?>">
                             <input type="hidden" id="shopUserID" value="<?php echo (isset($shipment[0]->axis_user_id) ? $shipment[0]->axis_user_id : '')?>">
                             <input type="hidden" id="shopUserName" value="<?php echo (isset($shipment[0]->axis_username) ? $shipment[0]->axis_username : '')?>">


                            <select id="shops" class="form-control">
                                <?php
                                if (isset($shipment[0]->axis_shop_id)) {
                                    ?>
                                    <option selected="selected" value="<?php echo $shipment[0]->axis_shop_id;?>"><?php echo $shipment[0]->axis_shop_name;?></option>
                                    <?php
                                }
                                ?>
                            </select>

                    </div>
                </div>
            </div>

             <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-left">
                        <label>Are you SME?</label>
           
                         <select id="smeornot" class="form-control">
                             <option value="1" <?php echo (isset($shipment[0]->is_sme) && $shipment[0]->is_sme == 1? 'selected':'');?>>Yes</option>
                             <option value="0" <?php echo (isset($shipment[0]->is_sme) && $shipment[0]->is_sme == 0? 'selected':'');?>>No</option>

                         </select>

                         <label>Registration Number (Only fill up if you answered YES above)</label>
                         <input type="text" id="reg_no" class="form-control" value="<?php echo (isset($shipment[0]->registration_no) ? $shipment[0]->registration_no :'');?>">
                    </div>
                </div>
            </div>
            
        </div>
    </fieldset>
    <!-- body content of step 2 end -->
           
           
        </form>
    </div>
<?php
} else {

?>

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
                <a href="/<?php echo $marketplace;?>/authenticate">Add More Account</a>
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
                            <h5 class="mb-0">
                                <?php
                                if ($marketplaceID == 2) {
                                    echo $value['shopname'];
                                } else {
                                    echo $value['account'];
                                }
                                ?>
                                    
                                </h5>
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

   <?php
   //only for ebay
    if ($marketplaceID == 1) {
        
    ?>
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
            <section id="nav-tabs-end">
                <div class="row">
                    <div class="col-sm-12">
                                                
                    <ul class="nav nav-tabs justify-content-end" role="tablist">
                        <?php
                        $i = 0;
                        if ($policies) {
                      
                       
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
                        }
                        ?>
                       
                       
                    </ul>
                    <div class="tab-content">
                        <?php
                        $i=0;
                 if ($policies) {
                        foreach ($policies as $key => $value) {
                            foreach ($value as $account => $policy) {

                                $i++;
                                ?>
                                 <div class="tab-pane <?php echo ($i == 1 ? 'active' : '');?>" id="<?php echo $account;?>-align-end" aria-labelledby="<?php echo $account;?>-tab-end" role="tabpanel">
                                    <?php
                                
                                    foreach ($policy as $k => $v) {
                                        
                                        ?>
                                        <h6><?php echo ucfirst($k);?> Policy</h6>
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
                        
                 }
                        ?>

                       
                        
                    </div>
                           
                    </div>
                </div>
            </section>
<!-- Nav Tabs End Aligned Ends -->
                   
            </div>
            
           
        </div>
    </fieldset>
    <!-- step 2 content end-->
     <?php
                }
                ?>

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
    foreach ($consent['authnauth'] as $key => $value) {
        ?>
        <li class="nav-item">
        <a class="nav-link" id="<?php echo $value['account'];?>-tab" data-toggle="tab" href="#<?php echo $value['account'];?>" aria-controls="<?php echo $value['account'];?>" role="tab" aria-selected="true">
            <i class="bx bx-home align-middle"></i>
            <span class="align-middle"><?php
            if ($marketplaceID == 2) {
                echo $value['shopname'];
            } else {
                echo $value['account'];
            }
            ?></span>
        </a>
        </li>
        <?php
        $i++;

    }
    ?>
    
    
</ul>
<div class="tab-content">
   
    <?php
    $j = 0;


     foreach ($consent['authnauth'] as $key => $value) {

       ?>
        <div class="tab-pane text-left" id="<?php echo $value['account'];?>" aria-labelledby="<?php echo $value['account'];?>-tab" role="tabpanel">

             <div class="col-12">
                <div class="form-group">
                    <label for="eventName13">Axis Shop ID</label>
                    <?php echo ($shipment[$j]->axis_shop_id != 0? $shipment[$j]->axis_shop_id: 'Not Setup');?>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="eventName13">Axis Shop Name</label>
                  
                     <?php echo ($shipment[$j]->axis_shop_name != null? $shipment[$j]->axis_shop_name: 'Not Setup');?>
                </div>
            </div>
             <div class="col-12">
                                <div class="form-group">
                                    <label for="eventName13">Axis Shop User ID</label>
                                  
                                     <?php echo ($shipment[$j]->axis_user_id != null? $shipment[$j]->axis_user_id: 'Not Setup');?>
                                </div>
                            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="eventName13">Marketplace ID</label>
                    <?php echo $shipment[$j]->marketplace_id;?>
                </div>
            </div>

             <div class="col-12">
                <div class="form-group">
                    <label for="eventName13">Is SME?</label>
                    <?php echo ($shipment[$j]->is_sme == 1? 'yes': 'no');?>
                </div>
            </div>


             <div class="col-12">
                <div class="form-group">
                    <label for="eventName13">SME ID</label>
                    <?php echo $shipment[$j]->sme_id;?>
                </div>
            </div>

             <div class="col-12">
                <div class="form-group">
                    <label for="eventName13">Registration No</label>
                    <?php echo $shipment[$j]->registration_no;?>
                </div>
            </div>

             <div class="col-12">
                <div class="form-group">
                    <label for="eventName13">
                        <a href="/<?php echo $marketplace;?>/<?php echo $value['account'];?>/edit/shipment">Edit</a>
                    </label>
                   
                </div>
            </div>


       </div>
       <?php
        $j++;

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

<?php
}
?>

@endsection

@section('assets')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

{{Html::script('assets/js/scripts/navs/navs.js')}}
{{Html::script('assets/js/scripts/forms/wizard-steps.js')}}

@endsection

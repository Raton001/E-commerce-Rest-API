@extends('layouts.app')

@section('content')
    <div class="">
        <!-- <div class="content-overlay"></div> -->
        <div class="content-wrapper">
            <div class="content-header">
            </div>
            <div class="content-body">
     
                <section id="">

                <div class="row">
                  <div class="col-md-9">
                    <?php

                    foreach ($launchpack as $key => $value) {

                      if (isset($value['template'])) {

                      foreach ($value['template'] as $k => $v) {
                       
                      
                      ?>
                      <form  method="post" id="listingForm">
                          @csrf 
                   <section class="invoice-view-wrapper" id="listing-launchpack" data-listing-container>
                    <div class="row">
                        <!-- invoice view page -->
                        <div class="col-xl-12 col-md-12 col-12">
                            <div class="card invoice-print-area">
                                <div class="card-body pb-0 mx-25">
                                    <!-- header section -->
                                    <div class="row">
                                        <div class="col-lg-4 col-md-12">
                                         
                                            <span class="invoice-number mr-50">#<?php echo $value['pack'][0]['launch_name'];?> </span>
                                        
                                     
                                           
                                        </div>
                                        <div class="col-lg-8 col-md-12">
                                            <div class="d-flex align-items-center justify-content-lg-end flex-wrap">
                                                <div class="mr-3">
                                                    <small class="text-muted">Launch Date:
                                                      <?php 
                                                      
                                                      $date=date_create($value['pack'][0]['launch_date']);
                                                      echo date_format($date,"Y/m/d H:i:s");
                                                      ?></small>
                                                    <span></span>
                                                </div>
                                               
                                            </div>
                                        </div>
                                    </div>
                                    <!-- logo and title -->
                                    <div class="row my-2 my-sm-3">
                                        <div class="col-sm-12 col-12">
                                          <!--non-modified data-->
                                          <input data-input data-input type="hidden" name="<?php echo $v['AutoPay']['name'];?>" value="<?php echo $v['AutoPay']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['ShipToLocations']['name'];?>" value="<?php echo $v['ShipToLocations']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['Site']['name'];?>" value="<?php echo $v['Site']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['SiteId']['name'];?>" value="<?php echo $v['SiteId']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['BuyerResponsibleForShipping']['name'];?>" value="<?php echo $v['BuyerResponsibleForShipping']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['CategoryMappingAllowed']['name'];?>" value="<?php echo $v['CategoryMappingAllowed']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['ConditionID']['name'];?>" value="<?php echo $v['ConditionID']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['Country']['name'];?>" value="<?php echo $v['Country']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['CrossBorderTrade']['name'];?>" value="<?php echo $v['CrossBorderTrade']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['Currency']['name'];?>" value="<?php echo $v['Currency']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['DisableBuyerRequirements']['name'];?>" value="<?php echo $v['DisableBuyerRequirements']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['eBayPlus']['name'];?>" value="<?php echo $v['eBayPlus']['value'];?>">

                                          <input data-input type="hidden" name="<?php echo $v['ExtendedSellerContactDetails']['ClassifiedAdContactByEmailEnabled']['name'];?>" value="<?php echo $v['ExtendedSellerContactDetails']['ClassifiedAdContactByEmailEnabled']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['ExtendedSellerContactDetails']['ContactHoursDetails']['Hours1AnyTime']['name'];?>" value="<?php echo $v['AutoPay']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['ExtendedSellerContactDetails']['ContactHoursDetails']['Hours1Days']['name'];?>" value="<?php echo $v['ExtendedSellerContactDetails']['ContactHoursDetails']['Hours1Days']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['ExtendedSellerContactDetails']['ContactHoursDetails']['Hours1From']['name'];?>" value="<?php echo $v['ExtendedSellerContactDetails']['ContactHoursDetails']['Hours1From']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['ExtendedSellerContactDetails']['ContactHoursDetails']['Hours1To']['name'];?>" value="<?php echo $v['ExtendedSellerContactDetails']['ContactHoursDetails']['Hours1To']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['ExtendedSellerContactDetails']['ContactHoursDetails']['Hours2AnyTime']['name'];?>" value="<?php echo $v['AutoPay']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['ExtendedSellerContactDetails']['ContactHoursDetails']['Hours2Days']['name'];?>" value="<?php echo $v['ExtendedSellerContactDetails']['ContactHoursDetails']['Hours2Days']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['ExtendedSellerContactDetails']['ContactHoursDetails']['Hours2From']['name'];?>" value="<?php echo $v['ExtendedSellerContactDetails']['ContactHoursDetails']['Hours2From']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['ExtendedSellerContactDetails']['ContactHoursDetails']['Hours2To']['name'];?>" value="<?php echo $v['ExtendedSellerContactDetails']['ContactHoursDetails']['Hours2To']['value'];?>">
                                          <input data-input type="hidden" name="<?php echo $v['ExtendedSellerContactDetails']['ContactHoursDetails']['TimeZoneID']['name'];?>" value="<?php echo $v['ExtendedSellerContactDetails']['ContactHoursDetails']['TimeZoneID']['value'];?>">

                                          <input data-input type="hidden" name="<?php echo $v['IncludeRecommendations']['name'];?>" value="<?php echo $v['IncludeRecommendations']['value'];?>">
                                           <input data-input type="hidden" name="<?php echo $v['ListingDuration']['name'];?>" value="<?php echo $v['ListingDuration']['value'];?>">
                                            <input data-input type="hidden" name="<?php echo $v['ListingType']['name'];?>" value="<?php echo $v['ListingType']['value'];?>">
                                             <input data-input type="hidden" name="<?php echo $v['Location']['name'];?>" value="<?php echo $v['Location']['value'];?>">
                                              <input data-input type="hidden" name="<?php echo $v['PaymentMethods']['name'];?>" value="<?php echo $v['PaymentMethods']['value'];?>">

                                               <input data-input type="hidden" name="<?php echo $v['PrivateListing']['name'];?>" value="<?php echo $v['PrivateListing']['value'];?>">
                                          

                                            <h4>
                                               <label class="form-check-label"></label>
                                          <input type="checkbox" name="count_1_check" id="count_1" class="form-check-input" data-listing value="" checked>
                                              <input data-input  class="text-primary" type="text" name="<?php echo $v['Title']['name'];?>" value="<?php echo $v['Title']['value'];?>">
                                            </h4>
                                            <span>Package ID: <input data-input type="text" name="<?php echo $v['ApplicationData']['name'];?>" value="<?php echo $v['ApplicationData']['value'];?>"></span>
                                        </div>
                                      </div>
                                    <div class="row my-2 my-sm-3">
                                        
                                        <div class="col-sm-12 col-12 text-center text-sm-right order-1 order-sm-2 d-sm-flex justify-content-end mb-1 mb-sm-0">
                                         <input data-input type="hidden" name="<?php echo $v['PictureDetails']['GalleryType']['name'];?>" value="<?php echo $v['PictureDetails']['GalleryType']['value'];?>">
                                         <?php
                                         $PictureURL = explode(',http', $v['PictureDetails']['PictureURL']['value']);
                                     
                                         ?>
            <section id="component-swiper-coverflow">
                    <div class="card ">
                       
                        <div class="card-body">
                            <div class="swiper-coverflow swiper-container">
                                <div class="swiper-wrapper">
                                   
                                      <?php
                                      foreach ($PictureURL as $key => $url) {

                                        if (strpos($url, 'http') !== false) {
                                          $url = $url;
                                        } else {
                                          $url = 'http'.$url;
                                        }

                                        ?>
                                        <div class="swiper-slide"> <img class="archive-store-gallery" name="<?php echo $v['PictureDetails']['PictureURL']['name'];?>" src="<?php echo $url;?>" alt="banner">
                                        </div>
                                        <?php
                                        
                                      }
                                      ?>
  
                                </div>
                          
                                <div class="swiper-pagination swiper-pagination-white"></div>
                            </div>
                        </div>
                    </div>
                </section>
                                           

                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-4">
                                            <span>Start Price</span>
                                            <input data-input type="text" name="<?php echo $v['StartPrice']['currencyID']['name'];?>" value="<?php echo trim($v['StartPrice']['currencyID']['value']);?>">
                                            <?php
                                            if (isset($v['StartPrice']['StartPrice']['name'])) {
                                              ?>
                                              <input data-input data-selling-price type="text" name="<?php echo $v['StartPrice']['StartPrice']['name'];?>" value="<?php echo trim($v['StartPrice']['StartPrice']['value']);?>">
                                              <?php
                                            }
                                            ?>
                                            
                                            <input type="hidden" data-netprofit value="<?php echo (isset($value['pricelist']->net_profit)? $value['pricelist']->net_profit: '');?>">
                                        </div>
                                        <div class="col-4">
                                            <span>Category</span><br/>

                                           <input data-input type="text" name="<?php echo $v['PrimaryCategory']['CategoryID']['name'];?>" value="<?php echo $v['PrimaryCategory']['CategoryID']['value'];?>">
                                        </div>
                                        <div class="col-4">
                                            <span>Quantity</span><br/>

                                          <input data-input type="number" style="width: 20px;" name="<?php echo $v['Quantity']['value'];?>" value="<?php echo $v['Quantity']['value'];?>">
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- invoice address and contact -->
                                    <div class="row invoice-info">
                                        <div class="col-sm-6 col-12 mt-1">
                                            <h6 class="invoice-from">Return Policy</h6>
                                            <div class="mb-1">
                                                <span>Description</span>
                                            </div>
                                            <div class="mb-1">
                                                <span>InternationalRefundOption</span>
                                            </div>
                                            <div class="mb-1">
                                                <span>InternationalReturnsAcceptedOption</span>
                                            </div>
                                            <div class="mb-1">
                                                <span>RefundOption</span>
                                            </div>
                                            <div class="mb-1">
                                                <span>ReturnsAcceptedOption</span>
                                            </div>
                                            <div class="mb-1">
                                                <span>ReturnsWithinOption</span>
                                            </div>
                                            <div class="mb-1">
                                                <span>ShippingCostPaidByOption</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12 mt-1">
                                            <h6 class="invoice-to"></h6>
                                            <div class="mb-1">
                                                <span><input data-input type="text" name="<?php echo $v['ReturnPolicy']['Description']['name'];?>" value="<?php echo $v['ReturnPolicy']['Description']['value'];?>"></span>
                                            </div>
                                            <div class="mb-1">
                                                <span><input data-input type="text" name="<?php echo $v['ReturnPolicy']['InternationalRefundOption']['name'];?>" value="<?php echo $v['ReturnPolicy']['InternationalRefundOption']['value'];?>"></span>
                                            </div>
                                            <div class="mb-1">
                                                <span><input data-input type="text" name="<?php echo $v['ReturnPolicy']['InternationalReturnsAcceptedOption']['name'];?>" value="<?php echo $v['ReturnPolicy']['InternationalReturnsAcceptedOption']['value'];?>"></span>
                                            </div>
                                            <div class="mb-1">
                                                <span><input data-input type="text" name="<?php echo $v['ReturnPolicy']['RefundOption']['name'];?>" value="<?php echo $v['ReturnPolicy']['RefundOption']['value'];?>"></span>
                                            </div>
                                            <div class="mb-1">
                                                <span><input data-input type="text" name="<?php echo $v['ReturnPolicy']['ReturnsAcceptedOption']['name'];?>" value="<?php echo $v['ReturnPolicy']['ReturnsAcceptedOption']['value'];?>"></span>
                                            </div>
                                            <div class="mb-1">
                                                <span><input data-input type="text" name="<?php echo $v['ReturnPolicy']['ReturnsWithinOption']['name'];?>" value="<?php echo $v['ReturnPolicy']['ReturnsWithinOption']['value'];?>"></span>
                                            </div>
                                            <div class="mb-1">
                                                <span><input data-input type="text" name="<?php echo $v['ReturnPolicy']['ShippingCostPaidByOption']['name'];?>" value="<?php echo $v['ReturnPolicy']['ShippingCostPaidByOption']['value'];?>"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <!-- product details table-->
                                <div class="invoice-product-details table-responsive">
                                     <section id="accordionWrapa">
                   
                    <div class="accordion" id="accordionWrapa1">
                        <div class="card collapse-header">
                            <div id="heading1" class="card-header" role="tablist" data-toggle="collapse" data-target="#accordion1" aria-expanded="false" aria-controls="accordion1">
                                <span class="collapse-title">ItemSpecifics</span>
                            </div>
                            <div id="accordion1" role="tabpanel" data-parent="#accordionWrapa1" aria-labelledby="heading1" class="collapse">
                                <div class="card-body">
                                   <table class="table table-borderless mb-0">
                                        <thead>
                                            <tr class="border-0">
                                              <th scope="col"></th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Value</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                          <?php
                                        $i = 0;
                                        if (isset($v['ItemSpecifics'])) {

                                        
                                          foreach ($v['ItemSpecifics'] as $item => $specs) {
                                            $i++;
                                            foreach ($specs as $i => $sp) {
                                              ?>
                                              <tr>
                                                <td><?php echo $i;?></td>
                                                <td><input data-input type="text" name="<?php echo $sp['Name']['name'];?>" value="<?php echo $sp['Name']['value'];?>"></td>
                                                <td><input data-input type="text" name="<?php echo $sp['Value']['name'];?>" value="<?php echo $sp['Value']['value'];?>"></td>
                                                
                                               
                                            </tr>
                                              <?php
                                            }
                                            }
                                          }
                                          ?>
                                           
                                          
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>
                <!-- Accordion end -->
                                </div>

                                <!-- product details table-->
                                <div class="invoice-product-details table-responsive">
<!-- full Editor start -->
                <section class="full-editor">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                 
                                    <h4 class="card-title">Description</h4>
                                   
                                </div>
                                <div class="card-body">
                                    
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div id="full-wrapper">
                                                <div id="full-container">

                                                  <?php
                                                  if (isset($v['Description'])) {
                                                    ?>
                                                      <div class="editor" data-desc id="<?php
                                                        echo $v['Description']['name'];
                                                        ?>">
                                                      <!-- <pre> -->
                                                       
                                                        <?php
                                                        echo html_entity_decode($v['Description']['value']);
                                                        ?>
                                                      <!-- </pre> -->
                                                    </div>
                                                    <?php
                                                  }
                                                  ?>
                                                  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- full Editor end -->

                                </div>
                                <!-- invoice subtotal -->
                                <div class="card-body pt-0 mx-25">
                                    <hr>
                                    <div class="row">
                                        <div class="col-4 col-sm-6 col-12 mt-75">
                                          <!--  <input data-input type="button" value="Launch to eBay" data-btn-submit class="btn btn-primary mb-1"> -->

                                           <button type="button" data-input class="btn btn-primary glow" data-btn-submit>
                            <span data-spinner class="spinner-border spinner-border-sm hidden" role="status" aria-hidden="true"></span>
                            <span data-spinner-text>Launch to eBay</span>
                        </button>

                                        </div>
                                        <div class="col-8 col-sm-6 col-12 d-flex justify-content-end mt-75">
                                            <div class="invoice-subtotal">
                               
                                                <div class="invoice-calc d-flex justify-content-between">
                                                    <span class="invoice-title">Total</span>
                                                    <span class="invoice-value" data-total-selling-price></span>
                                                </div>
                                                <div class="invoice-calc d-flex justify-content-between">
                                                    <span class="invoice-title">Estimated Net profit</span>
                                                    <span class="invoice-value" data-total-netprofit></span>
                                                </div>
                                                
                                                 
                                                
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>
                

                        </form>
                      <?php
                    }
                    }
                  }
                    ?>
                  </div>
                  <div class="col-md-3">
                    @include('sidebar')
          
                  </div>
                </div>
                </section>
            </div>
        </div>
    </div>
@endsection

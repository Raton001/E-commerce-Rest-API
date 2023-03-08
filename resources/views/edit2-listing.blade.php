@extends('layouts.app')

@section('content')

<!--     <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body"> -->
              
              <section id="">
                    <div class="row justify-content-center">
                      <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                            
                            <div class="row">
                              <div class="col-md-12">
                                {{ __('Edit Listing') }}
                              </div>
                            </div>
                          </div>

                            <div class="card-body">

                              <div class="row">
                                  <div class="col-md-12">
                                    <?php
                                    $url = 'ebay/'.$account.'/list/'.$itemID;
                                    ?>
                                   <form action="{{ url($url) }}" method="post" id="listingForm" enctype="multipart/form-data">
                                    @csrf 

                                    
                                      <?php echo $hiddenFields;?>
                                   

                                    <!--row 1-->
                                     <div class="row">
                                      <div class="col-md-12">
                                        <div class="card">
                                          <div class="card-header">

                                          </div>

                                          <div class="card-body">

                                            <div class="row">
                                              <div class="col-md-6">
                                                <div class="card">
                                                  <div class="card-header"></div>
                                                  <div class="card-body">

                                                    <div class="row">
                                                      <div class="col-md-12">
                                                     
                                                          <img src="<?php echo $listing['PictureDetails']['GalleryURL']['value']?>" style="width: 300px;">
                                                          <input type="file" name="GalleryURL" class="form-control" value="<?php echo (isset($listing['PictureDetails']['GalleryURL']['value']) ? $listing['PictureDetails']['GalleryURL']['value'] : '')?>">
                                                      </div>
                                                    </div>
                                                    <div class="row">
                                                      <div class="col-md-12">
                                                        
                                                          <?php
                                                                    $photos = $listing['PictureDetails']['PictureURL'];
                                                                    if (is_array($photos)) {

                                                                      foreach ($photos as $key => $value) {

                                                                        if (is_string($value)) {
                                                                          if (isset($value['value'])) {
                                                                            ?>
                                                                             <img src="<?php echo $value['value'];?>" style="width: 100px;">
                                                                            <?php
                                                                          } else {
                                                                            ?>
                                                                             <img src="<?php echo $value;?>" style="width: 100px;">
                                                                            <?php
                                                                          }
                                                                          ?>
                                                                         
                                                                          <?php
                                                                        }
                                                                        
                                                                      }
                                                                      
                                                                    } else {
                                                                      ?>
                                                                      <img src="<?php echo $photos['value'];?>" style="width: 100px;">
                                                                      <?php
                                                                    }
                                                                    ?>
                                                                  <input type="file" name="GalleryURL" class="form-control" multiple="multiple">
                                                      </div>
                                                    </div>

                                                   
                                                  </div>

                                                </div>
                                              </div>


                                              <div class="col-md-6">
                                                <div class="card">
                                                  <div class="card-header">
                                                    
                                                  </div>

                                                  
                                                  <div class="card-body">

                                                    <h1><input type="text" name="<?php echo $listing['Title']['name'];?>" value="<?php echo $listing['Title']['value'];?>"></h1>
                                                    

                                                    <p>StartPrice: <?php echo $listing['Currency']['value'];?>
                                                    <input type="text" name="<?php echo $listing['StartPrice']['name'];?>" value="<?php echo $listing['StartPrice']['value'];?>"></p>
                                                    <p>ListingDuration : <?php echo $listing['ListingDuration']['value'];?></p>
                                                    <p>ListingType : <?php echo $listing['ListingType']['value'];?> </p>

                                                    <p>Site : <?php echo $listing['Site']['value'];?> </p>
                                                    <p>Hit Count : <?php echo $listing['HitCount']['value'];?> </p>
                                                    <p>Watch Count : <?php echo $listing['WatchCount']['value'];?> </p>
                                                    <p>TimeLeft : <?php echo $listing['TimeLeft']['value'];?> </p>
                                                  </div>
                                                </div>
                                              </div>

                                            </div>


                                          </div>
                                        </div>
                                      </div>

                                    </div>

                                    <!--row 2-->
                                     <div class="row" style="margin-top: 20px;">

                                      <div class="col-md-6">

                                          <div class="card">
                                          <div class="card-header">{{ __('Category') }}</div>

                                          <div class="card-body">  
                                
                                       
                                                <?php
                                               echo $listing['PrimaryCategory']['CategoryID']['value'];
                                               echo '<br/>';
                                               echo $listing['PrimaryCategory']['CategoryName']['value'];
                                                ?>

                                              <h3>Item Specifics</h3>
                                              <table data-item-specs-table>
                                                <tbody>
                                                <?php
                                           
                                                foreach ($listing['ItemSpecifics']['NameValueList'] as $key => $value) {
                                                  unset($value['Source']);
                                                 
                                                 ?>
                                                 <tr>
                                                  <td>
                                                    <div class="livicon-evo" data-delete-itemspec data-options=" name: trash.svg; style: lines; size: 30px; strokeStyle: original; strokeWidth: original; tryToSharpen: true; rotate: none; flipHorizontal: false; flipVertical: false; strokeColor: #22A7F0; strokeColorAction: #b3421b; strokeColorAlt: #F9B32F; strokeColorAltAction: #ab69c6; fillColor: #91e9ff; fillColorAction: #ff926b; solidColor: #6C7A89; solidColorAction: #4C5A69; solidColorBgAction: #ffffff; solidColorBg: #ffffff; colorsOnHover: none; colorsHoverTime: 0.3; colorsWhenMorph: none; brightness: 0.1; saturation: 0.07; morphState: start; morphImage: none; allowMorphImageTransform: false; strokeWidthFactorOnHover: none; strokeWidthOnHoverTime: 0.3; keepStrokeWidthOnResize: false; animated: true; eventType: hover; eventOn: self; autoPlay: false; delay: 0; duration: default; repeat: default; repeatDelay: default; drawOnViewport: false; viewportShift: oneHalf; drawDelay: 0; drawTime: 1; drawStagger: 0.1; drawStartPoint: middle; drawColor: same; drawColorTime: 1; drawReversed: false; drawEase: Power1.easeOut; eraseDelay: 0; eraseTime: 1; eraseStagger: 0.1; eraseStartPoint: middle; eraseReversed: true; eraseEase: Power1.easeOut; touchEvents: false "></div>
                                                  </td>
                                                  <td>
                                                    <?php 
                                                    if (isset($recommended[$value['Name']['value']])) {
                                                      if($recommended[$value['Name']['value']]['Rule'] == 'Required') {
                                                        ?>
                                                        <span style="color: red;">*</span>
                                                        <?php
                                                      }
                                                    }
                                                    ?>
                                                  </td>
                                                   <td>
                                                    
                                                     <input class="itemspec_input" type="text" name="<?php echo $value['Name']['name'];?>" value="<?php echo $value['Name']['value'];?>">
                                                   </td>
                                                    <td>
                                                      <?php
                                                       if (is_string($value['Value']['value'])) {
                                                        ?>
                                                        <input data-rec-input type="text" name="<?php echo $value['Value']['name'];?>" value="<?php echo $value['Value']['value'];?>">
                                                        <?php
                                                       } else {
                                                        ?>
                                                        <input data-rec-input type="text" name="<?php echo $value['Value']['name'];?>" value="">
                                                        <?php
                                                       }
                                                      ?>
                                                     
                                                   </td>
                                                   <td>
                                                     <?php
                                                     if (isset($recommended[$value['Name']['value']])) {

                                                        $rec = $recommended[$value['Name']['value']]['Recommendation'];
                                                        if (is_array($rec)) {
                                                          ?>
                                                          <select style="width: 100px;" class="itemRecValListing">
                                                          <?php
                                                          foreach ($rec as $krec => $vrec) {
                                                            ?>
                                                            <option><?php echo $vrec;?></option>
                                                            <?php
                                                          }
                                                          ?>
                                                          </select>
                                                          <?php
                                                        }
                                                        ?>
                                                      
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


                                              <input type="button" data-add-item-specs value="Add custom Item specific"></button>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="card">
                                            <div class="card-header">{{ __('Return Policy') }}</div>

                                            <div class="card-body">  
                                    
                                       
                                             <table class="">
                                              <?php
                                              foreach ($listing['ReturnPolicy'] as $key => $value) {
                                                ?>
                                                 <tr>
                                                   <th><?php echo $key;?></th>
                                                   <td><?php echo $value['value'];?></td>
                                                 </tr>
                                                <?php
                                              }
                                              ?>
                                              
                                             </table>
                                          </div>
                                        </div>
                                    </div>

                                    </div>

                                    <!--row 3-->

                                     <div class="row" style="margin-top: 20px;">
                                          <div class="col-md-4">
                                            <div class="card">
                                              <div class="card-header">{{ __('Seller Shipping Profile') }}</div>

                                              <div class="card-body"> 
                                           
                                               <table class="">
                                                <tr>
                                                <?php
                                                foreach ($listing['SellerProfiles']['SellerShippingProfile'] as $key => $value) {
                                                  ?>
                                                  <td><?php echo $value['value'];?></td>
                                                  <?php
                                                }
                                                ?>
                                                </tr>
                                               </table>
                                           </div>
                                          </div>
                                          </div>

                                       
                                          <div class="col-md-4">
                                            <div class="card">
                                              <div class="card-header">{{ __('Seller Return Profile') }}</div>

                                              <div class="card-body"> 
                                      
                                             
                                               <table class="">
                                                <tr>
                                                <?php
                                                foreach ($listing['SellerProfiles']['SellerReturnProfile'] as $key => $value) {
                                                  ?>
                                                  <td><?php echo $value['value'];?></td>
                                                  <?php
                                                }
                                                ?>
                                                </tr>
                                               </table>
                                             </div>
                                            </div>
                                          </div>

                                      
                                          <div class="col-md-4">
                                            <div class="card">
                                            <div class="card-header">{{ __('Seller Payment Profile') }}</div>

                                            <div class="card-body"> 
                               
                                           
                                             <table  class="">
                                              <tr>
                                              <?php
                                              foreach ($listing['SellerProfiles']['SellerPaymentProfile'] as $key => $value) {
                                                ?>
                                                <td><?php echo $value['value'];?></td>
                                                <?php
                                              }
                                              ?>
                                              </tr>
                                               </table>
                                             </div>
                                           </div>
                                          </div>

                                        </div>




                                     <input type="submit" class="btn btn-primary" value="SUBMIT">
                                   </form>
                                  </div>

                                   
                              </div>
                            </div>
                        </div>
                      </div>
                  </div>
          </section>
<!--     </div>
</div>

</div> -->


@endsection

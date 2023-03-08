@extends('layouts.app')

@section('content')

              <section id="">
           
                    <div class="row justify-content-center">
                      <div class="col-12">
                        <div class="card">
                          <div class="card-header">
                             <div class="row">
                              <div class="col-3">
                                {{ __('Listing') }}
                              </div>
                              <div class="col-9 float-right">
                                <ul class="list-group list-group-horizontal">
                                  <li class="list-group-item"><a href="/ebay/<?php echo $account;?>/listing/edit/<?php echo $itemID;?>">Edit</a></li>
                                  <li class="list-group-item"><a href="">Re-List</a></li>
                                  <li class="list-group-item"><a href="/ebay/<?php echo $account;?>/sellsimilar/<?php echo $itemID;?>">Sell Similar</a></li>
                                </ul>
                              </div>
                            </div>

                          </div>
                          <div class="card-body">

                            <div class="row">
                                <div class="col-6">
                                        
                                      
                                    <div class="row">
                                      <div class="col-md-12">
                                        
                                          <img src="<?php echo $listing['Item']['PictureDetails']['GalleryURL']?>" style="width: 300px;">
                                          
                                      </div>
                                    </div>


                                   <div class="row">
                                    <div class="col-md-12">
                                      <?php

                                       $photos = $listing['Item']['PictureDetails']['PictureURL'];
                                      if (is_array($photos)) {
                                       
                                        foreach ($photos as $key => $value) {
                                          ?>
                                          <img src="<?php echo $value;?>" style="width: 100px;">
                                          <?php
                                        }
                                        
                                      } else {
                                        ?>
                                        <img src="<?php echo $photos;?>" style="width: 100px;">
                                        <?php
                                      }
                                      ?>

                                      

                                        
                                    </div>
                                  </div>

                              </div>
                              <div class="col-6">

                                  <h1><?php echo $listing['Item']['Title'];?></h1>
                                  <p>BuyItNowPrice: <?php echo $listing['Item']['Currency'];?>
                                  <?php echo $listing['Item']['BuyItNowPrice'];?> </p>

                                  <p>StartPrice: <?php echo $listing['Item']['Currency'];?>
                                  <?php echo $listing['Item']['StartPrice'];?></p>

                                  <p>ListingDuration : <?php echo $listing['Item']['ListingDuration'];?> </p>
                                  <p>ListingType : <?php echo $listing['Item']['ListingType'];?> </p>

                                  <p>Site : <?php echo $listing['Item']['Site'];?> </p>
                                  <p>Hit Count : <?php echo $listing['Item']['HitCount'];?> </p>
                                  <p>Watch Count : <?php echo $listing['Item']['WatchCount'];?> </p>
                                  <p>TimeLeft : <?php echo $listing['Item']['TimeLeft'];?> </p>
                                  

                                  

                                
                              </div>
                            </div>

                            <!--row 2-->

                            <div class="row" style="margin-top: 20px;">

                              <div class="col-md-6">

                                  <div class="card">

                                    <div class="card-header">{{ __('Category') }}</div>

                                    <div class="card-body">  
                               
                                      <?php
                                        echo $listing['Item']['PrimaryCategory']['CategoryName'];
                                      ?>

                                        <h3>Item Specifics</h3>
                                        <table class="">
                                          <?php
                                          if(isset($listing['Item']['ItemSpecifics'])) {

                                          foreach ($listing['Item']['ItemSpecifics']['NameValueList'] as $key => $value) {
                                            ?>
                                            <tr>
                                              <th><?php echo $value['Name'];?></th>
                                              <td>

                                                <?php 
                                                if (is_array($value['Value'])) {
                                                  ?>
                                                  <ul>
                                                    <?php
                                                    foreach ($value['Value'] as $specs) {
                                                      ?>
                                                      <li><?php echo $specs;?></li>
                                                      <?php
                                                    }
                                                    ?>  
                                                  </ul>
                                                  <?php
                                                } else {
                                                  echo $value['Value'];;
                                                }
                                                
                                                ?>
                                                  
                                                </td>

                                            </tr>
                                            <?php
                                          }
                                          }
                                          
                                          ?>
                                        </table>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="card">
                                    <div class="card-header">{{ __('Return Policy') }}</div>

                                    <div class="card-body">  
                            
                               
                                     <table class="">
                                      <?php
                                      foreach ($listing['Item']['ReturnPolicy'] as $key => $value) {
                                        ?>
                                         <tr>
                                           <th><?php echo $key;?></th>
                                           <td><?php echo $value?></td>
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
                            <div class="row">      
                             <div class="col-12">

                              <div class="card">
                                <div class="card-header">{{ __('Description') }}</div>

                                <div class="card-body">
                                 <textarea><?php echo html_entity_decode($listing['Item']['Description']);?></textarea>
                                </div>
                                </div>
                              </div>
                            </div>



                          </div>

                        </div>
                      </div>
                    </div>
                  
                </section>

      

@endsection

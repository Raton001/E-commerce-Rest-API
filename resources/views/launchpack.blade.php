@extends('layouts.app')
@section('assets')
    {{Html::style('css/pages/app-todo.css')}}
    {{Html::style('css/plugins/forms/wizard.css')}}
    {{Html::style('js/vendors/css/extensions/swiper.min.css')}}
    {{Html::style('css/plugins/extensions/swiper.css')}}
    {{Html::style('css/transformations.css')}}

@endsection
@section('content')
 <div class="launchpack">
      <!-- <div class="content-overlay"></div> -->
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Dashboard Ecommerce Starts -->
                <section id="">
                  <div class="row justify-content-center">
                      <div class="col-md-9">
                      <div class="card">
                          <div class="card-header">
                            <fieldset class="form-group">

                             <select data-selected-account class="custom-select">
                              <option value="All" selected>All Store</option>
                               <?php

                               if (isset($stores)) {

                                 foreach ($stores as $value) {
                                   ?>
                                   <option value="<?php echo $value;?>">
                                       <?php echo $value;?>
                                   </option>
                                   <?php
                                }
                               }


                               ?>
                               </select>
                             </fieldset>
                          </div>
                          <div class="card-body">
                             <div class="row" style="margin-top: 20px;">
                                <div class="col-md-12">
                                   <form  method="post" id="listingForm">
                                      @csrf

                                      <?php
                                      echo $hiddenFields;


                                     ?>
                                     <!--content starts-->

                               <div id="accordion">

                                 <div class="table-responsive">
                                  <!-- table start -->
                                  <table id="launchpacks" data-launchpacks class="table mb-0">
                                      <thead>
                                          <tr>
                                              <th>#</th>
                                              <td>Image</td>
                                              <th>Title</th>
                                              <th></th>
                                              <th>Required Selling Limit</th>

                                              <!-- <th>Estimated Net profit</th> -->

                                          </tr>
                                      </thead>
                                      <tbody>
                                      <?php
                                       $i = 0;
                                       $j = 0;

                                      foreach ($launchpack as $key => $value) {


                                        $pricelist2 = $value['pricelist2'];

                                        foreach ($value['pack'] as $k => $v) {
                                 $launchpackID = $v['id'];

                                        ?>
                                        <tr class="group">
                                          <td colspan="6">

                                             <a target="_blank" href="/ebay/launchpack/<?php echo $v['id']?>"><?php echo $v['launch_name'];?></a>

                                          </td>
                                      </tr>
                                      <?php

                                      foreach ($value['template'] as $key => $v) {
                                       $i++;
                                       $j++;

                                      ?>

                                      <tr data-listing-container>
                                          <td class="text-bold-600">
                                            <label class="form-check-label">
                                          <input type="checkbox" name="count_<?php echo $i;?>_check" id="count_<?php echo $i;?>" class="form-check-input" data-listing value="" checked>
                                        </label>
                                            </td>
                                            <td>
                                              <?php
                                         $PictureURL = explode(',http', $v['PictureDetails']['PictureURL']['value']);

                                         ?>
                                         <!-- <img src="<?php echo $PictureURL[0];?>" class="launchpack-avatar"> -->
                                            </td>
                                          <td>


                                              <input type="text" name="<?php echo $v['Title']['name']?>" value="<?php echo $v['Title']['value']?>">

                                          </td>
                                          <td><a href="/ebay/launchpack/<?php echo $launchpackID;?>/<?php echo $value['listing'][$v['ApplicationData']['value']];?>">view</a></td>
                                          <td class="text-bold-600 text-center"><i class="bx bx-trending-up text-success align-middle mr-50"></i>

                                            <?php
                                            if (isset($v['StartPrice']['StartPrice'])) {
                                              ?>
                                              <input type="text" data-selling-price data-input name="<?php echo $v['StartPrice']['StartPrice']['name'];?>" value="<?php echo $v['StartPrice']['StartPrice']['value'];?>">
                                              <?php
                                            }
                                            ?>

                                            <span>

                                              <?php


                                            // if (isset($pricelist2)) {

                                            //  if (isset($pricelist2[$v['ApplicationData']['value']]->selling_price)) {
                                            //   echo $pricelist2[$v['ApplicationData']['value']]->selling_price;
                                            //  }
                                            // }

                                            ?>
                                            </span>
                                          </td>
    <!--                                       <td class="text-bold-600 text-center">
                                            <span data-netprofit>
                                                <?php
                                              if (isset($pricelist2)) {
                                               if (isset($pricelist2[$v['ApplicationData']['value']]->net_profit)) {

                                                 echo $pricelist2[$v['ApplicationData']['value']]->net_profit;
                                               }
                                              }

                                              ?>
                                              </span>
                                          </td> -->


                                         <td>

                                  <!--item specs-->
                                  <div class="row" style="margin-top: 20px;display: none;">

  <div class="col-md-12">


    <div class="card">
      <div class="card-header">Category</div>

      <div class="card-body">
      <!-- description -->

    <?php
    if (isset($v['Description'])) {
      ?>
       <textarea data-input name="<?php echo $v['Description']['name'];?>">
           <?php echo $v['Description']['value'];?>
         </textarea>
      <?php
    }
    ?>



        <?php

        if (isset($v['PrimaryCategory']['CategoryID']['name'])) {
         echo $v['PrimaryCategory']['CategoryID']['name'];
        }

        ?>

       <h3>Item Specifics</h3>
          <table data-item-specs-table>
            <tbody>
            <?php
            $j = 0;
        if (isset($v['ItemSpecifics'])) {


            $specs = $v['ItemSpecifics']['NameValueList'];
            if (isset($specs)) {
              foreach ($specs as $key => $spec) {
                $j++;

                ?>
                   <div class="form-group">
                      <label><?php echo $spec['Name']['value'];?></label>
                     <input data-input type="hidden" value="<?php echo $spec['Name']['value'];?>" name="<?php echo $spec['Name']['name'];?>" value="<?php echo $key;?>">
                      <input data-input class="form-control" type="text" name="<?php echo $spec['Value']['name'];?>" value="<?php echo $spec['Value']['value'];?>">

                    </div>


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



                                         </td>
                                      </tr>


                                    <?php
                                    }
                                     }
                                   }
                                    ?>

                                  </tbody>
                                  <tfoot>

                                  <tr>
                                    <td></td>
                                    <td>Total</td>
                                    <td data-total-selling-price class="text-center"></td>
                                    <!-- <td data-total-netprofit class="text-center"></td> -->

                                  </tr>
                                </tfoot>

                                </table>
                               </div>
</div>

                                     <!--content ends-->


                                    <input type="button" value="Launch to eBay" data-btn-submit class="btn btn-primary mb-1">

                                   </form>
                                </div>
                              </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-3">
                        @include('sidebar')
                      </div>
                    </div>
                </section>
            </div>
        </div>
  </div>


    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
@endsection

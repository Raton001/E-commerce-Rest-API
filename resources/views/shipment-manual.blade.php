@extends('layouts.app')

@section('content')

                <!-- Dashboard Ecommerce Starts -->
                <section id="">

                    <input type="hidden" id="account" value="<?php echo $account;?>">
                    <input type="hidden" id="orderID" value="<?php echo $orderID;?>">

                    <select id="smeItemSpecs" name="sme" class="form-control">
                      <option>Select a Brand</option>
                      <?php
                        if (isset($sme)) {
                        


                        foreach ($sme as $key => $value) {
                          
                          ?>
                         
                          <option value="<?php echo $value->id."-".$value->brandID;?>">
                            
                            <?php echo $value->name;?>
                             
                            </option>

                          <?php

                          }
                        }
                      ?>
                    </select>

                    <p id="product_list_total"></p>
                    <select id="product_list" class="hidden form-control"></select>
                    <div id="invoiceTemp"></div>

                </section>




@endsection

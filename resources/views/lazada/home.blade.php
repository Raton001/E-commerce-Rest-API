@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-12">

                 <section id="basic-tabs-components">
                    <div class="card">
                       
                        <div class="card-body">
                           
                            <ul class="nav nav-tabs" role="tablist">
                                 <?php
                                 $i = 0;
                                    foreach ($store as $value) {
                                        $i++;
                                        ?>
                   
                                        <li class="nav-item">
                                            <a class="nav-link <?php echo ($i==0?'active':'');?>" id="<?php echo $value;?>-tab" data-toggle="tab" href="#<?php echo $value;?>" aria-controls="<?php echo $value;?>" role="tab" aria-selected="true">
                                                <i class="bx bx-<?php echo $value;?> align-middle"></i>
                                                <span data-store-name class="align-middle"><?php echo $value;?></span>
                                            </a>
                                        </li>

                                        <?php
                                    }
                                    ?>
                                
                                
                            </ul>
                            <div class="tab-content">
                                <?php
                                 $i = 0;
                                    foreach ($store as $value) {
                                        $i++;

                                        ?>
                   
                                        <div class="tab-pane <?php echo ($i==0?'active':'');?>" id="<?php echo $value;?>" aria-labelledby="<?php echo $value;?>-tab" role="tabpanel">
                               
                                   <!--content strats-->
                                    <div class="row" data-store-id>
                        <div class="col-xl-3 col-md-4 col-sm-6 " data-dash-stats>
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="badge-circle badge-circle-lg badge-circle-light-info mx-auto my-1">
                                        <i class="bx bx-edit-alt font-medium-5"></i>
                                    </div>
                                    <p class="text-muted mb-0 line-ellipsis">Available Selling Limit</p>
                                    <!-- <p class="text-muted font-small-1">(Available)</p> -->
                                    <h2 class="mb-0">RM
                                    <div class="spinner-grow spinner-grow text-light" role="status" data-spinner>
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                       
                                    <span data-available-limit></span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6 " data-dash-stats>
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="badge-circle badge-circle-lg badge-circle-light-warning mx-auto my-1">
                                        <i class="bx bx-file font-medium-5"></i>
                                    </div>
                                    <p class="text-muted mb-0 line-ellipsis">Order Total</p>
                                    <h2 class="mb-0">
                                        <div class="spinner-grow spinner-grow text-light" role="status" data-spinner>
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                        <span data-order-total></span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6 " data-dash-stats>
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto my-1">
                                        <i class="bx bx-message font-medium-5"></i>
                                    </div>
                                    <p class="text-muted mb-0 line-ellipsis">Order Volume</p>
                                    <h2 class="mb-0">
                                        $<div class="spinner-grow spinner-grow text-light" role="status" data-spinner>
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                        &nbsp;<span data-order-volume></span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6 " data-dash-stats>
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto my-1">
                                        <i class="bx bx-money font-medium-5"></i>
                                    </div>
                                    <p class="text-muted mb-0 line-ellipsis">Active Listing</p>
                                    <h2 class="mb-0">
                                        <div class="spinner-grow spinner-grow text-light" role="status" data-spinner>
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                            <span data-active-listing></span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-sm-6 " data-dash-stats>
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto my-1">
                                        <i class="bx bx-purchase-tag font-medium-5"></i>
                                    </div>
                                    <p class="text-muted mb-0 line-ellipsis">Awaiting Shipment</p>
                                    <h2 class="mb-0">
                                        <div class="spinner-grow spinner-grow text-light" role="status" data-spinner>
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                            <span data-pending-shipment></span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-xl-2 col-md-4 col-sm-6 ">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto my-1">
                                        <i class="bx bx-shopping-bag font-medium-5"></i>
                                    </div>
                                    <p class="text-muted mb-0 line-ellipsis">Order</p>
                                    <h2 class="mb-0">40</h2>
                                </div>
                            </div>
                        </div> -->
                    </div>
                                   <!--content ends-->
                                </div>

                                        <?php
                                    }
                                    ?>
                                
                                
                            </div>

                            <!--launchpacks starts-->
    <div class="card-body">
                <div class="todo-app-area">
                                    <div class="todo-app-list-wrapper">
                                        <div class="todo-app-list">
                                            <div class="todo-fixed-search d-flex justify-content-between align-items-left">
                                                <div class="sidebar-toggle d-block d-lg-none">
                                                    <i class="bx bx-menu"></i>
                                                </div>
                                                <div class="todo-sort dropdown ml-1">
                                                   <!--  <button class="btn sorting" type="button" id="sortDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        
                                                        <span>Store</span>
                                                    </button> -->
                                                    <!-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="sortDropdown" class="nav nav-tabs" role="tablist" data-selected-store>

                                                        <a id="profile-tab" data-toggle="tab" href="#profile" aria-controls="profile" role="tab" aria-selected="false" class="nav-link dropdown-item ascending" href="javascript:void(0);">Pentasynergy</a>
                                                        <a class="nav-link nav-link dropdown-item ascending" id="home-tab" data-toggle="tab" href="#home" aria-controls="home" role="tab" aria-selected="true">BMama</a>
                                                        <a class="nav-link nav-link dropdown-item ascending" id="about-tab" data-toggle="tab" href="#about" aria-controls="about" role="tab" aria-selected="false">Pinozar</a>
                                                        <a class="dropdown-item descending" href="javascript:void(0);">Reset</a>


                                                    </div> -->

                                                    <select data-selected-store class="hidden">
                                                        <?php
                                                        foreach ($store as $value) {
                                                            ?>
                                                        <option value="<?php echo $value;?>"><?php echo $value;?></option>

                                                            <?php
                                                        }
                                                        ?>
                                                       
                                                    </select>
                                                </div>
                                                <div class="todo-sort dropdown mr-1" data-sort-by-month>
                                                    <button class="btn sorting" type="button" id="sortDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                       
                                                        <span>Launchpack For</span>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="sortDropdown">
                                                        <a class="dropdown-item ascending" href="javascript:void(0);" data-date="01-21">Jan <span>2021</span></a>
                                                        <a class="dropdown-item descending" href="javascript:void(0);" data-date="02-21">Feb <span>2021</span></a>
                                                        <a class="dropdown-item descending" href="javascript:void(0);" data-date="03-21">March <span>2021</span></a>
                                                        <a class="dropdown-item descending" href="javascript:void(0);" data-date="04-21">Apr <span>2020</span></a>
                                                         <a class="dropdown-item descending" href="javascript:void(0);" data-date="12-20">Dec <span>2020</span></a>

                                                          <a class="dropdown-item descending" href="javascript:void(0);" data-date="11-20">Nov <span>2020</span></a>
                                                           
                                                        <a class="dropdown-item descending" href="javascript:void(0);" data-date="90days">90 days</a>

                                              

                                                    </div>
                                                </div>
                                                <fieldset class="form-group position-relative has-icon-left m-0 flex-grow-1">
                                                    <input type="text" class="form-control todo-search" id="todo-search" placeholder="Search Launchpack" data-search-launchpack>
                                                    <div class="form-control-position">
                                                        <i class="bx bx-search"></i>
                                                    </div>
                                                </fieldset>
                                              
                    
                        <div launch-box>
                        <h2>$<span data-price-total>0</span></h2>
                        <p style="color: red;" class="hidden" data-launch-ebay-error-prompt>Selected listing total amount exceeded your 'Available Selling Limit'</p>
                        <button type="button" class="btn btn-primary glow" data-launch-ebay>
                            <span data-spinner class="spinner-border spinner-border-sm hidden" role="status" aria-hidden="true"></span>
                            <span data-spinner-text>Launch to eBay</span>
                        </button>
                    </div>

                
                              
                                            </div>
                                            <div class="todo-task-list list-group">
                            <!-- Striped rows start -->
                <div class="row" id="table-striped">
                    <div class="col-12">
                        <div class="card">
                            
                            <!-- table striped -->
                            <div class="table-responsive">
                                <table class="table table-striped mb-0" id="launchpacks" data-launchpacks>
                                    <thead>
                                        <tr>
                                            <th>
                                                 <div class="checkbox">
                                                    <input type="checkbox" class="checkbox-input" id="mainCheckBox">
                                                    <label for="mainCheckBox"></label>
                                                </div>
                                            </th>
                                            <th>Title</th>
                                           <th>Status</th>
                                           
                                            <th>Date Created</th>
                                            <th>Date Launched</th>

                                            <th>Required Selling Limit</th>
                                            <?php
                                            if ($role == 1) {
                                                ?>
                                                <th>Status</th>
                                                <?php
                                            }
                                            ?>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($launchpacks)) {

                                         
                                            foreach ($launchpacks as $key => $launchpack) {
                                                $bg = '';
                                                 if ($launchpack['mylisting'] == $launchpack['listing']) {
                                                    $bg = 'disabled';
                                                    $status = 'light';

                                                    } else if ($launchpack['mylisting'] > 0) {
                                                        $status = 'warning';
                                                    } else {
                                                        $status = 'primary';
                                                    }
                                                ?>
                                            <tr data-launchpack-id ="<?php echo $launchpack['id'];?>" class="<?php echo $bg;?>">
                                                <td>
                                                    <div class="checkbox">

                                                        <input type="checkbox" class="checkbox-input" id="checkbox_<?php echo $key;?>" <?php echo $bg;?>>
                                                        <label for="checkbox_<?php echo $key;?>"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                   <!--  <img src="/images/icon/sketch.png" alt="file" class="mr-1" height="36" width="27"> -->
                                                   
                                                    <?php echo $launchpack['name'];?>
                                                    
                                                    
                                               
                                                </td>
                                                <td>
                                                     <div class="badge badge-pill badge-light-<?php echo $status;?> mr-1">
                                                    <?php

                                                    if ($launchpack['mylisting'] > 0) {
                                                        $text = $launchpack['mylisting']. '/'.$launchpack['listing'].' launched.';
                                                    } else {
                                                        if ($launchpack['listing'] <= 1) {
                                                            $text = $launchpack['listing'].' listing';

                                                        } else {
                                                            $text = $launchpack['listing'].' listings';

                                                        }
                                                    }
                                                    echo $text;
                                                    ?>
                                                </div>
                                                </td>
                                                
                                                
                                                <td><?php 
                                                $date=date_create($launchpack['date']);
                                                    // echo date_format($date,"jS M y h:i a");
                                                    echo date_format($date,"jS M");

                                                ?></td>
                                                <td><?php 
                                                $date=date_create($launchpack['date']);
                                                    // echo date_format($date,"jS M y h:i a");
                                                    echo date_format($date,"jS M");

                                                ?></td>
                                                <td><?php #echo $launchpack['currency'];?>$<span data-price><?php echo $launchpack['price'];?></span></td>
                                                <?php
                                                if ($role == 1) {
                                                    ?>
                                                    <td>
                                                    <div class="custom-control custom-switch custom-switch-success mr-2 mb-1">
                                                       
                                                        <input data-launchpack-status type="checkbox" class="custom-control-input" id="customSwitchcolor<?php echo $key;?>" <?php echo ($launchpack['status'] == 1 ? 'checked' :'');?>>
                                                        <label class="custom-control-label" for="customSwitchcolor<?php echo $key;?>"></label>
                                                    </div>
                                                    </td>
                                                    <?php
                                                }
                                                ?>
                                                
                                            </tr>
                                       <?php
                                            }
                                        }
                                       ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Striped rows end -->
                                                
                                            </div>
                                        </div>
                                    </div>
                                        </div>
</div>
                            <!--launhcpacks ends-->
                        </div>
                    </div>
                </section>

    </div>
</div>


@endsection



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

                                       <tr>
                                           <td colspan="5">
                                           </td>
                                           <td>
                                <div launch-box>
                        <h2>$<span data-price-total>0</span></h2>
                        <p style="color: red;" class="hidden" data-launch-ebay-error-prompt>Selected listing total amount exceeded your 'Available Selling Limit'</p>
                        <button type="button" class="btn btn-primary glow" data-launch-ebay>
                            <span data-spinner class="spinner-border spinner-border-sm hidden" role="status" aria-hidden="true"></span>
                            <span data-spinner-text>Launch to eBay</span>
                        </button>
                    </div>
                                           </td>
                                       </tr>
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

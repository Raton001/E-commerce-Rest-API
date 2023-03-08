 <!--content starts-->
   <div class="row" data-store-id="<?php echo $value;?>">
          <?php

            if (isset($summary[$key])) {

            
           foreach ($summary[$key] as $k => $sum) {
          
            if ($k != 'request_id') {
            ?>
                  <div class="col-xl-3 col-md-3 col-sm-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="badge-circle badge-circle-lg badge-circle-light-info mx-auto my-1">
                                <i class="bx bx-star font-medium-5"></i>
                            </div>

                            <?php

                            

                            
                            ?>
                            <p class="text-muted mb-0 line-ellipsis"><?php echo $k;?></p>
                            <p class="text-muted font-small-1">
                                <?php
                               if (isset($sum['threshold_type'])) {
                                echo '<span class="bullet bullet-xs bullet-primary mr-50"></span> '.$sum['target'].' '.$sum['unit']."<br/>";
                                

                               }
                              
                                ?>
                                </p>

                         <p class="text-muted font-small-1">
                                <?php
                               if (isset($sum['threshold_type'])) {
                                
                                echo '<span class="bullet bullet-xs bullet-danger mr-50"></span>'.$sum['my'].' '.$sum['unit']."<br/>";


                               }
                              
                                ?>
                                </p>
                           
                        </div>
                    </div>
                </div>
            <?php
           } }
           }
           ?>
   </div>
<!--content ends-->
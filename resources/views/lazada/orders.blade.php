
<div class="table-responsive">
 <table class="table table-striped">
      <thead>
          <tr class="text-left">
            <th>
              <fieldset>
                <div class="checkbox checkbox-info checkbox-glow">
                    <input type="checkbox" id="ship_all" checked>
                    <label for="ship_all"></label>
                </div>
            </fieldset>
            </th>
              <th>Order ID</th>
              <th>Status</th>

              <th>Total</th>
       
              <th>Buyer Username</th>
              <th>Created Time</th>
              <th>Payment Method</th>
              <th>Carrier</th>
              <th>Action</th>
          </tr>
      </thead>
      <tbody>
         <form method="post" action="/lazada/<?php echo $account;?>/order/request/bundle/shipment">
           @csrf
          <?php

            if (!is_array($data)) {
            ?>
            <tr>
              <td colspan="9">No Record</td>
            </tr>
            <?php
           
           } else {
               

           foreach ($data as $k => $v) {

            $action = '';
                      $orderID = $v['order_id'];

                      switch ($title) {

                        default:
                         
                          $action= '<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#order_'.$orderID.'">View </button>';
                       
                          break;
                      }
           			?>
           				<tr id="<?php echo $v['order_id'];?>">
                    <td>
                      <fieldset>
                            <div class="checkbox checkbox-info checkbox-glow">
                              <?php 
                         $checkStatus = 'checked';

                              if ($title == 'new') {

                                if (isset($axisStatus->{$v['order_id']})) {

                                switch ($axisStatus->{$v['order_id']}->status) {
                                case 'completed':
                               $status =  "<div class='badge badge-pill badge-secondary mr-1 mb-1'>Packed</div>";
                         $checkStatus = 'disabled';
                          $action='<a href="/lazada/'.$account.'/order/ship/'.$v['order_id'].'" data-item-id="'.$v['order_id'].'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';
                                
                                  break;
                                case 'pending':
                              $status = "<div class='badge badge-pill badge-success mr-1 mb-1'>Shipment Created</div>";
                         $checkStatus = 'disabled';
                          $action='<a href="/lazada/'.$account.'/order/ship/'.$v['order_id'].'" data-item-id="'.$v['order_id'].'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';
                               
                                  break;
                                case 'Under Process':
                             $status ="<div class='badge badge-pill badge-warning mr-1 mb-1'>Preparing</div>";
                         $checkStatus = 'disabled';
                          $action='<a href="/lazada/'.$account.'/order/ship/'.$v['order_id'].'" data-item-id="'.$v['order_id'].'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';
                                 
                                  break;
                                
                          case 'Deleted':
                          $status = "<div class='badge badge-pill badge-danger mr-1 mb-1'>Deleted</div>";
                         $checkStatus = 'disabled';
                          $action='<a href="/shopee/'.$account.'/order/ship/'.$v['order_id'].'" data-item-id="'.$v['order_id'].'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';

                          break;

                           case 'on hold':
                          $status = "<div class='badge badge-pill badge-danger mr-1 mb-1' style='background-color:#ccc;'>On Hold</div>";
                         $checkStatus = 'disabled';
                          $action='<a href="/shopee/'.$account.'/order/ship/'.$v['order_id'].'" data-item-id="'.$v['order_id'].'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';

                          break;
 
                        default:
                
                         $status = "<div class='badge badge-pill badge-info mr-1 mb-1'>".$axisStatus->{$v['order_id']}->status."</div>";
                         $checkStatus = 'disabled';
                          $action='<a href="/shopee/'.$account.'/order/ship/'.$v['order_id'].'" data-item-id="'.$v['order_id'].'"><small class="text-muted">Ship Now</span></a>';
                          break;
                              }
                              //end switch
                              } else {

                                //not in axis db
                              if ($v['statuses'][0] == 'pending') {
                              $status = "<div class='badge badge-pill badge-info mr-1 mb-1'>Create Shipment</div>";

                              $checkStatus = 'checked';
                              $action='<a href="/shopee/'.$account.'/order/ship/'.$v['order_id'].'" data-item-id="'.$v['order_id'].'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';
                                      } else if($v['statuses'][0] == 'canceled') {
                                          $status = '-';
                              $checkStatus = 'disabled';
                              $action='<a href="/shopee/'.$account.'/order/ship/'.$v['order_id'].'" data-item-id="'.$v['order_id'].'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';
                                      } else if($v['statuses'][0] == 'unpaid') {
                                          $status = 'unpaid';
                              $checkStatus = 'disabled';
                              $action='<a href="/shopee/'.$account.'/order/ship/'.$v['order_id'].'" data-item-id="'.$v['order_id'].'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';
                          } else if($v['statuses'][0] == 'shipped') {
                                          $status = 'shipped';
                              $checkStatus = 'disabled';
                              $action='<a href="/shopee/'.$account.'/order/ship/'.$v['order_id'].'" data-item-id="'.$v['order_id'].'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';
                          }

                              }
                              }  else {
                           

                          if ($v['statuses'][0] == 'pending') {
                              $status = "<div class='badge badge-pill badge-info mr-1 mb-1'>Create Shipment</div>";

                  $checkStatus = 'checked';
                  $action='<a href="/shopee/'.$account.'/order/ship/'.$v['order_id'].'" data-item-id="'.$v['order_id'].'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';
                          } else if($v['statuses'][0] == 'canceled') {
                              $status = '-';
                  $checkStatus = 'disabled';
                  $action='<a href="/shopee/'.$account.'/order/ship/'.$v['order_id'].'" data-item-id="'.$v['order_id'].'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';
                          } else if($v['statuses'][0] == 'unpaid') {
                              $status = '-';
                  $checkStatus = 'disabled';
                  $action='<a href="/shopee/'.$account.'/order/ship/'.$v['order_id'].'" data-item-id="'.$v['order_id'].'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';
                          }else if($v['statuses'][0] == 'shipped') {
                                          $status = 'shipped';
                              $checkStatus = 'disabled';
                              $action='<a href="/shopee/'.$account.'/order/ship/'.$v['order_id'].'" data-item-id="'.$v['order_id'].'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';
                          }


                              }
                              
                              ?>

                                <input type="checkbox" name="orders[]" id="ship_<?php echo $v['order_id'];?>" value="<?php echo $v['order_id'];?>" <?php echo $checkStatus;?>>
                                <label for="ship_<?php echo $v['order_id'];?>"></label>
                            </div>
                        </fieldset>
                    </td>
           					<td><?php echo $v['order_id'];?></td>
           					<td>
                      <?php 
                      if ($title == 'new') {
                        if (isset($axisStatus->{$v['order_id']}->status)) {

                          switch ($axisStatus->{$v['order_id']}->status) {
                        case 'completed':
                          echo "<div class='badge badge-pill badge-secondary mr-1 mb-1'>Packed</div>";
                          break;
                        case 'pending':
                          echo "<div class='badge badge-pill badge-success mr-1 mb-1'>Pending</div>";
                          break;
                        case 'Under Process':
                          echo "<div class='badge badge-pill badge-warning mr-1 mb-1'>Preparing</div>";
                          break;
                        case 'Deleted':
                          echo "<div class='badge badge-pill badge-danger mr-1 mb-1'>Deleted</div>";
                          break;
                        case 'on hold':
                        echo "<div class='badge badge-pill badge-danger mr-1 mb-1' style='background-color:#ccc;'>On Hold</div>";
                        break;
                        
                        default:
                          echo "Ready to Ship";
                          break;
                      }
                        } else {
                  
                          if ($v['statuses'][0] == 'pending') {
                            echo "<div class='badge badge-pill badge-info mr-1 mb-1'>Create Shipment</div>";
                          } else if($v['statuses'][0] == 'canceled') {
                            echo "<div class='badge badge-pill badge-danger mr-1 mb-1'>Cancelled</div>";
                          } else if ($v['statuses'][0] == 'unpaid'){
                            echo "<div class='badge badge-pill badge-warning mr-1 mb-1'>Unpaid</div>";

                          }else if($v['statuses'][0] == 'shipped') {
                                           echo "<div class='badge badge-pill badge-primary mr-1 mb-1'>Shipped</div>";
                          }
                         
                        }
                        
                      } else {
                      echo $v['statuses'][0];

                      }
                      
                      ?>
                        
                    </td>

           				
           					<td><?php echo $v['price'];?></td>

           					<td>
           				 <?php echo $v['customer_first_name'];?>
           						
           					</td>

           					<td>
           						<?php echo $v['created_at'];?>
           						</td>
                      <td>
                  
                      <?php echo $v['payment_method'];?>

                      </td>
                      <td>
                        <?php 
                        echo $v['warehouse_code'];
                        ?>
                      </td>
                      <td>
                        <?php echo  $action;?>
                        
                      </td>
           					<td>


								</button>

                				<!-- Modal -->
                        @include('lazada.order-detail', ['data'=>$v])

           					</td>
           				</tr>


           				<?php
           		
           		
           ?>
           <?php
           }


       }
           ?>
           <?php
           if ($title == 'new') {
            ?>
            <tr>
             <td colspan="7">
             </td>
             <td>
                <button type="submit" class="btn btn-primary btn-block subtotal-preview-btn">Submit</button>
             </td>
           </tr>
            <?php
           }

           ?>
           
          </form>
      </tbody>
  </table>
</div>

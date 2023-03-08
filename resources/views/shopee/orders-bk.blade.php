<div class="table-responsive">
 <table class="table table-striped">
      <thead>
          <tr class="text-left">
            <th></th>
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
              <th>Ship By</th>
              <th>Carrier</th>
              <th>Airway Bill</th>
              <th>Action</th>
          </tr>
      </thead>
      <tbody>
         <form method="post" action="/shopee/<?php echo $account;?>/order/request/bundle/shipment">
           @csrf
          <?php

            if (!is_array($data)) {
            ?>
            <tr>
              <td colspan="9">No Record</td>
            </tr>
            <?php
           
           } else {
             
            $count = 0;
           foreach ($data as $k => $v) {
             
            $count++;
            $action = '';
            if (isset( $v->ordersn)) {
                
          
                      $orderID = $v->ordersn;
                      switch ($title) {
                       
                        case 'READY_TO_SHIP':
                          
                         if ($v->order_status == 'READY_TO_SHIP') {
                          $action='<a href="/shopee/'.$account.'/order/ship/'.$orderID.'" data-item-id="'.$orderID.'">Ship Now</a>';
                         } else {
                          $action= '<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#order_'.$orderID.'">View </button>';
                         }
                         
                        
                         
                          break;
                        default:
                         
                          $action= '<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#order_'.$orderID.'">View </button>';
                       
                          break;
                      }
           			?>
           				<tr id="<?php echo $v->ordersn;?>">
                    <td>
                      <?php  echo $count;?>
                    </td>
                    <td>
                      <fieldset>
                            <div class="checkbox checkbox-info checkbox-glow">
                              <?php 
                              if ($title == 'READY_TO_SHIP') {
                           
                                switch ($v->order_status) {
                                case 'completed':
                              $checkStatus = 'disabled';
                                
                                  break;

                                case 'hold':
                              $checkStatus = 'disabled';
                                
                                  break;
                                case 'pending':
                              $checkStatus = 'disabled';
                               
                                  break;
                                case 'under process':
                              $checkStatus = 'disabled';
                                 
                                  break;

                                 case 'Deleted':
                              $checkStatus = 'checked';
                                 
                                  break;
                                
                                default:
                             $checkStatus = 'checked';

                        
                                  break;
                              }
                              } else {
                             $checkStatus = 'disabled';

                              }
                              
                              ?>

                                <input type="checkbox" name="orders[]" id="ship_<?php echo $v->ordersn;?>" value="<?php echo $v->ordersn;?>" <?php echo $checkStatus;?>>
                                <label for="ship_<?php echo $v->ordersn;?>"></label>
                            </div>
                        </fieldset>
                    </td>
           					<td><?php echo $v->ordersn;?></td>
           					<td>
                      <?php 
                      
                      if ($title == 'READY_TO_SHIP') {
              
                        switch ($v->order_status) {
                        case 'completed':
                          echo "Packed";
                          break;
                        case 'pending':
                          echo "Shipment Created";
                          break;
                        case 'Under Process':
                          echo "Preparing";
                          break;

                        case 'Deleted':
                          echo "Deleted";
                          break;
                        case 'READY_TO_SHIP':
                        echo "Create Shipment";
                        break;
                        default:
                          echo "Ready to Ship";
                          break;
                      }
                      } else {
                       
                      

                      }
                      
                      ?>
                        
                    </td>

           				
           					<td><?php echo $v->total_amount;?></td>

           					<td>
           				 <?php echo $v->buyer_username;?>
           						
           					</td>

           					<td>
           						<?php echo date('Y-m-d', $v->create_time);?>
           						</td>
                      <td>
                  
                      <?php echo date('Y-m-d', $v->ship_by_date);?>

                      </td>
                      <td>
                        <?php 
                        echo $v->shipping_carrier;
                        ?>
                      </td>
                      <td>
                        <?php
                        if (isset($v->airway_bill)) {
                        ?>
                        <a href="<?php echo $v->airway_bill;?>" target="_blank">Click</a>
                        <?php
                        } else {
                            if ($v->order_status == 'READY_TO_SHIP') {
                                
                          
                          ?>
                          <a  target="_blank" href="https://seller.shopee.com.my/portal/sale/order">Arrange Shipment</a>
                          <?php
                            }
                        }

                        ?>
                      </td>
                      <td>
                        <?php echo  $action;?>
                        
                      </td>
           					<td>


								</button>

                				<!-- Modal -->
                        @include('shopee.order-detail', ['data'=>$v])

           					</td>
           				</tr>


           				<?php
           		
           		
           ?>
           <?php
           }
  }

       }
           ?>
           <?php
           if ($title == 'READY_TO_SHIP') {
            ?>
            <tr>
             <td colspan="9" style="--bs-table-accent-bg:none;">
             </td>
             <td style="--bs-table-accent-bg:none;">
                <button type="submit" class="btn btn-secondary btn-block subtotal-preview-btn">Submit</button>
             </td>
           </tr>
            <?php
           }

           ?>
           
          </form>
      </tbody>
       <tfoot>
            <tr>
                <td colspan="7">

                
                 <nav aria-label="Page navigation">
                        <ul class="pagination pagination-borderless justify-content-center mt-2">
                          <?php
                          if ($pagination) {
                            ?>
                            <li class="page-item active" aria-current="page"><a class="page-link" href="/<?php echo $marketplace;?>/<?php echo $account;?>/orders/<?php echo $page;?>">Next</a></li>
                            <?php
                          }
                          ?>
                    
                        </ul>
                    </nav>
             </td>
            </tr>
         </tfoot>
  </table>
</div>

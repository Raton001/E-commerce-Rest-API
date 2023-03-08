<div class="table-responsive">
 <table class="table table-striped">
      <thead>
          <tr class="text-left">
            <th></th>
            <th></th>
              <th>Order ID</th>
              <th>Status</th>
              <!-- <th>Address</th> -->
              
              <!-- <th>Item</th> -->
              <th>Subtotal</th>
              <!-- <th>Tax</th> -->

              <th>Total</th>
       
              <th>CreatedDate</th>
              <th>Shipped Time</th>

              <th>Action</th>
          </tr>
      </thead>
      <tbody>
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
                      $orderID = $v['OrderID'];
                      switch ($title) {
                       
                        case 'Active':
                          
                         
                         $action='<a href="/ebay/'.$account.'/order/ship/'.$orderID.'" data-item-id="'.$orderID.'">Ship Now</a>';
                        
                         
                          break;
                        default:
                         
                          $action= '<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#order_'.$orderID.'">View </button>';
                       
                          break;
                      }
           		
           				?>
           				<tr>
                     <td>
                      <?php  echo $count;?>
                    </td>
                    <td>
                      <fieldset>
                            <div class="checkbox checkbox-info checkbox-glow">
                              <?php 
                             $checkStatus = 'checked';
                      
                              
                              ?>

                                <input type="checkbox" name="orders[]" id="ship_<?php echo $orderID;?>" value="<?php echo $orderID;?>" <?php echo $checkStatus;?>>
                                <label for="ship_<?php echo $orderID;?>"></label>
                            </div>
                        </fieldset>
                    </td>
           					<td><?php echo $v['OrderID'];?></td>
           					<td><?php echo $v['OrderStatus'];?></td>
           					
           					<td><?php echo $v['Subtotal'];?></td>
           				
           					<td><?php echo $v['Total'];?></td>

           					<td>
           						<?php 
	           					if (isset($v['TransactionArray']['Transaction']['CreatedDate'])) {
	           					echo date("Y-m-d",strtotime($v['TransactionArray']['Transaction']['CreatedDate']));

	           					}
	           					?>
           						
           					</td>

           					<td>
           						<?php 
	           					if (isset($v['TransactionArray']['Transaction']['ShippedTime'])) {
	           				echo date("Y-m-d",strtotime($v['TransactionArray']['Transaction']['ShippedTime']));

	           					}
	           					?>
           						
           							
           						</td>
                      <td>
                        <?php   

                         echo $action;
                         
                        ?>
                      </td>

           					<td>


								</button>

                				<!-- Modal -->
                        @include('ebay.order-detail', ['data'=>$v])

           					</td>
           				</tr>


           				<?php
           		
           		
           ?>
           <?php
           }


       }
           ?>
          
      </tbody>
  </table>
</div>

<div class="table-responsive">
 <table class="table table-striped">
      <thead>
          <tr class="text-left">
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

           foreach ($data as $k => $v) {
           			
           		
           				?>
           				<tr>
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
           						<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#order_<?php echo $v['OrderID'];?>">
								  View
								</button>

							                      				<!-- Modal -->
<div class="modal fade" id="order_<?php echo $v['OrderID'];?>" tabindex="-1" role="dialog" aria-labelledby="order_<?php echo $v['OrderID'];?>Label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="order_<?php echo $v['OrderID'];?>Label">Order #<?php echo $v['OrderID'];?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<?php

      	echo "<pre>";
      	var_dump($v);
      	?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>
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

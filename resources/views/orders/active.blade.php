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
              <th>Carrier</th>
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
                    <td><?php echo $v['TransactionArray']['Transaction']['ShippingDetails']['ShipmentTrackingDetails']['ShippingCarrierUsed'];?></td>

                    <td><?php echo date("Y-m-d",strtotime($v['TransactionArray']['Transaction']['CreatedDate']));?></td>

                    <td><?php echo date("Y-m-d",strtotime($v['TransactionArray']['Transaction']['ShippedTime']));?></td>


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
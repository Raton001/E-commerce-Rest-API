<div class="table-responsive">
 <table class="table table-striped">
      <thead>
          <tr class="text-left">
              <th>Order ID</th>
              <!-- <th>Address</th> -->
              
              <!-- <th>Item</th> -->
              <th>Subtotal</th>
              <th>Total</th>
              <th>Payment Status</th>
              <th>CreatedDate</th>
              <th>Action</th>
          </tr>

          <?php
          echo "<pre>";
          if (isset($data['SoldList'])) {
          	foreach ($data['SoldList'] as $key => $value) {
          		// if (isset($value['OrderTransactionArray'])) {
          			foreach ($value as $k => $v) {
          				if (isset($v['Transaction'])) {
          					foreach ($v as $k2 => $v2) {
          						?>
          						<tr>
          							<td><?php echo $v2['OrderLineItemID'];?></td>
          							<td><?php echo $v2['TotalTransactionPrice'];?></td>
          							<td><?php echo $v2['TotalPrice'];?></td>
          							<td><?php echo $v2['SellerPaidStatus'];?></td>
          							<td><?php echo date("Y-m-d",strtotime($v2['CreatedDate']));?></td>
          							<td>-</td>

          						</tr>
          						<?php
          					}
          				}	
          			}
          		// }
          	?>

          	<?php
          	}
          }
          
          ?>
      </thead>
      <tbody>

      </tbody>
  </table>
</div>
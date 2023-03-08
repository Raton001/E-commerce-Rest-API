
<ul class="nav nav-pills">
	<?php
	$keys = array_keys($order_status);
	foreach ($keys as $key) {
	?>
	<li class="nav-item">
	<a class="nav-link" id="<?php echo $key;?>-tab" data-toggle="pill" href="#<?php echo $key;?>" aria-expanded="true"><?php echo $key;?></a>
	</li>
	<?php
	}
	?>
</ul>
<div class="tab-content">

	<?php

       	foreach ($order_status as $key => $value) {

       		?>
       		<div role="tabpanel" class="tab-pane" id="<?php echo $key;?>" aria-labelledby="<?php echo $key;?>-tab" aria-expanded="true">
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
				              <th>Ship By</th>
				              <th>Carrier</th>
				              <th>Action</th>
				          </tr>
				      </thead>
				      <tbody>
         <form method="post" action="/shopee/<?php echo $account;?>/order/request/bundle/shipment">
           @csrf
       			<?php
       			
       			foreach ($value as $k2 => $v2) {
       				//order starts
              	$v = $v2['order'];
               	?>

				<?php

				if (!isset($v2['order'])) {
				?>
				<tr>
				<td colspan="9">No Record</td>
				</tr>
				<?php

				} else {


				$action = '';
				$orderID = $v->ordersn;
				switch ($title) {

				case 'READY_TO_SHIP':


				$action='<a href="/shopee/'.$account.'/order/ship/'.$orderID.'" data-item-id="'.$orderID.'">Ship Now</a>';


				break;
				default:

				$action= '<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#order_'.$orderID.'">View </button>';

				break;
				}
				?>
				<tr id="<?php echo $v->ordersn;?>">
				<td>
				<fieldset>
				<div class="checkbox checkbox-info checkbox-glow">
				<input type="checkbox" name="orders[]" id="ship_<?php echo $v->ordersn;?>" value="<?php echo $v->ordersn;?>">
				<label for="ship_<?php echo $v->ordersn;?>"></label>
				</div>
				</fieldset>
				</td>
				<td><?php echo $v->ordersn;?></td>
				<td><?php echo $v->order_status;?></td>


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
				?>



               	<?php
               	//order ends
       			}
       			?>
       			<tr>
             <td colspan="7">
             </td>
             <td>
                <button type="submit" class="btn btn-primary btn-block subtotal-preview-btn">Submit</button>
             </td>
           </tr>
          </form>
      </tbody>
  </table>
</div>
            </div>
       		<?php
       	}
	?>
   
</div>